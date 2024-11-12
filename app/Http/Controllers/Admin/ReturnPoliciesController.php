<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReturnPolicy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ReturnPoliciesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('return policies');
        $this->create_page_name=__('create return policy');
        $this->edit_page_name=__('edit return policy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $return_policies=ReturnPolicy::latest()->paginate(15);
        if(admin()->hasPermissionTo('view return policy')){
        return inertia('ReturnPolicies/Index',['return_policies'=>$return_policies])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(ReturnPolicy $return_policy)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add return policy')){
        return inertia('ReturnPolicies/Create')->with(['page_name'=>$this->create_page_name]);
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
            'desc' => 'required|array',
            'period' => 'required|integer|min:1',
            'status' => 'nullable',
        ]);
        $return_policy=ReturnPolicy::create($data);
        return redirect()->route('return_policies.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnPolicy $return_policy)
    {
        if(admin()->hasPermissionTo('edit return policy')){
        return inertia('ReturnPolicies/Edit',compact('return_policy'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnPolicy $return_policy)
    {
        $data=$request->validate([
            'name' => 'required|array',
            'desc' => 'required|array',
            'period' => 'required|integer|min:1',
            'status' => 'nullable',
        ]);
        $return_policy->update($data);
        return redirect()->route('return_policies.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnPolicy $return_policy)
    {
        $return_policy->delete();
        return redirect()->route('return_policies.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            ReturnPolicy::destroy($request->checked);
            return redirect()->route('return_policies.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('return_policies.index');
        }
    }





}
