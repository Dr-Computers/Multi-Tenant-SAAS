<?php

namespace App\Http\Controllers\Admin\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class  CategoryController extends Controller
{
    public function index()
    {
        $categories = RealestateCategory::get();
        return view('admin.realestate.categories.index', compact('categories'));
    }

    public function create()
    {

        return view('admin.realestate.categories.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:100',
            // 'icon'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $category                  = new RealestateCategory();
        $category->name            = $request->name;
        $category->save();

        return redirect()->route('admin.realestate.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = RealestateCategory::where('id', $id)->first() ?? abort(404);

        return view('admin.realestate.categories.form', compact('category'));
    }

    public function update(Request $request, RealestateCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $category->name            = $request->name;
        $category->save();

        return redirect()->route('admin.realestate.categories.index')->with('success', 'Owner updated successfully.');
    }

    public function destroy(RealestateCategory $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function show(RealestateCategory $category)
    {
        return view('admin.realestate.categories.show', compact('category'));
    }
}
