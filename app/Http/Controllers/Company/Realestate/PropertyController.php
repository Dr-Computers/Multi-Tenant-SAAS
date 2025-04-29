<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyLandmark;
use App\Models\PropertyUnit;
use App\Models\RealestateAmenity;
use App\Models\RealestateCategory;
use App\Models\RealestateFurnishing;
use App\Models\RealestateInvoice;
use App\Models\RealestateLandmark;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::where('company_id', Auth::user()->creatorId())->get();
       
        return view('company.realestate.properties.index', compact('properties'));
    }
    public function create()
    {
        $is_rent   = RealestateCategory::where('is_rent', 1)->get();
        $is_sell   = RealestateCategory::where('is_sell', 1)->get();

        $furnishings = RealestateFurnishing::where('status', '1')->get();
        $landmarks  = RealestateLandmark::where('status', '1')->get();
        $amenities   = RealestateAmenity::where('status', '1')->get();

        return view('company.realestate.properties.create', compact('is_rent', 'is_sell', 'furnishings', 'landmarks', 'amenities'));
    }
    public function store(Request $request)
    {
    
        DB::beginTransaction();
        try {
            $property = new Property();

            $categories[] = $request->category ?? '';

            $request->merge(['categories' => $categories]);


            $imagePath = null;
            $videoPath = null;

            // Handle images upload
            if ($request->hasFile('images')) {
                $imagePath = $this->storeFiles($request->file('images'), $request->coverImage);
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
            $property->name             = $request->property_name;
            $property->purpose_type     = $request->type;
            $property->mode             = $request->mode;
            $property->ownership        = $request->ownership;
            $property->total_floor      = $request->total_floor;
            $property->available_floor  = $request->available_floor;
            $property->super_buit_up_area= $request->super_built_up_area;
            $property->carpet_area      = $request->carpet_area;
            $property->closed_parking   = $request->covered_parking;
            $property->open_parking     = $request->open_parking;
            $property->availability_status = $request->available_status;
            $property->age_property       = $request->property_age;
            $property->thumbnail_image    =  isset($imagePath['coverImagePath']) ? $imagePath['coverImagePath'] : '';
            $property->youtube_video      = $youtube_video;
            $property->maintatenance_type = '';
            $property->maintatenace_fee   = '';
            $property->overlooking        = '';
            $property->water_availability = '';
            $property->status_electricity = '';
            $property->authority_approvel = '';
            $property->authority_approvel_document_id = '';
            $property->fire_safty_start_date = '';
            $property->fire_safty_end_date = '';
            $property->insurance_start_date = '';
            $property->insurance_end_date = '';
            $property->building_no        = '';
            $property->lifts              = '';
            $property->moderation_status = 'approved';
            $property->author_id        = auth()->user()->id;
            $property->latitude         = $request->latitude;
            $property->longitude        = $request->longitude;
            $property->location         = $request->location_info;
            $property->description      = $request->description;
            $property->unique_id        = $request->account . date('maYdhis');
            $property->views            = 0;
            $property->is_featured      = 0;
            $property->city             = $request->city;
            $property->locality         = $request->locality;
            $property->sub_locality     = $request->sub_locality;
            $property->plot_area        = $request->plot_area ?? '';
            $property->open_sides       = $request->open_sides;
            $property->plot_type        = $request->plot_type ?? '';
            $property->save();


            $property->amenities()->sync($request->input('amenities', []));
            if ($request->furnishing_status != 'unfurnished') {
                $property->furnishing()->sync($request->input('furnishing', []));
            }
            $this->propertyCategoryService($request, $property);

            // $this->saveCustomFields($property, $request->input('custom_fields', [])); //moredetail

            $this->landmarks($property, $request->input('facilities', []));

         

            DB::commit();
            Session::flash('success_msg', 'Successfully Created');

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
    }

    public function edit(string $id)
    {
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

        // $customFields = CustomField::get();

        return view('company.realestate.properties.edit', compact('categories', 'is_rent', 'is_sell', 'furnishings', 'landmarks', 'amenities', 'property'));
    }

    public function update(
        int|string $id,
        Request $request
    ) {


        $property = Property::where([
            'id' => $id

        ])->first() ?? abort(404);



        DB::beginTransaction();

        try {

            $categories[] = $request->category ?? '';

            $request->merge(['categories' => $categories]);

            $imagePath = null;
            $videoPath = null;

            // Handle images upload
            if ($request->hasFile('images')) {
                $imagePath = $this->storeFiles($request->file('images'), $request->coverImage);
            }


            // Handle videos upload
            if ($request->hasFile('videos')) {
                $videoPath = $this->storeFiles($request->file('videos'));
            }

            $youtube_video = '';
            if ($request->filled('youtube_video')) {
                $youtube_video = $this->getYouTubeVideoId($request->input('youtube_video'));
            }


            // Find images and videos that were removed by comparing with the new ones
            if (is_array($property->images)) {
                $removedImages = array_diff($property->images ?? [], $request->existingImage ?? []);
            }
            if (is_array($property->video)) {
                $removedVideos = array_diff($property->video ?? [], $request->existingVideo ?? []);
            }

            // Merge the existing and new images and videos to get the final list
            $NewimagePath = array_merge($imagePath['filePaths'] ?? [], $request->existingImage ?? []);
            $NewvideoPath = array_merge($videoPath ?? [], $request->existingVideo ?? []);

            foreach ($removedImages ?? [] as $imageLoc) {
                try {
                    // Check if the original image file exists before unlinking
                    $UnlinkimagePath = public_path('images/' . $imageLoc);
                    if (file_exists($UnlinkimagePath)) {
                        unlink($UnlinkimagePath);
                    }
                } catch (Exception $e) {
                }
            }

            foreach ($removedVideos ?? [] as $videoLoc) {
                unlink('images/' . $videoLoc);
            }

            $old_type  = $property->type;
            $property->name             = $request->property_name;
            $property->slug             = Str::slug($request->property_name);
            $property->type             = $request->mode;
            $property->mode             = $request->type;
            $property->project_id       = $request->project;
            $property->number_bedroom   = $request->room;
            $property->number_bathroom  = $request->bathroom;
            $property->number_floor     = $request->total_floor;
            $property->content          = $request->unique_info;
            $property->square           = $request->super_built_up_area;
            $property->price            = $request->price;
            $property->images           = $NewimagePath;
            $property->video            = $NewvideoPath;
            if ($property->unique_id == '') {
                $property->unique_id        = $request->account . date('mYdhisa');
            }
            $property->author_id        = $request->account;
            $property->author_type      = Account::class;
            // $property->moderation_status = ModerationStatusEnum::PENDING;
            $property->furnishing_status = $request->has('furnishing_status') ? $request->furnishing_status : 'unfurnished';
            $property->construction_status = $request->available_status;
            $property->expire_date      = '2050-12-30';
            $property->never_expired    = 1;
            $property->latitude         = $request->latitude;
            $property->longitude        = $request->longitude;
            $property->unit_info        = $request->unit_info;
            $property->location         = $request->location_info;
            $property->city             = $request->city;
            $property->locality         = $request->locality;
            $property->sub_locality     = $request->sub_locality;
            $property->apartment        = $request->apartment;
            $property->youtube_video    = $youtube_video;
            $property->landmark         = $request->landmark;
            $property->available_floor  = $request->available_floor;
            $property->balconies        = $request->balconie;
            $property->carpet_area      = $request->carpet_area;
            $property->built_up_area    = $request->built_up_area;
            $property->covered_parking  = $request->covered_parking;
            $property->open_parking     = $request->open_parking;
            $property->property_age     = $request->property_age;
            $property->possession       = $request->possession;
            $property->ownership        = $request->ownership;
            $property->open_sides       = $request->open_sides;
            $property->other_rooms      = $request->has('other_rooms') && count($request->other_rooms) > 0 ? implode(',', $request->other_rooms) : null;
            $property->all_include      = $request->has('all_include') ? 1 : 0;
            $property->tax_include      = $request->has('tax_include') ? 1 : 0;
            $property->negotiable       = $request->has('negotiable') ? 1 : 0;
            $property->cover_image      = (isset($imagePath['coverImagePath']) && strlen($imagePath['coverImagePath']) > 3) ? $imagePath['coverImagePath'] : $request->coverImage;
            $property->occupancy_type   = $request->has('occupancy_type') ? $request->occupancy_type : '';
            $property->available_for    = $request->has('available_for') ? $request->available_for : '';
            $property->plot_area        = $request->plot_area ?? '';
            $property->pantry           = $request->pantry ?? '';
            $property->washroom         = $request->washroom ?? 0;
            $property->cabin            = $request->cabin ?? 0;
            $property->seats            = $request->seats ?? 0;
            $property->units_on_floor   = $request->units_on_floor ?? 0;
            $property->ac_count         = $request->ac_count ?? 0;
            $property->fans_count       = $request->fans_count ?? 0;
            $property->work_stations    = $request->work_stations ?? 0;
            $property->chairs_count     = $request->chairs_count ?? 0;
            $property->plot_type        = $request->plot_type ?? '';
            $property->moderation_status = $request->property_status ?? 'pending';
            $property->built_suit       = $request->has('built_suit') ? 1 : 0;
            $property->keywords         = $request->keywords ?? '';
            $property->save();

            $property->features()->sync($request->input('amenities', []));

            // if ($request->furnishing_status == 'furnished') {

            //     $furnishingIds = Furnishing::whereStatus('published')->pluck('id');

            //     $request->merge(['furnishing' => $furnishingIds]);
            // }

            // if ($request->furnishing_status != 'unfurnished') {
            $property->furnishing()->sync($request->input('furnishing', []));
            // }



            $this->saveCustomFields($property, $request->input('custom_fields', [])); //moredetail


            $this->saveFacilitiesService($property, $request->input('facilities', []));

            // $saveFacilitiesService->execute($property, $request->input('facilities', []));  // landmark

            $this->propertyCategoryService($request, $property);

            // $saveRulesInformation->execute($property, $request->type, $request->input('rule', []));
            $this->saveRulesInformation($property, $request->input('rule', []));


            // AccountActivityLog::query()->create([
            //     'action' => 'update_property',
            //     'reference_name' => $property->name,
            //     'reference_url' => route('admin.properties.edit', $property->id),
            // ]);
            DB::commit();



            // SlugHelper::createSlug($property);

            Session::flash('success_msg', 'Successfully Updated');

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully Updated',
                'redirect' => 'back'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            // Return error response if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
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
        if (!permission_check('Property Delete')) {
            return abort(404);
        }

        if (auth('web')->user()->acc_type == 'superadmin') {
            $property = Property::withTrashed()
                ->with('author')
                ->whereId($id)
                ->first() ?? abort(404);
            DB::beginTransaction();
            try {
                foreach ($property->images  ?? [] as $imageLoc) {
                    $imagePath = public_path('images/' . $imageLoc);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // FacilityDistance::where('reference_id', $property->id)->delete();
                // RuleDetails::where('reference_id', $property->id)->delete();
                DB::table('re_custom_field_values')->where('reference_type', 'App\Models\Property')->where('reference_id', $property->id)->delete();
                DB::table('re_property_categories')->where('property_id', $id)->delete();
                DB::table('re_property_furnishing')->where('property_id', $id)->delete();
                $this->adDeleted($property);

                $property->forceDelete();

                // $property->delete();

                DB::commit();
                Session::flash('success_msg', 'Successfully Deleted');
                if ($request->has('from') && $request->from == 'trash') {
                    return redirect()->route('admin.trash.index');
                }
                return redirect()->back();
            } catch (Exception $e) {
                DB::rollBack();
                // Return error response if something goes wrong
                Session::flash('failed_msg', 'Failed..!' . $e->getMessage());
                return redirect()->back();
            }
        } else {
            DB::beginTransaction();
            try {
                $property = Property::where('id', $id)->first() ?? abort(404);
                $this->adDeleted($property);
                $property->delete();
                DB::commit();
                Session::flash('success_msg', 'Successfully Deleted');
                return redirect()->back();
            } catch (Exception $e) {
                DB::rollBack();
                // Return error response if something goes wrong
                Session::flash('failed_msg', 'Failed..!' . $e->getMessage());
                return redirect()->back();
            }
        }
    }
    public function show(string $id)
    {
        //
        $property = Property::findOrFail($id);
        if ($property->type == 'pg') {
            return view('admin.properties.pg-single', compact('property'));
        } else if ($property->category->name == 'Plot and Land') {
            return view('admin.properties.plot-single', compact('property'));
        } else {
            return view('admin.properties.rent-sale-single', compact('property'));
        }
    }

    protected function saveCustomFields(Property $property, array $customFields = []): void
    {
        $customFields = CustomFieldValue::formatCustomFields($customFields);
        DB::table('re_custom_field_values')->where('reference_id', $property->id)->delete();
        $property->customFields()->saveMany($customFields);
    }

    protected function saveFacilitiesService(Property $property, array $facilities = []): void
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

        foreach ($files ?? [] as $index => $file) {
            $folderPath = 'properties';

            $result = uploadFile($file, $folderPath, 'public', true);

            if ($result) {
                $filePaths[$index + 1] = $result;

                if ($file->getClientOriginalName() === $coverImage) {
                    $coverImagePath = $result;
                }
            } else {
                throw new \Exception("Failed to upload file: " . $file->getClientOriginalName());
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


    public function units(Request $request){

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
