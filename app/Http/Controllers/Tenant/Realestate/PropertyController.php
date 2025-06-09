<?php

namespace App\Http\Controllers\Owner\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{

    use ActivityLogger;
    public function index()
    {
        if (Auth::user()->can('properties listing')) {
            $properties = Property::where('company_id', Auth::user()->creatorId())->where('owner_id',Auth::user()->id)->get();

            return view('owner.realestate.properties.index', compact('properties'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

      public function show(string $id)
    {
        if (Auth::user()->can('property details')) {
            //
            $property = Property::findOrFail($id);
            return view('owner.realestate.properties.property-single', compact('property'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

}
