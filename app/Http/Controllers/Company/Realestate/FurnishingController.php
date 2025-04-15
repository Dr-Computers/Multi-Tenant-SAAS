<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateFurnishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class  FurnishingController extends Controller
{
    public function index()
    {
        $furnishings = RealestateFurnishing::get();
        return view('company.realestate.furnishings.index', compact('furnishings'));
    }

    public function create()
    {
        return view('company.realestate.furnishings.form');
    }
    public function store(Request $request){
        $furnishing                   = new RealestateAddonRequest();
        $furnishing->requesting_type  = 'furnishing';
        $furnishing->request_for      = $request->category;
        $furnishing->verified_by      = null;
        $furnishing->notes            = $request->notes;
        $furnishing->status           = 0;
        $furnishing->save();
        return redirect()->back()->with('success', 'New Furnishing Request submited.');

    }


    public function destroy(RealestateAddonRequest $furnishing)
    {
        $furnishing->delete();
        return redirect()->back()->with('success', 'Furnishing request deleted.');
    }

    public function show(RealestateAddonRequest $furnishing)
    {
        return view('company.realestate.furnishings.show', compact('furnishing'));
    }

}