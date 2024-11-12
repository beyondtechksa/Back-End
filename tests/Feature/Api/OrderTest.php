<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_fetch_their_orders()
    {
        // Assuming you have a logged-in user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some orders for the user
        Order::factory()->count(2)->create(['user_id' => $user->id]);

        // Hit the endpoint
        $response = $this->get('/api/my-orders');

        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'orders' => [
                    '*' => [
                        'id',
                        'user_id',
                        'subtotal_amount',
                        'shipping',
                        'discount',
                        'address' => [
                            'id',
                            'first_name',
                            'last_name',
                            'details',
                            'postal_code',
                            'street',
                            'city',
                            'country',
                        ],
                        'total_amount',
                        'status',
                        'created_at',
                        'updated_at',
                        'order_items' => [
                            '*' => [
                                'id',
                                'order_id',
                                'product_id',
                                'quantity',
                                'price',
                                'size',
                                'color',
                                'product' => [
                                    'id',
                                    'name_en',
                                    'name_ar',
                                    'brand_id',
                                    'final_selling_price',
                                    'discount_percentage_selling_price',
                                    'image',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function it_returns_order_details_when_valid_order_id_provided()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'subtotal_amount' => 16926.3,
            'shipping' => 20,
            'discount' => 23,
            'total_amount' => 13048.651,
            'status' => 2,
            'created_at' => '2024-05-20T01:39:12.000000Z',
            'updated_at' => '2024-05-20T10:40:18.000000Z',
        ]);

        $response = $this->postJson('/api/track-order', [
            'order_id' => $order->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'order' => [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'subtotal_amount' => $order->subtotal_amount,
                    'shipping' => $order->shipping,
                    'discount' => $order->discount,
                    'address' => null,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => '2024-05-20T01:39:12.000000Z',
                    'updated_at' => '2024-05-20T10:40:18.000000Z',
                ],
            ]);
    }

      /** @test */
      public function it_returns_error_when_invalid_order_id_provided()
      {

         $user = User::factory()->create();
         $this->actingAs($user);
          $response = $this->postJson('/api/track-order', [
              'order_id' => 999,
          ]);

          $response->assertStatus(400)
              ->assertJsonStructure([
                  'errors' => ['order_id'],
              ]);
      }




}
