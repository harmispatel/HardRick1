<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PatientRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\MediaTrait;
use App\Models\{User, DragsAllergy, Chronicdiseases, FoodAllergy, BloodType, Patient, UserAllergy};


class PatientController extends Controller
{
    use MediaTrait;

    //
    public function index()
    {
        $DragsAllergy =  DragsAllergy::get();
        $Chronicdiseases = Chronicdiseases::get();
        $FoodAllergy = FoodAllergy::get();
        $BloodType = BloodType::get();
        return view('admin.Patient.Patient_list', compact('DragsAllergy','Chronicdiseases','FoodAllergy', 'BloodType'));
    }

     // Load Specialist Data.
     public function loadPatientData(Request $request)
     {
         if ($request->ajax()) {
             $user = User::where('role', 2)->get();
            //  dd($user->toArray());
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
                $images = asset("public/patient/$row->profile_pic");
                
                $data = '';
                if ($row->profile_pic) {
                $data .= '<img src="'.$images.'" width="100">';

                }else{

                    $data .= '<img src="'.$default_image.'" width="100">';
                }
                return $data;
                
             })
             ->addColumn('actions', function ($row) {
                 $patient_id = isset($row->id) ? $row->id : 0;
                 $action_html = '';
                 $action_html .= '<a onclick="openaddmodel(\'Edit-Patient\','.$patient_id.',\'edit-Patient\',\'#newPatientForm\',\'#patientModalLabel\',\'#patientModal\',\'update-Patient\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                 $action_html .= '<a onclick="deletedata(\'1\','.$patient_id.',\'delete-Patient\',\'#PatientTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
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

     public function store(PatientRequest $request)
     {
        try {
            $id = $request->id;
            $input = $request->except('_token','id','dragsAllergy','ChronicDiseases','FoodAllergy','blood_type_id','image','birth_date');
            $input['role'] = 2;
            $input['password'] = bcrypt($request->password);

            

            if ($request->has('image')) {
                $input['profile_pic'] = $this->saveImage($request,'patient');
             }

                $patient = ($id==0 || $id==null ) ? User::create($input)->id :  User::find($id)->update($input);
                if(!empty($id) || $id !=null || $id !=0)
                {
                    $patient= $id;
                }
        
                $data = $request->only('birth_date','blood_type_id');
                $data['user_id'] = $patient;
                $patientDetail = $request->id ? Patient::where('user_id',$patient)->first() : [];
                $patient_detail = empty($patientDetail) ? Patient::create($data) : Patient::where('user_id',$id)->update($data);
                
                     $foods = isset($request->FoodAllergy) ? $request->FoodAllergy : [];
                     $this->insertMultipleAllergy($patient ,$type=1 , $foods);
                     
                     //chronic Diseases allergy insert
                     $chronicDiseases = isset($request->ChronicDiseases) ? $request->ChronicDiseases : [];
                     $this->insertMultipleAllergy($patient ,$type=3 , $chronicDiseases);
                     
                     //drags allergy insert
                     $dragsAllergy = isset($request->dragsAllergy) ? $request->dragsAllergy : [];
                     $this->insertMultipleAllergy($patient ,$type=2 , $dragsAllergy);


            $message = $id ? "Patient Updated Successfully" : "New Patient Created Successfully";

            return $this->sendResponse(true, $message, $input);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th); 
            return $this->sendResponse(false, "500, Internal Server Error!");

        }

     }

     public function edit(Request $request)
     {
         $id = $request->id;
         
         try {
             $data = User::where('id', $id)->with('hasOnePatient','hasManyallergy')->first();

             $Alldatas = $data->hasManyallergy;
             foreach($Alldatas as $value){
                 if($value['allergy_type'] == 1){
                    $Foodallergy_id[] =  $value['allergy_id'];     
                 }else if($value['allergy_type'] == 2){
                    $dragsallergy_id[] = $value['allergy_id'];
                 }else{
                    $chronicdiseases_id[] = $value['allergy_id'];

                 }  
                }
            $data['Foodallergy_id'] = isset($Foodallergy_id) ? $Foodallergy_id : [] ;
            $data['dragsallergy_id'] = isset($dragsallergy_id) ? $dragsallergy_id : [] ;
            $data['chronicdiseases_id'] = isset($chronicdiseases_id) ? $chronicdiseases_id : [];
            $data['bloodType']  = isset($data->hasOnePatient['blood_type_id']) ? $data->hasOnePatient['blood_type_id'] : '';
            $data['birth_date'] = isset($data->hasOnePatient['birth_date']) ? $data->hasOnePatient['birth_date'] : '';
             $default_image = asset("/public/image/default-image.jpeg");
             $path =  asset("public/patient/$data->profile_pic");
             $data['image'] = ($data->profile_pic) ? $path : $default_image;

             return $this->sendResponse(true, "Hospital has been Retrive SuccessFully", $data);
         } catch (\Throwable $th) {
            dd($th);
             return $this->sendResponse(false, "500, Internal Server Error!");
         }
     }

            // Remove (Delete) the specified and all Specialist.
    public function destroy(Request $request)
    {
        $Type = $request->type;
        try {
            if ($Type == 1) {
                $specialist_id = $request->id;
                User::where('id', $specialist_id)->delete();
                return $this->sendResponse(true, "Hospital has been Deleted SuccessFully", $Type);
            } else {
                
                $ids = $request->id;
                User::whereIn('id',$ids)->delete();
                return $this->sendResponse(true, "Hospital has been Deleted SuccessFully", $Type);
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }

    public function insertMultipleAllergy($id ,$type , $allergys = []){ //id=patientId, type=allergy type, allergys = allergyArray
        $oldAllergys = UserAllergy::where('user_id',$id)->where('allergy_type',$type)->whereNotIn('allergy_id', $allergys)->get();
        foreach($oldAllergys as $oldAllergy){
            $oldAllergy->delete();
        }
        if(count($allergys)){
            foreach($allergys as $allergy){
                $userAllergy = UserAllergy::where('user_id',$id)->where('allergy_type',$type)->where('allergy_id',$allergy)->first();
                if(empty($userAllergy)){
                    $userAllergy = new UserAllergy;
                    $userAllergy->user_id = $id;
                    $userAllergy->allergy_type = $type;
                    $userAllergy->allergy_id = $allergy;
                    $userAllergy->save();
                }
            }
        }
    }


}
