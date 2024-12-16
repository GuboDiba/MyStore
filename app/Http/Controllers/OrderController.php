<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;



class OrderController extends Controller
{
    // Fetch all orders
    public function index()
    {
        // $orders = Order::with('items.product')->get();
        // $res = $this->STKPUSH("254790195109", "1000");
        return view("oders");
    }

    // Fetch a single order
    public function show($id)
    {
        $order = Order::with('items.product')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function showOrder($orderId)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($orderId);
    
        return view('orders', compact('order'));
    }
  
    

    
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json(['message' => 'Order created successfully!', 'order' => $order->load('items.product')], 201);
    }

    // Update an order
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update($request->only(['status', 'total_price']));
        return response()->json(['message' => 'Order updated successfully!', 'order' => $order]);
    }

    // Delete an order
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully!']);
    }
    public function paymentCallback(Request $request)
    {
        // Log the entire callback for debugging
        $response = $request->all();
        \Log::info('M-PESA Payment Callback:', $response);
    
        try {
            // Check if ResultCode exists in the response
            if (isset($response['Body']['stkCallback']['ResultCode'])) {
                $resultCode = $response['Body']['stkCallback']['ResultCode'];
                $resultDesc = $response['Body']['stkCallback']['ResultDesc'] ?? 'Unknown result';
                \Log::info('Payment Callback - ResultCode:', ['ResultCode' => $resultCode, 'ResultDesc' => $resultDesc]);

                if ($resultCode == 0) {
                    // Payment was successful, process payment details
                    $metadata = $response['Body']['stkCallback']['CallbackMetadata']['Item'];
                    $amountPaid = $metadata[0]['Value']; // Amount paid
                    $paymentReference = $response['Body']['stkCallback']['MerchantRequestID']; // Payment reference
                    $payerPhone = $metadata[3]['Value']; // Payer's phone number (index may vary!)
    
                    // Save successful payment in the database
                    // $order = new Payment();
                    // $order->amount_paid = $amountPaid;
                    // $order->payment_reference = $paymentReference;
                    // $order->payer_phone = $payerPhone;
                    // $order->status = 'paid';
                    // $order->paid_at = now();
                    // $order->save();
    
                    // $orderId = $order->id; // Ensure this line exists
                    // $payment->order_id = $orderId;
                    // \Log::info('Payment successful, Payment saved.', ['order_id' => $order->id]);
    
                    return redirect()->route('orders')->with('message', 'Payment successful, Your order is confirmed');

                    // return response()->json([
                    //     'message' => 'Payment successful',
                    //     'order' => $order
                    // ], 201); // 201 Created
                } else {
                    // Payment failed or was canceled
                    \Log::warning('Payment failed or canceled.', [
                        'ResultCode' => $resultCode,
                        'ResultDesc' => $resultDesc
                    ]);
                    return redirect()->route('cart.get')->with('error', 'Invalid order.');

                    // return response()->json([
                    //     'error' => 'Payment not completed',
                    //     'message' => $resultDesc
                    // ], 400); // 400 Bad Request
                }
            } else {
                // Unexpected response format
                \Log::error('Unexpected callback format:', $response);
                return response()->json(['error' => 'Invalid callback data'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Error handling payment callback', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
    

public function formatPhoneNumber($phone)
{
    // Remove any non-digit characters
    $phone = preg_replace('/\D/', '', $phone);

    // Check if phone number is valid
    if (strlen($phone) < 10) {
        throw new Exception('Invalid phone number format');
    }

    // If phone starts with 7, it's likely Kenyan, prepend country code
    if (substr($phone, 0, 1) == '7') {
        $phone = '+254' . substr($phone, 1);
    }

    return $phone;
}

public function processPayment(Request $request)
{
    try {
        Log::info('Validating request data', $request->all());
        
        $request->validate([
            'phone' => 'required|regex:/^\+?[0-9]{10,15}$/',
            'amount' => 'required|numeric|min:1',
        ]);
        // Format phone number and validate amount
        $formattedPhone = $this->formatPhoneNumber($request->phone);
        Log::info('Formatted Phone Number: ' . $formattedPhone);
        
        // Handle amount with decimals, convert to whole units (e.g., cents)
        $amount = (int) $request->amount; // Explicitly cast to integer
        Log::info('Final Amount being sent to STKPUSH: ' . $amount);

            
        
        // Call STKPUSH method to process payment
        $res = $this->STKPUSH($formattedPhone, $amount);
        Log::info('STKPUSH Response', ['response' => $res]);
        
        // Decode the response from STKPUSH
        $responseArray = json_decode($res, true);
        Log::info('Decoded STKPUSH Response', $responseArray ?? ['response' => $res]);
        
        // Return the success response
        return response('Payment initiated successfully');
       
        // return redirect()->route('cart.get');

    } catch (\Exception $e) {
        // Log the exception message
        Log::error('Payment processing failed', ['error' => $e->getMessage()]);
        
        // Return error response
        return response()->json([
            'error' => 'Payment processing failed',
            'message' => $e->getMessage()
        ], 500); 
    }
}


public function STKPUSH($phone, $amount)
{
   date_default_timezone_set('Africa/Nairobi');

   $PartyA = $phone; // This is your phone number,
   $AccountReference = '2255';
   $TransactionDesc = 'Test Payment';
   $Amount = $amount;
   
   # Get the timestamp, format YYYYmmddhms -> 20181004151020
   $Timestamp = date('YmdHis');    
   
   # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
   $Password = base64_encode( env("BSS_SHORT_CODE"). env("PASS_KEY").$Timestamp);

   $headers = ['Content-Type:application/json; charset=utf8'];

   $curl = curl_init("https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials");
   curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
   curl_setopt($curl, CURLOPT_HEADER, FALSE);
   curl_setopt($curl, CURLOPT_USERPWD, "nk16Y74eSbTaGQgc9WF8j6FigApqOMWr:40fD1vRXCq90XFaU");

   $result = curl_exec($curl);
   $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
   $result = json_decode($result);
   $access_token = $result->access_token;  
   curl_close($curl);

   # header for stk push
   $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

   # initiating the transaction
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL, env("INITIATE_URL"));
   curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

   $curl_post_data = array(
       //Fill in the request parameters with valid values
       'BusinessShortCode' => env("BSS_SHORT_CODE"),
       'Password' => $Password,
       'Timestamp' => $Timestamp,
       'TransactionType' => 'CustomerPayBillOnline',
       'Amount' => $Amount,
       'PartyA' => $PartyA,
       'PartyB' => env("BSS_SHORT_CODE"),
       'PhoneNumber' => $PartyA,
       'CallBackURL' => "https://f177-154-159-237-238.ngrok-free.app/payment/callback",
       'AccountReference' => $AccountReference,
       'TransactionDesc' => $TransactionDesc
   );
   Log::info('STK Push Payload:', $curl_post_data);


   $data_string = json_encode($curl_post_data);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_POST, true);
   curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
   $curl_response = curl_exec($curl);

   return $curl_response;

   curl_close($curl);
}


}

