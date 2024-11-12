<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReturnReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ReturnReasonsController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('return reasons');
        $this->create_page_name=__('create return reason');
        $this->edit_page_name=__('edit return reason');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $return_reasons=ReturnReason::latest()->paginate(15);
        if(admin()->hasPermissionTo('view return reason')){
        return inertia('ReturnReasons/Index',['return_reasons'=>$return_reasons])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add return reason')){
        return inertia('ReturnReasons/Create')->with(['page_name'=>$this->create_page_name]);
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
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
        ]);
        $return_reason=ReturnReason::create($data);
        return redirect()->route('return_reasons.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnReason $return_reason)
    {
        if(admin()->hasPermissionTo('edit return reason')){
        return inertia('ReturnReasons/Edit',compact('return_reason'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnReason $return_reason)
    {
        $data=$request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
        ]);
        $return_reason->update($data);
        return redirect()->route('return_reasons.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnReason $return_reason)
    {
        $return_reason->delete();
        return redirect()->route('return_reasons.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            ReturnReason::destroy($request->checked);
            return redirect()->route('return_reasons.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('return_reasons.index');
        }
    }





}
