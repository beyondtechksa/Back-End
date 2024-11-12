<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PriceFormula;
use App\Models\Currency;

class FormulasController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('formulas');
        $this->create_page_name=__('create pricing formula');
        $this->edit_page_name=__('edit pricing formula');
        $this->currencies=Currency::get();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formulas=PriceFormula::latest()->paginate(15);
        if(admin()->hasPermissionTo('view pricing formula')){
        return inertia('Formulas/Index',['formulas'=>$formulas])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(PriceFormula $formula)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add pricing formula')){
        return inertia('Formulas/Create',['currencies'=>$this->currencies])->with(['page_name'=>$this->create_page_name]);
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
            'discount_percentage_selling_price' => 'required|numeric|'.decimal_validation(),
            'packaging_shipping_fees' => 'required|numeric|'.decimal_validation(),
            'management_fees' => 'required|numeric|'.decimal_validation(),
            'profit_percentage' => 'required|numeric|'.decimal_validation(),
            'commercial_percentage' => 'required|numeric|'.decimal_validation(),
            'manual_price_adjustment' => 'required|numeric|'.decimal_validation(),
        ]);
        $formula=PriceFormula::create($data);
        return redirect()->route('formulas.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceFormula $formula)
    {
        if(admin()->hasPermissionTo('edit pricing formula')){
            $currencies=$this->currencies;
        return inertia('Formulas/Edit',compact('formula','currencies'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceFormula $formula)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'discount_percentage_selling_price' => 'required|numeric|'.decimal_validation(),
            'packaging_shipping_fees' => 'required|numeric|'.decimal_validation(),
            'management_fees' => 'required|numeric|'.decimal_validation(),
            'profit_percentage' => 'required|numeric|'.decimal_validation(),
            'commercial_percentage' => 'required|numeric|'.decimal_validation(),
            'manual_price_adjustment' => 'required|numeric|'.decimal_validation(),
        ]);
        $formula->update($data);
        return redirect()->route('formulas.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceFormula $formula)
    {
        $formula->delete();
        return redirect()->route('formulas.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            PriceFormula::destroy($request->checked);
            return redirect()->route('formulas.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('formulas.index');
        }
    }

    public function filter(Request $request){
        $formulas=PriceFormula::latest();
        if($request->search){
            $formulas->where('name','like','%'.$request->search.'%');
        }
        $data=$formulas->paginate(15);
        return inertia('Formulas/Index',['formulas'=>$data])->with(['page_name'=>__('filter')]);
    }

    

    
}
