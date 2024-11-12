<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Http;
use App\Models\Category;
class RolesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('roles');
        $this->create_page_name=__('create role');
        $this->edit_page_name=__('edit role');
    }
    /**
     * Display a listing of the resource.
     */

    public function test(){
        // $women_chilodren=Category::where('category_id',16)->where('id','!=',363)->get();
        // foreach($women_chilodren as $child){
        //     $child->update([
        //         'category_id'=>363
        //     ]);
        // }
        // return 'ok';
        // $permission = Permission::create(['name' => 'edit order','guard_name'=>'admin']);

        $permissions=[
            'view','add','edit','delete'
        ];
        foreach($permissions as $permission){
            $permission = Permission::create(['name' => $permission.' return status','guard_name'=>'admin']);
        }
    }

    public function index()
    {
        $roles=Role::with('permissions')->latest()->paginate(5);
        if(admin()->hasPermissionTo('view role')){
        return inertia('Roles/Index',['roles'=>$roles])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }


    public function create()
    {
        $permissions=Permission::get();
        if(admin()->hasPermissionTo('add role')){
        return inertia('Roles/Create',['permissions'=>$permissions])->with(['page_name'=>$this->create_page_name]);
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
        ]);
        $data['guard_name']='admin';
        $role=Role::create($data);
        $permissions=$request->permissions;
        if($permissions){
            $role->givePermissionTo($permissions);
        }

        return redirect()->route('roles.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role=Role::with('permissions')->find($role->id);
        $permissions=Permission::get();
        if(admin()->hasPermissionTo('edit role')){
        return inertia('Roles/Edit',compact('role','permissions'))->with(['page_name'=>$this->edit_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
        ]);
        $role->update($data);
        $permissions=Permission::get();
        $role->revokePermissionTo($permissions);
        $role->givePermissionTo($request->permissions);
        return redirect()->route('roles.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if(admin()->hasPermissionTo('delete role')){
        $role->delete();
        return redirect()->route('roles.index')->with('success',__('item deleted successfully'));
        }else{
            return  no_permission_redirect();
        }
    }
    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Role::destroy($request->checked);
            return redirect()->route('roles.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('roles.index');
        }
    }

    public function filter(Request $request){
        $roles=Role::with('permissions');
        if($request->search){
            $roles->where('name','like','%'.$request->search.'%');
        }
        $data=$roles->paginate(5);
        return inertia('Roles/Index',['roles'=>$data]);
    }

    public function test2(){

                // base_url
                $base_url = "https://cdn.dsmcdn.com";

                // get product info
                $product_link = "https://www.trendyol.com/penti/cuteness-printed-pembe-gomlek-pantolon-pijama-takimi-p-805630240?boutiqueId=647008&merchantId=4442&filterOverPriceListings=false&sav=true";
                // $product_link = "https://www.trendyol.com/cool-sexy/kadin-v-yaka-bluz-pudra-ayd279-p-802219277?boutiqueId=634268&merchantId=968";
                $response = file_get_contents($product_link);

                // Find JSON data within the script tag
                preg_match('/window\.__PRODUCT_DETAIL_APP_INITIAL_STATE__\s*=\s*(.*?);/', $response, $matches);

                if (isset($matches[1])) {
                    // Parse the JSON object
                    $product_data = json_decode($matches[1], true);

                    // Extract product information
                    $product_data = $product_data['product'];
                    $groupId = $product_data['productGroupId'];

                    $apiUrl = "https://public.trendyol.com/discovery-web-websfxproductgroups-santral/api/v1/product-groups/".$groupId;

                    // $jsonData = file_get_contents($apiUrl);

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $apiUrl);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                    $response = curl_exec($curl);
                    $data = json_decode($response, true);
                    // print_r($jsonData);
                    // Decode JSON data
                        if(count($data['result']['slicingAttributes'])>0){
                            $products = $data['result']['slicingAttributes'][0]['attributes'];
                            // print_r(json_encode($products));
                            foreach ($products as $index =>$product) {
                               return $this->group_single($product,$base_url);
                            }
                        }else{
                            $desc='';
                            $descriptions = $product_data['contentDescriptions'];
                            foreach ($descriptions as $description) {
                                $desc.=$description['description'].' ';
                            }
                           $attribute_name = count($product_data['variants'])>0?$product_data['variants'][0]['attributeName']:null;
                            return $product_data;
                        }



                } else {
                    echo "JSON data not found."."</br>";
                }
    }
    public function test3(){

                // base_url
                $base_url = "https://cdn.dsmcdn.com";

                // get product info
                $product_link = "https://www.trendyol.com/mango/pamuklu-logolu-tisort-p-783542965?merchantId=104723&boutiqueId=61&v=xs";
                // $product_link = "https://www.trendyol.com/cool-sexy/kadin-v-yaka-bluz-pudra-ayd279-p-802219277?boutiqueId=634268&merchantId=968";
                $response = file_get_contents($product_link);

                // Find JSON data within the script tag
                preg_match('/window\.__PRODUCT_DETAIL_APP_INITIAL_STATE__\s*=\s*(.*?);/', $response, $matches);

                if (isset($matches[1])) {
                    // Parse the JSON object
                    $product_data = json_decode($matches[1], true);

                    // Extract product information
                    $product_data = $product_data['product'];
                    $groupId = $product_data['productGroupId'];

                    $apiUrl = "https://public.trendyol.com/discovery-web-websfxproductgroups-santral/api/v1/product-groups/".$groupId;

                    $jsonData = file_get_contents($apiUrl);
                    // print_r($jsonData);
                    // Decode JSON data
                    $data = json_decode($jsonData, true);
                        if(count($data['result']['slicingAttributes'])>0){
                            $products = $data['result']['slicingAttributes'][0]['attributes'];
                            // print_r(json_encode($products));
                            foreach ($products as $index =>$product) {
                               return $this->group_single($product,$base_url);
                            }
                        }else{
                            $desc='';
                            $descriptions = $product_data['contentDescriptions'];
                            foreach ($descriptions as $description) {
                                $desc.=$description['description'].' ';
                            }
                           $attribute_name = count($product_data['variants'])>0?$product_data['variants'][0]['attributeName']:null;
                            return $attribute_name;
                        }



                } else {
                    echo "JSON data not found."."</br>";
                }
    }


    public function group_single($product,$base_url){
            $id = $product['contents'][0]['id'];
            // echo $id;
            $apiUrl2 = trim("https://public.trendyol.com/discovery-web-productgw-service/api/productDetail/" . strval($id) . "?storefrontId=1&culture=tr-TR&linearVariants=true&isLegalRequirementConfirmed=false&channelId=1");

            $jsonData = file_get_contents($apiUrl2);
            // print_r($jsonData);
            $data = json_decode($jsonData, true);
            return $data['result'];
            // 1- name
            $name = $data['result']['name'];
            // 2- images
            $images=[];
            $images_data = $data['result']['images'];
            foreach ($images_data as $key => $img) {
                $link = $base_url.$img;
                $images[]=$link;
            }
            // 3- price
            $price=$data['result']['price']['originalPrice']['value'];

            // 4- desc
            $desc='';
            $descriptions = $data['result']['descriptions'];
            $attribute_name = count($data['result']['variants'])>0?$data['result']['variants'][0]['attributeName']:null;
            // return $attribute_name;
    }
}
