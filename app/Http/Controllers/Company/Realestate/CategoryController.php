<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class  CategoryController extends Controller
{
    public function index()
    {
        $categories = RealestateCategory::get();
        return view('company.realestate.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('company.realestate.categories.request-form');
    }

    public function store(Request $request)
    {
        $category                   = new RealestateAddonRequest();
        $category->company_id       = Auth::user()->creatorId();
        $category->requesting_type  = 'category';
        $category->request_for      = $request->name;
        $category->verified_by      = null;
        $category->notes            = $request->notes;
        $category->status           = 0;
        $category->save();
        return redirect()->back()->with('success', 'New Category Request submited.');
    }


    public function destroy(RealestateAddonRequest $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function edit(RealestateAddonRequest $category)
    {
        return view('company.realestate.categories.request-show',compact('category'));
    }

    public function show()
    {
        $categories =  RealestateAddonRequest::where('requesting_type','category')->where('company_id',Auth::user()->creatorId())->get();
        return view('company.realestate.categories.requests', compact('categories'));
    }
}
