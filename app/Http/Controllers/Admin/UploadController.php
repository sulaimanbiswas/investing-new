<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function ckeditor(Request $request)
    {
        // CKEditor 5 SimpleUploadAdapter sends the file under 'upload'
        if (!$request->hasFile('upload')) {
            return response()->json(['error' => ['message' => 'No file uploaded']], 422);
        }

        $request->validate([
            'upload' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('upload');

        // Save under public/uploads/ckeditor
        $publicDir = public_path('uploads/ckeditor');
        if (!is_dir($publicDir)) {
            // recursively create directory with proper permissions
            if (!mkdir($publicDir, 0775, true) && !is_dir($publicDir)) {
                return response()->json(['error' => ['message' => 'Failed to create upload directory']], 500);
            }
        }

        $filename = Str::random(16) . '.' . $file->getClientOriginalExtension();
        $targetPath = $publicDir . DIRECTORY_SEPARATOR . $filename;
        try {
            $file->move($publicDir, $filename);
        } catch (\Throwable $e) {
            return response()->json(['error' => ['message' => 'Failed to store file', 'detail' => $e->getMessage()]], 500);
        }

        // Direct public URL
        $url = asset('uploads/ckeditor/' . $filename);

        return response()->json(['url' => $url, 'uploaded' => true], 201);
    }

    public function qr(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => ['message' => 'No file uploaded']], 422);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file = $request->file('file');

        // Optional folder hint so other modules (e.g. platforms) can reuse
        // this endpoint but store files in a different public subfolder.
        $folder = $request->string('folder')->lower()->value() ?? '';
        $subdir = match ($folder) {
            'platforms' => 'uploads/platforms',
            'products' => 'uploads/products',
            'platform-rules' => 'uploads/platform-rules',
            default => 'uploads/qrs',
        };

        // Save under public/uploads/qrs or public/uploads/platforms
        $publicDir = public_path($subdir);
        if (!is_dir($publicDir)) {
            if (!mkdir($publicDir, 0775, true) && !is_dir($publicDir)) {
                return response()->json(['error' => ['message' => 'Failed to create upload directory']], 500);
            }
        }

        $filename = Str::random(16) . '.' . $file->getClientOriginalExtension();

        try {
            $file->move($publicDir, $filename);
        } catch (\Throwable $e) {
            return response()->json(['error' => ['message' => 'Failed to store file', 'detail' => $e->getMessage()]], 500);
        }

        $relative = '/' . trim($subdir, '/') . '/' . $filename;
        $url = asset(ltrim($relative, '/'));

        return response()->json(['url' => $url, 'path' => $relative, 'uploaded' => true], 201);
    }

    public function deleteQr(Request $request)
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);
        $path = $request->string('path')->toString();

        // Only allow deleting files from known public upload subfolders
        if (
            !str_starts_with($path, '/uploads/qrs/') &&
            !str_starts_with($path, '/uploads/platforms/') &&
            !str_starts_with($path, '/uploads/products/') &&
            !str_starts_with($path, '/uploads/platform-rules/')
        ) {
            return response()->json(['deleted' => false, 'message' => 'Invalid path'], 422);
        }

        $full = public_path($path);
        if (file_exists($full)) {
            @unlink($full);
            return response()->json(['deleted' => true]);
        }
        return response()->json(['deleted' => false, 'message' => 'File not found'], 404);
    }
}
