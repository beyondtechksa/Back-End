<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Http\Resources\UserResource;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserNotification;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->page_name = 'users';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!admin()->hasPermissionTo('view customer'))
            return no_permission_redirect();
        $users = UserResource::collection(User::with(['address'])->latest()->paginate(20));
        return inertia('Users/Index', ['users' => $users])->with(['page_name' => $this->page_name]);
    }

    /**
     * Show the form for creating a new resource.
     */
//    public function create()
//    {
//        return inertia('Users/Create')->with(['page_name'=>$this->page_name]);
//    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:'.User::class,
//            'password' => ['required', 'confirmed', Rules\Password::defaults()],
//            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
//        ]);
//        $avatar=null;
//        if($request->avatar){
//            $avatar=uploadImage($request->file('avatar'),'users');
//        }
//        $user = User::create([
//            'name' => $request->name,
//            'email' => $request->email,
//            'password' => Hash::make($request->password),
//            'avatar'=>$avatar
//        ]);
//        return redirect()->route('users.index')->with('success',__('item created successfully'));
//    }

    /**
     * Display the specified resource.
     */


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
    public function destroy(User $user)
    {
        \File::delete(get_main_path($user->avatar));
        $user->delete();
        return redirect()->route('users.index')->with('success', __('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if (count($request->checked) > 0) {
            $items = User::whereIn('id', $request->checked)->get();
            foreach ($items as $item) {
                \File::delete(get_main_path($item->avatar));
                $item->delete();
            }
            return redirect()->route('users.index')->with('success', __('item deleted successfully'));
        } else {
            return redirect()->route('users.index');
        }
    }

    public function filter(Request $request)
    {
        $users = User::latest();
        if ($request->search) {
            $users->where('name', 'like', '%' . $request->search . '%');
        }
        if($request->created_at){
            $range = explode(" to ", $request->created_at);
            if (count($range) >= 2) {
                $users->whereBetween('created_at', $range);
            } else {
                $users->whereDate('created_at', $request->created_at);
            }
        }
        $data = UserResource::collection($users->paginate(5));
        return inertia('Users/Index', ['users' => $data]);
    }

    public function export()
    {
        return Excel::download(new CustomersExport(), 'customers.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function show($id)
    {
        $user = User::with(['addresses'])->withCount(['orders','addresses'])->find($id);
        $orders = DB::table('orders')
          ->where('user_id','=',$id)
            ->select(
                'id', 'user_id', 'subtotal_amount', 'shipping',
                'discount', 'address', 'total_amount','status',
                DB::raw("DATE(created_at) as order_created_at"),
            )
            ->orderBy('id', 'DESC')
            ->paginate(20);
        return inertia('Users/Show', ['user' => $user, 'orders' => $orders])->with(['page_name' => 'User Info']);

    }


    public function send_mail_fo_all(Request $request){
        $request->validate([
            'subject'=>'required|string|max:255',
            'message'=>'required|string',
        ]);
        $users=User::get();
        $subject=$request->subject;
        $message=$request->message;
        $link=$request->link;
        Notification::send($users, new UserNotification($subject,$message,$link));
        return 'ok';
    }
}
