<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Favourite;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavouriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_retrieve_user_favourites()
    {
        $user = User::factory()->create();
        $favourites = Favourite::factory()->count(1)->create(['user_id' => $user->id]);

        $this->mockUserFavourites($favourites);

        $response = $this->actingAs($user)
                         ->getJson('/api/favourites');

    $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'favourites' => [
                    '*' => [
                        'id',
                        'product_id',
                        'user_id',
                        'created_at',
                        'updated_at',
                        'product' => [
                            'id',
                            'name_en',
                            'name_ar',
                            'name_tr',
                            'desc_en',
                            'desc_ar',
                            'desc_tr',
                            'sku',
                            'admin_id',
                            'category_id',
                            'collection_id',
                            'brand_id',
                            'vendor_id',
                            'price',
                            'sale_price',
                            'discount_percentage_selling_price',
                            'packaging_shipping_fees',
                            'management_fees',
                            'profit_percentage',
                            'tax_percentage',
                            'commercial_percentage',
                            'manual_price_adjustment',
                            'final_price',
                            'final_selling_price',
                            'quantity',
                            'shipping',
                            'length',
                            'width',
                            'height',
                            'dimension_unit',
                            'weight',
                            'weight_unit',
                            'discount_price',
                            'discount_percentage',
                            'start_at',
                            'end_at',
                            'points',
                            'image',
                            'source_link',
                            'status',
                            'featured',
                            'new_arrival',
                            'ontop',
                            'home',
                            'trending',
                            'highlighted',
                            'most_sold',
                            'attributes_ids',
                            'colors_ids',
                            'sizes_ids',
                            'scraped_attributes',
                            'model_number',
                            'group_link',
                            'stock_status',
                            'related',
                            'home_forniture',
                            'created_at',
                            'updated_at',
                            'translated_name',
                            'translated_desc',
                            'rate',
                            'brand' => [
                                'id',
                                'name' => [
                                    'en',
                                    'ar',
                                    'tr',
                                ],
                                'slug' => [
                                    'en',
                                    'ar',
                                    'tr',
                                ],
                                'image',
                                'status',
                                'created_at',
                                'updated_at',
                                'translated_name',
                                'translated_slug',
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function test_add_favourite()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $data = [
            'product_id' => $product->id,
        ];

        $response = $this->postJson('/api/add-delete-favourite', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'success',
                     'favourite' => [
                         'product_id' => $data['product_id'],
                         'user_id' => $user->id,
                     ],
                 ]);

        $this->assertDatabaseHas('favourites', [
            'product_id' => $data['product_id'],
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test deleting a favourite.
     *
     * @return void
     */
    public function test_delete_Favourite()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $favourite = Favourite::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = [
            'product_id' => $favourite->product_id,
        ];

        $response = $this->postJson('/api/add-delete-favourite', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'success',
                     'data' => 'deleted successfully',
                 ])
                 ->assertJsonMissing(['favourite']); // Ensure 'favourite' key is not present

        $this->assertDatabaseMissing('favourites', [
            'product_id' => $favourite->product_id,
            'user_id' => $user->id,
        ]);
    }


    private function mockUserFavourites($favourites)
    {
        $this->app->bind('user_favourites', function () use ($favourites) {
            return $favourites;
        });
    }
}
