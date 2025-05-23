<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    use ActivityLogger;


    public function upload(Request $request)
    {

        $request->validate([
            'permission_file' => 'required|file|mimes:xlsx,csv,txt',
        ]);


        Artisan::call('permission:cache-reset');


        $file = $request->file('permission_file');
        $data = Excel::toArray([], $file)[0]; // Read the first sheet or csv

        // Skip header
        $header = array_map('strtolower', array_map('trim', $data[0]));
        $rows = array_slice($data, 1);

        foreach ($rows as $row) {
            if (count($row) < 8) {
                continue; // skip invalid rows
            }

            $section = trim($row[0]);
            $name    = trim($row[1]);
            if ($section != null && $name != null) {
                // Optional: check if exists already
                $existing = Permission::where('section', $section)->where('name', $name)->first();

                if ($existing) {
                    // Update existing permission
                    $existing->update([
                        'is_admin'    => (int) $row[2] ?? 0,
                        'is_company'  => (int) $row[3] ?? 0,
                        'is_owner'    => (int) $row[4] ?? 0,
                        'is_customer' => (int) $row[5] ?? 0,
                        'is_tenant'   => (int) $row[6] ?? 0,
                        'is_agent'    => (int) $row[7] ?? 0,
                        'updated_at'  => now(),
                    ]);
                } else {
                    // Create new permission
                    Permission::create([
                        'section'     => Str::ucfirst(trim($section)),
                        'name'        => Str::lower(trim($name)),
                        'guard_name'  => 'web',
                        'is_admin'    => (int) $row[2] ?? 0,
                        'is_company'  => (int) $row[3] ?? 0,
                        'is_owner'    => (int) $row[4] ?? 0,
                        'is_customer' => (int) $row[5] ?? 0,
                        'is_tenant'   => (int) $row[6] ?? 0,
                        'is_agent'    => (int) $row[7] ?? 0,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            }
        }

        $this->logActivity(
            'New Permission File Imported',
            'File Imported',
            route('admin.permissions.index'),
            'New Permission File Imported Successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return back()->with('success', 'Permissions uploaded successfully!');
    }

    public function index(Request $request)
    {
        $permissions = Permission::orderBy('section')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.form');
    }


    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $permission->update($request->only([
            'is_admin',
            'is_company',
            'is_owner',
            'is_customer',
            'is_tenant',
            'is_agent',
        ]));

        $this->logActivity(
            'Permission ' . $permission->name . ' Updated',
            $permission->name . ' Updated',
            route('admin.permissions.index'),
            'New Permission ' . $permission->name . ' Updated',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }
}
