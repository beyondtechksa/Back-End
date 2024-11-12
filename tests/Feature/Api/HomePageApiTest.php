<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\Settings;
use App\Models\Collection;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use App\Http\Enums\CacheEnums;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_brands_function()
    {
        // Arrange
        $brand = Brand::factory()->create([
            'name' => ['en' => 'Barrels and oil'],
            'slug' => ['en' => 'Barrels and oil'],
            'image' => 'brand_image.jpg',
            'status' => 1,
        ]);

        // Act
        $response = $this->getJson('/api/brands');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'slug',
                    'image',
                    'status',
                    'created_at',
                    'updated_at',
                    'translated_name',
                    'translated_slug',
                ],
            ],
        ]);

        $responseData = $response->json('data');
        $this->assertEquals($brand->id, $responseData[0]['id']);
        $this->assertEquals(url($brand->image), $responseData[0]['image']);
    }

    public function test_active_brands_caching()
    {
        // Arrange
        Brand::factory()->create([
            'name' => ['en' => 'Barrels and oil'],
            'slug' => ['en' => 'Barrels and oil'],
            'image' => 'brand_image.jpg',
            'status' => 1,
        ]);

        // Act
        $firstCall = active_brands();
        $secondCall = active_brands();

        // Assert
        $this->assertCount(1, $firstCall);
        $this->assertCount(1, $secondCall);
        $this->assertEquals($firstCall->first()->id, $secondCall->first()->id);
        $this->assertNotNull(Cache::get(CacheEnums::ACTIVE_BRANDS));
    }

    public function testGetAllCategories()
    {
        // Arrange
        $category1 = Category::create([
            'list' => '1',
            'name' => ['en' => 'zepkids', 'ar' => 'zepkids'],
            'menu_name' => ['en' => 'zepkids', 'ar' => 'zepkids'],
            'desc' => [],
            'slug' => ['en' => 'zepkids', 'ar' => 'zepkids'],
            'image' => null,
            'status' => '1',
            'category_id' => null,
            'show_in_navbar' => '0',
            'mark_as_top_category' => '0',
        ]);

        // Act
        $response = $this->get('/api/get_all_categories');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'list',
                    'name',
                    'menu_name',
                    'desc',
                    'slug',
                    'image',
                    'status',
                    'category_id',
                    'show_in_navbar',
                    'mark_as_top_category',
                    'created_at',
                    'updated_at',
                    'translated_name',
                    'translated_desc',
                    'translated_slug',
                    'all_children',
                ],
            ],
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category1->id,
            'list' => '1',
            'name' => json_encode(['en' => 'zepkids', 'ar' => 'zepkids']),
            'menu_name' => json_encode(['en' => 'zepkids', 'ar' => 'zepkids']),
            'desc' => json_encode([]),
            'slug' => json_encode(['en' => 'zepkids', 'ar' => 'zepkids']),
            'image' => null,
            'status' => '1',
            'category_id' => null,
            'show_in_navbar' => '0',
            'mark_as_top_category' => '0',
        ]);
    }

    public function testGetAllCategoriesWithCache()
    {
        // Arrange
        $category1 = Category::create([
            'list' => '1',
            'name' => ['en' => 'zepkids', 'ar' => 'zepkids'],
            'menu_name' => ['en' => 'zepkids', 'ar' => 'zepkids'],
            'desc' => [],
            'slug' => ['en' => 'zepkids', 'ar' => 'zepkids'],
            'image' => null,
            'status' => '1',
            'category_id' => null,
            'show_in_navbar' => '0',
            'mark_as_top_category' => '0',
        ]);

        // Act
        $response1 = $this->get('/api/get_all_categories');
        $response2 = $this->get('/api/get_all_categories');

        // Assert
        $response1->assertStatus(200);
        $response2->assertStatus(200);

        $this->assertEquals(
            $response1->json('data'),
            $response2->json('data')
        );

        $this->assertTrue(
            Cache::has(CacheEnums::CATEGORIES_WITH_PARENTS)
        );
    }
    public function test_get_main_categories()
    {
        // Arrange
        $mainCategories = Category::factory()->count(6)->create();
        $response = $this->get('/api/get_main_categories');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'categories' => [
                '*' => [
                    'id',
                    'list',
                    'name',
                    'menu_name',
                    'desc',
                    'slug',
                    'image',
                    'status',
                    'category_id',
                    'show_in_navbar',
                    'mark_as_top_category',
                    'created_at',
                    'updated_at',
                    'translated_name',
                    'translated_desc',
                    'translated_slug',
                    'all_children',
                ]
            ]
        ]);

        $response->assertJsonFragment([
            'status' => 1,
            'category_id' => null,
        ]);
        $response->assertJsonMissing([
            'status' => 0,
        ]);
    }
/*
    public function test_home_page_api(): void
    {
        $news_bar              = Settings::factory()->count(1)->create(['slug' => 'news_bar']);
        $top_sliders           = Settings::factory()->count(1)->create(['slug' => 'top_slider']);
        $top_categories        = Category::factory()->count(8)->create(['status' => 1 , 'mark_as_top_category' => 1]);
        $banners               = Settings::factory()->count(5)->create();
        $new_arrival_products  = Product::factory()->count(4)->create(['new_arrival' => 1]);
        $featured_products     = Product::factory()->count(4)->create(['featured' => 1]);
        $furniture_products    = Product::factory()->count(4)->create(['home_forniture' => 1]);
        $shop_brands           = Settings::factory()->count(1)->create(['slug' => 'shop_by_brand']);

        // Call the controller method
        $response = $this->get('/api/home');

        // Verify the response structure
        $response->assertStatus(200)
            ->assertJson([
            'news_bar'              => $news_bar ,
            'top_sliders'           => $top_sliders,
            'top_categories'        => $top_categories,
            'banners'               => $banners,
            'new_arrival_products'  => $new_arrival_products,
            'featured_products'     => $featured_products,
            'furniture_products'    => $furniture_products,
            'shop_brands'           => $shop_brands,
            ]);
    }
*/
}
