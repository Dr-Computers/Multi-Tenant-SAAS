<?php

namespace App\Http\Controllers\Owner\Realestate;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\Property;
use App\Models\PropertyUnit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityLogger;

class PropertyUnitController extends Controller
{
    use HandlesMediaFolders;
    use ActivityLogger;
    public function index($id)
    {
        if (Auth::user()->can('unit listing')) {
            $property = Property::where('id', $id)->first() ?? abort(404);
            $user_id = auth()->user()->id;
            $units = PropertyUnit::with([
                'lease' => function ($query) use ($user_id) {
                    $query->where('tenant_id', '=', $user_id);
                }
            ])->get();
            dd($units);
            return view('owner.realestate.properties.property-units.index', compact('units', 'property'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show($property_id, PropertyUnit $unit)
    {
        if (Auth::user()->can('unit details')) {
            $property = Property::where('id', $property_id)->first() ?? abort(404);
            return view('owner.realestate.properties.property-units.unit-single', compact('property', 'unit'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
