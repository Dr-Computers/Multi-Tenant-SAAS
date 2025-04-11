<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index() {
        $properties = collect();
        return view('company.realestate.maintainers.index', compact('properties'));

    }
    public function create() {}
    public function store(Request $request) {}
    public function edit($id) {}
    public function update(Request $request, User $owner) {}
    public function destroy(User $owner) {}
    public function show(User $owner) {}
}
