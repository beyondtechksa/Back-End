<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Currency;

class VouchersController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('vouchers');
        $this->create_page_name=__('create voucher');
        $this->edit_page_name=__('edit voucher');
        $this->currencies=Currency::get();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers=Voucher::latest()->paginate(15);
        if(admin()->hasPermissionTo('view voucher')){
        return inertia('Vouchers/Index',['vouchers'=>$vouchers])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Voucher $voucher)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add voucher')){
        return inertia('Vouchers/Create',['currencies'=>$this->currencies])->with(['page_name'=>$this->create_page_name]);
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
            'code' => 'required|string|max:10|unique:vouchers',
            'reference' => 'required|string|max:255',
            'reference_details' => 'nullable|string|max:255',
            'currency_id'=>'required|numeric',
            'amount' => 'required|numeric|'.decimal_validation(),
            'valid_untill' => 'nullable|date',
            'status' => 'nullable',
        ]);
        $voucher=Voucher::create($data);
        return redirect()->route('vouchers.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        if(admin()->hasPermissionTo('edit voucher')){
            $currencies=$this->currencies;
        return inertia('Vouchers/Edit',compact('voucher','currencies'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, voucher $voucher)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:vouchers,code,'.$voucher->id,
            'reference' => 'required|string|max:255',
            'reference_details' => 'nullable|string|max:255',
            'currency_id'=>'required|numeric',
            'amount' => 'required|numeric|'.decimal_validation(),
            'valid_untill' => 'nullable|date',
            'status' => 'nullable',
        ]);
        $voucher->update($data);
        return redirect()->route('vouchers.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Voucher::destroy($request->checked);
            return redirect()->route('vouchers.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('vouchers.index');
        }
    }

    public function filter(Request $request){
        $vouchers=Voucher::latest();
        if($request->search){
            $vouchers->where('name','like','%'.$request->search.'%');
        }
        $data=$vouchers->paginate(15);
        return inertia('Vouchers/Index',['vouchers'=>$data])->with(['page_name'=>__('filter')]);
    }

    

    
}
