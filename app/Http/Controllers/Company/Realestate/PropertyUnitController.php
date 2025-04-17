<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;

class PropertyUnitController extends Controller
{
    public function index($id)
    {
        $property = Property::where('id', $id)->first() ?? abort(404);
        $units = PropertyUnit::all();
        return view('company.realestate.properties.property-units.index', compact('units', 'property'));
    }


    public function create(Property $property, $id)
    {
        $property = Property::where('id', $id)->first() ?? abort(404);
        return view('company.realestate.properties.property-units.form', ['unit' => new PropertyUnit(), 'property' => $property]);
    }

    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'kitchen' => 'nullable|integer',
            'bed_rooms' => 'nullable|integer',
            'bath_rooms' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'other_rooms' => 'nullable|string',
            'registration_no' => 'nullable|string',
            'rent_type' => 'required|string',
            'price' => 'required|numeric',
            'deposite_type' => 'nullable|string',
            'deposite_amount' => 'nullable|numeric',
            'late_fee_type' => 'nullable|string',
            'late_fee_amount' => 'nullable|numeric',
            'incident_reicept_amount' => 'nullable|numeric',
            'unique_info' => 'nullable|string',
            'coverImage' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);


        $unit                   = new PropertyUnit();
        $unit->property_id      = $id;
        $unit->name             = $request->name;
        $unit->kitchen          = $request->kitchen;
        $unit->bed_rooms        = $request->bed_rooms;
        $unit->bath_rooms       = $request->bath_rooms;
        $unit->balconies        = $request->balconies;
        $unit->other_rooms      = $request->other_rooms;
        $unit->registration_no  = $request->registration_no;
        $unit->rent_type        = $request->rent_type;
        $unit->price            = $request->price;
        $unit->deposite_type    = $request->deposite_type;
        $unit->deposite_amount  = $request->deposite_amount;
        $unit->late_fee_type    = $request->late_fee_type;
        $unit->late_fee_amount  = $request->late_fee_amount;
        $unit->incident_reicept_amount   = $request->incident_reicept_amount;
        $unit->notes      = $request->unique_info;
        $unit->status           = $request->status;
        $unit->save();




        return redirect()->route('company.realestate.property.units.index',$id)->with('success', 'Property unit created successfully.');
    }

    public function edit(PropertyUnit $property_unit,$id)
    {
        $property = Property::where('id', $id)->first() ?? abort(404);
        return view('property-units.form', compact('property_unit','property'));
    }

    public function update(Request $request, PropertyUnit $property_unit)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'kitchen' => 'nullable|integer',
            'bed_rooms' => 'nullable|integer',
            'bath_rooms' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'other_rooms' => 'nullable|string',
            'registration_no' => 'nullable|string',
            'rent_type' => 'required|string',
            'price' => 'required|numeric',
            'deposite_type' => 'nullable|string',
            'deposite_amount' => 'nullable|numeric',
            'late_fee_type' => 'nullable|string',
            'late_fee_amount' => 'nullable|numeric',
            'incident_reicept_amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'flooring' => 'nullable|string',
            'price_included' => 'nullable|string',
            'youtube_video' => 'nullable|url',
            'thumbnail_image' => 'nullable|string',
            'status' => 'nullable|boolean',
            'property_id' => 'required|integer'
        ]);

        $property_unit->update($data);
        return redirect()->route('property-units.index')->with('success', 'Property unit updated successfully.');
    }

    public function destroy(PropertyUnit $property_unit)
    {
        $property_unit->delete();
        return redirect()->route('property-units.index')->with('success', 'Property unit deleted successfully.');
    }
}
