<?php

namespace App\Http\Controllers\Company\Realestate;

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
            return view('company.realestate.landmarks.index', compact('landmarks'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {

        if (Auth::user()->can('manage landmark request')) {
            return view('company.realestate.landmarks.request-form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('manage landmark request')) {
            $landmark                   = new RealestateAddonRequest();
            $landmark->company_id       = Auth::user()->creatorId();
            $landmark->requesting_type  = 'landmark';
            $landmark->request_for      = $request->name;
            $landmark->verified_by      = null;
            $landmark->notes            = $request->notes;
            $landmark->status           = 0;
            $landmark->save();
            $this->logActivity(
                'Requested a New Landmark Name',
                'Landmark Name: ' . $request->name,
                route('company.realestate.landmarks.index'),
                'Requested a New Landmark Name successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back()->with('success', 'New Landmark Request submited.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy(RealestateAddonRequest $landmark)
    {
        if (Auth::user()->can('manage landmark request')) {
            $landmark->delete();
            $this->logActivity(
                'Requested a Landmark deleted',
                'Landmark Name: ' . $landmark->request_for,
                route('company.realestate.landmarks.index'),
                'Requested a Landmark deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back()->with('success', 'Landmark Request Deleted.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit(RealestateAddonRequest $landmark)
    {
        if (Auth::user()->can('manage landmark request')) {
            return view('company.realestate.landmarks.request-show', compact('landmark'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function show()
    {
        if (Auth::user()->can('manage landmark request')) {
            $landmarks =  RealestateAddonRequest::where('requesting_type', 'landmark')->where('company_id', Auth::user()->creatorId())->get();

            return view('company.realestate.landmarks.requests', compact('landmarks'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
