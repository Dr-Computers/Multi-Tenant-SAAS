<?php

namespace App\Http\Controllers\Admin\Realestate;

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
            return view('admin.realestate.categories.index', compact('categories'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('create category')) {
            return view('admin.realestate.categories.form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create category')) {
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

            $this->logActivity(
                'Realestate Category Section as Created',
                'Category  Name' . $category->name,
                route('admin.realestate.categories.index'),
                'Realestate Category Section Created successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->route('admin.realestate.categories.index')->with('success', 'Category created successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit category')) {
            $category = RealestateCategory::where('id', $id)->first() ?? abort(404);

            return view('admin.realestate.categories.form', compact('category'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, RealestateCategory $category)
    {
        if (Auth::user()->can('edit category')) {
            $validator = Validator::make($request->all(), [
                'name'   => 'required|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $category->name            = $request->name;
            $category->save();

            $this->logActivity(
                'Realestate Category Section as Updated',
                'Category  Name' . $category->name,
                route('admin.realestate.categories.index'),
                'Realestate Category Section Updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->route('admin.realestate.categories.index')->with('success', 'Owner updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(RealestateCategory $category)
    {
        if (Auth::user()->can('delete category')) {
            $this->logActivity(
                'Realestate Category Section as Deleted',
                'Category  Name' . $category->name,
                route('admin.realestate.categories.index'),
                'Realestate Category Section Deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            $category->delete();

            return redirect()->back()->with('success', 'Category deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show(RealestateCategory $category)
    {
        if (Auth::user()->can('category details')) {
            return view('admin.realestate.categories.show', compact('category'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }



    public function requestsList(Request $request)
    {
        if (Auth::user()->can('manage category request')) {
            $categoryRequests = RealestateAddonRequest::where('requesting_type', 'category')->orderBy('status', 'asc')->get();
            return view('admin.realestate.categories.requests', compact('categoryRequests'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsSingle(Request $request, $id)
    {
        if (Auth::user()->can('manage category request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'category')->first();
            return view('admin.realestate.categories.request-single', compact('categoryRequest'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsAccept(Request $request, $id)
    {
        if (Auth::user()->can('manage category request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'category')->first();
            $categoryRequest->status = 1;
            $categoryRequest->save();

            $category                  = new RealestateCategory();
            $category->name            = $categoryRequest->request_for;
            $category->save();

            $this->logActivity(
                'Realestate Category Section Request Accepted',
                'Requested Category  Name' . $category->name,
                route('admin.realestate.categories.index'),
                'Realestate Category Section Request Accepted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            return redirect()->back()->with('success', 'Category Request accepted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsReject(Request $request, $id)
    {
        if (Auth::user()->can('manage category request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'category')->first();
            $categoryRequest->status = -1;
            $categoryRequest->save();

            $this->logActivity(
                'Realestate Category Section Request Rejected',
                'Requested Category  Name' . $categoryRequest->request_for,
                route('admin.realestate.categories.index'),
                'Realestate Category Section Request Rejected',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('error', 'Category Request rejected  successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
