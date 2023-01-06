<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\MediaTrait;
use App\Models\{Patient,Doctor,BloodType,Chronicdiseases,DragsAllergy,FoodAllergy,Specialist,Subscription,Appointment,User};
use Validator;

class DashboardController extends Controller
{
   
    use MediaTrait;
    // Dashboard View
    public function index()
    {
        $patient = Patient::count();
        $doctor = Doctor::count();
        $bloodType = BloodType::count();
        $chronicDiseases = Chronicdiseases::count();
        $dragsallergy = DragsAllergy::count();
        $foodallergy = FoodAllergy::count();
        $specialist = Specialist::count();
        $subcribe = Subscription::count();
        $appointment = Appointment::count();
        return view('admin.dashboard.dashboard',compact('patient','doctor','bloodType','chronicDiseases','dragsallergy','foodallergy','specialist','subcribe','appointment'));
    }

    // Admin Logout
    public function adminLogout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('adminlogin');
    }

    public function profile()
    {
        try {

            $id = Auth::user()->id;
            if($id != "") 
            {
                $data = User::where('id',$id)->first();
                $default_image = asset("/public/image/default-image.jpeg");
                $path =  asset("public/profileImage/".$data->profile_pic);
                $data['image'] = ($data->profile_pic) ? $path : $default_image;
                return $this->sendResponse(true,'successfully loaded data',$data);
            }    
        } catch (Exception $e) {
            
        }
    }
    
    public function updateProfile(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email'=>'required',   
        ]);
        $id =  $request->user_id;
       
        $data=User::find($id);
        $input = $request->except('_token', 'user_id','image');
        if ($request->has('image')) {
            $input['profile_pic'] = $this->saveImage($request,'profileImage');
            $this->old_file_remove($data->profile_pic,'profileImage');
        }
        if ($id == 0) {
            User::insert($input);
        } else {
            User::find($id)->update($input);
        }
        $input['type']='reload';
        return $this->sendResponse(true,'Profile Updated Successfully"',$input);
    }
}
