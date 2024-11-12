<?php

namespace Tests\Feature;

use Tests\TestCase;
use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mocking the Cache facade
        Cache::shouldReceive('remember')->andReturnUsing(function ($key, $time, $callback) {
            return $callback();
        });

        // Mocking the forget() method
        Cache::shouldReceive('forget')->andReturn(true);
    }

    public function testHome()
    {
        // Create test data using factories
        Admin::factory()->count(1)->create();

        $topCategories = Category::factory()->count(6)->create(['status' => 1]);
        $parentCategories = Category::factory()->count(3)->create(['category_id' => null]);
        $latestCollections = Collection::factory()->count(4)->create(['status' => 1]);
        $allCollections = Collection::factory()->count(6)->create(['status' => 1]);
        $trendingProducts = Product::factory()->count(15)->create(['trending' => 1, 'status' => 1]);
        $featuredProducts = Product::factory()->count(15)->create(['featured' => 1, 'status' => 1]);
        $newArrivalProducts = Product::factory()->count(15)->create(['new_arrival' => 1, 'status' => 1]);
        $brands = Brand::factory()->count(5)->create(['status' => 1]);

        // Call the controller method
        $response = $this->get('/');

        // Verify that the view data contains the expected keys
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home/Index')
            ->has('parent_categories')
            ->has('top_categories')
            ->has('latest_collections')
            ->has('trending')
            ->has('brands')
            ->has('featured')
            ->has('collections')
            ->has('new_arrival')
            ->has('settings')
        );

        // Verify the structure of the data
        $response->assertStatus(200);
    }
}
