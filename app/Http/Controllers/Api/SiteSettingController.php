<?php

namespace App\Http\Controllers\Api;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;

class SiteSettingController extends Controller
{
    public function index() {
        $site_settings = SiteSetting::all();
        return response()->json($site_settings);
    }

 
    public function store(Request $request){
        return $this->saveSiteSetting($request);
    }
    public function update(Request $request,$id){
        return $this->saveSiteSetting($request,$id);
    }
    public function delete($id)
    {
        $education = SiteSetting::find($id);
    
        if (!$education) {
            return response()->json(["message" => "Site Ayarı bulunamadı"], 404);
        }
    
        $education->delete();
    
        return response()->json(["message" => "Site Ayarı başarıyla silindi"]);
    }

  

    public function saveSiteSetting(Request $request, $id=null){
        $validatedData = $request->validate([
            "setting_key"=>"required|string",
        ],
        [
            "setting_key.required"=>"Ayar başlığı boş geçilemez",
        ] );

        $site_settingData = [
            "setting_key"=>    $validatedData["setting_key"],
             "setting_value" =>$request->setting_value,
             "description" =>$request->description,
             "status"=>$request->status ?? 1
        ];

        $site_setting = !empty($id) ? SiteSetting::find($id) : SiteSetting::create($site_settingData);

        if(empty($site_setting)){
            return response()->json(["message"=>"Ayar bulunamadı"],404);
        }

      

        $site_setting->update($site_settingData);
        return response()->json(["message"=>!empty($id) ? "Başarıyla güncellendi" : "Site Ayarı oluşturuldu","data"=>$site_setting],200);
 
    }

 
}
