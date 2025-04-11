<?php

namespace App\Http\Controllers\Admin\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateFurnishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class  FurnishingController extends Controller
{
    public function index()
    {
        $furnishings = RealestateFurnishing::get();
        return view('admin.realestate.furnishings.index', compact('furnishings'));
    }

    public function create()
    {

        return view('admin.realestate.furnishings.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:100',
            // 'icon'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $category                  = new RealestateFurnishing();
        $category->name            = $request->name;
        $category->save();

        return redirect()->route('admin.realestate.furnishings.index')->with('success', 'Furnishing created successfully.');
    }

    public function edit($id)
    {
        $furnishing = RealestateFurnishing::where('id', $id)->first() ?? abort(404);

        return view('admin.realestate.furnishings.form', compact('furnishing'));
    }

    public function update(Request $request, RealestateFurnishing $furnishing)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $furnishing->name            = $request->name;
        $furnishing->save();

        return redirect()->route('admin.realestate.furnishings.index')->with('success', 'Furnishing updated successfully.');
    }

    public function destroy(RealestateFurnishing $furnishing)
    {
        $furnishing->delete();
        return redirect()->back()->with('success', 'Furnishing deleted successfully.');
    }

    public function show(RealestateFurnishing $furnishing)
    {
        return view('admin.realestate.furnishings.show', compact('furnishing'));
    }

}