<?php

namespace App\Http\Controllers\Admin\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateFurnishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class  FurnishingController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('furnishing listing')) {

            $furnishings = RealestateFurnishing::get();
            return view('admin.realestate.furnishings.index', compact('furnishings'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('create furnishing')) {
            return view('admin.realestate.furnishings.form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create furnishing')) {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|max:100',
                // 'icon'    => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $category                  = new RealestateFurnishing();
            $category->name            = $request->name;
            $category->save();

            return redirect()->route('admin.realestate.furnishings.index')->with('success', 'Furnishing created successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit furnishing')) {
            $furnishing = RealestateFurnishing::where('id', $id)->first() ?? abort(404);

            return view('admin.realestate.furnishings.form', compact('furnishing'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, RealestateFurnishing $furnishing)
    {
        if (Auth::user()->can('edit furnishing')) {
            $validator = Validator::make($request->all(), [
                'name'   => 'required|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $furnishing->name            = $request->name;
            $furnishing->save();

            return redirect()->route('admin.realestate.furnishings.index')->with('success', 'Furnishing updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(RealestateFurnishing $furnishing)
    {
        if (Auth::user()->can('delete furnishing')) {
            $furnishing->delete();
            return redirect()->back()->with('success', 'Furnishing deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show(RealestateFurnishing $furnishing)
    {
        if (Auth::user()->can('furnishing details')) {
            return view('admin.realestate.furnishings.show', compact('furnishing'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function requestsList(Request $request)
    {
        if (Auth::user()->can('manage furnishing request')) {
            $categoryRequests = RealestateAddonRequest::where('requesting_type', 'furnishing')->orderBy('status', 'asc')->get();
            return view('admin.realestate.furnishings.requests', compact('categoryRequests'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsSingle(Request $request, $id)
    {
        if (Auth::user()->can('manage furnishing request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'furnishing')->first();
            return view('admin.realestate.furnishings.request-single', compact('categoryRequest'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsAccept(Request $request, $id)
    {
        if (Auth::user()->can('manage furnishing request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'furnishing')->first();
            $categoryRequest->status = 1;
            $categoryRequest->save();

            $category                  = new RealestateFurnishing();
            $category->name            = $categoryRequest->request_for;
            $category->save();

            return redirect()->back()->with('success', 'Furnishing Request accepted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsReject(Request $request, $id)
    {
        if (Auth::user()->can('manage furnishing request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'furnishing')->first();
            $categoryRequest->status = -1;
            $categoryRequest->save();
            return redirect()->back()->with('error', 'Furnishing Request rejected  successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
