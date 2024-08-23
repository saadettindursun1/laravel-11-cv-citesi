<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageServices;

class SliderController extends Controller
{
    public function index() {
        $sliders = Slider::orderBy("id","desc")->paginate(20);
        return response()->json($sliders);
    }

    public function edit($id) {
        $slider = Slider::where("id",$id)->first();
        return response()->json($slider);
    }



    public function store(Request $request){
        return $this->saveslider($request);
    }
    public function update(Request $request,$id){
        return $this->saveslider($request,$id);
    }

    public function saveslider(Request $request, $id=null){
        $validatedData = $request->validate([
            "title"=>"required|string",
        ],
        [
            "title.required"=>"Başlık boş geçilemez",
        ] );

        $sliderData = [
            "title"=>    $validatedData["title"],
             "content" =>$request->content,
             "link" =>$request->link,
             "video_link" =>$request->video_link,
             "status"=>$request->status ?? 1
        ];

        $slider = !empty($id) ? Slider::find($id) : Slider::create($sliderData);

        if(empty($slider)){
            return response()->json(["message"=>"Slider bulunamadı"],404);
        }

        if($request->hasFile("file")){
            $uploadedImages = $this->saveImageUpload($request,$slider);
            $slider->image = $uploadedImages[0]["path"];
        }

        $slider->update($sliderData);
        return response()->json(["message"=>!empty($id) ? "Başarıyla güncellendi" : "Slider oluşturuldu","data"=>$slider],200);
 
    }

    private function saveImageUpload($request,$data){
        $images = $request->file("file");
        $uploadImageService = new UploadImageServices();
        $uploadImageService->createFolder(("uploads/slider"));
        if(!empty($data->image)){
            $uploadImageService->deleteFile($data->image);
        }
        $uploadedImages = $uploadImageService->uploadMultipleImages($images,"slider");
        return $uploadedImages;
    }
}
