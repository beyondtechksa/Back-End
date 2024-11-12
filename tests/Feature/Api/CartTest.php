<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\Size;
use App\Models\User;
use App\Models\Color;
use App\Models\Address;
use App\Models\Product;
use Mockery\MockInterface;
use Laravel\Sanctum\Sanctum;
use App\Services\OrderService;
use App\Models\PaymentTransaction;
use App\Exceptions\OrderCreationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartTest extends TestCase
{
    use RefreshDatabase;
    public function it_should_add_product_to_cart_successfully()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/add-to-cart', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => 'success',
                     'success' => true,
                 ]);
    }

    /** @test */
    public function it_should_return_error_for_invalid_product_id()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/add-to-cart', [
            'product_id' => 999, // Assuming 999 does not exist in Product table
        ]);

        $response->assertStatus(400)
                 ->assertJsonStructure([
                     'errors' => [
                         'product_id',
                     ],
                 ]);
    }

    public function it_should_update_cart_quantity_successfully()
    {
        $cart = Cart::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/update-cart-quantity', [
            'id' => $cart->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => 'success',
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 2,
        ]);
    }

    /** @test */
    public function it_should_return_error_for_invalid_cart_id()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $response = $this->postJson('/api/update-cart-quantity', [
            'id' => 999, // Assuming 999 does not exist in Cart table
            'quantity' => 2,
        ]);

        $response->assertStatus(400)
                 ->assertJsonStructure([
                     'errors' => [
                         'id',
                     ],
                 ]);
    }

    /** @test */
    public function it_should_return_error_for_invalid_quantity()
    {
        $cart = Cart::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $response = $this->postJson('/api/update-cart-quantity', [
            'id' => $cart->id,
            'quantity' => 0, // Invalid quantity
        ]);

        $response->assertStatus(400)
                 ->assertJsonStructure([
                     'errors' => [
                         'quantity',
                     ],
                 ]);
    }


        public function it_should_update_cart_size_successfully()
        {
            $cart = Cart::factory()->create();
            $size = Size::factory()->create();
            $user = User::factory()->create();
            Sanctum::actingAs($user, ['*']);

            $response = $this->postJson('/api/update-cart-size', [
                'id' => $cart->id,
                'size_id' => $size->id,
            ]);

            $response->assertStatus(200)
                    ->assertJson([
                        'data' => 'success',
                        'success' => true,
                    ]);

            $this->assertDatabaseHas('carts', [
                'id' => $cart->id,
                'size' => json_encode(['en' => $size->name]),
            ]);
        }

        /** @test */
        public function it_should_return_error_for_invalid_cart_id_on_update_size()
        {
            $size = Size::factory()->create();
            $user = User::factory()->create();
            Sanctum::actingAs($user, ['*']);
            $response = $this->postJson('/api/update-cart-size', [
                'id' => 999, // Assuming 999 does not exist in Cart table
                'size_id' => $size->id,
            ]);

            $response->assertStatus(400)
                     ->assertJsonStructure([
                         'errors' => [
                             'id',
                         ],
                     ]);
        }

        public function it_should_update_cart_color_successfully()
        {
            $cart = Cart::factory()->create();
            $color = Color::factory()->create();
            $user = User::factory()->create();
            Sanctum::actingAs($user, ['*']);

            $response = $this->postJson('/api/update-cart-color', [
                'id' => $cart->id,
                'color_id' => $color->id,
            ]);

            $response->assertStatus(200)
                     ->assertJson([
                         'data' => 'success',
                         'success' => true,
                     ]);

            $this->assertDatabaseHas('carts', [
                'id' => $cart->id,
                'color' => json_encode(['en' => $color->name]),
            ]);
        }
        /** @test */
        public function it_should_return_error_for_invalid_cart_id_on_update_color()
        {
            $color = Color::factory()->create();
            $user = User::factory()->create();
            Sanctum::actingAs($user, ['*']);
            $response = $this->postJson('/api/update-cart-color', [
                'id' => 999,
                'color_id' => $color->id,
            ]);

            $response->assertStatus(400)
                     ->assertJsonStructure([
                         'errors' => [
                             'id',
                         ],
                     ]);
        }

        /** @test */
        public function it_should_clear_cart_successfully()
        {
            $cart = Cart::factory()->create();
            $user = User::factory()->create();
            Sanctum::actingAs($user, ['*']);
            $response = $this->postJson('/api/clear-cart');

            $response->assertStatus(200)
                     ->assertJson([
                         'data' => 'success',
                         'success' => true,
                     ]);

            $this->assertDatabaseMissing('carts', [
                'user_id' => $user ->id,
            ]);
        }



    /** @test */
    public function it_should_return_cart_details_successfully()
    {
        // Create a user and some cart items
        $user = User::factory()->create();
        $cartItems = Cart::factory()->count(3)->create(['user_id' => $user->id]);

        $headers = ['currency' => 'SAR'];

        $this->actingAs($user);

        $response = $this->withHeaders($headers)->getJson('/api/my-cart');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'cart' => [
                         'cart',
                         'shipping',
                         'discountPercentage',
                         'subTotal',
                         'total',
                     ],
                     'message',
                 ]);
    }

    /** @test */
    public function it_should_return_error_for_invalid_currency()
    {
        // Create a user
        $user = User::factory()->create();

        $headers = ['currency' => 'INR'];

        $this->actingAs($user);

        $response = $this->withHeaders($headers)->getJson('/api/my-cart');

        $response->assertStatus(400)
                 ->assertJsonStructure([
                     'errors' => [
                         'currency',
                     ],
                 ]);
    }

    /** @test */
    public function it_should_return_success_for_empty_cart()
    {
        $user = User::factory()->create();

        $headers = ['currency' => 'SAR'];

        $this->actingAs($user);

        $response = $this->withHeaders($headers)->getJson('/api/my-cart');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'success',
                    'cart' => 'empty cart'
                ]);
    }

    public function test_delete_cart_item()
    {
        // Create a user and a cart item for that user
        $user = User::factory()->create();
        $cartItem = Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->postJson('/api/delete-cart-item', ['id' => $cartItem->id]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'success',
                'data' => 'deleted successfully',
            ]);

        $this->assertDatabaseMissing('carts', [
            'id' => $cartItem->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test deleting a non-existent cart item.
     *
     * @return void
     */
    public function test_delete_non_existent_cart_item()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/delete-cart-item', ['id' => 999]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonValidationErrors(['id']);

        $this->assertDatabaseMissing('carts', ['id' => 999]);
    }

}

