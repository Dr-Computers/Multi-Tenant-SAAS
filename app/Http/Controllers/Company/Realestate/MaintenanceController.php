<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\MaintenanceJob;
use App\Models\MaintenanceTypes;
use App\Models\MediaFile;
use App\Models\MediaFolder;
use App\Models\PersonalDetail;
use App\Models\User;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\ActivityLogger;

class MaintenanceController extends Controller
{
    use HandlesMediaFolders;
    use ActivityLogger;
    public function index()
    {
        $maintainers = User::where('type', 'maintainer')->where('parent', Auth::user()->creatorId())->get();
        return view('company.realestate.maintainers.index', compact('maintainers'));
    }

    public function create()
    {

        $types = MaintenanceTypes::get();
        return view('company.realestate.maintainers.form', compact('types'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:100',
            'email'    => 'required|email|unique:users',
            'mobile'   => 'required',
            'type'   => 'required',
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
        $user->type            = 'maintainer';
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
        $personal->user_id      = $user->id;
        $personal->address      = $request->address;
        $personal->trn_no       =  $request->trn_no;
        $personal->city         =  $request->city;
        $personal->state        =  $request->state;
        $personal->postal_code  =  $request->postal_code;
        $personal->country      =  $request->country;
        $personal->save();

        $jobType               = new MaintenanceJob();
        $jobType->user_id      = $user->id;
        $jobType->type_id          = $request->type;
        $jobType->save();

        $role_r = Role::findByName('maintainer-' . Auth::user()->creatorId());
        $user->assignRole($role_r);

        $this->logActivity(
            'Create a Maintainer User',
            'User Id ' . $user->id,
            route('company.realestate.maintainers.index'),
            'New Maintainer User Created successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->route('company.realestate.maintainers.index')->with('success', 'Maintainer created successfully.');
    }

    public function edit($id)
    {
        $user = User::with('personal')->where('parent', '=', Auth::user()->creatorId())->where('id', $id)->first() ?? abort(404);
        $types = MaintenanceTypes::get();
        return view('company.realestate.maintainers.form', compact('user', 'types'));
    }

    public function update(Request $request, User $maintainer)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:100',
            'email'  => 'required|email|unique:users,email,' . $maintainer->id,
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
            'type'   => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $maintainer->name            = $request->name;
        $maintainer->email           = $request->email;
        $maintainer->mobile          = $request->mobile;
        // $maintainer->is_enable_login = $request->has('password_switch');
        if ($request->hasFile('profile')) {
            $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');
            $maintainer->avatar = $file_id;
        }
        $maintainer->is_active = $request->has('is_active')  ? 1 : 0;
        $maintainer->save();

        if ($maintainer->getRoleNames()->first() != 'maintainer') {
            $role_r = Role::findByName('maintainer-' . Auth::user()->creatorId());
            $maintainer->roles()->sync([$role_r->id]);
        }

        $personal = PersonalDetail::where('user_id', $maintainer->id)->first();
        $personal->address        = $request->address;
        $personal->trn_no        =  $request->trn_no;
        $personal->city            =  $request->city;
        $personal->state        =  $request->state;
        $personal->postal_code    =  $request->postal_code;
        $personal->country        =  $request->country;
        $personal->save();

        MaintenanceJob::where('user_id', $maintainer->id)->delete();
        $jobType               = new MaintenanceJob();
        $jobType->user_id      = $maintainer->id;
        $jobType->type_id      = $request->type;
        $jobType->save();

        $this->logActivity(
            'Update a Maintainer User',
            'User Id ' . $maintainer->id,
            route('company.realestate.maintainers.index'),
            'Maintainer User Updated successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->route('company.realestate.maintainers.index')->with('success', 'Maintainer updated successfully.');
    }

    public function destroy(User $maintainer)
    {
        MaintenanceJob::where('user_id', $maintainer->id)->delete();
        $maintainer->delete();
        $this->logActivity(
            'Delete a Maintainer User',
            'User Id ' . $maintainer->id,
            route('company.realestate.maintainers.index'),
            'Maintainer User Deleted successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->back()->with('success', 'Maintainer deleted successfully.');
    }

    public function show(User $maintainer)
    {
        return view('company.realestate.maintainers.show', compact('maintainer'));
    }

    public function resetPasswordForm(User $user)
    {
        return view('company.realestate.maintainers.reset-password', compact('user'));
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
        return view('company.realestate.maintainers.form-documents', compact('user'));
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
            'Maintainer document uploaded',
            'User Id ' . $user_id,
            route('company.realestate.maintainers.index'),
            'Maintainer document uploaded successfully',
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
            'Maintainer document deleted',
            '',
            route('company.realestate.maintainers.index'),
            'Maintainer document  Delete successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->back();
    }
}
