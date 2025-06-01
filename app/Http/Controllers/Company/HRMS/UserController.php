<?php

namespace App\Http\Controllers\Company\HRMS;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CustomField;
use App\Models\Document;
use App\Models\MediaFile;
use App\Models\MediaFolder;
use App\Models\PersonalDetail;
use App\Models\Role;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\Media\HandlesMediaFolders;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Illuminate\Support\Str;
use App\Traits\ActivityLogger;

class UserController extends Controller
{
    use HandlesMediaFolders;
    use ActivityLogger;

    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
    }

    public function index()
    {
        if (Auth::user()->can('staff user listing')) {
            $users = User::where('parent', Auth::user()->creatorId())->where('type', 'company-staff')->get();
            return view('company.hrms.user.index')->with('users', $users);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create()
    {
        if (Auth::user()->can('create staff user')) {
            $customFields = CustomField::where('created_by', '=', Auth::user()->creatorId())->where('module', '=', 'user')->get();
            $user         = Auth::user();
            $roles  = Role::where('created_by', Auth::user()->creatorId())->where('is_deletable', 1)->get();

            return view('company.hrms.user.form', compact('customFields', 'roles'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create staff user')) {
            DB::beginTransaction();
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $userpassword               = $request->input('password');
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users',
                    'mobile' => 'required|max:250',
                    'role'   => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $enableLogin       = 0;
            if (!empty($request->password_switch) && $request->password_switch == 'on') {
                $enableLogin   = 1;
                $validator = Validator::make(
                    $request->all(),
                    ['password' => 'required|min:6']
                );

                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }


            if ($request->hasFile('profile')) {
                $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');
            } else {
                $file_id = NULL;
            }


            $user               = new User();
            $user['name']       = $request->name;
            $user['email']      = $request->email;
            $user['mobile']     = $request->mobile;
            $user['email_verified_at'] = date('Y-m-d H:i:s');
            $psw                = $request->password;
            $user['password'] = !empty($userpassword) ? Hash::make($userpassword) : null;
            $user['type']       = 'company-staff';
            $user['lang']       = !empty($default_language) ? $default_language->value : '';
            $user['created_by'] = Auth::user()->id;
            $user['is_enable_login'] = $enableLogin;
            $user['is_active'] = $request->has('is_active') ? 1 : 0;
            $user['avatar']     = $file_id;
            $user['parent']     = Auth::user()->creatorId();
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

            $role_r = Role::findById($request->role);
            $user->assignRole($role_r);

            $uArr = [
                'email' => $user->email,
                'password' => $psw,
            ];

            try {
                $resp = Utility::sendEmailTemplate('user_created', [$user->id => $user->email], $uArr);
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }
            DB::commit();

            $this->logActivity(
                'Create a Staff User',
                'User Id ' . $user->id,
                route('company.hrms.users.index'),
                'New Staff User Created successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            return redirect()->route('company.hrms.users.index')->with('success', __('User successfully added.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show(User $user)
    {
        if (Auth::user()->can('staff user details')) {
            $companyId = Auth::user()->creatorId();
            $documents = Document::where('company_id', $companyId)->where('user_id', $user->id)->get();
            $activity_logs = ActivityLog::where('company_id', $companyId)->where('user_id', $user->id)->get();
            return view('company.hrms.user.show', compact('user', 'documents', 'activity_logs'));
        } else {

            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function resetPasswordForm(User $user)
    {
        return view('company.hrms.user.reset-password', compact('user'));
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit staff user')) {
            $user  = Auth::user();
            $user              = User::findOrFail($id);
            $user->customField = CustomField::getData($user, 'user');
            $roles  = Role::where('created_by', Auth::user()->creatorId())->where('is_deletable', 1)->get();
            $customFields      = CustomField::where('created_by', '=', Auth::user()->creatorId())->where('module', '=', 'user')->get();

            return view('company.hrms.user.form', compact('user', 'customFields', 'roles'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
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


    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit staff user')) {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'mobile' => 'required|max:120',
                    'role'   => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $input = $request->all();
            $user->fill($input)->save();

            if ($request->hasFile('profile')) {
                $file_id = $this->uploadAndSaveFile($request->profile, Auth::user()->creatorId(), 'avatar');
                $user->avatar = $file_id;
            }

            $user->is_active = $request->has('is_active')  ? 1 : 0;
            $user->save();
            // $role_r = Role::findById($request->role);
            // $user->assignRole($role_r);
            $user->roles()->sync([$request->input('role')]);

            CustomField::saveData($user, $request->customField);

            $personal = PersonalDetail::where('user_id', $user->id)->first();
            $personal->address        = $request->address;
            $personal->trn_no        =  $request->trn_no;
            $personal->city            =  $request->city;
            $personal->state        =  $request->state;
            $personal->postal_code    =  $request->postal_code;
            $personal->country        =  $request->country;
            $personal->save();
            DB::commit();
            $this->logActivity(
                'Update a Staff User',
                'User Id ' . $user->id,
                route('company.hrms.users.index'),
                'Staff User Updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );
            return redirect()->route('company.hrms.users.index')->with(
                'success',
                'User successfully updated.'
            );
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->can('delete staff user')) {
            $user = User::find($id);

            if ($user) {
                $user->delete();
                $this->logActivity(
                    'Delete a Staff User',
                    'User Id ' . $id,
                    route('company.hrms.users.index'),
                    'Staff User Deleted successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );
                return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function createDocuments($id)
    {
        $user = User::find($id);
        return view('company.hrms.user.form-documents', compact('user'));
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
            'Staff document uploaded',
            'User Id ' . $user_id,
            route('company.users.index'),
            'Staff document uploaded successfully',
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
            'Staff document deleted',
            '',
            route('company.users.index'),
            'Staff document  Delete successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return redirect()->back();
    }
}
