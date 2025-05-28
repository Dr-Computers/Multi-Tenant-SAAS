<?php

namespace App\Http\Controllers\Company;

use App\Models\Liability;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ActivityLogger;

class LiabilityController extends Controller
{
    use ActivityLogger;
    public function index()
    {
        if (\Auth::user()->can('liabilities listing')) {
            $company_id                 = Auth::user()->creatorId();
            $liabilities = Liability::where('company_id', $company_id)->orderBy('created_at', 'desc')->paginate(25);


            return view('company.liability.index', compact('liabilities'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function create()
    {
        if (Auth::user()->can('create a liability')) {
            $company_id                 = Auth::user()->creatorId();
            $properties = Property::where('company_id', $company_id)->get()->pluck('name', 'id');
            $properties->prepend(__('Select Property'), 0);
            return view('company.liability.create', compact('properties'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function store(Request $request)
    {
        // Check if the user has permission to create a liability
        if (\Auth::user()->can('create a liability')) {
            // Validate the incoming request data
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'property_id' => 'nullable|exists:properties,id',
                'amount' => 'required|numeric',
                'due_date' => 'required|date',
                'vendor_name' => 'nullable|string|max:255',
                'payment_terms' => 'nullable|string|max:255',
                'interest_rate' => 'nullable|numeric|between:0,100',
                'status' => 'required|string|in:active,paid,overdue',
                'notes' => 'nullable|string',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
            }

            $company_id        = Auth::user()->creatorId();
            // Create a new liability record
            Liability::create([
                'name' => $request->name,
                'type' => $request->type,
                'property_id' => !empty($request->property_id) ? $request->property_id : null,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'vendor_name' => !empty($request->vendor_name) ? $request->vendor_name : null,
                'payment_terms' => !empty($request->payment_terms) ? $request->payment_terms : null,
                'status' => $request->status,
                'interest_rate' => !empty($request->interest_rate) ? $request->interest_rate : null,
                'notes' =>  !empty($request->notes) ? $request->notes : null,
                'company_id' => $company_id,
            ]);

            $this->logActivity(
                'Create a Liability',
                'Liability name ' . $request->name,
                route('company.liability.index'),
                'New Liability  Created successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            // Redirect to the liabilities index page with a success message
            return redirect()->route('company.liabilities.index')->with('success', __('Liability successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function edit($id)
    {
        $company_id        = Auth::user()->creatorId();
        if (\Auth::user()->can('edit a liability')) {
            $property = Property::where('company_id', $company_id)->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);

            $liability = Liability::find($id);
            return view('company.liability.edit', compact('property', 'liability'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit a liability')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'type' => 'required|string|max:255',
                    'property_id' => 'nullable|exists:properties,id',
                    'amount' => 'required|numeric',
                    'due_date' => 'required|date',
                    'vendor_name' => 'nullable|string|max:255',
                    'payment_terms' => 'nullable|string|max:255',
                    'status' => 'required|string|in:active,paid,overdue',
                    'interest_rate' => 'nullable|numeric|between:0,100',
                    'notes' => 'nullable|string',
                ]
            );
            if ($validator->fails()) {

                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput(); // Add withInput() here
            }
            // Retrieve the asset by ID
            $liability = liability::findOrFail($id);

            // Update asset properties
            $liability->update([
                'name' => $request->name,
                'type' => $request->type,
                'property_id' => !empty($request->property_id) ? $request->property_id : null,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'vendor_name' => !empty($request->vendor_name) ? $request->vendor_name : null,
                'payment_terms' => !empty($request->payment_terms) ? $request->payment_terms : null,
                'status' => $request->status,
                'interest_rate' => !empty($request->interest_rate) ? $request->interest_rate : null,
                'notes' =>  !empty($request->notes) ? $request->notes : null,

            ]);

            $this->logActivity(
                'Update a Liability',
                'Liability name ' . $request->name,
                route('company.liability.index'),
                'Liability  Updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            return redirect()->route('company.liabilities.index')->with('success', __('Liability successfully Updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete a liability')) {
            $liability = Liability::find($id);
            $liability->delete();

            $this->logActivity(
                'Delete a Liability',
                'Liability name ' . $liability->name,
                route('company.liability.index'),
                'Liability  Deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', 'Liability successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
}
