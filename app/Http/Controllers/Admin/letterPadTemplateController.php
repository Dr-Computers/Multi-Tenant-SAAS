<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterPadTemplate;
use Illuminate\Http\Request;
use App\Traits\Media\HandlesMediaFolders;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class letterPadTemplateController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('letter pad template listing')) {
            $templates = LetterPadTemplate::get();
            return view('admin.template.letter_pad.index', compact('templates'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('show letter pad template')) {
            return view('admin.template.letter_pad.form');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('show letter pad template')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'header' => 'required|string',
                'footer' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);


            $new = new LetterPadTemplate();
            $new->name = $request->name;
            $new->header = $request->header;
            $new->footer = $request->footer;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads/templates');
                $new->image = $path;
            }
            try {
                $new->save();

                return redirect()->back()->with('success', 'Letter pad Template created successfully.');
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }




    public function edit($template)
    {
        if (Auth::user()->can('edit letter pad template')) {
            $template  = LetterPadTemplate::where('id', $template)->first() ?? abort(404);
            return view('admin.template.letter_pad.form', compact('template'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit letter pad template')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'header' => 'required|string',
                'footer' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // not 'required' in update
            ]);

            $new = LetterPadTemplate::findOrFail($id);

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

            return redirect()->back()->with('success', 'Letter pad Template Updated Successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->can('delete letter pad template')) {
            $template = LetterPadTemplate::findOrFail($id);

            // Delete image file if it exists
            if ($template->image && Storage::exists($template->image)) {
                Storage::delete($template->image);
            }

            // Delete database record
            $template->delete();

            return redirect()->back()->with('success', 'Letter pad Template deleted successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
