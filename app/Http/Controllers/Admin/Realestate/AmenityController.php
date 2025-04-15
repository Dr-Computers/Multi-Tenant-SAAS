<?php

namespace App\Http\Controllers\Admin\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = RealestateAmenity::get();
        return view('admin.realestate.amenities.index', compact('amenities'));
    }

    public function create()
    {

        return view('admin.realestate.amenities.form');
    }

    public function store(Request $request)
    {
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
    }

    public function edit($id)
    {
        $amenity = RealestateAmenity::where('id', $id)->first() ?? abort(404);

        return view('admin.realestate.amenities.form', compact('amenity'));
    }

    public function update(Request $request, RealestateAmenity $amenity)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $amenity->name            = $request->name;
        $amenity->save();

        return redirect()->route('admin.realestate.amenities.index')->with('success', 'Amenity updated successfully.');
    }

    public function destroy(RealestateAmenity $amenity)
    {
        $amenity->delete();
        return redirect()->back()->with('success', 'Amenity deleted successfully.');
    }

    public function show(RealestateAmenity $amenity)
    {
        return view('admin.realestate.amenities.show', compact('amenity'));
    }
}
