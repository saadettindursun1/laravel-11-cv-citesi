<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Career;
use App\Models\Education;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Skills;
use App\Models\Slider;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){

        $slider = Slider::where("status",1)->first();
        $social_medias = SocialMedia::where("status",1)->get();
        $about = About::first();
        $skills = Skills::all();
        $careers = Career::orderBy('end_date',"desc")->get();
        $educations = Education::orderBy('end_date',"desc")->get();
        $projects = Project::where("status",1)->get();
        $site_setting = SiteSetting::where("status",1)->get();
        return view("frontend.index", compact('slider','social_medias','about','skills','careers',"educations","projects","site_setting"));
    }

}
