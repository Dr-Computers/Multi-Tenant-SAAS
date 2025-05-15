<?php

namespace App\Http\Controllers\Admin\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateLandmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityLogger;

class  LandmarkController extends Controller
{
    use ActivityLogger;

    public function index()
    {
        if (Auth::user()->can('landmark listing')) {
            $landmarks = RealestateLandmark::get();
            return view('admin.realestate.landmarks.index', compact('landmarks'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {

        if (Auth::user()->can('create landmark')) {
            return view('admin.realestate.landmarks.form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create landmark')) {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|max:100',
                // 'icon'    => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $category                  = new RealestateLandmark();
            $category->name            = $request->name;
            $category->save();

            return redirect()->route('admin.realestate.landmarks.index')->with('success', 'Landmark created successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit landmark')) {
            $landmark = RealestateLandmark::where('id', $id)->first() ?? abort(404);

            return view('admin.realestate.landmarks.form', compact('landmark'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, RealestateLandmark $landmark)
    {
        if (Auth::user()->can('edit landmark')) {
            $validator = Validator::make($request->all(), [
                'name'   => 'required|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $landmark->name            = $request->name;
            $landmark->save();

            return redirect()->route('admin.realestate.landmarks.index')->with('success', 'Owner updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(RealestateLandmark $landmark)
    {
        if (Auth::user()->can('delete landmark')) {
            $landmark->delete();
            return redirect()->back()->with('success', 'Landmark deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show(RealestateLandmark $landmark)
    {
        if (Auth::user()->can('landmark details')) {
            return view('admin.realestate.landmarks.show', compact('landmark'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function requestsList(Request $request)
    {
        if (Auth::user()->can('manage landmark request')) {
            $categoryRequests = RealestateAddonRequest::where('requesting_type', 'landmark')->orderBy('status', 'asc')->get();
            return view('admin.realestate.landmarks.requests', compact('categoryRequests'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsSingle(Request $request, $id)
    {
        if (Auth::user()->can('manage landmark request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'landmark')->first();
            return view('admin.realestate.landmarks.request-single', compact('categoryRequest'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsAccept(Request $request, $id)
    {
        if (Auth::user()->can('manage landmark request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'landmark')->first();
            $categoryRequest->status = 1;
            $categoryRequest->save();

            $category                  = new RealestateLandmark();
            $category->name            = $categoryRequest->request_for;
            $category->save();

            return redirect()->back()->with('success', 'Landmark Request accepted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function requestsReject(Request $request, $id)
    {
        if (Auth::user()->can('manage landmark request')) {
            $categoryRequest = RealestateAddonRequest::where('id', $id)->where('requesting_type', 'landmark')->first();
            $categoryRequest->status = -1;
            $categoryRequest->save();
            return redirect()->back()->with('error', 'Landmark Request rejected  successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
