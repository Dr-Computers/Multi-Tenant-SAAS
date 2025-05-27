<?php

namespace App\Http\Controllers\Company;

use App\Models\Asset;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ActivityLogger;

class AssetController extends Controller
{
    use ActivityLogger;
    public function index()
    {
        // if (\Auth::user()->can('manage asset')) {

        $company_id                 = Auth::user()->creatorId();
        $assets = Asset::where('company_id', $company_id)->orderBy('created_at', 'desc')->paginate(25);

        return view('company.asset.index', compact('assets'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function create()
    {
        // if (\Auth::user()->can('create asset')) {
        $company_id                 = Auth::user()->creatorId();
        $properties = Property::where('company_id', $company_id)->get()->pluck('name', 'id');
        $properties->prepend(__('Select Property'), 0);
        return view('company.asset.create', compact('properties'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function store(Request $request)
    {
        // if (\Auth::user()->can('create invoice payment')) {
        $validator = \Validator::make(
            $request->all(),
            [

                'name' => 'required|string|max:255',
                'type' => 'required|string|in:fixed_asset,current_asset,bank',
                'property_id' => 'nullable|exists:properties,id',
                'location' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_price' => 'nullable|numeric',
                'vendor_name' => 'nullable|string|max:255',
                'initial_value' => 'required|numeric',
                'current_market_value' => 'required|numeric',
                'accumulated_depreciation' => 'nullable|numeric',
                'owner_name' => 'nullable|string|max:255',
                'title_deed_number' => 'nullable|string|max:255',
                'asset_condition' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
            ]
        );


        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
        }

        $company_id                 = Auth::user()->creatorId();

        Asset::create([
            'name' => $request->name,
            'type' => $request->type,
            'property_id' => !empty($request->property_id) ? $request->property_id : null,
            'location' => $request->location,
            'purchase_date' => !empty($request->purchase_date) ? $request->purchase_date : null,
            'purchase_price' => !empty($request->purchase_price) ? $request->purchase_price : null,
            'vendor_name' => $request->vendor_name,
            'initial_value' => $request->initial_value,
            'current_market_value' => !empty($request->current_market_value) ? $request->current_market_value : null,
            'accumulated_depreciation' => !empty($request->accumulated_depreciation) ? $request->accumulated_depreciation : null,
            'accumulated_depreciation' => $request->accumulated_depreciation ? $request->accumulated_depreciation : null,

            'owner_name' => $request->owner_name,
            'title_deed_number' => $request->title_deed_number,
            'condition' => $request->condition,
            'company_id' => $company_id,
            'status' => $request->status,
        ]);

        $this->logActivity(
            'Create a  asset',
            'Asset name ' . $request->name,
            route('company.asset.index'),
            'New Asset Created successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );


        return redirect()->route('company.assets.index')->with('success', __('Assets successfully created.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function edit($id)
    {
        // if (\Auth::user()->can('edit asset')) {
        $company_id                 = Auth::user()->creatorId();
        $property = Property::where('company_id', $company_id)->get()->pluck('name', 'id');
        $property->prepend(__('Select Property'), 0);

        $asset = Asset::find($id);
        return view('company.asset.edit', compact('property', 'asset'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function update(Request $request, $id)
    {
        // if (\Auth::user()->can('edit asset')) {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:fixed_asset,current_asset,bank',
                'property_id' => 'nullable|exists:properties,id',
                'location' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_price' => 'nullable|numeric',
                'vendor_name' => 'nullable|string|max:255',
                'initial_value' => 'required|numeric',
                'current_market_value' => 'required|numeric',
                'accumulated_depreciation' => 'nullable|numeric',
                'owner_name' => 'nullable|string|max:255',
                'title_deed_number' => 'nullable|string|max:255',
                'asset_condition' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
            ]
        );
        if ($validator->fails()) {

            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
        }
        // Retrieve the asset by ID
        $asset = Asset::findOrFail($id);

        // Update asset properties
        $asset->update([
            'name' => $request->name,
            'type' => $request->type,
            'property_id' => !empty($request->property_id) ? $request->property_id : null,
            'location' => $request->location,
            'purchase_date' => !empty($request->purchase_date) ? $request->purchase_date : null,
            'purchase_price' => !empty($request->purchase_price) ? $request->purchase_price : null,
            'vendor_name' => $request->vendor_name,
            'initial_value' => $request->initial_value,
            'current_market_value' => !empty($request->current_market_value) ? $request->current_market_value : null,
            'accumulated_depreciation' => !empty($request->accumulated_depreciation) ? $request->accumulated_depreciation : null,
            'accumulated_depreciation' => $request->accumulated_depreciation ? $request->accumulated_depreciation : null,

            'owner_name' => $request->owner_name,
            'title_deed_number' => $request->title_deed_number,
            'condition' => $request->condition,

            'status' => $request->status,
        ]);


        $this->logActivity(
            'Update a asset',
            'Asset name ' . $request->name,
            route('company.asset.index'),
            'Asset Updated successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return redirect()->route('company.assets.index')->with('success', __('Assets successfully Updated.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function destroy($id)
    {
        // if (\Auth::user()->can('delete asset')) {
        $asset = Asset::find($id);
        $asset->delete();

        $this->logActivity(
            'Delete a asset',
            'Asset name ' . $asset->name,
            route('company.asset.index'),
            'Asset Deleted successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->back()->with('success', 'Asset successfully deleted.');
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }
}
