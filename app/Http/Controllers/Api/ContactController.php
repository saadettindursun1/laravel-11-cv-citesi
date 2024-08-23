<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(){
        $contacts = Contact::paginate(20);
        return response()->json(["message"=> "Gelen Kutusu", "data"=>$contacts],200);
    }

    public function store(Request $request){

        $validateData = $request->validate([
            "name" => "required",
            "email" => "required|email",
            "phone" => "nullable",
            "subject" => "nullable",
            "body" => "nullable",
            "status" => "required",
        ]);

        $validateData["ip"] = request()->ip();
        $contact = Contact::create($validateData);
        return response()->json(["message"=>"Mesaj Başarıyla Gönderildi", "data"=>$contact],200);
    }

    public function mailSend(Request $request){
            
        $subject = $request->input("subject");
        $message = $request->input("message");
        $email = $request->input("email");

        $contact = Contact::where("email",$email)->first();

        if(empty($contact)){
            return response()->json(["message"=>"E Posta Bulunamadı"],401);

        }
        $contact->message = $message;
        Mail::to($email)->send(new ContactMail($subject,$contact));

        return response()->json(["message"=>"Mail başarıyla gönderildi"],200);

        
    }
}
