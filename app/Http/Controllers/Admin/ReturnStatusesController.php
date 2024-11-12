<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReturnStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ReturnStatusesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('return statuses');
        $this->create_page_name=__('create return status');
        $this->edit_page_name=__('edit return status');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $return_statuses=ReturnStatus::latest()->paginate(15);
        if(admin()->hasPermissionTo('view return status')){
        return inertia('ReturnStatuses/Index',['return_statuses'=>$return_statuses])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add return status')){
        return inertia('ReturnStatuses/Create')->with(['page_name'=>$this->create_page_name]);
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
        $return_status=ReturnStatus::create($data);
        return redirect()->route('return_statuses.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnStatus $return_status)
    {
        if(admin()->hasPermissionTo('edit return status')){
        return inertia('ReturnStatuses/Edit',compact('return_status'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnStatus $return_status)
    {
        $data=$request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
        ]);
        $return_status->update($data);
        return redirect()->route('return_statuses.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnStatus $return_status)
    {
        $return_status->delete();
        return redirect()->route('return_statuses.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            ReturnStatus::destroy($request->checked);
            return redirect()->route('return_statuses.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('return_statuses.index');
        }
    }





}
