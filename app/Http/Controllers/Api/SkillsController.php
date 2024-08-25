<?php

namespace App\Http\Controllers\Api;

use App\Models\Skills;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;

class SkillsController extends Controller
{
    public function index() {
        $skills = Skills::all();
        return response()->json($skills);
    }

 
    public function store(Request $request){
        return $this->saveSiteSetting($request);
    }
    public function update(Request $request,$id){
        return $this->saveSiteSetting($request,$id);
    }
    public function delete($id)
    {
        $skill = Skills::find($id);
    
        if (!$skill) {
            return response()->json(["message" => "Yetenek bulunamadı"], 404);
        }
    
        $skill->delete();
    
        return response()->json(["message" => "Yetenek başarıyla silindi"]);
    }

  

    public function saveSiteSetting(Request $request, $id=null){
        $validatedData = $request->validate([
            "title"=>"required|string",
        ],
        [
            "title.required"=>"Yetenek başlığı boş geçilemez",
        ] );

        $skillData = [
            "title"=>    $validatedData["title"],
             "level" =>$request->level,
             "status"=>$request->status ?? 1
        ];

        $skill = !empty($id) ? Skills::find($id) : Skills::create($skillData);

        if(empty($skill)){
            return response()->json(["message"=>"Yetenek bulunamadı"],404);
        }

      

        $skill->update($skillData);
        return response()->json(["message"=>!empty($id) ? "Başarıyla güncellendi" : "Yetenek oluşturuldu","data"=>$skill],200);
 
    }

 
}
