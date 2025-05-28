<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateFurnishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityLogger;

class  FurnishingController extends Controller
{
    use ActivityLogger;
    public function index()
    {
        if (Auth::user()->can('furnishing listing')) {
            $furnishings = RealestateFurnishing::get();
            return view('company.realestate.furnishing.index', compact('furnishings'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('manage furnishing request')) {
            return view('company.realestate.furnishing.request-form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->can('manage furnishing request')) {
            $furnishing                   = new RealestateAddonRequest();
            $furnishing->company_id       = Auth::user()->creatorId();
            $furnishing->requesting_type  = 'furnishing';
            $furnishing->request_for      = $request->name;
            $furnishing->verified_by      = null;
            $furnishing->notes            = $request->notes;
            $furnishing->status           = 0;
            $furnishing->save();
            $this->logActivity(
                'Requested a New Furnishing Name',
                'Furnishing Name: ' . $request->name,
                route('company.realestate.furnishing.index'),
                'Requested a New Category Name successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back()->with('success', 'New Furnishing Request submited.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy(RealestateAddonRequest $furnishing)
    {
        if (Auth::user()->can('manage furnishing request')) {
            $furnishing->delete();
            $this->logActivity(
                'Requested a furnishing deleted',
                'Furnishing Name: ' . $furnishing->request_for,
                route('company.realestate.furnishing.index'),
                'Requested a furnishing deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back()->with('success', 'Furnishing request deleted.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit(RealestateAddonRequest $furnishing)
    {
        if (Auth::user()->can('manage furnishing request')) {
            return view('company.realestate.furnishing.request-show', compact('furnishing'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show()
    {

        if (Auth::user()->can('manage furnishing request')) {
            $furnishings =  RealestateAddonRequest::where('requesting_type', 'furnishing')->where('company_id', Auth::user()->creatorId())->get();

            return view('company.realestate.furnishing.requests', compact('furnishings'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
