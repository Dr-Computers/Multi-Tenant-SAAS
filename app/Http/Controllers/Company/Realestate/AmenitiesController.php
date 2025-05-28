<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityLogger;

class AmenitiesController extends Controller
{

    use ActivityLogger;
    public function index()
    {
        if (Auth::user()->can('amenity listing')) {
            $amenities = RealestateAmenity::get();
            return view('company.realestate.amenities.index', compact('amenities'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {

        if (Auth::user()->can('manage amenity request')) {
            return view('company.realestate.amenities.request-form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('manage amenity request')) {
            $amenity                   = new RealestateAddonRequest();
            $amenity->company_id       = Auth::user()->creatorId();
            $amenity->requesting_type  = 'amenity';
            $amenity->request_for      = $request->name;
            $amenity->verified_by      = null;
            $amenity->notes            = $request->notes;
            $amenity->status           = 0;
            $amenity->save();
            $this->logActivity(
                'Requested a New Amenity Name',
                'Amenity Name: ' . $request->name,
                route('company.realestate.amenities.index'),
                'Requested a New Amenity Name successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back()->with('success', 'New Amenity request submited.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit(RealestateAddonRequest $amenity)
    {
        if (Auth::user()->can('manage amenity request')) {
            return view('company.realestate.amenities.request-show', compact('amenity'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy(RealestateAddonRequest $amenity)
    {
        if (Auth::user()->can('manage amenity request')) {
            $amenity->delete();

            $this->logActivity(
                'Requested a amenity deleted',
                'Amenity Name: ' . $amenity->request_for,
                route('company.realestate.amenities.index'),
                'Requested a amenity deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', 'Amenity request deleted.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show()
    {
        if (Auth::user()->can('manage amenity request')) {
            $amenities =  RealestateAddonRequest::where('requesting_type', 'amenity')->where('company_id', Auth::user()->creatorId())->get();
            return view('company.realestate.amenities.requests', compact('amenities'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
