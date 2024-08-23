<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageServices;

class TagController extends Controller
{
    public function index() {
        $tags = Tag::orderBy("id","desc")->paginate(20);
        return response()->json($tags);
    }

    public function edit($id) {
        $tag = Tag::where("id",$id)->first();
        return response()->json($tag);
    }



    public function store(Request $request){
        return $this->saveTag($request);
    }
    public function update(Request $request,$id){
        return $this->saveTag($request,$id);
    }

    public function saveTag(Request $request, $id=null){
        $validatedData = $request->validate([
            "name"=>"required|string",
        ],
        [
            "name.required"=>"Etiket boş geçilemez",
        ] );

        $tagData = [
            "name"=>    $validatedData["name"],
            "status"=>$request->status ?? 1
        ];

        $tag = !empty($id) ? Tag::find($id) : Tag::create($tagData);

        if(empty($tag)){
            return response()->json(["message"=>"Etiket bulunamadı"],404);
        }

        if($request->hasFile("file")){
            $uploadedImages = $this->saveImageUpload($request,$tag);
            $tag->image = $uploadedImages[0]["path"];
        }
        $tag->slug = null;

        $tag->update($tagData);
        return response()->json(["message"=>!empty($id) ? "Başarıyla güncellendi" : "Etiket oluşturuldu","data"=>$tag],200);
 
    }

    private function saveImageUpload($request,$data){
        $images = $request->file("file");
        $uploadImageService = new UploadImageServices();
        $uploadImageService->createFolder(("uploads/tag"));
        if(!empty($data->image)){
            $uploadImageService->deleteFile($data->image);
        }
        $uploadedImages = $uploadImageService->uploadMultipleImages($images,"tag");
        return $uploadedImages;
    }
}
