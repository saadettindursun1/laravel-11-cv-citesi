<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Services\UploadImageServices;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    public function index() {
        $blogs = Blog::with("category")->orderBy("id","desc")->paginate(20);
        return response()->json($blogs);
    }
    public function edit($id) {
        $blogs = Blog::where("id",$id)->with("category")->first();
        return response()->json($blogs);
    }
    public function store(BlogRequest $request){
        return $this->saveBlog($request);
    }
    public function update(BlogRequest $request,$id){
        return $this->saveBlog($request,$id);
    }


    public function saveBlog(BlogRequest $request, $id = null)
    {
        try {
            
            $blogData = [
                "name" => $request->name,
                "content" => $request->content,
                "category_id" => $request->category_id,
                "status" => $request->status ?? 1
            ];
    
            $blog = $id ? Blog::find($id) : new Blog;
    
            if (empty($blog)) {
                return response()->json(["message" => "Blog bulunamadı"], 404);
            }
    
            $blog->fill($blogData);
    
            if ($request->hasFile("file")) {
                $uploadedImages = $this->saveImageUpload($request, $blog);
                $blog->image = $uploadedImages[0]["path"];
            }
    
            $blog->slug = null;
            $blog->save();
    
            return response()->json(["message" => $id ? "Başarıyla güncellendi" : "Blog oluşturuldu", "data" => $blog], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    

    private function saveImageUpload($request,$data){
        $images = $request->file("file");
        $uploadImageService = new UploadImageServices();
        $uploadImageService->createFolder(("uploads/blog"));
        if(!empty($data->image)){
            $uploadImageService->deleteFile($data->image);
        }
        $uploadedImages = $uploadImageService->uploadMultipleImages($images,"blog");
        return $uploadedImages;
    }
}
