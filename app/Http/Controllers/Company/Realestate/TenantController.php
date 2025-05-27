<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\MediaFile;
use App\Models\MediaFolder;
use App\Models\PersonalDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\ActivityLogger;

class TenantController extends Controller
{
    use HandlesMediaFolders;
    use ActivityLogger;

    public function index()
    {
        $tenants = User::where('type', 'tenant')->where('parent', Auth::user()->creatorId())->get();
        return view('company.realestate.tenants.index', compact('tenants'));
    }

    public function create()
    {

        return view('company.realestate.tenants.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:100',
            'email'    => 'required|email|unique:users',
            'mobile'   => 'required',
            'password' => 'required|min:6',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $user = new User();
        $user->name            = $request->name;
        $user->email           = $request->email;
        $user->mobile          = $request->mobile;
        $user->type            = 'tenant';
        $user->is_enable_login = $request->has('password_switch');
        $user->created_by      = auth()->user()->id;
        $user->parent          = auth()->user()->creatorId();
        $user->password        = Hash::make($request->password);
        $user->is_active = $request->has('is_active')  ? 1 : 0;
        if ($request->hasFile('profile')) {
            $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');
            $user->avatar = $file_id;
        }

        $user->save();

        $personal               = new PersonalDetail();
        $personal->user_id        = $user->id;
        $personal->address        = $request->address;
        $personal->trn_no        =  $request->trn_no;
        $personal->city            =  $request->city;
        $personal->state        =  $request->state;
        $personal->postal_code    =  $request->postal_code;
        $personal->country        =  $request->country;
        $personal->save();

        $role_r = Role::findByName('tenant-' . Auth::user()->creatorId());
        $user->assignRole($role_r);

        $this->logActivity(
            'Create a Tenent User',
            'User Id ' . $user->id,
            route('company.realestate.tenants.index'),
            'New Tenent User Created successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->route('company.realestate.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function edit($id)
    {
        $user = User::with('personal')->where('parent', '=', Auth::user()->creatorId())->where('id', $id)->first() ?? abort(404);

        return view('company.realestate.tenants.form', compact('user'));
    }

    public function update(Request $request, User $tenant)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
            'email'  => 'required|email|unique:users,email,' . $tenant->id,
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $tenant->name            = $request->name;
        $tenant->email           = $request->email;
        $tenant->mobile          = $request->mobile;
        // $tenant->is_enable_login = $request->has('password_switch');
        $tenant->is_active = $request->has('is_active')  ? 1 : 0;
        if ($request->hasFile('profile')) {
            $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');
            $tenant->avatar = $file_id;
        }

        $tenant->save();

        if ($tenant->getRoleNames()->first() != 'tenant') {
            $role_r = Role::findByName('tenant-' . Auth::user()->creatorId());
            $tenant->roles()->sync([$role_r->id]);
        }


        $personal = PersonalDetail::where('user_id', $tenant->id)->first();
        $personal->address        = $request->address;
        $personal->trn_no        =  $request->trn_no;
        $personal->city            =  $request->city;
        $personal->state        =  $request->state;
        $personal->postal_code    =  $request->postal_code;
        $personal->country        =  $request->country;
        $personal->save();

        $this->logActivity(
            'Update a Tenent User',
            'User Id ' . $tenant->id,
            route('company.realestate.tenants.index'),
            'Tenent User Updated successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );


        return redirect()->route('company.realestate.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(User $tenant)
    {

        PersonalDetail::where('user_id', $tenant->id)->delete();
        $tenant->delete();
        $this->logActivity(
            'Delete a Tenent User',
            'User Id ' . $tenant->id,
            route('company.realestate.tenants.index'),
            'Tenent User Deleted successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->back()->with('success', 'Tenant deleted successfully.');
    }

    public function show(User $tenant)
    {
        return view('company.realestate.tenants.show', compact('tenant'));
    }

    public function resetPasswordForm(User $user)
    {
        return view('company.realestate.tenants.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password reset successfully.');
    }

    public function createDocuments($id)
    {
        $user = User::find($id);
        return view('company.realestate.tenants.form-documents', compact('user'));
    }


    public function uploadDocuments(Request $request, $user_id)
    {

        $request->validate([
            'document_type' => 'required',
            'documents.*' => 'required|file|max:51200', // 50MB max per file
        ]);

        $user = User::findOrFail($user_id);
        $companyId = Auth::user()->creatorId();
        $disk = env('FILESYSTEM_DISK', 'public');
        $basePath = 'uploads/company_' . $companyId;

        // Safe user folder name (slug it)
        $userFolder = Str::slug($user->name) . '-' . $user->id;

        // Make sure the full user folder exists

        $userPath = $basePath . '/users';
        $documentPath = $basePath . '/users/documents';
        $fullPath = $basePath . '/users/documents' . '/' . $userFolder;

        if (!Storage::disk($disk)->exists($userPath)) {
            Storage::disk($disk)->makeDirectory($userPath);
            $user = MediaFolder::create([
                'company_id' => $companyId,
                'parent_id' => NULL,
                'name' => 'users',
                'path' => $userPath,
                'slug' => Str::slug('users'),
            ]);
        } else {
            $user = MediaFolder::where('company_id', $companyId)->where('name', 'users')->first();
        }



        if (!Storage::disk($disk)->exists($documentPath)) {
            Storage::disk($disk)->makeDirectory($documentPath);
            $document = MediaFolder::create([
                'company_id' => $companyId,
                'parent_id' => $user->id,
                'name' => 'documents',
                'path' => $documentPath,
                'slug' => Str::slug('documents'),
            ]);
        } else {
            $document = MediaFolder::where('company_id', $companyId)->where('name', 'documents')->first();
        }


        if (!Storage::disk($disk)->exists($fullPath)) {
            Storage::disk($disk)->makeDirectory($fullPath);
            MediaFolder::create([
                'company_id' => $companyId,
                'parent_id' => $document->id,
                'name' => $userFolder,
                'path' => $fullPath,
                'slug' => $userFolder,
            ]);
        }

        foreach ($request->documents ?? [] as $file) {

            // Upload and save the file inside the correct user folder
            $file_id = $this->uploadAndSaveFile($file, $companyId, $userFolder);

            $new_doc = new Document();
            $new_doc->company_id = $companyId;
            $new_doc->user_id = $user_id;
            $new_doc->file_id = $file_id;
            $new_doc->document_type = $request->document_type ?? 'unknown';
            $new_doc->save();
        }

        $this->logActivity(
            'Tenent document uploaded',
            'User Id ' . $user_id,
            route('company.realestate.tenants.index'),
            'Tenent document uploaded successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return response()->json(['success' => true]);
    }


    public function deleteDocument(Document $document)
    {

        $file = MediaFile::findOrFail($document->file->id);
        if ($file) {
            $this->softDeleteFile($file);
        }
        $document->delete();
        $this->logActivity(
            'Tenent document deleted',
            '',
            route('company.realestate.tenants.index'),
            'Tenent document  Delete successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->back();
    }
}
