<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\UploadImageServices;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::orderBy("id","desc")->paginate(20);
        return response()->json($categories);
    }

    public function store(Request $request){
        return $this->saveCategory($request);
    }
    public function update(Request $request,$id){
        return $this->saveCategory($request,$id);
    }

    public function saveCategory(Request $request, $id=null){
        $validatedData = $request->validate([
            "name"=>"required|string",
        ],
        [
            "name.required"=>"Etiket boş geçilemez",
        ] );

        $categoryData = [
            "name"=>    $validatedData["name"],
            "status"=>$request->status ?? 1
        ];

        $category = !empty($id) ? Category::find($id) : Category::create($categoryData);

        if(empty($category)){
            return response()->json(["message"=>"Etiket bulunamadı"],404);
        }

        if($request->hasFile("file")){
            $uploadedImages = $this->saveImageUpload($request,$category);
            $category->image = $uploadedImages[0]["path"];
        }
        $category->slug = null;
        $category->update($categoryData);
        return response()->json(["message"=>!empty($id) ? "Başarıyla güncellendi" : "Etiket oluşturuldu","data"=>$category],200);
 
    }

    private function saveImageUpload($request,$data){
        $images = $request->file("file");
        $uploadImageService = new UploadImageServices();
        $uploadImageService->createFolder(("uploads/category"));
        if(!empty($data->image)){
            $uploadImageService->deleteFile($data->image);
        }
        $uploadedImages = $uploadImageService->uploadMultipleImages($images,"category");
        return $uploadedImages;
    }
}
