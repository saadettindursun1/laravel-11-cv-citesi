<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageServices;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::with("category")->orderBy("id","desc")->paginate(20);
        return response()->json($projects);
    }
    public function edit($id) {
        $projects = Project::where("id",$id)->with("category")->first();
        return response()->json($projects);
    }
    public function store(Request $request){
        return $this->saveBlog($request);
    }
    public function update(Request $request,$id){
        return $this->saveBlog($request,$id);
    }


    public function saveBlog(Request $request, $id = null)
    {

        try {
            
            $projectData = [
                "name" => $request->name,
                "content" => $request->content,
                "category_id" => $request->category_id,
                "finish_time" => Carbon::parse( $request->finish_time)->format("Y-m-d"),
                "link" => $request->link ?? "#",
                "tags" => $request->tags,
                "status" => $request->status ?? 1
            ];
    
            $project = $id ? Project::find($id) : new Project();
    
            if (empty($project)) {
                return response()->json(["message" => "Proje bulunamadı"], 404);
            }
    
            $project->fill($projectData);
    
            if ($request->hasFile("file")) {
                $uploadedImages = $this->saveImageUpload($request, $project);
                $project->image = $uploadedImages[0]["path"];
            }
    
            $project->slug = null;
            $project->save();
    
            return response()->json(["message" => $id ? "Başarıyla güncellendi" : "Proje oluşturuldu", "data" => $project], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    

    private function saveImageUpload($request,$data){
        $images = $request->file("file");
        $uploadImageService = new UploadImageServices();
        $uploadImageService->createFolder(("uploads/project"));
        if(!empty($data->image)){
            $uploadImageService->deleteFile($data->image);
        }
        $uploadedImages = $uploadImageService->uploadMultipleImages($images,"project");
        return $uploadedImages;
    }
}
