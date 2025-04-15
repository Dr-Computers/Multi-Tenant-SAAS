<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = RealestateAmenity::get();
        return view('company.realestate.amenities.index', compact('amenities'));
    }

    public function create()
    {

        return view('company.realestate.amenities.form');
    }

    public function store(Request $request){
        $amenity                   = new RealestateAddonRequest();
        $amenity->requesting_type  = 'amenity';
        $amenity->request_for      = $request->category;
        $amenity->verified_by      = null;
        $amenity->notes            = $request->notes;
        $amenity->status           = 0;
        $amenity->save();
        return redirect()->back()->with('success', 'New Amenity request submited.');

    }


    public function destroy(RealestateAddonRequest $amenity)
    {
        $amenity->delete();
        return redirect()->back()->with('success', 'Amenity request deleted.');
    }

    public function show(RealestateAddonRequest $amenity)
    {
        return view('company.realestate.amenities.show', compact('amenity'));
    }
}
