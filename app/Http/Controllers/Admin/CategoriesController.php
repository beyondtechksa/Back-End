<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Http\Resources\CategoryParentReseorce;
use App\Http\Requests\Admin\CategoryRequest;

class CategoriesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('categories');
        $this->create_page_name=__('create category');
        $this->edit_page_name=__('edit category');
    }

    public function index()
    {

        $categories=Category::with('parent','admin')->withCount('products')->latest()->when(\request()->has('search') && \request('search')!='null', function ($q) {
            $q->where('name', 'like', '%' . \request('search') . '%');
        })->paginate(15);
        if(admin()->hasPermissionTo('view product category')){
        return inertia('Categories/Index',['categories'=>$categories])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Category $category)
    {
        return '';
    }
    public function tree(Category $category)
    {
        if(admin()->hasPermissionTo('view product category')){
            return inertia('Categories/ViewTree')->with(['page_name'=>__('view tree')]);
            }else{
                return  no_permission_redirect();
            }
    }
    public function top(Category $category)
    {
        if(admin()->hasPermissionTo('view product category')){
            $top_categories=top_categories();
            return inertia('Categories/Top',['categories'=>$top_categories])->with(['page_name'=>__('Top Categories')]);
            }else{
                return  no_permission_redirect();
            }
    }
    public function mobile_top(Category $category)
    {
        if(admin()->hasPermissionTo('view product category')){
            $top_categories=mobile_top_categories();
            return inertia('Categories/MobileTop',['categories'=>$top_categories])->with(['page_name'=>__('Top Categories')]);
            }else{
                return  no_permission_redirect();
            }
    }

    public function update_category_status(Request $request){
        $status=Status::find($request->id);
        $status->update(['status'=>!$status->status]);
        return 'ok';
    }

    public function create()
    {
        if(admin()->hasPermissionTo('add product category')){
        return inertia('Categories/Create',[
            'categories_with_parents'=>categories_with_parents()])->with(['page_name'=>$this->create_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }


    public function store(CategoryRequest $request)
    {
        $data=$request->validated();

        $category=Category::create($data);

        return redirect()->route('categories.index')->with('success',__('item created successfully'));
    }



    public function edit(Category $category)
    {
        if(admin()->hasPermissionTo('edit product category')){
            $parents=getAllParents($category);
            $categories_with_parents=categories_with_parents();
        return inertia('Categories/Edit',compact('category','parents','categories_with_parents'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }


    public function update(CategoryRequest $request, Category $category)
    {
        $data=$request->validated();
        $category->update($data);
        return redirect()->route('categories.index')->with('success',__('item updated successfully'));
    }


    public function getAllChildren(Category $category)
    {
        $children = $category->allChildren()->get();

        $allChildren = collect();
        foreach ($children as $child) {
            $allChildren->push($child);
            if ($child->children->isNotEmpty()) {
                $allChildren = $allChildren->merge(getAllChildren($child));
            }
        }

        return $allChildren;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Category::destroy($request->checked);
            return redirect()->route('categories.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('categories.index');
        }
    }

    public function filter(Request $request){
        $categories=Category::latest();
        if($request->search){
            $categories->where('name','like','%'.$request->search.'%');
        }
        $data=$categories->paginate(15);
        return inertia('Categories/Index',['categories'=>$data])->with(['page_name'=>__('filter')]);
    }

    public function update_categories_list(Request $request){
        $categories=$request->categories;
        foreach($categories as $key=>$value){
            Category::find($value['id'])->update(['list'=>$key+1]);
        }
    }

    public function update_categories_top_list(Request $request){
        $categories=$request->categories;
        foreach($categories as $key=>$value){
            if($request->type=='mobile'){
                Category::find($value['id'])->update(['mobile_top_list'=>$key+1]);
            }else{
                Category::find($value['id'])->update(['top_list'=>$key+1]);
            }
        }
    }

    public function get_main_parents(){
        return Category::withCount('children')->where('category_id',null)->orderBy('list','asc')->get();
    }
    public function get_children($category_id){
        return Category::withCount('children')->where('category_id',$category_id)->orderBy('list','asc')->get();
    }

    public function update_shown_status(Request $request){
        $category=Category::find($request->id);
        $status=$category->show_in_navbar==1?0:1;
        $category->update([
            'show_in_navbar'=>$status
        ]);

        return $status;
    }
    public function update_status(Request $request){
        $category=Category::find($request->id);
        $status=$category->status==1?0:1;
        $category->update([
            'status'=>$status
        ]);

        return $status;
    }
    public function update_top_status(Request $request){
        $category=Category::find($request->id);
        if($request->type=='mobile'){
            $status=$category->mark_as_mobile_top_category==1?0:1;
            $category->update([
                'mark_as_mobile_top_category'=>$status
            ]);
        } else{
            $status=$category->mark_as_top_category==1?0:1;
            $category->update([
                'mark_as_top_category'=>$status
            ]);

        }

        return $status;
    }

    public function get_main_categories(){
        return Category::where('category_id',null)->get();
    }

}
