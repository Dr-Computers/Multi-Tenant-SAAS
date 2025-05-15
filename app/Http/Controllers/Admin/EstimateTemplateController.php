<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstimateTemplate;
use Illuminate\Http\Request;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\ActivityLogger;


class EstimateTemplateController extends Controller
{
    use ActivityLogger;

    
    public function index()
    {
        if (Auth::user()->can('edit email template')) {
            $templates = EstimateTemplate::get();
            return view('admin.template.estimates.index', compact('templates'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {

        if (Auth::user()->can('edit email template')) {
            return view('admin.template.estimates.form');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('edit email template')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $new = new EstimateTemplate();
            $new->name = $request->name;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('app/public/uploads/templates');
                $new->image = $path;
            }
            $new->save();

            return redirect()->back()->with('success', 'Estimate Template created successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }




    public function edit($template)
    {
        if (Auth::user()->can('edit email template')) {
            $template  = EstimateTemplate::where('id', $template)->first() ?? abort(404);
            return view('admin.template.estimates.form', compact('template'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit email template')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // not 'required' in update
            ]);

            $new = EstimateTemplate::findOrFail($id);
            $new->name = $request->name;

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($new->image && Storage::exists($new->image)) {
                    Storage::delete($new->image);
                }

                // Store new image
                $path = $request->file('image')->store('app/public/uploads/templates');
                $new->image = $path;
            }

            $new->save();

            return redirect()->back()->with('success', 'Estimate Template Updated Successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->can('edit email template')) {
            $template = EstimateTemplate::findOrFail($id);

            // Delete image file if it exists
            if ($template->image && Storage::exists($template->image)) {
                Storage::delete($template->image);
            }

            // Delete database record
            $template->delete();

            return redirect()->back()->with('success', 'Estimate Template deleted successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
