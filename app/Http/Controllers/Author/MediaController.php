<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('author.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('author/' . Auth::id(), 'public');

            Media::create([
                'user_id' => Auth::id(),
                'filename' => basename($path),
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $path
            ]);
        }

        return redirect()->route('author.media.index')
            ->with('success', 'Media uploaded successfully.');
    }

    public function selector()
    {
        $media = Media::where('user_id', Auth::id())
            ->where('mime_type', 'like', 'image%')
            ->latest()
            ->get();

        return view('author.media.selector', compact('media'));
    }
}
