<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\DataTables\Admin\Frontend\PageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Frontend\Page\StorePageRequest;
use App\Http\Requests\Admin\Frontend\Page\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:page-create'])->only('create', 'store');
        $this->middleware(['permission:page-read'])->only('index');
        $this->middleware(['permission:page-update'])->only('edit', 'update');
        $this->middleware(['permission:page-delete'])->only('destroy');
    }

    public function index(PageDataTable $dataTable)
    {
        return $dataTable->render('admin.frontend.pages.index');
    }

    public function create()
    {
        return view('admin.frontend.pages.create');
    }

    public function store(StorePageRequest $request)
    {
        Page::create($request->validated());

        return success(trans('Page Created Successfully'), route('admin.frontend.pages.index'));
    }

    public function edit(Page $page)
    {
        return view('admin.frontend.pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->update($request->validated() + [
                'slug' => $page->is_system ? $page->slug : $request->validated('slug'),
            ]);

        return success(trans('Page Updated Successfully'), route('admin.frontend.pages.index'));
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return success(trans('Page Deleted Successfully'));
    }
}
