<?php

namespace App\Traits\Media;

use App\Models\MediaFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Trash;
use App\Models\MediaFile;
use Illuminate\Support\Facades\Storage;

trait HandlesMediaFolders
{



    // public function CreateFolder($companyId, $FolderName = null)
    // {
    //     $storageDisk = config('filesystems.default');
    //     // Step 1: Ensure company root folder exists
    //     $rootFolder = MediaFolder::firstOrCreate([
    //         'company_id' => $companyId,
    //         'parent_id' => null,
    //         'slug' => 'root-' . $companyId,
    //     ], [
    //         'name' => 'Company_' . $companyId,
    //     ]);
    //     if (!Storage::disk($storageDisk)->directoryExists('uploads/company_' . $companyId)) {
    //         Storage::disk($storageDisk)->makeDirectory('uploads/company_' . $companyId);
    //     }

    //     // Step 2: Locate requested folder or create unknown folder
    //     $targetFolder = null;

    //     if ($FolderName) {
    //         $targetFolder = MediaFolder::where('company_id', $companyId)
    //             ->where('parent_id', $rootFolder->id)
    //             ->where('name', $FolderName)
    //             ->first();
    //     }

    //     if (!$targetFolder) {
    //         $randomName = $FolderName . '_' . Str::random(6);
    //         $targetFolder = MediaFolder::create([
    //             'company_id' => $companyId,
    //             'parent_id' => $rootFolder->id,
    //             'name' => $FolderName,
    //             'path'      => 'uploads/company_' . $companyId . '/' . $randomName,
    //             'slug' => $randomName,
    //         ]);

    //         if (!Storage::disk($storageDisk)->directoryExists('uploads/company_' . $companyId . '/' . $randomName)) {
    //             Storage::disk($storageDisk)->makeDirectory('uploads/company_' . $companyId . '/' . $randomName);
    //         }
    //     }

    //     return $targetFolder;
    // }
    public function CreateFolder($companyId, $FolderName = null)
    {
        $disk = env('FILESYSTEM_DISK', 'public');

        // Step 1: Ensure company root folder exists
        $rootFolder = MediaFolder::firstOrCreate([
            'company_id' => $companyId,
            'parent_id'  => null,
            'slug'       => 'Company_' . $companyId,
        ], [
            'name' => 'Company_' . $companyId,
        ]);

        if (!Storage::disk($disk)->directoryExists('uploads/company_' . $companyId)) {
            Storage::disk($disk)->makeDirectory('uploads/company_' . $companyId);
        }

        // Step 2: Find or create target folder
        $targetFolder = null;

        if ($FolderName) {
            $targetFolder = MediaFolder::where('company_id', $companyId)
                ->where('parent_id', $rootFolder->id)
                ->where('name', $FolderName)
                ->first();
        }

        if (!$targetFolder) {
            $randomName = $FolderName . '_' . Str::random(6);
            $targetFolder = MediaFolder::create([
                'company_id' => $companyId,
                'parent_id'  => $rootFolder->id,
                'name'       => $FolderName,
                'path'       => 'uploads/company_' . $companyId . '/' . $randomName,
                'slug'       => $randomName,
            ]);

            if (!Storage::disk($disk)->directoryExists('uploads/company_' . $companyId . '/' . $randomName)) {
                Storage::disk($disk)->makeDirectory('uploads/company_' . $companyId . '/' . $randomName);
            }
        }

        return $targetFolder;
    }

    public function resolveOrCreateFolder($companyId, $requestedFolderId = null)
    {
        $storageDisk = config('filesystems.default');
        // Step 1: Ensure company root folder exists
        $rootFolder = MediaFolder::firstOrCreate([
            'company_id' => $companyId,
            'parent_id' => null,
            'slug' => 'Company_' . $companyId,
        ], [
            'name' => 'Company_' . $companyId,
        ]);

        if (!Storage::disk($storageDisk)->directoryExists('uploads/company_' . $companyId)) {
            Storage::disk($storageDisk)->makeDirectory('uploads/company_' . $companyId);
        }

        // Step 2: Locate requested folder or create unknown folder
        $targetFolder = null;

        if ($requestedFolderId) {
            $targetFolder = MediaFolder::where('company_id', $companyId)
                ->where('parent_id', $rootFolder->id)
                ->where('id', $requestedFolderId)
                ->first();
        }

        if (!$targetFolder) {
            $randomName = 'unknown_' . Str::random(6);
            $targetFolder = MediaFolder::create([
                'company_id' => $companyId,
                'parent_id' => $rootFolder->id,
                'path'      => 'uploads/company_' . $companyId . '/' . $randomName,
                'name' => $randomName,
                'slug' => $randomName,
            ]);
            if (!Storage::disk($storageDisk)->directoryExists('uploads/company_' . $companyId . '/' . $randomName)) {
                Storage::disk($storageDisk)->makeDirectory('uploads/company_' . $companyId . '/' . $randomName);
            }
        }

        return $targetFolder;
    }
    public function softDeleteFile(MediaFile $file)
    {
        $storageDisk = config('filesystems.default');

        // Move to trash (DB record only)
        Trash::create([
            'company_id' => $file->company_id,
            'type' => 'media_file',
            'deleted_id' => $file->id,
            'name' => $file->name,
            'data' => $file->toArray()
        ]);

        // Optionally move file to trash folder in storage
        if ($file->folder && Storage::disk($storageDisk)->exists($file->folder->path . '/' . $file->url)) {
            Storage::disk($storageDisk)->move($file->folder->path . '/' . $file->url, 'trash/' . $file->url);
        }

        $file->delete();
    }

    protected function softDeleteFolder(MediaFolder $folder)
    {

        $storageDisk  = env('FILESYSTEM_DISK', 'public');

        // Step 1: Get all direct child folders
        $childFolders = MediaFolder::where('parent_id', $folder->id)->get();

        // Step 2: Recursively delete each child
        foreach ($childFolders ?? [] as $child) {
            $this->softDeleteFolder($child);
        }

        // Step 3: Soft delete all files in current folder
        foreach ($folder->files ?? [] as $file) {
            $this->softDeleteFile($file);
        }

        // Step 4: Add current folder to trash
        Trash::create([
            'company_id' => $folder->company_id,
            'type' => 'media_folder',
            'deleted_id' => $folder->id,
            'name' => $folder->name,
            'data' => $folder->toArray()
        ]);

        // Step 5: Move folder to trash in storage (optional)$folder->path && 
        if (Storage::disk($storageDisk)->exists($folder->path)) {
            Storage::disk($storageDisk)->move($folder->path, 'uploads/company_' . $folder->company_id . '/' . 'trash/' . $folder->path);
        }

        // Step 6: Delete folder from DB
        $folder->delete();
    }

    public function uploadAndSaveFiles($files, $companyId, $folder_name = null)
    {


        $folder = MediaFolder::where('name', $folder_name)->where('company_id', $companyId)->first();

        $disk = env('FILESYSTEM_DISK', 'public');
        $base_path = 'uploads/company_' . $companyId;


        // Make sure base path exists
        if (!Storage::disk($disk)->directoryExists($base_path)) {
            Storage::disk($disk)->makeDirectory($base_path);
        }

        $uploadedFiles = [];
        $storageDisk = config('filesystems.default'); // 'local', 's3', etc.

        foreach ($files as $file) {
            if ($folder) {
                $filePath = $folder->path;
            } else {

                if ($folder_name == null) {
                    $filePath = $base_path;
                } else {
                    $slug = Str::slug($folder_name) . '-' . uniqid();
                    $filePath = $base_path . '/' . $slug;
                    if (!Storage::disk($disk)->directoryExists($filePath)) {
                        Storage::disk($disk)->makeDirectory($filePath);
                    }
                }
            }

            // Store the file
            $storedPath = $file->store($filePath, $storageDisk);

            $mediaFile = MediaFile::create([
                'company_id' => $companyId,
                'folder_id' => $folder ? $folder->id : 0,
                'name' => $file->getClientOriginalName(),
                'alt' => '',
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'url' => str_replace($filePath . '/', '', $storedPath),
                'options' => json_encode([])
            ]);

            $uploadedFiles[] = $mediaFile;
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles
        ]);
    }


    // public function uploadAndSaveFile($file, $companyId, $folder_name)
    // {

    //     $folder = MediaFolder::where('name', $folder_name)->where('company_id', $companyId)->first();


    //     $disk = env('FILESYSTEM_DISK', 'public');
    //     $base_path = 'uploads/company_' . $companyId;

    //     // Make sure base path exists
    //     if (!Storage::disk($disk)->directoryExists($base_path)) {
    //         Storage::disk($disk)->makeDirectory($base_path);
    //     }

    //     $storageDisk = config('filesystems.default'); // 'local', 's3', etc.


    //     if ($folder) {
    //         $filePath = $folder->path;
    //     } else {
    //         $slug = Str::slug($folder_name) . '-' . uniqid();
    //         $filePath = $base_path . '/' . $slug;
    //         $folder = MediaFolder::create([
    //             'company_id' => $companyId,
    //             'name' => $folder_name,
    //             'slug' => $slug,
    //             'path' => $filePath,
    //             'parent_id' => NULL
    //         ]);
    //         if (!Storage::disk($disk)->directoryExists($filePath)) {
    //             Storage::disk($disk)->makeDirectory($filePath);
    //         }
    //     }

    //     // Store the file
    //     $storedPath = $file->store($filePath, $disk);

    //     $mediaFile = MediaFile::create([
    //         'company_id' => $companyId,
    //         'folder_id' => $folder ? $folder->id : 0,
    //         'name' => $file->getClientOriginalName(),
    //         'alt' => '',
    //         'mime_type' => $file->getMimeType(),
    //         'size' => $file->getSize(),
    //         'url' => str_replace($filePath . '/', '', $storedPath),
    //         'options' => json_encode([])
    //     ]);

    //     return  $mediaFile->id;
    // }

    public function uploadAndSaveFile($file, $companyId, $folder_name)
    {
        $disk = env('FILESYSTEM_DISK', 'public');
        $base_path = 'uploads/company_' . $companyId;

        // Ensure base path exists
        if (!Storage::disk($disk)->directoryExists($base_path)) {
            Storage::disk($disk)->makeDirectory($base_path);
        }

        // Find or create folder
        $folder = MediaFolder::where('name', $folder_name)->where('company_id', $companyId)->first();

        if ($folder) {
            $filePath = $folder->path;
        } else {
            $slug = Str::slug($folder_name) . '-' . uniqid();
            $filePath = $base_path . '/' . $slug;

            $folder = MediaFolder::create([
                'company_id' => $companyId,
                'name'       => $folder_name,
                'slug'       => $slug,
                'path'       => $filePath,
                'parent_id'  => NULL
            ]);

            if (!Storage::disk($disk)->directoryExists($filePath)) {
                Storage::disk($disk)->makeDirectory($filePath);
            }
        }

        // Store the file
        $storedPath = $file->store($filePath, $disk);

        $mediaFile = MediaFile::create([
            'company_id' => $companyId,
            'folder_id'  => $folder ? $folder->id : 0,
            'name'       => $file->getClientOriginalName(),
            'alt'        => '',
            'mime_type'  => $file->getMimeType(),
            'size'       => $file->getSize(),
            'url'        => str_replace($filePath . '/', '', $storedPath),
            'options'    => json_encode([])
        ]);

        return $mediaFile->id;
    }
}
