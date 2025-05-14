<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceTemplate;
use Illuminate\Http\Request;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceTemplateController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('invoice template listing')) {
            $templates = InvoiceTemplate::get();
            return view('admin.template.invoices.index', compact('templates'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {

        if (Auth::user()->can('create invoice template')) {
            return view('admin.template.invoices.form');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create invoice template')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $new = new InvoiceTemplate();
            $new->name = $request->name;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads/templates');
                $new->image = $path;
            }
            $new->save();

            return redirect()->back()->with('success', 'Invoice Template created successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }




    public function edit($template)
    {
        if (Auth::user()->can('edit invoice template')) {
            $template  = InvoiceTemplate::where('id', $template)->first() ?? abort(404);
            return view('admin.template.invoices.form', compact('template'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit invoice template')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'header' => 'required|string',
                'footer' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // not 'required' in update
            ]);

            $new = InvoiceTemplate::findOrFail($id);

            $new->name = $request->name;
            $new->header = $request->header;
            $new->footer = $request->footer;



            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($new->image && Storage::exists($new->image)) {
                    Storage::delete($new->image);
                }

                // Store new image
                $path = $request->file('image')->store('uploads/templates');
                $new->image = $path;
            }

            $new->save();

            return redirect()->back()->with('success', 'Invoice Template Updated Successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->can('delete invoice template')) {
            $template = InvoiceTemplate::findOrFail($id);

            // Delete image file if it exists
            if ($template->image && Storage::exists($template->image)) {
                Storage::delete($template->image);
            }

            // Delete database record
            $template->delete();

            return redirect()->back()->with('success', 'Invoice Template deleted successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
