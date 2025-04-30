<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateFurnishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class  FurnishingController extends Controller
{
    public function index()
    {
        $furnishings = RealestateFurnishing::get();
        return view('company.realestate.furnishing.index', compact('furnishings'));
    }

    public function create()
    {
        return view('company.realestate.furnishing.request-form');
    }
    public function store(Request $request){
        $furnishing                   = new RealestateAddonRequest();
        $furnishing->company_id       = Auth::user()->creatorId();
        $furnishing->requesting_type  = 'furnishing';
        $furnishing->request_for      = $request->name;
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

    public function edit(RealestateAddonRequest $furnishing)
    {
        return view('company.realestate.furnishing.request-show',compact('furnishing'));
    }

    public function show()
    {
        $furnishings =  RealestateAddonRequest::where('requesting_type','furnishing')->where('company_id',Auth::user()->creatorId())->get();

        return view('company.realestate.furnishing.requests', compact('furnishings'));
    }

}