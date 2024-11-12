<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Word;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminsController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('admins');
        $this->create_page_name=__('create admin');
        $this->edit_page_name=__('edit admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins=AdminResource::collection(Admin::latest()->paginate(5));
        if(admin()->hasPermissionTo('view admin')){
            return inertia('Admins/Index',['admins'=>$admins])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

 

    public function create()
    {
        $roles=Role::get();
        if(admin()->hasPermissionTo('add admin')){
        return inertia('Admins/Create',compact('roles'))->with(['page_name'=>$this->create_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.Admin::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
        ]);
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar'=>$request->avatar,
        ]);
        $admin->assignRole($request->role);
        return redirect()->route('admins.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $admin=New AdminResource($admin);
        $roles=Role::get();
        if(admin()->hasPermissionTo('edit admin')){
        return inertia('Admins/Edit',compact('admin','roles'))->with(['page_name'=>$this->edit_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,'.$admin->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'avatar' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
        ]);
        $data=[
            'name'=>$request->name,
            'email'=>$request->email,
        ];
        if($request->password){
            $data['password']=Hash::make($request->password);
        }
        if($request->avatar){
            $data['avatar']=$request->avatar;
        }
        $admin->update($data);
        if(count($admin->roles)>0){
            $admin->removeRole($admin->roles[0]);
        }
        $role=Role::where('name',$request->role)->first();
        if($role){
            $admin->assignRole($request->role);
        }
        return redirect()->route('admins.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if(admin()->hasPermissionTo('delete admin')){
        $admin->delete();
        return redirect()->route('admins.index')->with('success',__('item deleted successfully'));
        }else{
            return  no_permission_redirect(); 
        }
    }
    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Admin::destroy($request->checked);
            return redirect()->route('admins.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('admins.index');
        }
    }

    public function filter(Request $request){
        $admins=Admin::latest()->where("type",'!=',1);
        if($request->search){
            $admins->where('name','like','%'.$request->search.'%');
        }
        $data=$admins->paginate(5);
        return inertia('Admins/Index',['admins'=>$data]);
    }
}
