<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){

        $slider = Slider::where("status",1)->first();
        $social_medias = SocialMedia::where("status",1)->get();
        return view("frontend.index", compact('slider','social_medias'));
    }

}
