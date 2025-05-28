<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyRequest;
use App\Models\MediaFile;
use App\Models\Owner;
use App\Models\Property;
use App\Models\PropertyLandmark;
use App\Models\PropertyUnit;
use App\Models\RealestateAmenity;
use App\Models\RealestateCategory;
use App\Models\RealestateFurnishing;
use App\Models\RealestateInvoice;
use App\Models\RealestateLandmark;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Storage;
use App\Traits\ActivityLogger;


class PropertyController extends Controller
{

    use HandlesMediaFolders;
    use ActivityLogger;
    public function index()
    {
        if (Auth::user()->can('properties listing')) {
            $properties = Property::where('company_id', Auth::user()->creatorId())->get();

            return view('company.realestate.properties.index', compact('properties'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function create()
    {
        if (Auth::user()->can('create a property')) {
            $is_rent     = RealestateCategory::where('is_rent', 1)->get();
            $is_sell     = RealestateCategory::where('is_sell', 1)->get();

            $furnishings = RealestateFurnishing::where('status', '1')->get();
            $landmarks   = RealestateLandmark::where('status', '1')->get();
            $amenities   = RealestateAmenity::where('status', '1')->get();

            $owners      = User::where('type', 'owner')->where('parent', Auth::user()->creatorId())->get();

            return view('company.realestate.properties.create', compact('is_rent', 'is_sell', 'furnishings', 'landmarks', 'amenities', 'owners'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function store(PropertyRequest $request)
    {

        if (Auth::user()->can('create a property')) {
            DB::beginTransaction();
            try {
                $property = new Property();

                $categories[] = $request->category ?? '';

                $request->merge(['categories' => $categories]);


                $imagePath = null;
                $videoPath = null;
                $documentPath = null;

                // Handle images upload
                if ($request->hasFile('images')) {
                    $imagePath = $this->storeFiles($request->file('images') ?? [], $request->coverImage);
                }


                if ($request->hasFile('documents')) {
                    $documentPath = $this->storeFiles($request->file('documents') ?? []);
                }

                // Handle videos upload
                if ($request->hasFile('videos')) {
                    $videoPath = $this->storeFiles($request->file('videos'));
                }

                $youtube_video = '';
                if ($request->filled('youtube_video')) {
                    $youtube_video = $this->getYouTubeVideoId($request->input('youtube_video'));
                }

                $property->company_id       = Auth::user()->creatorId();
                $property->name             = $request->property_name ?? null;
                $property->purpose_type     = $request->type ?? null;
                $property->mode             = $request->mode ?? null;
                $property->ownership        = $request->ownership ?? null;
                $property->total_floor      = $request->total_floor ?? null;
                $property->available_floor  = $request->available_floor ?? null;
                $property->super_buit_up_area = $request->super_built_up_area ?? null;
                $property->carpet_area      = $request->carpet_area ?? null;
                $property->closed_parking   = $request->covered_parking ?? null;
                $property->open_parking     = $request->open_parking ?? null;
                $property->availability_status = $request->available_status ?? null;
                $property->age_property       = $request->property_age ?? null;
                $property->thumbnail_image    =  isset($imagePath['coverImagePath']) ? $imagePath['coverImagePath'] : '';
                $property->youtube_video      = $youtube_video ?? null;
                $property->maintatenance_type = '';
                $property->maintatenace_fee   = '';
                $property->overlooking        = '';
                $property->water_availability = '';
                $property->status_electricity = '';
                $property->authority_approvel = '';
                $property->authority_approvel_document_id = '';
                $property->fire_safty_start_date = $request->fire_safty_start_date ?? null;
                $property->fire_safty_end_date = $request->fire_safty_end_date ?? null;
                $property->insurance_start_date = $request->insurance_start_date ?? null;
                $property->insurance_end_date = $request->insurance_end_date ?? null;
                $property->building_no        = $request->building_no ?? null;
                $property->lifts              = '';
                $property->moderation_status = '1';
                $property->author_id        = auth()->user()->id;
                $property->latitude         = $request->latitude ?? null;
                $property->longitude        = $request->longitude ?? null;
                $property->location         = $request->location_info ?? null;
                $property->description      = $request->description ?? null;
                $property->unique_id        = Auth::user()->creatorId() . date('maYdhis');
                $property->views            = 0;
                $property->is_featured      = 0;
                $property->city             = $request->city ?? null;
                $property->locality         = $request->locality ?? null;
                $property->sub_locality     = $request->sub_locality ?? null;
                $property->plot_area        = $request->plot_area ?? null;
                $property->open_sides       = $request->open_sides ?? null;
                $property->plot_type        = $request->plot_type ?? null;
                $property->owner_id         = $request->owner ?? null;
                $property->furnishing_status = $request->has('furnishing_status') ? $request->furnishing_status : 'unfurnished';
                $property->save();


                $property->amenities()->sync($request->input('amenities', []));

                $property->propertyImages()->sync($imagePath['filePaths'] ?? []);
                $property->propertyDocuments()->sync($documentPath['filePaths'] ?? []);


                if ($request->furnishing_status != 'unfurnished') {
                    $property->furnishing()->sync($request->input('furnishing', []));
                }

                $this->propertyCategoryService($request, $property);

                // $this->saveCustomFields($property, $request->input('custom_fields', [])); //moredetail

                $this->landmarks($property, $request->input('landmarks', []));

                DB::commit();
                Session::flash('success_msg', 'Successfully Created');

                $this->logActivity(
                    'Create a Property',
                    'Property Id ' . $property->id,
                    route('company.realestate.properties.index'),
                    'A Property created successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully Created',
                    'redirect' => route('company.realestate.properties.index')
                ]);
            } catch (\Exception $e) {

                DB::rollBack();
                // Return error response if something goes wrong
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit(string $id)
    {
        if (Auth::user()->can('edit a property')) {
            //
            $property = Property::where(['id' => $id])->first() ?? abort(404);


            if (! $property) {
                abort(404);
            }


            $categories = RealestateCategory::where('status', 'published')->get();

            $is_rent = RealestateCategory::where('is_rent', 1)->get();
            $is_sell = RealestateCategory::where('is_sell', 1)->get();


            $furnishings = RealestateFurnishing::where('status', '1')->get();
            $landmarks  = RealestateLandmark::where('status', '1')->get();
            $amenities   = RealestateAmenity::where('status', '1')->get();
            $owners      = User::where('type', 'owner')->where('parent', Auth::user()->creatorId())->get();

            // $customFields = CustomField::get();

            return view('company.realestate.properties.edit', compact('categories', 'is_rent', 'is_sell', 'furnishings', 'landmarks', 'amenities', 'property', 'owners'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(
        int|string $id,
        Request $request
    ) {

        if (Auth::user()->can('edit a property')) {
            $property = Property::where([
                'id' => $id

            ])->first() ?? abort(404);


            DB::beginTransaction();
            try {

                $categories[] = $request->category ?? '';

                $request->merge(['categories' => $categories]);
                $storageDisk = config('filesystems.default');

                $imagePath = null;
                $videoPath = null;
                $documentPath = null;


                // Handle videos upload
                if ($request->hasFile('videos')) {
                    $videoPath = $this->storeFiles($request->file('videos'));
                }

                // Handle images upload
                if ($request->hasFile('images')) {
                    $imagePath = $this->storeFiles($request->file('images') ?? [], $request->coverImage);
                }


                if ($request->hasFile('documents')) {
                    $documentPath = $this->storeFiles($request->file('documents') ?? []);
                }

                $youtube_video = '';
                if ($request->filled('youtube_video')) {
                    $youtube_video = $this->getYouTubeVideoId($request->input('youtube_video'));
                }


                // Find images and videos that were removed by comparing with the new ones
                if (is_array($property->propertyImages)) {
                    $removedImages = array_diff($property->propertyImages ?? [], $request->existingImage ?? []);
                }

                if (is_array($property->propertyDocuments)) {
                    $removedDocs = array_diff($property->propertyDocuments ?? [], $request->existingDocx ?? []);
                }

                if (is_array($property->video)) {
                    $removedVideos = array_diff($property->video ?? [], $request->existingVideo ?? []);
                }

                // Merge the existing and new images and videos to get the final list
                $NewimagePath = array_merge($imagePath['filePaths'] ?? [], $request->existingImage ?? []);
                $NewdocPath = array_merge($documentPath['filePaths'] ?? [], $request->existingDocx ?? []);
                $NewvideoPath = array_merge($videoPath ?? [], $request->existingVideo ?? []);

                $removedImageList = MediaFile::whereIn('id', $removedImages)->get();

                foreach ($removedImageList ?? [] as $img) {

                    if (Storage::disk($storageDisk)->exists($img->file_url)) {
                        unlink('storage/' . $img->file_url);
                    }
                    MediaFile::where('id', $img->id)->delete();
                }


                $removedDocsList = MediaFile::whereIn('id', $removedDocs)->get();


                foreach ($removedDocsList ?? [] as $doc) {
                    if (Storage::disk($storageDisk)->exists($doc->file_url)) {
                        unlink('storage/' . $doc->file_url);
                    }
                    MediaFile::where('id', $doc->id)->delete();
                }



                foreach ($removedVideos ?? [] as $videoLoc) {
                    unlink('images/' . $videoLoc);
                }


                $property->company_id       = Auth::user()->creatorId();
                $property->name             = $request->property_name ?? null;
                $property->purpose_type     = $request->type ?? null;
                $property->mode             = $request->mode ?? null;
                $property->ownership        = $request->ownership ?? null;
                $property->total_floor      = $request->total_floor ?? null;
                $property->available_floor  = $request->available_floor ?? null;
                $property->super_buit_up_area = $request->super_built_up_area ?? null;
                $property->carpet_area      = $request->carpet_area ?? null;
                $property->closed_parking   = $request->covered_parking ?? null;
                $property->open_parking     = $request->open_parking ?? null;
                $property->availability_status = $request->available_status ?? null;
                $property->age_property       = $request->property_age ?? null;
                $property->thumbnail_image    =  isset($imagePath['coverImagePath']) ? $imagePath['coverImagePath'] : '';
                $property->youtube_video      = $youtube_video ?? null;
                $property->maintatenance_type = '';
                $property->maintatenace_fee   = '';
                $property->overlooking        = '';
                $property->water_availability = '';
                $property->status_electricity = '';
                $property->authority_approvel = '';
                $property->authority_approvel_document_id = '';
                $property->fire_safty_start_date = $request->fire_safty_start_date ?? null;
                $property->fire_safty_end_date = $request->fire_safty_end_date ?? null;
                $property->insurance_start_date = $request->insurance_start_date ?? null;
                $property->insurance_end_date = $request->insurance_end_date ?? null;
                $property->building_no        = $request->building_no ?? null;
                $property->lifts              = '';
                $property->moderation_status = '1';
                $property->author_id        = auth()->user()->id;
                $property->latitude         = $request->latitude ?? null;
                $property->longitude        = $request->longitude ?? null;
                $property->location         = $request->location_info ?? null;
                $property->description      = $request->description ?? null;
                if ($property->unique_id == '') {
                    $property->unique_id        = $request->account . date('mYdhisa');
                }
                $property->views            = 0;
                $property->is_featured      = 0;
                $property->city             = $request->city ?? null;
                $property->locality         = $request->locality ?? null;
                $property->sub_locality     = $request->sub_locality ?? null;
                $property->plot_area        = $request->plot_area ?? null;
                $property->open_sides       = $request->open_sides ?? null;
                $property->plot_type        = $request->plot_type ?? null;
                $property->owner_id         = $request->owner ?? null;
                $property->furnishing_status = $request->has('furnishing_status') ? $request->furnishing_status : 'unfurnished';
                $property->save();

                $property->amenities()->sync($request->input('amenities', []));

                $property->propertyImages()->sync($NewimagePath ?? []);
                $property->propertyDocuments()->sync($NewdocPath ?? []);

                if ($request->furnishing_status != 'unfurnished') {
                    $property->furnishing()->sync($request->input('furnishing', []));
                }
                $this->propertyCategoryService($request, $property);

                // $this->saveCustomFields($property, $request->input('custom_fields', [])); //moredetail

                $this->landmarks($property, $request->input('landmarks', []));

                DB::commit();

                Session::flash('success_msg', 'Successfully Updated');

                $this->logActivity(
                    'Update a Property',
                    'Property Id ' . $property->id,
                    route('company.realestate.properties.index'),
                    'A Property updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );


                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully Updated',
                    'redirect' => route('company.realestate.properties.index')
                ]);
            } catch (\Exception $e) {

                DB::rollBack();
                // Return error response if something goes wrong
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function multiDestroy(Request $request)
    {
        if ($request->has('delete_property') && $request->filled('delete_property') && is_array($request->delete_property)) {
            foreach ($request->delete_property ?? [] as $delId) {
                DB::beginTransaction();
                try {
                    $property = Property::where('id', $delId)->first() ?? abort(404);
                    $this->adDeleted($property);
                    $property->delete();
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    // Return error response if something goes wrong
                    Session::flash('failed_msg', 'Failed..!' . $e->getMessage());
                    return redirect()->back();
                }
            }
            Session::flash('success_msg', 'Successfully Deleted');
            return redirect()->back();
        } else {
            Session::flash('failed_msg', 'Please selected at least one item');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, string $id)
    {

        if (Auth::user()->can('delete a property')) {
            DB::beginTransaction();
            try {
                $company_id       = Auth::user()->creatorId();
                $property = Property::where('id', $id)->where('company_id', $company_id)->first() ?? abort(404);
                $property->landmarks()->detach();
                $property->amenities()->detach();
                $propertyImg = $property->propertyImages;
                $propertyDoc = $property->propertyDocuments;
                $property->propertyImages()->detach();
                $property->propertyDocuments()->detach();
                $property->furnishing()->detach();

                $storageDisk = config('filesystems.default');

                foreach ($propertyImg ?? [] as $img) {

                    if (Storage::disk($storageDisk)->exists($img->file_url)) {
                        unlink('storage/' . $img->file_url);
                    }
                    MediaFile::where('id', $img->id)->delete();
                }

                foreach ($propertyDoc ?? [] as $doc) {
                    if (Storage::disk($storageDisk)->exists($doc->file_url)) {
                        unlink('storage/' . $doc->file_url);
                    }
                    MediaFile::where('id', $doc->id)->delete();
                }


                $property->delete();
                DB::commit();

                $this->logActivity(
                    'Delete a Property',
                    'Property Id ' . $property->id,
                    route('company.realestate.properties.index'),
                    'A Property deleted successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return redirect()->back()->with('success', 'Successfully Deleted.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function show(string $id)
    {
        if (Auth::user()->can('property details')) {
            //
            $property = Property::findOrFail($id);
            return view('company.realestate.properties.property-single', compact('property'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    protected function saveCustomFields(Property $property, array $customFields = []): void
    {
        $customFields = CustomFieldValue::formatCustomFields($customFields);
        DB::table('re_custom_field_values')->where('reference_id', $property->id)->delete();
        $property->customFields()->saveMany($customFields);
    }

    protected function landmarks(Property $property, array $facilities = []): void
    {

        PropertyLandmark::where('property_id', $property->id)->delete();

        foreach ($facilities ?? [] as $facilityValue) {
            if ($facilityValue['id'] != '') {
                $faciDistance              = new PropertyLandmark();
                $faciDistance->property_id  = $property->id;
                $faciDistance->landmark_id   = $facilityValue['id'];
                $faciDistance->landmark_value        = $facilityValue['distance'] ?? '';
                $faciDistance->save();
            }
        }
    }




    protected function propertyCategoryService($request, Property $property)
    {
        $categories = $request->input('categories', []);
        if (is_array($categories)) {
            if ($categories) {
                $property->categories()->sync($categories);
            } else {
                $property->categories()->detach();
            }
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


            $folderPath = ['uploads', 'company_' . $company_id, 'properties'];

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


    protected function getYouTubeVideoId($url)
    {
        // Parse the URL
        $parsedUrl = parse_url($url);

        // Validate the host
        $validHosts = ['www.youtube.com', 'youtube.com', 'm.youtube.com', 'youtu.be'];
        if (!isset($parsedUrl['host']) || !in_array($parsedUrl['host'], $validHosts)) {
            return false; // Not a valid YouTube URL
        }

        // Handle short URLs (youtu.be)
        if ($parsedUrl['host'] === 'youtu.be') {
            return isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : false;
        }

        // Handle YouTube Shorts URLs
        if (isset($parsedUrl['path']) && str_starts_with($parsedUrl['path'], '/shorts/')) {
            return str_replace('/shorts/', '', $parsedUrl['path']);
        }

        // Handle regular YouTube video URLs (youtube.com)
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            return $queryParams['v'] ?? false;
        }

        return false; // No valid video ID found
    }


    public function getPropertyUnit($property_id)
    {
        $units = PropertyUnit::where('property_id', $property_id)->get()->pluck('name', 'id');
        $property = Property::find($property_id);
        $invoiceNumber = $this->propertyInvoiceNumber($property_id);

        // Ensure invoicePrefix() returns a valid default
        $prefix = invoicePrefix() ?? "#INVOICE";

        // Use database value for invoice prefix or default to "#INVOICE"
        $invPrefix = $property && !empty($property->invoice_prefix) ? $property->invoice_prefix : $prefix;

        return response()->json([
            'units' => $units,
            'invoice_prefix' => $invPrefix,
            'invoice_number' => $invoiceNumber,
        ]);
    }


    private function propertyInvoiceNumber($property_id)
    {
        $latest = RealestateInvoice::where('property_id', $property_id)
            ->orderBy('invoice_id', 'desc') // Ensure correct order
            ->first();

        return $latest ? ($latest->invoice_id + 1) : 1; // Continue sequence
    }
    public function getUnitRentType($unitId)
    {
        $rentType = PropertyUnit::findOrFail($unitId)->rent_type;
        return response()->json($rentType);
    }
}
