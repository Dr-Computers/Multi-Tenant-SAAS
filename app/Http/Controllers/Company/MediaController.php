<?php

namespace App\Http\Controllers\Company;

use App\Models\MediaFolder;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Trash;
use Illuminate\Support\Facades\Auth;
use App\Traits\Media\HandlesMediaFolders;

class MediaController extends Controller
{
    use HandlesMediaFolders;

    public function index()
    {
        $folders = MediaFolder::whereNull('parent_id')->where('company_id', Auth::user()->creatorId())->get();
        $files   = MediaFile::where('folder_id', 0)->where('company_id', Auth::user()->creatorId())->get();

        return view('company.media.index', compact('folders', 'files'));
    }

    public function subFolder($id)
    {

        $folders = MediaFolder::where('parent_id', $id)->where('company_id', Auth::user()->creatorId())->get();
        $files   = MediaFile::where('folder_id', $id)->where('company_id', Auth::user()->creatorId())->get();
        $folder  = MediaFolder::where('id', $id)->where('company_id', Auth::user()->creatorId())->first();

        $breadcrumbs = $this->getBreadcrumb($folder);
        return view('company.media.index', compact('folders', 'files', 'folder', 'breadcrumbs'));
    }

    public function createFolder(Request $request)
    {
        $folder_id = $request->folder_id;
        $folder  = MediaFolder::where('id', $folder_id)->where('company_id', Auth::user()->creatorId())->first();
        return view('company.media.folder-form', compact('folder'));
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'parent_id' => 'nullable|exists:media_folders,id'
        ]);

        $companyId = Auth::user()->creatorId();
        $disk = env('FILESYSTEM_DISK', 'public');
        $basePath = 'uploads/company_' . $companyId;

        $parent_id = $request->parent_id != 0 ? $request->parent_id  : null;

        // Check for duplicate folder under same parent
        $exists = MediaFolder::where('company_id', $companyId)
            ->where('name', $request->name)
            ->where('parent_id', $parent_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A folder with this name already exists.'
            ], 422);
        }

        // Generate folder slug
        $slug = Str::slug($request->name) . '-' . uniqid();

        // Determine full path (recursively if parent exists)
        $path = $basePath;
        if ($parent_id) {
            $parent = MediaFolder::find($parent_id);
            if ($parent) {
                $path = $parent->path; // inherit parent path
            }
        }
        $fullPath = $path . '/' . $slug;
        // Create DB entry
        $folder = MediaFolder::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'slug' => $slug,
            'path' => $fullPath,
            'parent_id' => $parent_id
        ]);

        // Create base company directory if not exists
        if (!Storage::disk($disk)->directoryExists($basePath)) {
            Storage::disk($disk)->makeDirectory($basePath);
        }

        // Create actual folder in storage
        if (!Storage::disk($disk)->directoryExists($fullPath)) {
            Storage::disk($disk)->makeDirectory($fullPath);
        }

        return response()->json(['success' => true, 'folder' => $folder]);
    }

    public function  renameFolder($folder_id)
    {
        $folder  = MediaFolder::where('id', $folder_id)->where('company_id', Auth::user()->creatorId())->first();
        return view('company.media.folder-rename', compact('folder'));
    }

    public function updateFolder(Request $request, $folder_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $companyId = Auth::user()->creatorId();
        $disk = env('FILESYSTEM_DISK', 'public');
    
        $folder = MediaFolder::where('id', $folder_id)
            ->where('company_id', $companyId)
            ->firstOrFail();
    
        // Prevent duplicate name under same parent
        $exists = MediaFolder::where('company_id', $companyId)
            ->where('name', $request->name)
            ->where('parent_id', $folder->parent_id)
            ->where('id', '!=', $folder->id)
            ->exists();
    
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A folder with this name already exists in this location.'
            ], 422);
        }
    
        $oldSlug = $folder->slug;
        $oldPath = $folder->path;
        $newSlug = Str::slug($request->name) . '-' . uniqid();
        $newPath = str_replace($oldSlug, $newSlug, $oldPath);
    
        // Step 1: Move storage folder
        if (Storage::disk($disk)->exists($oldPath)) {
            Storage::disk($disk)->move($oldPath, $newPath);
        }
    
        // Step 2: Update current folder
        $folder->name = $request->name;
        $folder->slug = $newSlug;
        $folder->path = $newPath;
        $folder->save();
    
        // Step 3: Recursively update child folders and files
        $this->updateChildPaths($folder, $oldPath, $newPath);
    
        return response()->json(['success' => true, 'folder' => $folder]);
    }
    


    public function getBreadcrumb($folder)
    {
        $breadcrumbs = [];

        while ($folder) {
            $breadcrumbs[] = [
                'id' => $folder->id,
                'name' => $folder->name,
            ];
            $folder = $folder->parent; // assuming you have a relation defined
        }

        return array_reverse($breadcrumbs);
    }

    public function showFileUploadForm($folder, Request $request)
    {
        $folder  = MediaFolder::where('id', $folder)->where('company_id', Auth::user()->creatorId())->first();
        return view('company.media.files-form', compact('folder'));
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'documents.*' => 'required|file|max:51200'
        ]);

        $companyId = auth()->user()->creatorId();
        $requestedFolderId = $request->input('folder_id');

        // Get or create proper folder
        // $folder = $this->resolveOrCreateFolder($companyId, $requestedFolderId);

        // Upload and save files
        $folder = MediaFolder::where('id', $requestedFolderId)->where('company_id', $companyId)->first();

        $files = $request->file('documents', []);
        $uploadedFiles = $this->uploadAndSaveFiles($files, $companyId, $folder->name ?? null);

        return response()->json([
            'success' => true,
            'folder_id' => $request->input('folder_id'),
            'files' => $uploadedFiles
        ]);
    }

    public function deleteFile($id)
    {
        $file = MediaFile::findOrFail($id);

        $this->softDeleteFile($file);

        return response()->json(['success' => true, 'message' => 'File moved to trash.']);
    }

    public function deleteFolder($id)
    {
        $folder = MediaFolder::findOrFail($id);

        $this->softDeleteFolder($folder);

        return response()->json(['success' => true, 'message' => 'Folder moved to trash.']);
    }

    


    protected function updateChildPaths(MediaFolder $parent, $oldParentPath, $newParentPath)
    {
        // Update child folders
        $children = MediaFolder::where('parent_id', $parent->id)->get();
        $disk = env('FILESYSTEM_DISK', 'public');
        foreach ($children as $child) {
            $oldChildPath = $child->path;
            $newChildPath = str_replace($oldParentPath, $newParentPath, $oldChildPath);

            // Rename folder in storage
    
            if (Storage::disk($disk)->exists($oldChildPath)) {
                Storage::disk($disk)->move($oldChildPath, $newChildPath);
            }

            $child->path = $newChildPath;
            $child->save();

            // Recursive call for nested folders
            $this->updateChildPaths($child, $oldChildPath, $newChildPath);
        }

        // Update files in this folder
        $files = MediaFile::where('folder_id', $parent->id)->get();
        foreach ($files as $file) {
            $oldUrl = $file->url;
            $newUrl = str_replace($oldParentPath, $newParentPath, $oldUrl);

            // Rename file in storage
            if (Storage::disk($disk)->exists($oldUrl)) {
                Storage::disk($disk)->move($oldUrl, $newUrl);
            }

            $file->url = $newUrl;
            $file->save();
        }
    }
}
