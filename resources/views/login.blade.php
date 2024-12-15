<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
    <div style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
        <div style="background-color: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px;">
            <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Login</h2>
            <form action="{{ route('v1.login') }}" method="POST">
            @csrf
                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; font-size: 14px; color: #333; margin-bottom: 5px;">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required style="width: 100%; padding: 10px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px; outline: none;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="password" style="display: block; font-size: 14px; color: #333; margin-bottom: 5px;">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required style="width: 100%; padding: 10px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px; outline: none;">
                </div>
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <input type="checkbox" id="remember">
                    <label for="remember" style="font-size: 14px; color: #333; margin-left: 5px;">Remember me</label>
                </div>
                <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Login</button>
            </form>
            <p style="text-align: center; margin-top: 10px;">New User?<a href="/register" style="color: #007bff; text-decoration: none;"> Register</a></p>
        </div>
    </div>

    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);
        
        fetch("{{ route('v1.login') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Show the error message if there's an error
                document.getElementById('error-message').innerText = data.message;
            } else {
                // Handle success (for example, redirect or show success message)
                window.location.href = "/"; // Redirect or show success message
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
</body>
</html>
