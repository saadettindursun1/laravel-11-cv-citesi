<?php

namespace App\Http\Controllers\Api;

use App\Models\About;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageServices;

class AboutController extends Controller
{

    public function index()
    {
        return About::firstOrFail();
    }

    public function update(Request $request)
    {
        $request->validate([
            "title" => "required",
        ]);

        $content = About::first();

        if ($request->hasFile("file")) {
            $images = $request->file("file");

            $uploadImageService = new UploadImageServices();

            $uploadImageService->createFolder("uploads");
            $uploadImageService->deleteFile($content->image);

            $uploadImages = $uploadImageService->uploadMultipleImages($images);

            $request->merge(["image" => $uploadImages[0]["path"]]);
        }
        $content->update($request->only("title", "image", "content"));

        return response()->json(["message" => "Başarıyla Güncellendi", "data" => $content], 200);
    }
}
