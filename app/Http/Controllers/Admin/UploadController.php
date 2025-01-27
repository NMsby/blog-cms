<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class UploadController extends AdminController
{
    public function imageUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        $path = $request->file('file')->store('posts/content', 'public');

        return response()->json([
            'location' => asset('storage/' . $path)
        ]);
    }
}
