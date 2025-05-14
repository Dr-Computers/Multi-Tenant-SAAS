<?php

namespace App\Http\Controllers\Admin\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AmenityController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('amenity listing')) {
            $amenities = RealestateAmenity::get();
            return view('admin.realestate.amenities.index', compact('amenities'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('create amenity')) {
            return view('admin.realestate.amenities.form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create amenity')) {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|max:100',
                // 'icon'    => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $amenity                  = new RealestateAmenity();
            $amenity->name            = $request->name;
            $amenity->save();

            return redirect()->route('admin.realestate.amenities.index')->with('success', 'Amenity created successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit amenity')) {
            $amenity = RealestateAmenity::where('id', $id)->first() ?? abort(404);

            return view('admin.realestate.amenities.form', compact('amenity'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, RealestateAmenity $amenity)
    {
        if (Auth::user()->can('edit amenity')) {
            $validator = Validator::make($request->all(), [
                'name'   => 'required|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $amenity->name            = $request->name;
            $amenity->save();

            return redirect()->route('admin.realestate.amenities.index')->with('success', 'Amenity updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(RealestateAmenity $amenity)
    {
        if (Auth::user()->can('delete amenity')) {
            $amenity->delete();
            return redirect()->back()->with('success', 'Amenity deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show(RealestateAmenity $amenity)
    {
        if (Auth::user()->can('amenity details')) {
            return view('admin.realestate.amenities.show', compact('amenity'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }



    public function requestsList(Request $request)
    {
        if (Auth::user()->can('manage amenity request')) {
            $categoryRequests = RealestateAddonRequest::where('requesting_type', 'amenity')->orderBy('status', 'asc')->get();
            return view('admin.realestate.amenities.requests', compact('categoryRequests'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsSingle(Request $request, $id)
    {
        if (Auth::user()->can('manage amenity request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'amenity')->first();
            return view('admin.realestate.amenities.request-single', compact('categoryRequest'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsAccept(Request $request, $id)
    {
        if (Auth::user()->can('manage amenity request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'amenity')->first();
            $categoryRequest->status = 1;
            $categoryRequest->save();

            $category                  = new RealestateAmenity();
            $category->name            = $categoryRequest->request_for;
            $category->save();

            return redirect()->back()->with('success', 'Amenities Request accepted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsReject(Request $request, $id)
    {
        if (Auth::user()->can('manage amenity request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'amenity')->first();
            $categoryRequest->status = -1;
            $categoryRequest->save();
            return redirect()->back()->with('error', 'Amenities Request rejected  successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
