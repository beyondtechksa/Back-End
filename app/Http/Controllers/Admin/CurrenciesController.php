<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CurrenciesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('currencies');
        $this->create_page_name=__('create currency');
        $this->edit_page_name=__('edit currency');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies=Currency::with('admin')->latest()->paginate(15);
        if(admin()->hasPermissionTo('view currency')){
        return inertia('Currencies/Index',['currencies'=>$currencies])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Currency $currency)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add currency')){
        return inertia('Currencies/Create')->with(['page_name'=>$this->create_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'flag' => 'required|string|max:255',
            'prefix' => 'required|string|max:255',
            'exchange_rate' => 'required',
            'shipping_fees' => 'nullable',
            'free_shipping_amount' => 'nullable',
            'country_tax' => 'nullable',
            'country_tax_prefix' => 'nullable|string|max:255',
        ]);
        $currency=Currency::create($data);
        Artisan::call('cache:clear');

        return redirect()->route('currencies.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        if(admin()->hasPermissionTo('edit currency')){
        return inertia('Currencies/Edit',compact('currency'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, currency $currency)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'flag' => 'required|string|max:255',
            'prefix' => 'required|string|max:255',
            'exchange_rate' => 'required',
            'shipping_fees' => 'nullable',
            'free_shipping_amount' => 'nullable',
            'country_tax' => 'nullable',
            'country_tax_prefix' => 'nullable|string|max:255',
        ]);
        $currency->update($data);
        Artisan::call('cache:clear');

        return redirect()->route('currencies.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();
        Artisan::call('cache:clear');

        return redirect()->route('currencies.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            currency::destroy($request->checked);
            Artisan::call('cache:clear');

            return redirect()->route('currencies.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('currencies.index');
        }
    }

    public function filter(Request $request){
        $currencies=Currency::with('admin')->latest();
        if($request->search){
            $currencies->where('name','like','%'.$request->search.'%');
            $currencies->orWhere('prefix','like','%'.$request->search.'%');
        }
        $data=$currencies->paginate(15);
        return inertia('Currencies/Index',['currencies'=>$data])->with(['page_name'=>__('filter')]);
    }




}
