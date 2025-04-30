<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AmenitiesController extends Controller
{
    public function index()
    {
        $amenities = RealestateAmenity::get();
        return view('company.realestate.amenities.index', compact('amenities'));
    }

    public function create()
    {

        return view('company.realestate.amenities.request-form');
    }

    public function store(Request $request){
        $amenity                   = new RealestateAddonRequest();
        $amenity->company_id       = Auth::user()->creatorId();
        $amenity->requesting_type  = 'amenity';
        $amenity->request_for      = $request->name;
        $amenity->verified_by      = null;
        $amenity->notes            = $request->notes;
        $amenity->status           = 0;
        $amenity->save();
        return redirect()->back()->with('success', 'New Amenity request submited.');

    }

    public function edit(RealestateAddonRequest $amenity)
    {
        return view('company.realestate.amenities.request-show',compact('amenity'));
    }


    public function destroy(RealestateAddonRequest $amenity)
    {
        $amenity->delete();
        return redirect()->back()->with('success', 'Amenity request deleted.');
    }

    public function show()
    {
        $amenities =  RealestateAddonRequest::where('requesting_type','amenity')->where('company_id',Auth::user()->creatorId())->get();
        return view('company.realestate.amenities.requests', compact('amenities'));
    }
}
