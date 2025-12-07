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

    public function gateway(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => ['message' => 'No file uploaded']], 422);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file = $request->file('file');
        $publicDir = public_path('uploads/gateways');

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

        $relative = '/uploads/gateways/' . $filename;
        $url = asset('uploads/gateways/' . $filename);

        return response()->json(['url' => $url, 'path' => $relative, 'uploaded' => true], 201);
    }

    public function deleteGateway(Request $request)
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);
        $path = $request->string('path')->toString();

        if (!str_starts_with($path, '/uploads/gateways/')) {
            return response()->json(['deleted' => false, 'message' => 'Invalid path'], 422);
        }

        $full = public_path(ltrim($path, '/'));
        if (file_exists($full)) {
            @unlink($full);
            return response()->json(['deleted' => true]);
        }
        return response()->json(['deleted' => false, 'message' => 'File not found'], 404);
    }

    public function logo(Request $request)
    {
        if (!$request->hasFile('logo')) {
            return response()->json(['error' => ['message' => 'No file uploaded']], 422);
        }

        $file = $request->file('logo');
        $extension = strtolower($file->getClientOriginalExtension());

        // Accept jpg, jpeg, png, webp files
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            return response()->json(['error' => ['message' => 'Invalid file type. Only jpg, jpeg, png and webp files are allowed.']], 422);
        }

        // Check file size (max 5MB)
        if ($file->getSize() > 5120 * 1024) {
            return response()->json(['error' => ['message' => 'File size exceeds 5MB limit.']], 422);
        }

        // Save under public/uploads/logos
        $publicDir = public_path('uploads/logos');
        if (!is_dir($publicDir)) {
            if (!mkdir($publicDir, 0775, true) && !is_dir($publicDir)) {
                return response()->json(['error' => ['message' => 'Failed to create upload directory']], 500);
            }
        }

        $filename = 'logo.' . $extension;
        try {
            $file->move($publicDir, $filename);
        } catch (\Throwable $e) {
            return response()->json(['error' => ['message' => 'Failed to store file', 'detail' => $e->getMessage()]], 500);
        }

        $relative = '/uploads/logos/' . $filename;
        $url = asset('uploads/logos/' . $filename);

        return response()->json(['url' => $url, 'path' => $relative, 'uploaded' => true], 201);
    }

    public function favicon(Request $request)
    {
        if (!$request->hasFile('favicon')) {
            return response()->json(['error' => ['message' => 'No file uploaded']], 422);
        }

        $file = $request->file('favicon');
        $extension = strtolower($file->getClientOriginalExtension());

        // Accept ico and png files
        if (!in_array($extension, ['ico', 'png'])) {
            return response()->json(['error' => ['message' => 'Invalid file type. Only .ico and .png files are allowed.']], 422);
        }

        // Save under public/uploads/favicons
        $publicDir = public_path('uploads/favicons');
        if (!is_dir($publicDir)) {
            if (!mkdir($publicDir, 0775, true) && !is_dir($publicDir)) {
                return response()->json(['error' => ['message' => 'Failed to create upload directory']], 500);
            }
        }

        $filename = 'favicon.' . $extension;
        try {
            $file->move($publicDir, $filename);
        } catch (\Throwable $e) {
            return response()->json(['error' => ['message' => 'Failed to store file', 'detail' => $e->getMessage()]], 500);
        }

        $relative = '/uploads/favicons/' . $filename;
        $url = asset('uploads/favicons/' . $filename);

        return response()->json(['url' => $url, 'path' => $relative, 'uploaded' => true], 201);
    }

    public function ogImage(Request $request)
    {
        if (!$request->hasFile('og_image')) {
            return response()->json(['error' => ['message' => 'No file uploaded']], 422);
        }

        $file = $request->file('og_image');
        $extension = strtolower($file->getClientOriginalExtension());

        // Accept jpg, jpeg, png, webp files
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            return response()->json(['error' => ['message' => 'Invalid file type. Only jpg, jpeg, png and webp files are allowed.']], 422);
        }

        // Check file size (max 5MB)
        if ($file->getSize() > 5120 * 1024) {
            return response()->json(['error' => ['message' => 'File size exceeds 5MB limit.']], 422);
        }

        // Save under public/uploads/og-images
        $publicDir = public_path('uploads/og-images');
        if (!is_dir($publicDir)) {
            if (!mkdir($publicDir, 0775, true) && !is_dir($publicDir)) {
                return response()->json(['error' => ['message' => 'Failed to create upload directory']], 500);
            }
        }

        $filename = 'og-image.' . $extension;
        try {
            $file->move($publicDir, $filename);
        } catch (\Throwable $e) {
            return response()->json(['error' => ['message' => 'Failed to store file', 'detail' => $e->getMessage()]], 500);
        }

        $relative = '/uploads/og-images/' . $filename;
        $url = asset('uploads/og-images/' . $filename);

        return response()->json(['url' => $url, 'path' => $relative, 'uploaded' => true], 201);
    }
}
