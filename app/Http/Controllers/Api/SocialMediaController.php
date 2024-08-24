<?php

namespace App\Http\Controllers\Api;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadImageServices;

class SocialMediaController extends Controller
{
    public function index() {
        $social_medias = SocialMedia::orderBy("order_no")->paginate(20);
        return response()->json($social_medias);
    }

 
    public function store(Request $request){
        return $this->saveSocialMedia($request);
    }
    public function update(Request $request,$id){
        return $this->saveSocialMedia($request,$id);
    }
    public function delete($id)
    {
        $education = SocialMedia::find($id);
    
        if (!$education) {
            return response()->json(["message" => "Sosyal medya bulunamadı"], 404);
        }
    
        $education->delete();
    
        return response()->json(["message" => "Sosyal medya başarıyla silindi"]);
    }

    public function updateOrderNo(Request $request){

        foreach($request->order as $id => $key)
        {
            SocialMedia::where("id",$id)->update(["order_no"=>$key]);
        }
        return response()->json(["message"=> "Başarıyla güncellendi","data"=>SocialMedia::get()],200);

    }

    public function saveSocialMedia(Request $request, $id=null){
        $validatedData = $request->validate([
            "title"=>"required|string",
        ],
        [
            "title.required"=>"Başlık boş geçilemez",
        ] );

        $social_mediaData = [
            "title"=>    $validatedData["title"],
             "link" =>$request->link,
             "icon" =>$request->icon,
             "status"=>$request->status ?? 1
        ];

        $social_media = !empty($id) ? SocialMedia::find($id) : SocialMedia::create($social_mediaData);

        if(empty($social_media)){
            return response()->json(["message"=>"Slider bulunamadı"],404);
        }

      

        $social_media->update($social_mediaData);
        return response()->json(["message"=>!empty($id) ? "Başarıyla güncellendi" : "Sosyal medya oluşturuldu","data"=>$social_media],200);
 
    }

 
}
