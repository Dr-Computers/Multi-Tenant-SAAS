<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityLogger;

class  CategoryController extends Controller
{
    use ActivityLogger;
    public function index()
    {
        if (Auth::user()->can('category listing')) {
            $categories = RealestateCategory::get();
            return view('company.realestate.categories.index', compact('categories'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('manage category request')) {
            return view('company.realestate.categories.request-form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('manage category request')) {
            $category                   = new RealestateAddonRequest();
            $category->company_id       = Auth::user()->creatorId();
            $category->requesting_type  = 'category';
            $category->request_for      = $request->name;
            $category->verified_by      = null;
            $category->notes            = $request->notes;
            $category->status           = 0;
            $category->save();

            $this->logActivity(
                'Requested a New Category Name',
                'Category Name: ' . $request->name,
                route('company.realestate.categories.index'),
                'Requested a New Category Name successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            return redirect()->back()->with('success', 'New Category Request submited.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy(RealestateAddonRequest $category)
    {
        if (Auth::user()->can('manage category request')) {
            $category->delete();

            $this->logActivity(
                'Requested a category deleted',
                'Category Name: ' . $category->request_for,
                route('company.realestate.categories.index'),
                'Requested a category deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', 'Category deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit(RealestateAddonRequest $category)
    {
        if (Auth::user()->can('manage category request')) {
            return view('company.realestate.categories.request-show', compact('category'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show()
    {
        if (Auth::user()->can('manage category request')) {
            $categories =  RealestateAddonRequest::where('requesting_type', 'category')->where('company_id', Auth::user()->creatorId())->get();
            return view('company.realestate.categories.requests', compact('categories'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
