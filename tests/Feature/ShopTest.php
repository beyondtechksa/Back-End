<?php

namespace Tests\Feature;

use Tests\TestCase;
use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Services\GlobalService;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categories = Category::factory()->count(6)->create(['status' => 1]);
        $this->parentCategories = Category::factory()->count(3)->create(['category_id' => null]);
        $this->brands = Brand::factory()->count(5)->create(['status' => 1]);
        $this->colors = Color::factory()->count(3)->create();
        $this->sizes = Size::factory()->count(3)->create();

        // Assign products to the first category
        $this->products = Product::factory()->count(15)
            ->create(['status' => 1, 'brand_id' => $this->brands->first()->id, 'category_id' => $this->categories->first()->id]);
    }

    public function testShopMethodWithoutCategory()
    {
        $response = $this->get('/shop');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home/Shop')
            ->has('products', 9)
            ->has('categories')
            ->has('colors')
            ->has('sizes')
            ->has('brands')
            ->has('request')
            ->where('category_id', null)
        );

        $response->assertStatus(200);
    }
    public function testShopMethodWithCategory()
    {
        $category = $this->categories->first(); // Ensure this fetches the correct category

        $response = $this->get("/shop?category_id={$category->id}");

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home/Shop')
            ->has('products', 9)
            ->has('categories')
            ->has('colors')
            ->has('sizes')
            ->has('brands')
            ->has('request')
        );

        $response->assertStatus(200);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
