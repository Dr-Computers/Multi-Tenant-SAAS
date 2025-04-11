<?php

namespace App\Http\Controllers\Admin\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateLandmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class  LandmarkController extends Controller
{
    public function index()
    {
        $landmarks = RealestateLandmark::get();
        return view('admin.realestate.landmarks.index', compact('landmarks'));
    }

    public function create()
    {

        return view('admin.realestate.landmarks.form');
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

        $category                  = new RealestateLandmark();
        $category->name            = $request->name;
        $category->save();

        return redirect()->route('admin.realestate.landmarks.index')->with('success', 'Landmark created successfully.');
    }

    public function edit($id)
    {
        $landmark = RealestateLandmark::where('id', $id)->first() ?? abort(404);

        return view('admin.realestate.landmarks.form', compact('landmark'));
    }

    public function update(Request $request, RealestateLandmark $landmark)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $landmark->name            = $request->name;
        $landmark->save();

        return redirect()->route('admin.realestate.landmarks.index')->with('success', 'Owner updated successfully.');
    }

    public function destroy(RealestateLandmark $landmark)
    {
        $landmark->delete();
        return redirect()->back()->with('success', 'Landmark deleted successfully.');
    }

    public function show(RealestateLandmark $landmark)
    {
        return view('admin.realestate.landmarks.show', compact('landmark'));
    }
}