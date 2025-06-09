<?php

namespace App\Http\Controllers\Company\Realestate;

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
            $units = PropertyUnit::all();
            return view('company.realestate.properties.property-units.index', compact('units', 'property'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create(Property $property, $id)
    {
        if (Auth::user()->can('create a unit')) {
            $property = Property::where('id', $id)->first() ?? abort(404);
            return view('company.realestate.properties.property-units.form', ['unit' => new PropertyUnit(), 'property' => $property]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request, $id)
    {
        if (Auth::user()->can('create a unit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                    'kitchen' => 'nullable|integer',
                    'bed_rooms' => 'nullable|integer',
                    'bath_rooms' => 'nullable|integer',
                    'balconies' => 'nullable|integer',
                    'other_rooms' => 'nullable|string',
                    'registration_no' => 'nullable|string',
                    'rent_type' => 'nullable|string',
                    'price' => 'required|numeric',
                    'deposite_type' => 'nullable|string',
                    'deposite_amount' => 'nullable|numeric',
                    'late_fee_type' => 'nullable|string',
                    'late_fee_amount' => 'nullable|numeric',
                    'incident_reicept_amount' => 'nullable|numeric',
                    'unique_info' => 'nullable|string',
                    'coverImage' => 'nullable|string',
                    'status' => 'nullable|boolean'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            DB::beginTransaction();

            $imagePath = null;

            if ($request->hasFile('images')) {
                $imagePath = $this->storeFiles($request->file('images') ?? [], $request->coverImage);
            }

            $unit                   = new PropertyUnit();
            $unit->property_id      = $id;
            $unit->company_id       = Auth::user()->creatorId();
            $unit->name             = $request->name;
            $unit->kitchen          = $request->kitchen;
            $unit->bed_rooms        = $request->bed_rooms;
            $unit->bath_rooms       = $request->bath_rooms;
            $unit->balconies        = $request->balconies;
            $unit->other_rooms      = $request->other_rooms;
            $unit->registration_no  = $request->registration_no;
            $unit->rent_type        = $request->rent_type  ?? '';
            $unit->rent_duration    = $request->rent_duration ?? 1;
            $unit->price            = $request->price;
            $unit->deposite_type    = $request->deposite_type ?? '';
            $unit->deposite_amount  = $request->deposite_amount  ?? '';
            $unit->late_fee_type    = $request->late_fee_type  ?? '';
            $unit->late_fee_amount  = $request->late_fee_amount  ?? '';
            $unit->incident_reicept_amount   = $request->incident_reicept_amount  ?? '';
            $unit->notes            = $request->unique_info;
            $unit->thumbnail_image    =  isset($imagePath['coverImagePath']) ? $imagePath['coverImagePath'] : '';
            $unit->status           = 'unleashed';

            try {
                $unit->save();
                $unit->propertyUnitImages()->sync($imagePath['filePaths'] ?? []);
                $this->logActivity(
                    'Create a Property Unit',
                    'Unit Id ' . $unit->id,
                    route('company.realestate.property.units.index', $id),
                    'A Property Unit created successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Property unit created successfully',
                    'redirect' => route('company.realestate.property.units.index', $id)
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show($property_id, PropertyUnit $unit)
    {
        if (Auth::user()->can('unit details')) {
            $property = Property::where('id', $property_id)->first() ?? abort(404);
            return view('company.realestate.properties.property-units.unit-single', compact('property', 'unit'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function edit($property_id, PropertyUnit $unit)
    {
        if (Auth::user()->can('edit a unit')) {
            $property = Property::where('id', $property_id)->first() ?? abort(404);

            return view('company.realestate.properties.property-units.form', ['unit' => $unit, 'property' => $property]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, $property_id, $unit)
    {
        if (Auth::user()->can('edit a unit')) {
            $company_id       = Auth::user()->creatorId();
            $property_unit    = PropertyUnit::where('id', $unit)->where('company_id', $company_id)->first() ?? abort(404);

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                    'kitchen' => 'nullable|integer',
                    'bed_rooms' => 'nullable|integer',
                    'bath_rooms' => 'nullable|integer',
                    'balconies' => 'nullable|integer',
                    'other_rooms' => 'nullable|string',
                    'registration_no' => 'nullable|string',
                    'rent_type' => 'nullable|string',
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
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $storageDisk = config('filesystems.default');
            $imagePath = null;


            if ($request->hasFile('images')) {
                $imagePath = $this->storeFiles($request->file('images') ?? [], $request->coverImage);
            }

            $removedImages  = [];
            if (is_array($property_unit->propertyUnitImages)) {
                $removedImages = array_diff($property->propertyUnitImages ?? [], $request->existingImage ?? []);
            }

            $removedImageList = MediaFile::whereIn('id', $removedImages)->get();

            foreach ($removedImageList ?? [] as $img) {

                if (Storage::disk($storageDisk)->exists($img->file_url)) {
                    unlink('storage/' . $img->file_url);
                }
                MediaFile::where('id', $img->id)->delete();
            }

            $NewimagePath = array_merge($imagePath['filePaths'] ?? [], $request->existingImage ?? []);


            $property_unit->company_id       = Auth::user()->creatorId();
            $property_unit->name             = $request->name;
            $property_unit->kitchen          = $request->kitchen;
            $property_unit->bed_rooms        = $request->bed_rooms;
            $property_unit->bath_rooms       = $request->bath_rooms;
            $property_unit->balconies        = $request->balconies;
            $property_unit->other_rooms      = $request->other_rooms;
            $property_unit->registration_no  = $request->registration_no;
            $property_unit->rent_type        = $request->rent_type;
            $property_unit->rent_duration    = $request->rent_duration;
            $property_unit->price            = $request->price;
            $property_unit->deposite_type    = $request->deposite_type;
            $property_unit->deposite_amount  = $request->deposite_amount;
            $property_unit->late_fee_type    = $request->late_fee_type;
            $property_unit->late_fee_amount  = $request->late_fee_amount;
            $property_unit->incident_reicept_amount   = $request->incident_reicept_amount;
            $property_unit->notes            = $request->unique_info;
            if ($request->has('exCoverImage')) {
                $property_unit->thumbnail_image  =  $request->exCoverImage;
            }
            if ($request->hasFile('images')) {
                $property_unit->thumbnail_image    =  isset($imagePath['coverImagePath']) ? $imagePath['coverImagePath'] : '';
            }
            // $property_unit->status           = $request->status;
            try {
                $property_unit->save();
                $property_unit->propertyUnitImages()->sync($NewimagePath ?? []);
                DB::commit();

                $this->logActivity(
                    'Update a Property Unit',
                    'Unit Id ' . $property_unit->id,
                    route('company.realestate.property.units.index', $property_id),
                    'A Property Unit updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'Property unit updated successfully',
                    'redirect' => route('company.realestate.property.units.index', $property_unit->property_id)
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(PropertyUnit $property_unit)
    {
        if (Auth::user()->can('delete a unit')) {
            $storageDisk = config('filesystems.default');
            $propertyImg = $property_unit->propertyImages;
            foreach ($propertyImg ?? [] as $img) {

                if (Storage::disk($storageDisk)->exists($img->file_url)) {
                    unlink('storage/' . $img->file_url);
                }
                MediaFile::where('id', $img->id)->delete();
            }
            $property_unit->delete();

            $this->logActivity(
                'Delete a Property Unit',
                'Unit Id ' . $property_unit->id,
                route('company.realestate.property.units.index', $property_unit->property_id),
                'A property unit deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', 'Property unit deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    protected function storeFiles($files, $coverImage = null)
    {

        $filePaths = [];
        $coverImagePath = "";
        $company_id       = Auth::user()->creatorId();

        foreach ($files ?? [] as $index => $file) {

            if (!($file instanceof \Illuminate\Http\UploadedFile) || !$file->isValid()) {
                continue;
            }


            $folderPath = ['uploads', 'company_' . $company_id, 'properties', 'units'];

            $result = $this->directoryCheckAndStoreFile($file, $company_id, $folderPath,);

            if ($result) {
                $filePaths[$index + 1] = $result->id;

                if ($file->getClientOriginalName() === $coverImage) {
                    $coverImagePath = $result->id;
                }
            } else {
                continue;
                // throw new \Exception("Failed to upload file: " . $file->getClientOriginalName());
            }
        }

        return [
            'filePaths' => $filePaths,
            'coverImagePath' => $coverImagePath,
        ];
    }
}
