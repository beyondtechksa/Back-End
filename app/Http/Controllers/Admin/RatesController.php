<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rates;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Http\Resources\UserResource;

class RatesController extends Controller
{
    public function __construct()
    {
        $this->page_name = 'rates';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!admin()->hasPermissionTo('view rates'))
            return no_permission_redirect();

        $rates = Rates::with([
            'product:id,name_ar,name_en',
            'user:id,name,email,phone'
        ])->latest()->paginate(10);
        return inertia('Rates/Index', ['rates' => $rates])->with(['page_name' => $this->page_name]);
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
//    public function edit(User $user)
//    {
//        return inertia('Users/Edit',compact('user'))->with(['page_name'=>$this->page_name]);
//    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(Request $request, User $user)
//    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
//            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
//            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
//        ]);
//        $data=[
//            'name'=>$request->name,
//            'email'=>$request->email,
//        ];
//        if($request->password){
//            $data['password']=Hash::make($request->password);
//        }
//        if($request->avatar){
//            \File::delete(get_main_path($user->avatar));
//            $data['avatar']=uploadImage($request->file('avatar'),'users');
//        }
//        $user->update($data);
//        return redirect()->route('users.index')->with('success',__('item updated successfully'));
//    }

    /**
     * Remove the specified resource from storage.
     */
    public function changeStatus($id)
    {
        $rate = Rates::find($id);
        $status =$rate->status;
        $rate->update([
            'status' => $status == 1 ? 0 : 1
        ]);
        return redirect()->route('rates.index')->with('success', __('item updated successfully'));

    }
    public function destroy(Rates $rate)
    {
        $rate->delete();
        return redirect()->route('rates.index')->with('success', __('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if (count($request->checked) > 0) {
            $items = Rates::whereIn('id', $request->checked)->get();
            foreach ($items as $item) {
                $item->delete();
            }
            return redirect()->route('rates.index')->with('success', __('item deleted successfully'));
        } else {
            return redirect()->route('rates.index');
        }
    }

    public function filter(Request $request)
    {
        $rates = Rates::latest();
        if ($request->search) {
            $rates->where('comment', 'like', '%' . $request->search . '%')
                ->orWhere('rate', '==' . $request->search . '%');
        }
        $data=$rates->paginate(10);
        return inertia('Rates/Index', ['rates' => $data]);
    }
}
