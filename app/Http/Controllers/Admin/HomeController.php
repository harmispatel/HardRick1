<?php

namespace App\Http\Controllers\Admin;
use App;
use Lang;
use DB;
use Hash;
use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    public function index()
    {
        return view('home');
    }
    
    public function privacy_policy(Request $request)
    { 
      $setting = DB::table('site_settings')->select('*')->first();
	    return view("privacy_policy",['setting'=>$setting]);
    }
    
    public function term_condition(Request $request)
    { 
      $setting = DB::table('site_settings')->select('*')->first();
	    return view("term_condition",['setting'=>$setting]);
    }
    
    public function about_us(Request $request)
    { 
      $setting = DB::table('site_settings')->select('*')->first();
	    return view("about_us",['setting'=>$setting]);
    }
    
    public function contact_us(Request $request)
    { 
      $setting = DB::table('site_settings')->select('*')->first();
	    return view("contact_us",['setting'=>$setting]);
    }
    
    public function contactussave(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');
        
        \Mail::send('contactusmail',
       array(
           'name' => $request->input('name'),
           'email' => $request->input('email'),
           'user_message' => $request->input('message')
       ), function($message) use ($request)
       {
          $message->from($request->input('email'));
          $message->to('harmistest@gmail.com')->subject($request->get('subject'));
       });

        $message = "Thank you for contact with us!";
        return back()->with('message', $message);
    }
}
