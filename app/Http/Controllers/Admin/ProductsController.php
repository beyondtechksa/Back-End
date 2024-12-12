<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use App\Models\Size;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Option;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Attribute;
use App\Models\Collection;
use App\Classes\WebScraper;
use App\Jobs\StoreProducts;
use App\Models\TempProduct;
use Illuminate\Support\Str;
use App\Jobs\ImportProducts;
use App\Jobs\ScrapeProducts;
use App\Models\PriceFormula;
use App\Models\TrakingProduct;
use App\Models\ReturnPolicy;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Imports\ProductsImport;
use Illuminate\Validation\Rule;
use App\Http\Enums\CompanyEnums;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Facade\Drivers\BigdartDriver;
use App\Facades\Services\CompanyFacade;
use App\Facades\Services\TestCompanyFacade;
use App\Http\Resources\CategoryParentReseorce;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;
use App\Jobs\ExportProductsBatch;
use Illuminate\Support\Facades\Http;
use App\Exports\ProductsExport;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->create_page_name = __('create product');
        $this->edit_page_name = __('edit product');
        // $this->categories = Category::with('allChildren')->get();
        $this->attributes = Attribute::with('options')->orderBy('list','asc')->get();
        $this->brands = Brand::get();
        $this->currencies = Currency::get();
    }

    /**
     * Display a listing of the resource.
     */


    public function scrap(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'products_number' => 'required|numeric|min:1',
        ]);
        // $url = $request->url;
        // $pages = ceil($request->products_number / 24);
        // for ($i = 1; $i <= $pages; $i++) {
        //     ScrapeProducts::dispatch($url . '&pi=' . $i, admin()->id);
        // }
        // return '';
        $targetUrl=env('SCRAPING_URL');
        $response = Http::post($targetUrl, [
            'url' => $request->url,
            'products_number' => $request->products_number,
            'admin_id' => admin()->id,
        ]);

        \Log::info('HTTP Status: ' . $response->status());
        \Log::info('Response Body: ' . $response->body());

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            \Log::error('Request failed: ' . $response->body());
            return response()->json(['error' => 'Something went wrong'], $response->status());
        }
    }

    public function companyScrapData(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'company_name' => [
                'required',
                'string',
                Rule::in(CompanyEnums::getCompaniesValues)
            ],
        ]);
        $driver = CompanyFacade::driver($request->company_name);
        // $driver = TestCompanyFacade::driver($request->company_name);
        $products = $driver->extract($request->category);
        return response()->json(['message' => 'true', 'products' => $products]);
    }


    public function get_temp_products($type)
    {
        return TempProduct::orderBy('product_id', 'asc')->where('admin_id', admin()->id)->where('type', $type)->paginate(200);
    }

    public function scrap_get()
    {
        $formulas = PriceFormula::get();
        $brands = $this->brands;
        $categories = categories_with_parents();
        $sizes=Size::get();
        $colors=Color::get();
        return inertia('Products/ScrapMultiple', ['formulas' => $formulas, 'brands' => $brands, 'categories' => $categories,'sizes'=>$sizes,'colors'=>$colors])->with(['page_name' => 'products']);
    }


    public function getColors()
    {
        $colors =  Color::select('id','name')->orderBy('id', 'DESC')->get();
        return response()->json(['message' => 'true', 'colors' => $colors ]);
    }

    public function getSizes()
    {
        $sizes = Size::select('id','name')->orderBy('id', 'DESC')->get();
        return response()->json(['message' => 'true', 'sizes' => $sizes ]);
    }


    public function scrapCompany()
    {
        $formulas = PriceFormula::get();
        $brands = $this->brands;
        $categories = categories_with_parents();
        $sizes=Size::get();
        $colors=Color::get();
        return inertia('Products/CompanyScrap', [
            'formulas' => $formulas,
            'brands' => $brands,
            'categories' => $categories,'sizes'=>$sizes,'colors'=>$colors
        ])->with(['page_name' => 'products']);
    }

    public function getCompanyCategories()
    {
        $company_name = request()->input('company_name') ?? null;
        Log::alert("company_name" . $company_name);
        $categories = [];
        $categories = CompanyEnums::getCategories($company_name);

        return response()->json(['message' => 'true', 'company_categories' => $categories,]);

    }


    public function getCompanies()
    {
        return response()->json(['message' => 'true', 'companies' => CompanyEnums::getCompaniesValues()]);
    }

    public function import($type)
    {

        $formulas = PriceFormula::get();
        $brands = $this->brands;
        $categories = categories_with_parents();
        return inertia('Products/ImportExcel', ['formulas' => $formulas, 'brands' => $brands, 'categories' => $categories])->with(['page_name' => 'products']);
    }
    public function import_excel_products(Request $request)
    {
        $import = new ProductsImport();
        Excel::import($import, $request->file('file')->store('excel'));
        $products = $import->imported_products;
        return response()->json(['message' => 'true', 'products' => $products]);
    }

    public function index(Request $request)
    {
        $products = Product::with('admin')->when($request->has('search'),function($query) use($request){
            $query->where('name_en','like','%'.$request->search.'%');
            $query->orwhere('name_ar','like','%'.$request->search.'%');
        })->latest()->paginate(30);
        if (admin()->hasPermissionTo('view product')) {
            return inertia('Products/Index', ['products' => $products])->with(['page_name' => 'products']);
        } else {
            return no_permission_redirect();
        }
    }

    public function advanced_products()
    {
        $products = Product::with(['category.parent.parent', 'brand', 'collection', 'admin'])->latest()->paginate(30);
        if (admin()->hasPermissionTo('view product')) {
            return inertia('Products/Advanced', ['products' => $products])->with(['page_name' => 'products']);
        } else {
            return no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Product $product)
    {
        return '';
    }

    public function create()
    {
        if (admin()->hasPermissionTo('add product')) {
            return inertia('Products/Create', [
                'categories' => categories_with_parents(),
                'attributes' => $this->attributes,
                'brands' => $this->brands,
                'currencies' => $this->currencies,
                'scrap_type' => ''
            ])->with(['page_name' => $this->create_page_name]);
        } else {
            return no_permission_redirect();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        
        $data = $request->validated();
        $product = Product::create([
            'admin_id' => admin()->id,
            'name_en' => $data['name_en'],
            'name_ar' => $data['name_ar'],
            'desc_en' => $data['desc_en'],
            'desc_ar' => $data['desc_ar'],
            'category_id' => $data['category_id'],
            'currency_id' => $data['currency_id'],
            'pricing_type' => $data['pricing_type'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'discount_percentage_selling_price' => $data['discount_percentage_selling_price']??0,
            'packaging_shipping_fees' => $data['packaging_shipping_fees'],
            'management_fees' => $data['management_fees'],
            'profit_percentage' => $data['profit_percentage'],
            'tax_percentage' => $data['tax_percentage'],
            'commercial_percentage' => $data['commercial_percentage'],
            'manual_price_adjustment' => $data['manual_price_adjustment'],
            'final_price' => $data['final_price'],
            'final_selling_price' => $data['final_selling_price'],
            'quantity' => $data['quantity'],
            'shipping' => $data['shipping'],
            'length' => $data['length'],
            'width' => $data['width'],
            'height' => $data['height'],
            'dimension_unit' => $data['dimension_unit'],
            'weight' => $data['weight'],
            'weight_unit' => $data['weight_unit'],
            'discount_price' => $data['discount_price'],
            'image' => $data['image'],
            'start_at' => $data['start_at'],
            'end_at' => $data['end_at'],
        ]);
        if (!empty($data['attributes_ids'])) {
            foreach ($data['attributes_ids'] as $attribute) {
                if (!empty($attribute['options'])) {
                    $attrib = ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attribute['id'],
                    ]);

                    foreach ($attribute['options'] as $option) {
                        if (array_key_exists('price', $option) && array_key_exists('quantity', $option)) {
                            ProductOption::create([
                                'option_id' => $option['id'],
                                'product_attribute_id' => $attrib->id,
                                'price' => $option['price'],
                                'quantity' => $option['quantity'],
                            ]);
                        }
                    }
                }
            }
        }

        if (!empty($data['files'])) {
            foreach ($data['files'] as $file) {
                if ($file['image'] != null) {
                    File::create([
                        'product_id' => $product->id,
                        'image' => $file['image']
                    ]);
                }
            }
        }


        return redirect()->route('products.index')->with('success', __('Product Created Sccessfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // return $product->product_attributes;
        if (admin()->hasPermissionTo('edit product')) {
            $categories = categories_with_parents();
            $attributes = $this->attributes;
            $brands = $this->brands;
            $currencies = $this->currencies;
            $product_attributes = ProductAttribute::where('product_id', $product->id)->get();
            $files = File::where('product_id', $product->id)->select('id', 'image')->get();

            return inertia('Products/Edit', compact(
                'product',
                'product_attributes',
                'files',
                'categories',
                'attributes',
                'brands',
                'currencies',
            ))->with(['page_name' => $this->edit_page_name]);
        } else {
            return no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, product $product)
    {
        $data = $request->validated();

        $product->update([
            'name_en' => $data['name_en'],
            'name_ar' => $data['name_ar'],
            'desc_en' => $data['desc_en'],
            'desc_ar' => $data['desc_ar'],
            // 'sku' => $data['sku'],
            'category_id' => $data['category_id'],
            'currency_id' => $data['currency_id'],
            'pricing_type' => $data['pricing_type'],
            'brand_id' => $data['brand_id'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'discount_percentage_selling_price' => $data['discount_percentage_selling_price'],
            'packaging_shipping_fees' => $data['packaging_shipping_fees'],
            'management_fees' => $data['management_fees'],
            'profit_percentage' => $data['profit_percentage'],
            'tax_percentage' => $data['tax_percentage'],
            'commercial_percentage' => $data['commercial_percentage'],
            'manual_price_adjustment' => $data['manual_price_adjustment'],
            'final_price' => $data['final_price'],
            'final_selling_price' => $data['final_selling_price'],
            'quantity' => $data['quantity'],
            'shipping' => $data['shipping'],
            'length' => $data['length'],
            'width' => $data['width'],
            'height' => $data['height'],
            'dimension_unit' => $data['dimension_unit'],
            'weight' => $data['weight'],
            'weight_unit' => $data['weight_unit'],
            'discount_price' => $data['discount_price'],
            'image' => $data['image'],
            'start_at' => $data['start_at'],
            'end_at' => $data['end_at'],
        ]);

        $prodAttri = ProductAttribute::where('product_id', $product->id)->pluck('id');
        if (!empty($prodAttri)) {
            ProductAttribute::destroy($prodAttri);
        }
        if (!empty($data['attributes_ids'])) {
            foreach ($data['attributes_ids'] as $attribute) {
                if (!empty($attribute['options'])) {
                    $attrib = ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attribute['id'],
                    ]);

                    foreach ($attribute['options'] as $option) {
//                            dd($option['id']);
                        ProductOption::create([
                            'option_id' => $option['option_id'] ?? $option['id'],
                            'product_attribute_id' => $attrib->id,
                            'price' => $option['price'],
                            'quantity' => $option['quantity'],
                        ]);

                    }
                }
            }
        }

        $files = File::where('product_id', $product->id)->get();
        if (!empty($files)) {
            $files->each->delete();
        }

        if (!empty($data['files'])) {
            foreach ($data['files'] as $file) {
                if ($file['image'] != null) {
                    File::create([
                        'product_id' => $product->id,
                        'image' => $file['image']
                    ]);
                }
            }
        }


        return redirect()->route('products.index')->with('success', __('Product Updated Sccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', __('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if (count($request->checked) > 0) {
            product::destroy($request->checked);
            return redirect()->route('products.index')->with('success', __('item deleted successfully'));
        } else {
            return redirect()->route('products.index');
        }
    }

    public function filter(Request $request)
    {
        $products = Product::latest();
        if ($request->search) {
            $products->where('name', 'like', '%' . $request->search . '%');
            $products->orWhere('sku', $request->search);
        }
        $data = $products->paginate(30);
        return inertia('Products/Index', ['products' => $data])->with(['page_name' => __('filter')]);
    }


    public function save_scraped_products(Request $request)
    {
        $request->validate([
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'formula_id' => 'required|numeric',
        ]);
        $products = TempProduct::where('admin_id', admin()->id)->where('type', $request->type)->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                StoreProducts::dispatch($request->all(), $product, admin()->id);
            }

            return 'Products are being previewed! You can leave the page while the products are saved';
        }

    }


    public function save_scraped_company_products(Request $request)
    {
        $request->validate([
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'formula_id' => 'required|numeric',
        ]);

        $products = TempProduct::where('admin_id', admin()->id)->where('type', 'company')->get();

        if (count($products) > 0) {

            foreach ($products as $product) {
                try{
                    StoreProducts::dispatch($request->all(), $product, admin()->id);
                }catch(\Exception $e){
                    \Log::info($e->getMessage());
                }
            }

            return 'Products are being previewed! You can leave the page while the products are saved';
        }

    }

    public function products_search()
    {
        $options = Option::get();
        $brands = Brand::get();
        $vendors = Vendor::get();
        $collections = Collection::get();
        $admins = Admin::get();
        $formulas = PriceFormula::get();
        $currencies = Currency::get();
        $categories = categories_with_parents();
        $attributes = $this->attributes;
        $sizes = Size::where('status',1)->get();
        $colors = Color::where('status',1)->get();
        $return_policies = ReturnPolicy::where('status',1)->get();
        return inertia('Products/Search', [
            'options' => $options,
            'categories' => $categories,
            'brands' => $brands,
            'vendors' => $vendors,
            'admins' => $admins,
            'formulas' => $formulas,
            'collections' => $collections,
            'attributes' => $attributes,
            'currencies' => $currencies,
            'sizes' => $sizes,
            'colors' => $colors,
            'return_policies' => $return_policies,
        ])->with(['page_name' => __('search products')]);
    }

    public function delete_tem_product($id)
    {
        $temp_product = TempProduct::find($id);
        $temp_product->delete();
    }

    public function delete_multiple_tem_product(Request $request)
    {
        if (count($request->checked) > 0) {
            TempProduct::destroy($request->checked);
            return 'deleted';
        } else {
            return 'ok';
        }
    }

    public function search_products(Request $request)
{


    $order_by = $request->order_by ?? 'products.id';
    $order_type = $request->order_type ?? 'desc';

    $products = Product::with(['category', 'brand', 'collection', 'files', 'admin', 'product_attributes','sizes'])
                        ->orderBy($order_by, $order_type);

    $this->applyFilters($products, $request);

    $pagination = $request->show_products ?? 50;

    return response()->json([
        'products' => $products->paginate($pagination),
        'totalCount' => 0,
    ]);
}

private function applyFilters($products, Request $request)
{
    if ($request->name_en) {
        $products->where('name_en', 'like', '%' . $request->name_en . '%');
    }
    if ($request->name_ar) {
        $products->where('name_ar', 'like', '%' . $request->name_ar . '%');
    }
    if ($request->name_tr) {
        $products->where('name_tr', 'like', '%' . $request->name_tr . '%');
    }
    if ($request->desc_tr) {
        $products->where('desc_tr', 'like', '%' . $request->desc_tr . '%');
    }

    $this->applyTranslatedStatus($products, $request);
    $this->applyDescriptionStatus($products, $request);
    $this->applyAttributesStatus($products, $request);
    $this->applyStockStatus($products, $request);
    $this->applyColorStatus($products, $request);
    $this->applyCategoryFilter($products, $request);
    $this->applyGeneralFilters($products, $request);
}

private function applyTranslatedStatus($products, Request $request)
{
    if ($request->translated_status == 1) {
        $products->whereNull('name_en')->whereNull('name_ar');
    } elseif ($request->translated_status == 2) {
        $products->whereNotNull('name_en')->whereNotNull('name_ar');
    }
}

private function applyDescriptionStatus($products, Request $request)
{
    if ($request->description_status == 1) {
        $products->whereNotNull('desc_tr');
    } elseif ($request->description_status == 2) {
        $products->whereNull('desc_tr');
    }
}

private function applyAttributesStatus($products, Request $request)
{
    if ($request->attributes_status === "1") {
        $products->whereHas('product_attributes.product_options');
    } elseif ($request->attributes_status === "2") {
        $products->whereDoesntHave('product_attributes.product_options');
    }

    if ($request->option_id) {
        $products->whereHas('product_attributes.product_options', function ($query) use ($request) {
            $query->where('option_id', $request->option_id);
        });
    }
}

private function applyStockStatus($products, Request $request)
{
    if ($request->stock_status === 'in stock') {
        // Filter products where at least one size is in stock
        $products->whereHas('sizes', function ($query) {
            $query->where('inStock', true);
        });
    } elseif ($request->stock_status === 'out of stock') {
        // Filter products where all sizes are not in stock
        $products->whereDoesntHave('sizes', function ($query) {
            $query->where('inStock', true);
        });
    }
}

private function applyColorStatus($products, Request $request)
{
    if ($request->color_status === '1') {
        $products->whereHas('colors', function ($query) {
            $query->whereNotNull('color_product.color_id');
        });
    } elseif ($request->color_status === '2') {
        $products->whereDoesntHave('colors');
    }

    if ($request->color_id) {
        $products->whereHas('colors', function ($query) use ($request) {
            $query->where('color_product.color_id', $request->color_id);
        });
    }
}

private function applyCategoryFilter($products, Request $request)
{
    if ($request->category_id) {
        $parentCategory = Category::find($request->category_id);
        if ($parentCategory) {
            $categoryIds = $parentCategory->getAllChildCategories()->pluck('id')->toArray();
            $products->whereIn('category_id', $categoryIds);
        }
    }
}

private function applyGeneralFilters($products, Request $request)
{
    $generalFilters = [
        'admin_id', 'brand_id', 'collection_id', 'company_name', 'sku', 'model_number',
        'featured', 'trending', 'new_arrival', 'related', 'highlight', 'most_sold', 'ontop',
        'price', 'sale_price', 'discount_price', 'final_price', 'final_selling_price', 'source_link'
    ];

    foreach ($generalFilters as $filter) {
        if ($request->$filter || $request->$filter === '0') {
            $products->where($filter, $request->$filter);
        }
    }

    if ($request->discount_percentage_selling_price !== null) {
        $products->where(function($query) use($request){
            $query->where('discount_percentage_selling_price', (int)$request->discount_percentage_selling_price)
                  ->orWhereNull('discount_percentage_selling_price');
        });
    }

    if ($request->range) {
        $range = explode(" to ", $request->range);
        if (count($range) === 2) {
            $products->whereBetween('created_at', $range);
        } else {
            $products->whereDate('created_at', $request->range);
        }
    }

    if (isset($request->price_range) && count($request->price_range) == 2) {
        $products->whereBetween('price', $request->price_range);
    }
    if ($request->status=="1" || $request->status=="0") {
        $products->where('status', $request->status);
    }
}

    public function move_to(Request $request)
    {
        $type = $request->type;

            // Define validation rules and update field mapping
            $typeMappings = [
                'brand' => [
                    'validation' => ['brand_id' => 'required|numeric'],
                    'field' => 'brand_id'
                ],
                'category' => [
                    'validation' => ['category_id' => 'required|numeric'],
                    'field' => 'category_id'
                ],
                'collection' => [
                    'validation' => ['collection_id' => 'required|numeric'],
                    'field' => 'collection_id'
                ],
                'model_number' => [
                    'validation' => ['model_number' => 'required|string|max:255'],
                    'field' => 'model_number'
                ],
                'return_policy' => [
                    'validation' => ['return_policy_id' => 'required|numeric'],
                    'field' => 'return_policy_id'
                ],
            ];

            if (!array_key_exists($type, $typeMappings)) {
                return response()->json(['error' => 'Invalid type'], 400);
            }

            $request->validate($typeMappings[$type]['validation']);

            $field = $typeMappings[$type]['field'];
            $value = $request->input($field);

            Product::whereIn('id', $request->products_ids)->update([$field => $value]);
    }

    public function remove_collections(Request $request){
        $products=Product::whereIn('id',$request->products_ids)->get();
        foreach ($products as $product) {
            $product->update([
                'collection_id' => null
            ]);
        }
    }


    public function multi_destroy_products(Request $request)
    {
        $checked = $request->checked;
        if (count($checked) > 0) {
            Product::destroy($checked);
        }
    }

    public function update_status(Request $request)
    {
        $checked = $request->checked;
        $column = $request->column;
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            $product->update([
                "{$column}" => $request[$column]
            ]);
        }
    }

    public function update_products_formula(Request $request)
    {
        $request->validate([
            'formula_id' => 'required|numeric'
        ]);
        $checked = $request->checked;
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            $formula = PriceFormula::find($request->formula_id);
            $final_price = calc_final_price($product->final_price, $formula);
            $product->update([
                'tax_percentage' => $formula->discount_percentage_selling_price, // vat
                'packaging_shipping_fees' => $formula->packaging_shipping_fees,
                'management_fees' => $formula->management_fees,
                'profit_percentage' => $formula->profit_percentage,
                'commercial_percentage' => $formula->commercial_percentage,  // shipping
                'manual_price_adjustment' => $formula->manual_price_adjustment,
                'final_selling_price' => $final_price
            ]);
        }
    }

    public function update_products_group(Request $request)
    {
        $request->validate([
            'primary_id' => 'required|numeric'
        ]);
        $checked = $request->checked;
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            $product->update([
                'group_id' => $request->primary_id != $product->id ? $request->primary_id : null,
            ]);
        }
    }

    public function update_products_discount(Request $request)
    {
        $request->validate([
            'discount_percentage_selling_price' => 'required'
        ]);
        $checked = $request->checked;
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            $final_price=calc_product_finale_price($product,$request->discount_percentage_selling_price);
            $product->update([
                'discount_percentage_selling_price' => $request->discount_percentage_selling_price,
                'final_selling_price' =>$final_price,
            ]);
        }
    }

    public function update_products_attributes(Request $request)
    {
        $request->validate([
            'attributes_ids' => 'required|array'
        ]);
        $checked = $request->checked;
        foreach ($checked as $product_id) {
            if ($request->attribute_update_type == 'add') {
                create_product_attributes($request->attributes_ids, $product_id);
            } else {
                foreach ($request->attributes_ids as $attribute) {
                    $product_attribute = ProductAttribute::where('attribute_id', $attribute['id'])->where('product_id', $product_id)->first();
                    $filteredOptions = array_filter($attribute['options'], function ($option) {
                        return $option['status'] === true;
                    });
                    foreach ($filteredOptions as $optio) {
                        ProductOption::where('option_id', $optio['id'])->where('product_attribute_id', $product_attribute->id)->delete();
                    }

                }

            }
        }
    }

    public function update_products_colors(Request $request)
    {
        $request->validate([
            'colors_ids' => 'array',
            'sizes_ids' => 'array',
        ]);
        $checked = $request->checked;
        $colors = $request->colors_ids;
        $sizes = $request->sizes_ids;
        $sizesData = array_map(function ($sizeId) {
            return ['inStock' => true];
        }, $sizes);
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            if ($request->color_update_type == 'add') {
                $product->colors()->syncWithoutDetaching($colors);
                $product->sizes()->syncWithoutDetaching(array_combine($sizes, $sizesData));
            } else {
                $product->colors()->detach($colors);
                $product->sizes()->detach($sizes);
            }
        }
    }

    public function update_translate_products(Request $request)
    {

        $checked = $request->checked;
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            $product->update([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
            ]);
        }
    }

    public function update_translate_description(Request $request)
    {

        $checked = $request->checked;
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            $product->update([
                'desc_en' => $request->desc_en,
                'desc_ar' => $request->desc_ar
            ]);
        }
    }

    public function update_products_rename(Request $request)
    {
        $request->validate([
            'has_brand' => 'required|numeric',
            'has_vendor' => 'required|numeric',
            'has_sku' => 'required|numeric',
            'has_attribute' => 'required|numeric',
        ]);
        $checked = $request->checked;
        foreach ($checked as $product_id) {
            $product = Product::find($product_id);
            if ($request->name_ar || $request->name_en) {
                $product->update([
                    'name_ar' => $request->name_ar,
                    'name_en' => $request->name_en,
                ]);
            }
            if ($request->has_brand == 0) {
                $product->update([
                    'brand_id' => null
                ]);
            }
            if ($request->has_vendor == 0) {
                $product->update([
                    'vendor_id' => null
                ]);
            }
            if ($request->has_sku == 0) {
                $product->update([
                    'sku' => null
                ]);
            }
            if ($request->has_attribute == 0) {
                $products_attributes = ProductAttribute::where('product_id', $product_id)->pluck('id');
                ProductAttribute::destroy($products_attributes);
            }

        }
    }



    public function get_products_details($id)
    {
        $details = [];
        $product = Product::find($id);
        $category = Category::find($product->category_id);
        $brand = Brand::find($product->brand_id);
        $collection = Collection::find($product->collection_id);
        $category['parents_name'] = get_parents_name($category);
        $attributes = ProductAttribute::with([
            'attribute' => function ($query) {
                $query;
            },
            'product_options' => function ($query) {
                $query->with('option');
            }
        ])->where('product_id', $id)->get();


        $details['category'] = $category;
        $details['brand'] = $brand;
        $details['collection'] = $collection;
        $details['attributes'] = $attributes;
        return $details;
    }

    public function delete_my_jobs()
    {
        DB::table('jobs')->delete();
        return 'ok';
    }

    public function tracking_history(){
        $traking_products= TrakingProduct::with('product')->whereNotNull('product_id')
        ->orderBy('updated_at','desc')->paginate(100);
        // $traking_products= TrakingProduct::with('product')->whereNotNull('product_id')->whereColumn('price','<>','old_price')->orWhereColumn('final_price','<>','old_final_price')
        // ->orderBy('updated_at','desc')->paginate(100);
        $sizes=Size::get();
        $colors=Color::get();
        return inertia('Products/Tracking', ['traking_products' => $traking_products,'sizes'=>$sizes,'colors'=>$colors])->with(['page_name' => 'tracking history']);
    }

    public function add_ids_to_session(Request $request){
        $ids=$request->checked;
        session()->put('checked',$ids);
    }

    public function export_products_excel(){
        return Excel::download(new ProductsExport(), 'products.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

}
