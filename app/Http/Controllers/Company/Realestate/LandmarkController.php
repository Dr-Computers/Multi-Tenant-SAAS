<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\RealestateAddonRequest;
use App\Models\RealestateLandmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class  LandmarkController extends Controller
{
    public function index()
    {
        $landmarks = RealestateLandmark::get();
        return view('company.realestate.landmarks.index', compact('landmarks'));
    }

    public function create()
    {

        return view('company.realestate.landmarks.request-form');
    }

    public function store(Request $request){
        $landmark                   = new RealestateAddonRequest();
        $landmark->company_id       = Auth::user()->creatorId();
        $landmark->requesting_type  = 'landmark';
        $landmark->request_for      = $request->name;
        $landmark->verified_by      = null;
        $landmark->notes            = $request->notes;
        $landmark->status           = 0;
        $landmark->save();
        return redirect()->back()->with('success', 'New Landmark Request submited.');

    }


    public function destroy(RealestateAddonRequest $landmark)
    {
        $landmark->delete();
        return redirect()->back()->with('success', 'Landmark Request Deleted.');
    }

    public function edit(RealestateAddonRequest $landmark)
    {
        return view('company.realestate.landmarks.request-show',compact('landmark'));
    }


    public function show()
    {
        $landmarks =  RealestateAddonRequest::where('requesting_type','landmark')->where('company_id',Auth::user()->creatorId())->get();

        return view('company.realestate.landmarks.requests', compact('landmarks'));
    }
}