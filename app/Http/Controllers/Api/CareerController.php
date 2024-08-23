<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Services\UploadImageServices;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(){
        $careers = Career::all();
        return response()->json($careers);
    }

    public function store(Request $request){
        return $this->saveCareer($request);
    }
    public function update(Request $request,$id){
        return $this->saveCareer($request,$id);
    }

    public function saveCareer($request,$id=null ){
        $validatedData = $request->validate([
            "title"=>"required|string",
            "company"=>"required|string",
            "start_date"=>"required|date",
            "end_date"=>"nullable|string",
            "description"=>"nullable|string",
        ],
        [
            "title.required"=>"Başlık boş geçilemez",
            "company.required"=>"Şirket adı boş geçilemez",
            "start_date.required"=>"Başlangıç tarihi boş geçilemez",
        ] 
    );

    $startDate = Carbon::parse($validatedData["start_date"])->format("Y-m-d");
    $endDate =isset($validatedData["end_date"]) ?  Carbon::parse($validatedData["end_date"])->format("Y-m-d") : null;

    $careerData = [
        "title"=>$validatedData["title"],
        "company"=>$validatedData["company"],
        "start_date" => $startDate,
        "end_date" => $endDate,
        "description"=>$validatedData["description"],
        "status"=>$request->status ?? !empty($endDate) ? 0:1
    ];

    $career = isset($id) ? Career::find($id) : Career::create($careerData);

    if(empty($career)){
        return response()->json(["message"=>"Kariyer bulunamadı.."],404);
    }

    if($request->hasFile("file")){
        $uploadedImages = $this->saveImageUpload($request,$career);
        $career->image = $uploadedImages[0]["path"];
    }

     $career->update($careerData);

    return response()->json(["message"=> isset($id)? "Kariyer başarıyla güncellendi." : "Kariyer başarıyla eklendi", "data"=>$career]);

 
    }
    private function saveImageUpload($request,$data){
        $images = $request->file("file");
        $uploadImageService = new UploadImageServices();
        $uploadImageService->createFolder(("uploads/kariyer"));
        if(!empty($data->image)){
            $uploadImageService->deleteFile($data->image);
        }
        $uploadedImages = $uploadImageService->uploadMultipleImages($images,"kariyer");
        return $uploadedImages;
    }
}
