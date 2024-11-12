<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;


class PagesController extends Controller
{
    public function __construct()
    {
        $this->index_page_name = __('pages');
        $this->create_page_name = __('create page');
        $this->edit_page_name = __('edit page');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (admin()->hasPermissionTo('view page')) {

            $pages = Page::latest()
                ->when(\request()->has('search'), function ($q) {
                    $q->where('name', 'like', '%' . \request('search') . '%');
                })->paginate(20);

            return inertia('Pages/Index', ['pages' => $pages])->with(['page_name' => $this->index_page_name]);
        } else {
            return no_permission_redirect();
        }
    }


    public function get_pages()
    {
        return Page::get();
    }


    public function create()
    {
        if (admin()->hasPermissionTo('add page')) {
            return inertia('Pages/Create')->with(['page_name' => $this->create_page_name]);
        } else {
            return no_permission_redirect();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data=$request->validate([
            'name' => 'required|array',
            'title' => 'required|array',
            'desc' => 'required|array',
            'show_in_footer_bar'=>'required'
        ]);
        $page = Page::create($data);
        return redirect()->route('pages.index')->with('success', __('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        if (admin()->hasPermissionTo('edit page')) {
            return inertia('Pages/Edit', compact('page'))->with(['page_name' => $this->edit_page_name]);
        } else {
            return no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $data=$request->validate([
            'name' => 'required|array',
            'title' => 'required|array',
            'desc' => 'required|array',
            'show_in_footer_bar'=>'required'
        ]);

        $page->update($data);
        return redirect()->route('pages.index')->with('success', __('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', __('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if (count($request->checked) > 0) {
            Page::destroy($request->checked);
            return redirect()->route('pages.index')->with('success', __('item deleted successfully'));
        } else {
            return redirect()->route('pages.index');
        }
    }
}
