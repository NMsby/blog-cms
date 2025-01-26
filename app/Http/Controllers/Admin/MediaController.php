<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Media::with('user')->latest();

        // Filter by type if specified
        if ($request->has('type')) {
            $query->where('mime_type', 'like', $request->type . '%');
        }

        $media = $query->paginate(20);

        return view('admin.media.index', compact('media'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max
            'folder' => 'nullable|string',
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $path = $file->store('media/' . ($request->folder ?? ''), 'public');

            // Get file info
            $mime = $file->getMimeType();
            $size = $file->getSize();

            // Create thumbnails for images
            $additional_attributes = [];
            if (str_starts_with($mime, 'image/')) {
                $additional_attributes = $this->processImage($path);
            }

            // Store media record
            $media = Media::create([
                'user_id' => auth()->id(),
                'filename' => basename($path),
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $mime,
                'size' => $size,
                'path' => $path,
                'additional_attributes' => $additional_attributes
            ]);

            $uploadedFiles[] = $media;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Files uploaded successfully',
                'files' => $uploadedFiles
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', count($uploadedFiles) . ' files uploaded successfully.');
    }

    protected function processImage($path)
    {
        $image = Image::read(Storage::disk('public')->path($path));

        // Generate thumbnail
        $thumbnailPath = 'media/thumbnails/' . basename($path);
        $thumbnail = $image->resize(300, 300);
        Storage::disk('public')->put($thumbnailPath, $thumbnail->encode());

        return [
            'dimensions' => [
                'width' => $image->width(),
                'height' => $image->height(),
            ],
            'thumbnail' => $thumbnailPath,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        $media->load('user');
        return view('admin.media.show', compact('media'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        // Delete the actual file
        Storage::disk('public')->delete($media->path);

        // Delete thumbnail if exists
        if (!empty($media->additional_attributes['thumbnail'])) {
            Storage::disk('public')->delete($media->additional_attributes['thumbnail']);
        }

        $media->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'File deleted successfully']);
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'File deleted successfully.');
    }

    public function download(Media $media)
    {
        return Storage::disk('public')->download(
            $media->path,
            $media->original_filename
        );
    }
}
