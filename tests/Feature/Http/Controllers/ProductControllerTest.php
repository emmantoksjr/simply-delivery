<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = "base64:i1QAejeCeZ8hG1UfG3CUNBghutWX4BDCIx/tpva3JHc=";

    private $headers = [
        'X-ACCESS-TOKEN' => self::TOKEN
    ];

    public function test_all_products_can_be_retrieved()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get('/products', $this->headers);

        $first_product_data = $products[0];
        $first_response_data = data_get($response, 'data')[0];

        $response->assertStatus(HTTP_SUCCESS);
        $this->performAssertions($first_product_data, $first_response_data);
    }

    public function test_a_single_product_can_be_retrieved()
    {
        $product = Product::factory()->create();

        $response = $this->get("/products/{$product->slug}", $this->headers);

        $response_data = data_get($response, 'data');

        $response->assertStatus(HTTP_SUCCESS);
        $this->performAssertions($product, $response_data);
    }

    public function test_product_item_can_be_created()
    {
        $product = Product::factory()->make();

        $response = $this->post('/products', $product->toArray(), $this->headers);

        $response_data = data_get($response, 'data');

        $response->assertStatus(HTTP_CREATED);
        $this->performAssertions($product, $response_data);
    }

    public function test_product_item_can_be_updated()
    {
        $payload = [
            'name' => 'Test Name',
            'slug' => 'test_slug',
            'description' => "Test description",
            'price' => 10,
            'properties' => ["size" => "small"]
        ];

        $product = Product::create($payload);

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseHas('products', ["slug" => "test_slug"]);

        $payload['name'] = 'Changed Name';
        $payload['slug'] = 'changed-name';

        $response = $this->put("/products/{$product->slug}", $payload, $this->headers);

        $response->assertStatus(HTTP_ACCEPTED);
        $this->assertDatabaseHas('products', ["slug" => "changed-name", "name" => "Changed Name"]);
    }

    public function test_a_product_can_be_deleted()
    {
        $product = Product::factory()->create();

        $created_response = $this->get("/products/{$product->slug}", $this->headers);
        $created_response->assertStatus(HTTP_SUCCESS);

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseHas('products', ["slug" => "{$product->slug}", "name" => "{$product->name}"]);

        $response = $this->delete("/products/{$product->slug}", [], $this->headers);
        $response->assertStatus(HTTP_SUCCESS);

        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseMissing('products', ["slug" => "{$product->slug}", "name" => "{$product->name}"]);
    }

    private function performAssertions(Product $product, array $response_data)
    {
        $this->assertEquals($product->name, $response_data['productName']);
        $this->assertEquals($product->slug, $response_data['slug']);
        $this->assertEquals($product->price, $response_data['price']);
    }
}
