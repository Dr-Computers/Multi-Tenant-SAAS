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
        $amenities = RealestateAmenity::get();
        return view('company.realestate.amenities.index', compact('amenities'));
    }

    public function create()
    {

        return view('company.realestate.amenities.request-form');
    }

    public function store(Request $request)
    {
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
    }

    public function edit(RealestateAddonRequest $amenity)
    {
        return view('company.realestate.amenities.request-show', compact('amenity'));
    }


    public function destroy(RealestateAddonRequest $amenity)
    {
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
    }

    public function show()
    {
        $amenities =  RealestateAddonRequest::where('requesting_type', 'amenity')->where('company_id', Auth::user()->creatorId())->get();
        return view('company.realestate.amenities.requests', compact('amenities'));
    }
}
