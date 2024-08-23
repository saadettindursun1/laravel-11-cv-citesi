<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Education;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageServices;

class EducationController extends Controller
{
    public function index(){
        $educations = Education::all();
        return response()->json($educations);
    }

    public function store(Request $request){
        return $this->saveEducation($request);
    }
    public function update(Request $request,$id){
        return $this->saveEducation($request,$id);
    }

    public function delete($id)
    {
        $education = Education::find($id);
    
        if (!$education) {
            return response()->json(["message" => "Okul bulunamadı"], 404);
        }
    
        $education->delete();
    
        return response()->json(["message" => "Okul başarıyla silindi"]);
    }
    
    public function saveEducation($request,$id=null ){
        $validatedData = $request->validate([
            "education_title"=>"required|string",
            "start_date"=>"nullable|date",
            "end_date"=>"nullable|string",
            "description"=>"nullable|string",
        ],
        [
            "education_title.required"=>"Başlık boş geçilemez",
        ] 
    );

    $startDate = Carbon::parse($validatedData["start_date"])->format("Y-m-d");
    $endDate =isset($validatedData["end_date"]) ?  Carbon::parse($validatedData["end_date"])->format("Y-m-d") : null;

    $educationData = [
        "education_title"=>$validatedData["education_title"],
        "start_date" => $startDate,
        "end_date" => $endDate,
        "description"=>$validatedData["description"],
"status" => $request->status ?? (!empty($endDate) ? 0 : 1)
    ];

    $education = isset($id) ? Education::find($id) : Education::create($educationData);

    if(empty($education)){
        return response()->json(["message"=>"Okul bulunamadı.."],404);
    }

    if($request->hasFile("file")){
        $uploadedImages = $this->saveImageUpload($request,$education);
        $education->image = $uploadedImages[0]["path"];
    }

     $education->update($educationData);

    return response()->json(["message"=> isset($id)? "Okul başarıyla güncellendi." : "Okul başarıyla eklendi", "data"=>$education]);

 
    }
    private function saveImageUpload($request,$data){
        $images = $request->file("file");
        $uploadImageService = new UploadImageServices();
        $uploadImageService->createFolder(("uploads/okul"));
        if(!empty($data->image)){
            $uploadImageService->deleteFile($data->image);
        }
        $uploadedImages = $uploadImageService->uploadMultipleImages($images,"okul");
        return $uploadedImages;
    }
}
