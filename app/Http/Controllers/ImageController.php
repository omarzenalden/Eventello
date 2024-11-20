<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    public function store(Request $request)
    {
         $request->validate([
            'place_id' => 'required',
            'image_path' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageName = time().'.'.$request->image_path->extension();
        $request->image_path->move(public_path('images'), $imageName);

        Image::create([
            'place_id' => $request->place_id,
            'image_path' => $imageName,
        ]);

        return response()->json(['message' => 'Image uploaded successfully','imageName'=>$imageName],200);
}
}
