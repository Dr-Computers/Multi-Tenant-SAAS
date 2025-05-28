<?php

namespace App\Http\Controllers\Company\Realestate;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\MediaFile;
use App\Models\MediaFolder;
use App\Models\Owner;
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
use Exception;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    use HandlesMediaFolders;
    use ActivityLogger;

    public function index()
    {
        if (Auth::user()->can('owner user listing')) {
            $owners = User::where('type', 'owner')->where('parent', Auth::user()->creatorId())->get();
            return view('company.realestate.owners.index', compact('owners'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('create owner user')) {
            return view('company.realestate.owners.form');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create owner user')) {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name'     => 'required|max:100',
                'email'    => 'required|email|unique:users',
                'mobile'   => 'required',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            try {
                $user = new User();
                $user->name            = $request->name;
                $user->email           = $request->email;
                $user->mobile          = $request->mobile;
                $user->type            = 'owner';
                $user->is_enable_login = $request->has('password_switch');
                $user->created_by      = auth()->user()->id;
                $user->parent          = auth()->user()->creatorId();
                $user->password        = Hash::make($request->password);
                $user->is_active       = $request->has('is_active') ? 1 : 0;

                if ($request->hasFile('profile')) {
                    $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');
                    $user->avatar = $file_id;
                }
                $user->save();

                $owner = new Owner();
                $owner->user_id = $user->id;
                $owner->is_tenants_approval = $request->has('is_tenants_approval');
                $owner->save();

                $personal               = new PersonalDetail();
                $personal->user_id        = $user->id;
                $personal->address        = $request->address;
                $personal->trn_no        =  $request->trn_no;
                $personal->city            =  $request->city;
                $personal->state        =  $request->state;
                $personal->postal_code    =  $request->postal_code;
                $personal->country        =  $request->country;
                $personal->save();

                $role_r = Role::findByName('owner-' . Auth::user()->creatorId());
                $user->assignRole($role_r);

                $this->logActivity(
                    'Create a Owner User',
                    'User Id ' . $user->id,
                    route('company.realestate.owners.index'),
                    'New Owner User Created successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );
                DB::commit();
                return redirect()->route('company.realestate.owners.index')->with('success', 'Owner created successfully.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit owner user')) {
            $user = User::with('owner')->where('parent', '=', Auth::user()->creatorId())->where('id', $id)->first() ?? abort(404);

            return view('company.realestate.owners.form', compact('user'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, User $owner)
    {
        if (Auth::user()->can('edit owner user')) {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name'   => 'required|max:100',
                'email'  => 'required|email|unique:users,email,' . $owner->id,
                'mobile' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            try {
                $owner->name            = $request->name;
                $owner->email           = $request->email;
                $owner->mobile          = $request->mobile;
                // $owner->is_enable_login = $request->has('password_switch');
                $owner->is_active       = $request->has('is_active') ? 1 : 0;

                if ($request->hasFile('profile')) {
                    $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');
                    $owner->avatar = $file_id;
                }

                $owner->save();

                $personal = PersonalDetail::where('user_id', $owner->id)->first();
                $personal->address        = $request->address;
                $personal->trn_no        =  $request->trn_no;
                $personal->city            =  $request->city;
                $personal->state        =  $request->state;
                $personal->postal_code    =  $request->postal_code;
                $personal->country        =  $request->country;
                $personal->save();

                $personal_owner = Owner::where('user_id', $owner->id)->first();
                $personal_owner->is_tenants_approval = $request->has('is_tenants_approval');
                $personal_owner->save();

                if ($owner->getRoleNames()->first() != 'owner') {
                    $role_r = Role::findByName('owner-' . Auth::user()->creatorId());
                    $owner->roles()->sync([$role_r->id]);
                }
                DB::commit();
                $this->logActivity(
                    'Update a Owner User',
                    'User Id ' . $owner->id,
                    route('company.realestate.owners.index'),
                    'Owner User Updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return redirect()->route('company.realestate.owners.index')->with('success', 'Owner updated successfully.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(User $owner)
    {
        if (Auth::user()->can('delete owner user')) {
            Owner::where('user_id', $owner->id)->delete();
            $owner->delete();
            $this->logActivity(
                'Delete a Owner User',
                'User Id ' . $owner->id,
                route('company.realestate.owners.index'),
                'Owner User Deleted successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back()->with('success', 'Owner deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show(User $owner)
    {
        if (Auth::user()->can('owner user details')) {
            return view('company.realestate.owners.show', compact('owner'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function resetPasswordForm(User $user)
    {
        return view('company.realestate.owners.reset-password', compact('user'));
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
        if (Auth::user()->can('upload owner documents')) {
            $user = User::find($id);
            return view('company.realestate.owners.form-documents', compact('user'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function uploadDocuments(Request $request, $user_id)
    {

        if (Auth::user()->can('upload owner documents')) {
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
                'Owner document uploaded',
                'User Id ' . $user_id,
                route('company.realestate.owners.index'),
                'Owner document uploaded successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return response()->json(['success' => true]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function deleteDocument(Document $document)
    {
        if (Auth::user()->can('upload owner documents')) {
            $file = MediaFile::findOrFail($document->file->id);
            if ($file) {
                $this->softDeleteFile($file);
            }
            $document->delete();
            $this->logActivity(
                'Owner document deleted',
                '',
                route('company.realestate.owners.index'),
                'Owner document  Delete successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->back();
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
