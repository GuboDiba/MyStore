<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a product.
     *
     * @return void
     */
    public function test_create_product()
    {
        $response = $this->withoutMiddleware()->postJson('/api/v1/products', [
            'name' => 'Product 1',
            'description' => 'Description for Product 1',
            'price' => 29.99,
            'stock' => 100,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Product created successfully!'
            ]);
    }

    // /**
    //  * Test fetching all products.
    //  *
    //  * @return void
    //  */
    // public function test_get_products()
    // {
    //     Product::factory()->count(3)->create();

    //     $response = $this->withoutMiddleware()->getJson('/api/v1/products');

    //     $response->assertStatus(200)
    //         ->assertJsonCount(3);
    // }

    // /**
    //  * Test updating a product.
    //  *
    //  * @return void
    //  */
    // public function test_update_product()
    // {
    //     $product = Product::factory()->create();

    //     $response = $this->withoutMiddleware()->putJson("/api/v1/products/{$product->id}", [
    //         'name' => 'Updated Product',
    //         'description' => 'Updated Description',
    //         'price' => 39.99,
    //         'stock' => 150,
    //     ]);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'name' => 'Updated Product',
    //             'description' => 'Updated Description',
    //             'price' => 39.99,
    //             'stock' => 150,
    //         ]);
    // }

    // /**
    //  * Test deleting a product.
    //  *
    //  * @return void
    //  */
    // public function test_delete_product()
    // {
    //     $product = Product::factory()->create();

    //     $response = $this->withoutMiddleware()->deleteJson("/api/v1/products/{$product->id}");

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'message' => 'Product deleted successfully'
    //         ]);

    //     $this->assertDatabaseMissing('products', ['id' => $product->id]);
    // }
}
