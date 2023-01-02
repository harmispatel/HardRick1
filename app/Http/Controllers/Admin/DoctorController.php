<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DoctorRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\MediaTrait;
use Config;
use App\Models\{User, Doctor, DoctorTime, DoctorClinicImages, Specialist, DoctorSpecialist};

class DoctorController extends Controller
{
    use MediaTrait;

    public function index()
    {
       
        $specialists = Specialist::pluck('name','id');
       
        return view('admin.Doctor.Doctor_list',compact('specialists'));
    }
    public function loadDoctorData(Request $request)
     {
         if ($request->ajax()) {
             $user = User::where('role', 1)->get();
             return DataTables::of($user)
             ->addIndexColumn()
             ->addColumn('user_status', function ($row){
                 $status = $row->status;
                //  print_r($row->status);
                $checked = ($status == 1) ? 'checked' : '';
                $html = '';
                if($status == 1){
                    $html .= '<span class="badge text-bg-success">Active</span>';
                }else{
                    $html .='<span class="badge text-bg-danger">Inactive</span>';
                }
                return $html;

                

             })
             ->addColumn('Image', function ($row){
                $default_image = asset("/public/image/default-image.jpeg");
                $images = asset("public/profileImage/$row->profile_pic");
                
                $data = '';
                if ($row->profile_pic) {
                $data .= '<img src="'.$images.'" width="100">';

                }else{

                    $data .= '<img src="'.$default_image.'" width="100">';
                }
                return $data;
                
             })
             ->addColumn('actions', function ($row) {
                 $doctor_id = isset($row->id) ? $row->id : 0;
                 $action_html = '';
                 $action_html .= '<a onclick="openaddmodel(\'Edit-Doctor\','.$doctor_id.',\'edit-Doctor\',\'#newDoctorForm\',\'#doctorModalLabel\',\'#doctorModal\',\'update-Doctor\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                 $action_html .= '<a onclick="deletedata(\'1\','.$doctor_id.',\'delete-Doctor\',\'#DoctorTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                 return $action_html;
             })
             ->addColumn('checkbox', function ($row) {
                 $specialist_id = isset($row->id) ? $row->id : '';

                 return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$specialist_id.'" aria-label="...">';
             })
             ->rawColumns(['checkbox','actions','Image','user_status'])
             ->make(true);
         }

     }
     public function store(Request $request)
     {
         try {
            $id = $request->id;
            
            $input = $request->except('_token','id','specialists','datefrom','dateto','yearofexp','certificates','averagehour','discounts','discount_rule','info','insta_link','facebook_link','price_of_ticket','clinicImage','image','timeid','dayName','startTime','endTime');
            $input['role'] = 1;
            $input['password'] = bcrypt($request->password);
            $input['from_date'] = $request->datefrom;
            $input['to_date'] = $request->dateto;

            if ($request->has('image')) {
                $input['profile_pic'] = $this->saveImage($request,'profileImage');
             }

            $doctor = ($id==0 || $id==null) ? User::create($input)->id : User::find($id)->update($input);
            if(!empty($id) || $id !=null || $id !=0)
            {
                $doctor= $id;
            }
            $specialists = isset($request->specialists) ? $request->specialists : [];
            $oldSpecialists = DoctorSpecialist::where('user_id',$doctor)->whereNotIn('specialist_id', $specialists)->get();
            foreach($oldSpecialists as $oldSpecialist){
                $oldSpecialist->delete();
            }
            if(count($specialists)){
                foreach($specialists as $specialist){
                    $doctorSpecialist = DoctorSpecialist::where('user_id',$doctor)->where('specialist_id',$specialist)->first();
                    if(empty($doctorSpecialist)){
                       $doctorSpecialist = new DoctorSpecialist;
                       $doctorSpecialist->user_id = $doctor;
                       $doctorSpecialist->specialist_id = $specialist;
                       $doctorSpecialist->save();
                    }
                }
            }
                    $availableTime = [];
                    if(isset($request->dayName)  && count($request->dayName))
                    {
                        foreach($request->dayName as $timeCount => $time )
                        {
                            
                            if(isset($request->timeid[$timeCount]) &&$request->timeid[$timeCount] != 0)
                            {
                                if(!in_array($timeCount,$availableTime))
                                {
                                    $availableTime[] = $timeCount;
                                    $timeSlot = DoctorTime::find($request->timeid[$timeCount]);
                                    $timeSlot->user_id = $doctor;
                                    $timeSlot->day_id = isset($request->dayName[$timeCount]) ? $request->dayName[$timeCount] : '';
                                    $timeSlot->start_time = isset($request->startTime[$timeCount]) ? $request->startTime[$timeCount] : '';
                                    $timeSlot->end_time = isset($request->endTime[$timeCount]) ? $request->endTime[$timeCount] : '';
                                    $timeSlot->save();
                                }
                            }
                            else
                            {
                                if(!in_array($timeCount,$availableTime))
                                {
                                    $availableTime[] = $timeCount;
                                    $timeSlot = new DoctorTime;
                                    $timeSlot->user_id = $doctor;
                                    $timeSlot->day_id = isset($request->dayName[$timeCount]) ? $request->dayName[$timeCount] : '';
                                    $timeSlot->start_time = isset($request->startTime[$timeCount]) ? $request->startTime[$timeCount] : '';
                                    $timeSlot->end_time = isset($request->endTime[$timeCount]) ? $request->endTime[$timeCount] : '';
                                    $timeSlot->save();
                                }
                            }
                            
                        }
                    }
            
            $doctorData = $request->only('yearofexp','certificates','discounts','discount_rule','info','insta_link','facebook_link','price_of_ticket','averagehour');
            $doctorData['user_id'] = $doctor;
            
            if ($request->has('clinicImage')) {
                $doctorData['picture_clinic'] = $this->clinicImages($request);
             }

         
            $doctorDeatilId = $request->id ? Doctor::where('user_id',$doctor)->first() : [];
            $doctor_detail = empty($doctorDeatilId) ? Doctor::create($doctorData) : Doctor::where('user_id',$id)->update($doctorData);
            
            $message = $id ? "Doctor Updated Successfully" : "New Doctor Created Successfully";

            return $this->sendResponse(true, $message, $input);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendResponse(false, "500, Internal Server Error!");

        }
     }

     public function edit(Request $request)
     {
        $id = $request->id;
        try{
            $data = User::where('id', $id)->with('hasOneDoctor','hasManyDoctorSpecialist','hasManyDoctorTime')->first();
            $weekName =  Config::get('commonVariable.weekName'); 
               $times =isset($data->hasManyDoctorTime) ? $data->hasManyDoctorTime : [];
               $timeCount = count($times);
            //    dd($timeCount);
               if ($timeCount > 0) {
                    $html = '';
                 foreach($times as $index=>$time){
                    // dd($time->id);
                    $html .= '<div id="time'.$time->id.'">';
                    $html .= '<div class="row">';
                    $html .= '<div class="col-md-6 mb-2">';
                    $html .= '<div class="form-group">';
                    $html .= '<label for="name" class="form-label">Choose Day</label>';
                    $html .= '<select name="dayName[]" class="select2 form-control">';
                    foreach($weekName as $key=>$dayNames){
                        $selected = '';
                        if ($key == $time->day_id) {
                            $selected = 'selected';
                        }
                        $html .= '<option value="'.$key.'" '.$selected.'>'.$dayNames.'</option>';
                    }
                    $html .= '</select>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-md-2 mb-2">';
                    $html .= '<div class="form-group">';
                    $html .= '<label for="name" class="control-label text-right">Start Time </label>';
                    $html .= '<input class="form-control timepicker " type="text" name="startTime['.$time->id.']" value="'.$time->start_time.'" title="Choose Time" onclick="timePickerRefresh()" />';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-md-2 mb-2">';
                    $html .= '<div class="form-group">';
                    $html .= '<label for="name" class="control-label text-right">End Time </label>';
                    $html .= '<input class="form-control timepicker" type="text" name="endTime[]" value="'.$time->end_time.'" title="Choose Time" onclick="timePickerRefresh()" />';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-md-2 mb-2">';
                    $html .= '<div class="form-group">';
                    $html .= '<a href="javascript:void(0)" class="btn btn-danger" style="margin-top: 25px;" onclick="removeTimeUpdate('.$time->id.')">Remove</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                 } 
               }
               
               $data['html'] = $html;
            $specialists = $data->hasManyDoctorSpecialist;
            foreach($specialists as $value){
                $specialist_id[] =  $value['specialist_id'];     
                
            }
            $data['specialist_id'] = isset($specialist_id) ? $specialist_id : [];
            $default_image = asset("/public/image/default-image.jpeg");
            $path =  asset("public/profileImage/".$data->profile_pic);
            $data['image'] = ($data->profile_pic) ? $path : $default_image;
            $clinicpath = asset("public/clinic/".$data->hasOneDoctor['picture_clinic']);
            $data['clinicImage'] = isset($data->hasOneDoctor['picture_clinic']) ? $clinicpath : $default_image;
            $data['averagehour'] = isset($data->hasOneDoctor['averagehour']) ? $data->hasOneDoctor['averagehour'] : [] ;
            $data['certificates'] = isset($data->hasOneDoctor['certificates']) ? $data->hasOneDoctor['certificates'] : [] ;
            $data['price_of_ticket'] = isset($data->hasOneDoctor['price_of_ticket']) ? $data->hasOneDoctor['price_of_ticket'] : [] ;
            $data['discounts'] = isset($data->hasOneDoctor['price_of_ticket']) ? $data->hasOneDoctor['price_of_ticket'] : [] ;
            $data['discount_rule'] = isset($data->hasOneDoctor['discount_rule']) ? $data->hasOneDoctor['discount_rule'] : [] ;
            $data['insta_link'] = isset($data->hasOneDoctor['insta_link']) ? $data->hasOneDoctor['insta_link'] : [] ;
            $data['facebook_link'] = isset($data->hasOneDoctor['facebook_link']) ? $data->hasOneDoctor['facebook_link'] : [] ;
            $data['yearofexp'] = isset($data->hasOneDoctor['yearofexp']) ? $data->hasOneDoctor['yearofexp'] : [] ;
            $data['info'] = isset($data->hasOneDoctor['info']) ? $data->hasOneDoctor['info'] : [] ;
            

            return $this->sendResponse(true, "Hospital has been Retrive SuccessFully", $data);

        } catch (\Throwable $th) {
            dd($th);
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
     }

     public function destroy(Request $request)
     {
         $Type = $request->type;
         try {
             if ($Type == 1) {
                 $doctor_id = $request->id;
                 User::where('id', $doctor_id)->delete();
                 return $this->sendResponse(true, "Doctor has been Deleted SuccessFully", $Type);
             } else {
                 
                 $ids = $request->id;
                 User::whereIn('id',$ids)->delete();
                 return $this->sendResponse(true, "Doctor has been Deleted SuccessFully", $Type);
             }
         } catch (\Throwable $th) {
             return $this->sendResponse(false, "500, Internal Server Error!");
         }
     }

     public function timeDestory(Request $request)
     {   
        try{
            $id = $request->id;
            $timeSlot = DoctorTime::find($id);
            $timeSlot->delete();
            return $this->sendResponse(true, "time has been Deleted SuccessFully", $timeSlot);

        }catch (\Throwable $th) {
             return $this->sendResponse(false, "500, Internal Server Error!");
         }

       
     }
}
