<?php 

namespace App\Http\Controllers;

use Validator;
use DB;
use Hash;
use Auth;
use Carbon;
use Session;
use Lang;
use App;
use Config;

use App\Models\User;
use App\Models\Doctor;
use App\Models\BloodType;
use App\Models\Chronicdiseases;
use App\Models\DoctorDetail;
use App\Models\DragsAllergy;
use App\Models\FoodAllergy;
use App\Models\Specialist;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\UserAllergy;
use App\Models\AppointmentDocuments;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class WebserviceController extends Controller
{

    public function registration(Request $request)  //both user registration --done
    { 
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);    

        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
        try
        {           

          if((empty($post['device'])) || (empty($post['deviceToken'])) || (empty($post['role']))  ||  (empty($post['name'])) || (empty($post['phone']))|| (empty($post['email'])) || (empty($post['password'])) || (empty($post['confirmPassword'])) )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
           
          if ($post['password'] != $post['confirmPassword']) 
          {
            $response = array('success' => 0, 'message' => trans('labels.passwordAndConfirmPasswordSame') );
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
            
          $checkusername = User::where('email','=',$post['email'])->first();
          if (!empty($checkusername)) 
          {
            $response = array('success' => 0, 'message' => trans('labels.emailAlreadyExisting'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

          $checkemail = User::where('phone','=',$post['phone'])->first();
          if (!empty($checkemail)) 
          {
            $response = array('success' => 0, 'message' => trans('labels.phoneAlreadyExisting'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

            $user = new User;
            $user->name = $post['name'];
            $user->phone = $post['phone'];
            $user->email = $post['email'];
            $user->role = $post['role'];
            $user->device = $post['device'];
            $user->status = 1;
            $user->deviceToken = $post['deviceToken'];
            $user->password = Hash::make($post['password']);  
            $user->save();
              
            $results = User::select('*')->where('id','=',$user->id)->first();     
                 
            if(!empty($results))
            {
                 \Mail::send('registrationmail',
                  array(
                      'name' => $results->name,
                      'email' =>  $results->email,
                      'phone' =>  $results->phone,
                       
                  ), function($message) use ($results)
                  {
                      $message->from('harmistest@gmail.com');
                      $message->to($results->email)->subject("Registration sucessfully!");
                  });
                $result = array();
                $result['userId'] = $results->id;
                $result['name'] = $results->name;
                $result['phone'] = $results->phone;        
                $result['email'] = $results->email;                            
                $result['role'] = $results->role;
                $result['latitude'] = !empty($results->latitude)  ?  $results->latitude : '0';
                $result['longitude'] = !empty($results->longitude)  ?  $results->longitude : '0';
                $result['status'] = !empty($results->status)  ?  $results->status : '';
                $result['address'] = !empty($results->address)  ?  $results->address : '';

                if($results->role=="1")
                {
                  if ($results->profile_pic != "") {
                    $result['profileImage']=$new.'/public/profileImage/'.$results->profile_pic;  
                  } else {
                    $result['profileImage']= $new.'/public/image/default-image.jpeg';  
                  }
                }
                else
                {
                  if ($results->profile_pic != "") {
                  $result['profileImage']=$new.'/public/patient/'.$results->profile_pic;  
                  } else {
                    $result['profileImage']= $new.'/public/image/default-image.jpeg';  
                  }
                }      
                    
                $response = array('success' => 1, 'message' => trans('labels.userRegisteredSucceessfully'),'result' => $result );
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }                     
        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function applogin(Request $request)   // 1- doctor  , 2 -patient -- done
    {
        $input = file_get_contents('php://input');
      
        $post = json_decode($input, true);
          
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
        try
        {           
          if((empty($post['device'])) || (empty($post['deviceToken'])) ||  (empty($post['phone'])) || (empty($post['password'])))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }
                  
          $phone = $post['phone'];
          $password=  Hash::make($post['password']);
          $results = User::select('*')->where('phone','=',$phone)->orwhere('email','=',$phone)->first(); 

          if(!empty($results))
          {                                  
            if(!Hash::check($post["password"],$results->password))
            {
              $arr = array('success' => 0, 'message' => trans('labels.invalidEmailOrPassword'));
              echo json_encode($arr,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);exit; 
            } 
          } 
          else 
          {
            $response = array('success' => 0, 'message' => trans('labels.invalidEmailOrPassword'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
                 
          if(!empty($results))
          {
            $datauser = User::where('id','=',$results->id)->update([
                'device'    => $post['device'],
                'deviceToken' => $post['deviceToken'],
            ]); 


            $result = array();
            $result['userId'] = $results->id;
            $result['name'] = $results->name;
            $result['name_ar'] = !empty($results->name_ar) ? $results->name_ar : '' ;
            $result['phone'] = $results->phone;
            $result['email'] = $results->email;
            $result['role'] = $results->role;
            $result['status'] = $results->status;
            $result['address'] = !empty($results->address) ? $results->address : '' ;
            $result['location'] = !empty($results->location) ? $results->location : '0' ;
            $result['latitude'] =!empty($results->latitude) ? $results->latitude : '0' ;
            $result['longitude'] = !empty($results->longitude) ? $results->longitude : '0' ;

            if(empty($results->name) || empty($results->name_ar) || empty($results->phone) || empty($results->email) || empty($results->address))   
            {
              $result['isprofile'] = false;  
            } 
            else 
            {
              $result['isprofile'] =true;  
            }   
             

            if($results->role=="1")
            {
              if ($results->profile_pic != "") {
                $result['profileImage']=$new.'/public/profileImage/'.$results->profile_pic;  
              } else {
                $result['profileImage']= $new.'/public/image/default-image.jpeg';  
              }
            }
            else
            {
              if ($results->profile_pic != "") {
              $result['profileImage']=$new.'/public/patient/'.$results->profile_pic;  
              } else {
                $result['profileImage']= $new.'/public/image/default-image.jpeg';  
              }
            }             

            $response = array('success' => 1, 'message' => trans('labels.successfullyLoggedIn') ,'result' => $result);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.invalidEmailOrPassword'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }    
        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }         
    }

    public function verifyMobile(Request $request){
       $input = file_get_contents('php://input');
       $post = json_decode($input, true);
       if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);

        try{
             $results = User::select('id')->where('phone',$post['phone'])->first();
             $data=0;
             $userId=NULL;
             $message=trans('labels.invalidmobile');
              if(!empty($results)){
                $data=1;
                $userId=$results->id;
                $message=trans('labels.validmobile');

              }

              $response = array('success' =>$data,'message'=>$message,'userId'=>$userId);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }

    }
    public function getuserinfo(Request $request)   // both user information -- done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
        try
        {           
          if((empty($post['userId'])))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }              
      
          $results = User::select('*')->where('id','=',$post['userId'])->first();              
          if(!empty($results))
          {
            $result = array();
            $result['userId'] = $results->id;
            $result['name'] = $results->name;
            $result['name_ar'] = $results->name_ar;
            $result['phone'] = $results->phone;
            $result['email'] = $results->email;
            $result['role'] = $results->role;
            $result['status'] = $results->status;
            $result['address'] = $results->address;
            $result['location'] = !empty($results->location) ? $results->location : '0' ;
            $result['latitude'] = !empty($results->latitude) ? $results->latitude : '0' ;
            $result['longitude'] =!empty($results->longitude) ? $results->longitude : '0' ; 

            $users = User::with('hasOnePatient','hasManyFoodallergy','hasManyDragsallergy','hasManyChronicDiseases')->find($results->id);
            $patientObj = $users->hasOnePatient ? $users->hasOnePatient : '';
            $bloodTypeObj = $patientObj ? $patientObj->hasOneBloodType : '';
            $result['bloodTypeId'] = $patientObj ? $patientObj->blood_type_id : '';

            $result['bloodType'] = $bloodTypeObj ? $bloodTypeObj->name : ''; 
            $result['dateOfBirth'] = $patientObj ? $patientObj->birth_date : ''; 


            if(empty($results->name) || empty($results->name_ar) || empty($results->phone) || empty($results->email) || empty($results->address) )   
            {
              $result['isprofile'] = false;  
            } 
            else 
            {
              $result['isprofile'] =true;  
            }   

            if($results->role=="1")
            {
              if ($results->profile_pic != "") {
                $result['profileImage']=$new.'/public/profileImage/'.$results->profile_pic;  
              } else {
                $result['profileImage']= $new.'/public/image/default-image.jpeg';  
              }
            }
            else
            {
              if ($results->profile_pic != "") {
              $result['profileImage']=$new.'/public/patient/'.$results->profile_pic;  
              } else {
                $result['profileImage']= $new.'/public/image/default-image.jpeg';  
              }
            }            


            if($results->role=="2")
            {
              $user = User::with('hasOnePatient','hasManyFoodallergy','hasManyDragsallergy','hasManyChronicDiseases')->find($results->id);
              $foodAllergys = $user->hasManyFoodallergy ? $user->hasManyFoodallergy : [];
              $dragsAllergys = $user->hasManyDragsallergy ? $user->hasManyDragsallergy : [];
              $chronicDiseasess = $user->hasManyChronicDiseases ? $user->hasManyChronicDiseases : [];
              $result['chronicDiseases'] = [];
              foreach($chronicDiseasess as $chronicDiseases){
                $oneChronicDiseases = $chronicDiseases->hasOneChronicDiseases ? $chronicDiseases->hasOneChronicDiseases : '';
                $chronicDiseaseObj = [];
                $chronicDiseaseObj['id'] = $chronicDiseases->allergy_id ? $chronicDiseases->allergy_id : '';
                $chronicDiseaseObj['name'] = $oneChronicDiseases ? $oneChronicDiseases->name : '';
                $result['chronicDiseases'][] = $chronicDiseaseObj;
              }
              $result['dragsAllergy'] = [];
              foreach($dragsAllergys as $dragsAllergy){
                $oneDragsAllergy = $dragsAllergy->hasOneDragsAllergy ? $dragsAllergy->hasOneDragsAllergy : '';
                $dragsAllergyObj = [];
                $dragsAllergyObj['id'] = $dragsAllergy->allergy_id ? $dragsAllergy->allergy_id : '';
                $dragsAllergyObj['name'] = $oneDragsAllergy ? $oneDragsAllergy->name : '';
                $result['dragsAllergy'][] = $dragsAllergyObj;
              }
              $result['foodAllergy'] = [];
              foreach($foodAllergys as $foodAllergy){
                $oneFoodAllergy = $foodAllergy->hasOneFoodAllergy ? $foodAllergy->hasOneFoodAllergy : '';
                $foodAllergyObj = [];

                $foodAllergyObj['id'] = $foodAllergy->allergy_id ? $foodAllergy->allergy_id : '';
                $foodAllergyObj['name'] = $oneFoodAllergy ? $oneFoodAllergy->name : '';
                $result['foodAllergy'][] = $foodAllergyObj;
              }
            }


            $response = array('success' => 1, 'message' => trans('labels.getuserdetailsucessfully') ,'result' => $result);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.invalidUserdetail'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }    
        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
    }

    public function userchatList(Request $request) // doctor list  --- done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);    
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);   
    
        try
        {        

          if((empty($post['docId'])))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }              
           
          $allresults  = DB::table('askdoctor')->where('assign_docId',$post['docId'])->pluck('userId')->toArray();

          if(!empty($allresults))
          {
            $users  = DB::table('users')->select('*')->whereIn('id',$allresults)->get();
            $totalcount = DB::table('users')->select('*')->whereIn('id',$allresults)->count();
            if (!empty($users)) 
            {
                $re = array();
                foreach ($users as $key => $results)
                {
                    $result = array();
                                   
                    
                    $result['userId'] = $results->id;
                    $result['name'] = $results->name;
                    $result['name_ar'] = !empty($results->name_ar) ? $results->name_ar : '' ;
                    $result['email'] = $results->email;
                    $result['remember_token'] = !empty($results->remember_token) ? $results->remember_token : '' ;
                    $result['phone'] = $results->phone;
                    $result['status'] = $results->status;
                    $result['registered_at'] = !empty($results->registered_at) ? $results->registered_at : '' ;
                    $result['address'] = !empty($results->address) ? $results->address : '' ;
                    $result['location']=!empty($results->location) ? $results->location : '0' ;
                    $result['latitude'] = !empty($results->latitude) ? $results->latitude : '0' ;
                    $result['longitude'] = !empty($results->latitude) ? $results->latitude : '0' ;
                    $result['roleId'] = $results->role;
                    $result['device'] = $results->device;
                    $result['deviceToken'] = $results->deviceToken;
                  
                    $result['price'] = !empty($results->price_of_ticket) ? $results->price_of_ticket : '0' ;

                    if ($results->role == '1') {
                      $result['role'] = "Doctor";  
                    } elseif ($results->role == '2') {
                      $result['role'] = "Patient";  
                    } 
                    
                    if ($results->profile_pic != "") {
                        $result['profileImage']=$new.'/public/profileImage/'.$results->profile_pic;  
                    } else {
                        $result['profileImage']= $new.'/public/image/default-image.jpeg';  
                    }
                
                
                    $re[]=$result; 
                }  
                $response = array('success' => 1, 'message' => trans('labels.getuserinfo'),'totalcount'=> $totalcount , 'result' => $re);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }     
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }          
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }


    public function chnagePassword(Request $request) // api change password   --done
    { 
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);        
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);
        if(empty($post['language']))
        {
          $post['language']='en';
        }          
        App::setLocale($post['language']);  

        try
        {      
          if((empty($post['userId'])) || (empty($post['confirmPassword'])) ||  (empty($post['password'])) )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }

          if($post['confirmPassword'] != $post['password']) 
          {
            $response = array('success' => 0, 'message' => trans('labels.passwordAndConfirmPasswordSame'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;  
          } 
                  
          $results = User::select('*')->where('id','=',$post['userId'])->first();         
          if(!empty($results))
          {   
            $results = User::where('id','=',$post['userId'])->update([
                'password'    => Hash::make($post['password'])
            ]); 

            $response = array('success' => 1, 'message' => trans('labels.passwordUpdateSuccessfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;            
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.usernotExisting'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }            
         
        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }    
    }

    public function forgotPwd(Request $request) // api forget password    --done
    {       
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);        
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);
        if(empty($post['language']))
        {
          $post['language']='en';
        }
        App::setLocale($post['language']);  

        try
        {      
          if((!isset($post['email'])))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;  
          }  

          $email = $post['email'];
          $results = User::select('*')->where('email','=',$email)->first();            
          if(!empty($results))
          {             
            $sendmail=array();                
            $sendmail['userId'] = $results->id;
            $id= $sendmail['userId'];
            $sendmail['name'] = $results->name;
            $sendmail['email'] = $results->email;
            $subject = "Forgot Password";
            $header="";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $message ="";
            $message .= "Hello ".$results->name."<br>";
            $message .= "You are requested to change password Click bellow link to reset that password<br>";
            $message .= '<a href='.$new.'ForgotPassword/'.$id.'>click here</a>';
           
            mail($sendmail['email'],$subject,$message,$header);                 
        
            $response = array('success' => 1, 'message' => trans('labels.youWillShortlyReceiveLinkToResetPassword'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;       
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.emailNotRegisteredWithSystem'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }        
    }

    public function bloodType(Request $request)  // blood type  -- Done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  

        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);

        try
        {            
            $results = BloodType::select()->get();   
     
            if(!empty($results))
            {
                $re= array();           
                foreach($results as $value)
                {
                  $result['id'] = $value->id;
                  if($post['language']=='en')
                  {
                    $result['name'] = $value->name; 
                  }
                  else
                  {
                    $result['name'] = $value->name_ar;  
                  }
                  
                  $re[]=$result; 
                } 
                $response = array('success' => 1, 'result' => $re);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }  
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

        }      
    }

    public function foodAllergy(Request $request)  // food   -- Done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  

        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);

        try
        {            
            $results = FoodAllergy::select()->get();   
     
            if(!empty($results))
            {
                $re= array();           
                foreach($results as $value)
                {
                  $result['id'] = $value->id;
                  if($post['language']=='en')
                  {
                    $result['name'] = $value->name; 
                  }
                  else
                  {
                    $result['name'] = $value->name_ar;  
                  }
                  
                  $re[]=$result; 
                } 
                $response = array('success' => 1, 'result' => $re);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }  
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
    }

    public function drugAllergy(Request $request)  // drug   -- Done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']); 

        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);

        try
        {            
            $results = DragsAllergy::select()->get();   
     
            if(!empty($results))
            {
                $re= array();           
                foreach($results as $value)
                {
                  $result['id'] = $value->id;
                  if($post['language']=='en')
                  {
                    $result['name'] = $value->name; 
                  }
                  else
                  {
                    $result['name'] = $value->name_ar;  
                  }
                  
                  $re[]=$result; 
                } 
                $response = array('success' => 1, 'result' => $re);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }  
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
    }

    public function chronicDiseases(Request $request)  // chronic diseases   -- Done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  

        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);

        try
        {            
            $results = Chronicdiseases::select()->get();   
     
            if(!empty($results))
            {
                $re= array();           
                foreach($results as $value)
                {
                  $result['id'] = $value->id;
                  if($post['language']=='en')
                  {
                    $result['name'] = $value->name; 
                  }
                  else
                  {
                    $result['name'] = $value->name_ar;  
                  }
                  
                  $re[]=$result; 
                } 
                $response = array('success' => 1, 'result' => $re);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }  
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
    }

    public function specialistType(Request $request)  // specialist type  -- Done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  

        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);

        try
        {            
            $results = Specialist::select()->get();   
     
            if(!empty($results))
            {
                $re= array();           
                foreach($results as $value)
                {
                  $result['id'] = $value->id;
                  if($post['language']=='en')
                  {
                    $result['name'] = $value->name; 
                  }
                  else
                  {
                    $result['name'] = $value->name_ar;  
                  }

                 if ($value->image != "") {
                    $result['image']=$new.'/public/specialist/'.$value->image;  
                  } else {
                    $result['image']= $new.'/public/image/default-image.jpeg';  
                  }
                        
                  
                  $re[]=$result; 
                } 
                $response = array('success' => 1, 'result' => $re);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }  
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

        }      
    }

    public function hospital(Request $request)  // hospital   -- Done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  

        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);

        try
        {            
            $results = DB::table('hospital')->select()->get();  
     
            if(!empty($results))
            {
                $re= array();           
                foreach($results as $value)
                {
                  $result['id'] = $value->id;             
                  $result['name'] = $value->name; 
                  $result['phone'] = $value->phone;             
                  $re[]=$result; 
                } 
                $response = array('success' => 1, 'result' => $re);
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;

            }
            else
            {
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }  
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
    }

    public function allTypeList(Request $request)  // all type  -- Done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  

        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);

        try
        {            
            $ChronicDiseases = Chronicdiseases::select()->get();   
            if(!empty($ChronicDiseases))
            {
              $re= array();           
              foreach($ChronicDiseases as $value)
              {
                $result['id'] = $value->id;
                if($post['language']=='en')
                {
                  $result['name'] = $value->name; 
                }
                else
                {
                  $result['name'] = $value->name_ar;  
                }            
                $re[]=$result; 
              } 
            }

            $Dragsallergy = Dragsallergy::select()->get();  
     
            if(!empty($Dragsallergy))
            {
              $re1= array();           
              foreach($Dragsallergy as $value)
              {
                $result['id'] = $value->id;
                if($post['language']=='en')
                {
                  $result['name'] = $value->name; 
                }
                else
                {
                  $result['name'] = $value->name_ar;  
                }
                
                $re1[]=$result; 
              }
            }  

            $Foodallergy = FoodAllergy::select()->get();  
     
            if(!empty($Foodallergy))
            {
              $re2= array();           
              foreach($Foodallergy as $value)
              {
                $result['id'] = $value->id;
                if($post['language']=='en')
                {
                  $result['name'] = $value->name; 
                }
                else
                {
                  $result['name'] = $value->name_ar;  
                }
                
                $re2[]=$result; 
              }
            }  

            $news= array();
            $news['ChronicDiseases']= $re;
            $news['Dragsallergy']= $re1;
            $news['Foodallergy']= $re2;

            $response = array('success' => 1, 'result' => $news);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;      
           
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
    }

    public function doctorInsert(Request $request)    //   -- update doctor  --done
    {            
        $post = $request->all();
       
        $decode = json_decode($post['json_content']);
  
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
      
        try
        {           
          if((empty($decode->name)) || (empty($decode->arabicName)) || (empty($decode->email)) || (empty($decode->certificate))  || (empty($decode->userId)) || (empty($decode->phone)) || (empty($decode->specialist)) || (empty($decode->status)) || (empty($decode->address)) || (empty($decode->location))  || (!isset($decode->priceOfTicket)) || (empty($decode->role)) || (empty($decode->lat))  ||  (empty($decode->long)) ||  (empty($decode->WorkHours)) || (empty($decode->averagehour)) || (empty($decode->yearofexp)) || (empty($decode->info))  )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }
          
          $checkusername = User::where('email','=',$decode->email)->where('id','!=',$decode->userId)->first();
          if (!empty($checkusername)) 
          {
              $response = array('success' => 0, 'message' => trans('labels.emailAlreadyExisting'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

          $checkemail = User::where('phone','=',$decode->phone)->where('id','!=',$decode->userId)->first();
          if (!empty($checkemail)) 
          {
              $response = array('success' => 0, 'message' => trans('labels.phoneAlreadyExisting'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
          
          
              if(!empty($post['image']))
              {   
                  $file = $request->file('image'); 
                  $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                  $picture = time() . "." . $image_name;
                  $destinationPath = public_path('profileImage/');
                  $file->move($destinationPath, $picture); 
          
                  $profile_pic = $picture;
              }else{
                  $profile_pic = "";
              }
                
              $registered_at = get_timestamp();

              $userId = DB::table('users')->where('id','=',$decode->userId)->update([
                'name'    =>  $decode->name,
                'name_ar'     =>   $decode->arabicName,
                'email'    =>  $decode->email,
               'phone'    =>  $decode->phone,           
                'profile_pic'     =>   $profile_pic,
                'status'     =>   $decode->status,
                'registered_at'     =>  $registered_at,
                'address'     =>   $decode->address,
                'location' =>$decode->location,
                'latitude'     =>   $decode->lat,
                'longitude'     =>   $decode->long,
                'role'     =>   $decode->role
              ]);  



                if(!empty($decode->certificate))
                {
                  $certificates = implode(',',$decode->certificate);
                }
                else
                {
                  $certificates ='';
                }

                $decode->instaLink = !empty($decode->instaLink) ? $decode->instaLink : '';
                $decode->facebookLink = !empty($decode->facebookLink) ? $decode->facebookLink : '';
                $decode->discount = !empty($decode->discount) ? $decode->discount : '0';
                $decode->discount_rule = !empty($decode->discount_rule) ? $decode->discount_rule : '';

                $upsdate = DB::table('doctor')->where('user_id','=',$decode->userId)->first();
                if(!empty($upsdate))
                {
                      $doctoreId = DB::table('doctor')->where('user_id','=',$decode->userId)->update([
                        'user_id'    =>  $decode->userId,
                        'certificates'     =>   $certificates,
                        'averagehour'     =>   $decode->averagehour,
                        'work_hour'    =>  "",
                        'price_of_ticket'     =>   $decode->priceOfTicket,
                        'discounts'     =>   $decode->discount,
                        'discount_rule'     =>   $decode->discount_rule,
                        'insta_link'     =>   $decode->instaLink,
                        'facebook_link'  =>  $decode->facebookLink,
                         'info'     =>   $decode->info,
                        'yearofexp'  =>  $decode->yearofexp,
                      ]); 
                }
                else
                {
                    $doctoreId = DB::table('doctor')->insertGetId([
                        'user_id'    =>  $decode->userId,
                        'certificates'     =>   $certificates,
                        'averagehour'     =>   $decode->averagehour,
                        'work_hour'    =>  "",
                        'price_of_ticket'     =>   $decode->priceOfTicket,
                        'discounts'     =>   $decode->discount,
                        'discount_rule'     =>   $decode->discount_rule,
                        'insta_link'     =>   $decode->instaLink,
                        'facebook_link'  =>  $decode->facebookLink,
                        'info'     =>   $decode->info,
                        'yearofexp'  =>  $decode->yearofexp
                  ]); 

                }

           

              if (!empty($post['picture_clinic'])) {
                  $picture_clinic = $request->file('picture_clinic'); 
                  foreach ($picture_clinic as $key => $picclinic) {
                    $clinicpic = str_replace(' ', '-', $picclinic->getClientOriginalName());
                    $clinicpicname = time() . "." . $clinicpic;
                    $clinidestinationPath = public_path('clinic/');
                    $picclinic->move($clinidestinationPath, $clinicpicname); 

                    $clinicImgId =DB::table('doctor_clinic_images')->where('user_id',$decode->userId)->insertGetId([
                        'user_id'     =>   $decode->userId,
                        'image'     =>   $clinicpicname,
                        'isMain'     =>   "0"
                    ]);
                  } 
              }

              if (!empty($decode->specialist)) 
              {     
                $getspe = DB::table('doctor_specialist')->where('user_id', $decode->userId)->delete();
                foreach ($decode->specialist as $key => $special) 
                {      
                  if($special != "")
                  {
                      $clinicspId =DB::table('doctor_specialist')->insertGetId([
                          'user_id'     =>  $decode->userId,
                          'specialist_id'     =>   $special
                      ]);  
                  }  
                }
              } 
              

              if (!empty($decode->WorkHours)) 
              {
                $getspe = DB::table('doctor_time')->where('user_id', $decode->userId)->delete();
                foreach ($decode->WorkHours as $key => $Whours) {
                    $clinicwoId =DB::table('doctor_time')->insertGetId([
                        'user_id'     =>   $decode->userId,
                        'day_id'     =>   $Whours->chooseDay,
                        'start_time'     =>   $Whours->startTime,
                        'end_time'     =>   $Whours->endTime
                    ]);
                }              
              }

              $results = DB::table('users')->select('*')->where('id',$decode->userId)->first();        

              if(!empty($results))
              {
                  $result = array();
                  $userID = $results->id;
                  
                  $doctor = DB::table('doctor')->select('*')->where('user_id',$userID)->first();                    
                  $doctor_clinic_images = DB::table('doctor_clinic_images')->select('*')->where('user_id',$userID)->get();
                  
                  $doctor_specialist = DB::table('doctor_specialist')->select('*')->where('user_id',$userID)->get();
                  $doctor_time = DB::table('doctor_time')->select('*')->where('user_id',$userID)->get();                    
                  
                  $result['userId'] = $userID;
                  $result['name'] = $results->name;
                  $result['name_ar'] = $results->name_ar;
                  $result['email'] = $results->email;
                  $result['remember_token'] = $results->remember_token;
                  $result['phone'] = $results->phone;
                  $result['status'] = $results->status;
                  $result['registered_at'] = $results->registered_at;
                  $result['address'] = $results->address;
                  $result['location']=!empty($results->location) ? $results->location :'0' ;
                  $result['latitude'] = !empty($results->latitude) ? $results->latitude :'0' ;
                  $result['longitude'] = !empty($results->longitude) ? $results->longitude :'0' ;
                  $result['roleId'] = $results->role;

                  if ($results->role == '1') {
                    $result['role'] = "Doctor";  
                  } elseif ($results->role == '2') {
                    $result['role'] = "Patient";  
                  } 
                  
                    if ($results->profile_pic != "") {
                        $result['profileImage']=$new.'/public/profileImage/'.$results->profile_pic;  
                    } else {
                        $result['profileImage']= $new.'/public/image/default-image.jpeg';  
                    }
                  
                  if(!empty($doctor)){
                    
                
                    $result['certificates']= $doctor->certificates;              
                    $result['price_of_ticket'] = $doctor->price_of_ticket;
                    $result['picture_clinic'] = $doctor->picture_clinic;
                    $result['discounts'] = $doctor->discounts;
                    $result['discount_rule'] = $doctor->discount_rule;
                    $result['insta_link'] = $doctor->insta_link;
                    $result['facebook_link'] = $doctor->facebook_link;  
                    $result['info'] = $doctor->info;
                    $result['yearofexp'] = $doctor->yearofexp;  
                  }
                  
                  $result['clinicImg'] = [];
                  if (!empty($doctor_clinic_images)) {
                    foreach ($doctor_clinic_images as $key => $getcImg) {
                      if ($getcImg->image != "") {
                         $result['clinicImg'][] =$new.'/public/clinic/'.$getcImg->image;  
                      } else {
                          $result['clinicImg'][]= $new.'/public/image/default-image.jpeg';  
                      }
                      
                    } 
                  }

                  $result['specialist'] = [];
                  if (!empty($doctor_specialist)) {
                    foreach ($doctor_specialist as $key => $getspe) {
                      $doctor_specialistName = DB::table('specialist')->select('*')->where('id',$getspe->specialist_id)->first();

                      if ($doctor_specialistName->name != "") {   
                          if($post['language']=="en")
                          {
                            $result['specialist'][] = $doctor_specialistName->name;
                          } 
                          else
                          {
                            $result['specialist'][] = $doctor_specialistName->name_ar;
                          }                
                          
                      } else {
                          $result['specialist'][] = "";
                      }
                    }   
                  }
                  
                  
                  if (!empty($doctor_time)) {
                    $days = array();
                    $result['workHour']=[];
                    foreach ($doctor_time as $key => $gettime) {
                      $daysName =  Config::get('commonVariable.weekName.'.$gettime->day_id);
                      if ($daysName != "") {
                          $worktime['days'] = $daysName;
                      } else {
                          $worktime['days'] = "";
                      }
                      $worktime['start_time'] = $gettime->start_time;
                      $worktime['end_time'] = $gettime->end_time;
                      $result['workHour'][] = $worktime;
                    }   
                  }            
                  
                  $response = array('success' => 1, 'message' => trans('labels.doctorupdateSuccessfully') , 'result' => $result);
                  echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
              } 
              else
              {
                $response = array('success' => 0, 'message' => trans('labels.profileNotUpdateSuccessfully'));
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
              }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function doctorDetail(Request $request)    //   -- doctor detail   --done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
          
        try
        {           
          if(empty($post['userId']))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $results = DB::table('users')->select('*')->where('id',$post['userId'])->first();        

          if(!empty($results))
          {
              $result = array();
              $userID = $results->id;
              
              $doctor = DB::table('doctor')->select('*')->where('user_id',$userID)->first();                    
              $doctor_clinic_images = DB::table('doctor_clinic_images')->select('*')->where('user_id',$userID)->get();          
              $doctor_specialist = DB::table('doctor_specialist')->select('*')->where('user_id',$userID)->get();
              $doctor_time = DB::table('doctor_time')->select('*')->where('user_id',$userID)->get();                    
              
              $result['userId'] = $userID;
              $result['name'] = $results->name;
              $result['name_ar'] = $results->name_ar;
              $result['email'] = $results->email;
              $result['remember_token'] = !empty($results->remember_token) ? $results->remember_token : '' ;
              $result['phone'] = $results->phone;
              $result['status'] = $results->status;
              $result['registered_at'] = $results->registered_at;
              $result['address'] = $results->address;
              $result['location']=!empty($results->location) ?  $results->location :'0';
              $result['latitude'] = !empty($results->latitude) ?  $results->latitude :'0';
              $result['longitude'] =!empty($results->longitude) ?  $results->longitude :'0';
              $result['roleId'] = $results->role;        

              $rating = DB::table('rating')->where('doc_id',$post['userId'])->sum('rating');
              $count = DB::table('rating')->where('doc_id',$post['userId'])->count();
              if(!empty($rating))
              {
                $vall = $rating / $count;
                $result['rating'] = intval($vall);
              }
              else
              {
                $result['rating'] = 0;
              }

              if ($results->role == '1') {
                $result['role'] = "Doctor";  
              } elseif ($results->role == '2') {
                $result['role'] = "Patient";  
              } 
              
                if ($results->profile_pic != "") {
                    $result['profileImage']=$new.'/public/profileImage/'.$results->profile_pic;  
                } else {
                    $result['profileImage']= $new.'/public/image/default-image.jpeg';  
                }                    
                
                $result['certificates'] = !empty($doctor->certificates) ? $doctor->certificates : '';
                $result['averagehour'] = !empty($doctor->averagehour) ? $doctor->averagehour :'';
                $result['hourlyRate'] = !empty($doctor->price_of_ticket) ? $doctor->price_of_ticket :'' ;
                $result['discounts'] = !empty($doctor->discounts) ? $doctor->discounts :'' ;
                $result['discount_rule'] = !empty($doctor->discount_rule) ? $doctor->discount_rule   :'' ;
                $result['instagramLink'] = !empty($doctor->insta_link) ? $doctor->insta_link :'' ;
                $result['facebookLink'] = !empty($doctor->facebook_link) ? $doctor->facebook_link :'' ; 
                $result['info'] = !empty($doctor->info) ? $doctor->info :'' ; 
                $result['yearofexp'] =!empty($doctor->yearofexp) ? $doctor->yearofexp :'0' ; 

                $discount = !empty($doctor) && !empty($doctor->discounts) ? $doctor->discounts : 0 ; 
                $price = !empty($doctor) && !empty($doctor->price_of_ticket) ? $doctor->price_of_ticket : 0 ;  

                $discount =  str_replace("%","",$discount);
                $totalprice = $price - ( $price * ($discount / 100));
                  
              $result['finalprice'] =!empty($totalprice) ? $totalprice :'0' ; 

            
              
              $result['clinicImg'] = [];
              $cliimage1 =array();
              if (!empty($doctor_clinic_images)) {
                foreach ($doctor_clinic_images as $key => $getcImg) {
                  if ($getcImg->image != "") {
                    $cliimage['id'] =$getcImg->Id;
                    $cliimage['image'] =$new.'/public/clinic/'.$getcImg->image;  
                  } else {
                      $cliimage['id'] =$getcImg->Id;
                      $cliimage['image']= $new.'/public/image/default-image.jpeg';  
                  }
                  $cliimage1[] = $cliimage;
                } 
              }
              $result['clinicImg'] = $cliimage1;
                $result['specialist'] = [];
                  $newspecialist =array();
                  if (!empty($doctor_specialist)) {
                    foreach ($doctor_specialist as $key => $getspe) {
                      $doctor_specialistName = DB::table('specialist')->select('*')->where('id',$getspe->specialist_id)->first();
                      if ($doctor_specialistName->name != "") {
                          $speci['id']= $doctor_specialistName->id;
                          if($post['language']=="en")
                          {
                            $speci['name']= $doctor_specialistName->name;
                          }
                          else
                          {
                            $speci['name']= $doctor_specialistName->name_ar;
                          }
                          
                        
                      } else {
                          $speci['id']=[];
                          $speci['name']= [];
                      }
                      $newspecialist[] = $speci;
                    }   
                  }
                   $result['specialist'] = $newspecialist;
                           
                   // echo "<pre>";
                   // print_r($doctor_time); exit;

              if (!empty($doctor_time)) {
                $days = array();
                $result['workHour']=[];
                foreach ($doctor_time as $key => $gettime) {
                  $daysName =  Config::get('commonVariable.weekName.'.$gettime->day_id);
                  $worktime['day_id'] = $gettime->day_id;
                  if ($daysName != "") {
                      $worktime['days'] = $daysName;
                  } else {
                      $worktime['days'] = "";
                  }
                  $worktime['start_time'] = $gettime->start_time;
                  $worktime['end_time'] = $gettime->end_time;
                  $duration = !empty($doctor->averagehour) ?  $doctor->averagehour : '30' ;  // split by 30 mins
                  $monday = strtotime("last monday");
                  $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
                  $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
                  $this_week_start = date("Y-m-d",$monday);
                  $this_week_end = date("Y-m-d",$sunday);     //    get current week 

                  $Variable1 = strtotime($this_week_start); 
                  $Variable2 = strtotime($this_week_end);  
                  for ($currentDate = $Variable1; $currentDate <= $Variable2; $currentDate += (86400)) 
                  {                                                        
                    $Store = date('Y-m-d', $currentDate); 
                    $datearray[] = $Store;     //get current week  array
                  } 
         
                  // $appointdata = DB::table('appointment')->where('doc_id','=',$results->id)->where('book_day',$gettime->day_id)->where('book_date','>=', $this_week_start)->where('book_date','<=', $this_week_end)->pluck('book_time')->toArray();

                  $appointdata = DB::table('appointment')->where('doc_id','=',$results->id)->where('book_day',$gettime->day_id)->pluck('book_time')->toArray();

                  $array_of_time = array ();
                  $array_of_time1 = array();
                  $start_time    = strtotime ($gettime->start_time); //change to strtotime
                  $end_time      = strtotime ($gettime->end_time); //change to strtotime

                  $add_mins  = $duration * 60;
                  $array1 = array();
                  $array2 = array();
                  while ($start_time <= $end_time) // loop between time
                  {
                    $a= date ("h:i A", $start_time);
                    if (strpos($a, 'AM') !== false) {
                         $array1['time'] = date ("h:i A", $start_time);

                         if(!empty($appointdata))
                         {
                             if(in_array($array1['time'],$appointdata))
                             {
                                $array1['isBooked'] = true;
                             }
                             else
                             {
                                $array1['isBooked'] = false;
                             }
                         }
                         else
                         {
                            $array1['isBooked'] = false;
                         }             
                         
                         $array_of_time[] = $array1;
                    }
                    else
                    {
                       $array2['time'] = date ("h:i A", $start_time);
                         if(!empty($appointdata))
                         {
                             if(in_array($array2['time'],$appointdata))
                             {
                                $array2['isBooked'] = true;
                             }
                             else
                             {
                                $array2['isBooked'] = false;
                             }
                         }
                         else
                         {
                            $array2['isBooked'] = false;
                         }
                       $array_of_time1[] = $array2;
                    }
                    
                     $start_time += $add_mins; // to check endtie=me
                  }

                  $worktime['sun'] =  $array_of_time;
                  $worktime['moon'] = $array_of_time1;

                  $result['workHour'][] = $worktime;
                }   
              }                      
              
              $response = array('success' => 1, 'message' => trans('labels.getdoctordetailSuccessfully') , 'result' => $result);
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          } 
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.profileNotUpdateSuccessfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function doctorList(Request $request) // doctor list  --- done
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);    
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);   
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
    
      
        try
        {           
            $limit = isset($post['startLimit']) ? $post['startLimit'] : 0;     

            $ddd = DB::table('users')->where('users.role','1')->where('users.status','1');
            $ddd->join('doctor','doctor.user_id','=','users.id');
            $ddd->join('doctor_specialist','doctor_specialist.user_id','=','users.id','left');

            $search='';
            if(!empty($post['search']))
            {
                $search = $post['search'];
                $speci = DB::table('specialist')->select('*')->where('name', 'LIKE',"%{$search}%")->first();
               
                if(!empty($speci))
                {
                   $doc_speci = DB::table('doctor_specialist')->where('specialist_id',$speci->id)->pluck('user_id')->toArray();
                   $serachdata = DB::table('users')->where('users.status','1')->where('users.name', 'LIKE',"%{$search}%")->orWhere('users.name_ar', 'LIKE',"%{$search}%")->orWhere('users.address', 'LIKE',"%{$search}%")->pluck('id')->toArray();
                    if(!empty($doc_speci))
                    {     
                        $newo= array_merge($doc_speci,$serachdata) ;     
                        $ddd->orwhereIn('users.id',$newo);                   
                    }
                    else
                    {
                        $ddd->where('users.name', 'LIKE',"%{$search}%")->orWhere('users.name_ar', 'LIKE',"%{$search}%")->orWhere('users.address', 'LIKE',"%{$search}%"); 
                    }
                } 
                else
                {
                  $ddd->where('users.name', 'LIKE',"%{$search}%")->orWhere('users.name_ar', 'LIKE',"%{$search}%")->orWhere('users.address', 'LIKE',"%{$search}%"); 
                }                                    
            }

            if(!empty($post['location']))
            {
                $location = $post['location'];
                $ddd->where('users.location', 'LIKE',"%{$location}%");
            }
           
            $ddd->select('users.*','doctor.price_of_ticket')->limit(20)->offset($limit);
            if(!empty($post['sortprice']))
            {
               if($post['sortprice']=="1")   // low to high price
               {
                  $ddd->orderBy('doctor.price_of_ticket', 'asc');   
               }
               elseif($post['sortprice']=="2")  // high to low price
               {
                  $ddd->orderBy('doctor.price_of_ticket', 'desc');
               }
            }
            elseif(!empty($post['sortatoz']))
            {
                if($post['sortatoz']=="1")    // ato z 
                {
                  $ddd->orderBy('users.name', 'asc');   
                }
                elseif($post['sortatoz']=="2")   // z to a
                {
                  $ddd->orderBy('users.name', 'desc');   
                }
            }
            else
            {
              $ddd->orderBy('users.id', 'desc');   
            }
                
            $allresults = $ddd->distinct()->get();  

            $ddd1 = DB::table('users')->where('users.role','1')->where('users.status','1');
            $ddd1->join('doctor','doctor.user_id','=','users.id');
            $ddd1->join('doctor_specialist','doctor_specialist.user_id','=','users.id','left');

            $search='';
            if(!empty($post['search']))
            {
                $search = $post['search'];
                $speci = DB::table('specialist')->select('*')->where('name', 'LIKE',"%{$search}%")->first();
               
                if(!empty($speci))
                {
                   $doc_speci = DB::table('doctor_specialist')->where('specialist_id',$speci->id)->pluck('user_id')->toArray();
                   $serachdata = DB::table('users')->where('users.status','1')->where('users.name', 'LIKE',"%{$search}%")->orWhere('users.name_ar', 'LIKE',"%{$search}%")->orWhere('users.address', 'LIKE',"%{$search}%")->pluck('id')->toArray();
                    if(!empty($doc_speci))
                    {     
                        $newo= array_merge($doc_speci,$serachdata) ;     
                        $ddd1->orwhereIn('users.id',$newo);                   
                    }
                    else
                    {
                        $ddd1->where('users.name', 'LIKE',"%{$search}%")->orWhere('users.name_ar', 'LIKE',"%{$search}%")->orWhere('users.address', 'LIKE',"%{$search}%"); 
                    }
                } 
                else
                {
                  $ddd1->where('users.name', 'LIKE',"%{$search}%")->orWhere('users.name_ar', 'LIKE',"%{$search}%")->orWhere('users.address', 'LIKE',"%{$search}%"); 
                }                                       
            }

            if(!empty($post['location']))
            {
                $location = $post['location'];
                $ddd1->where('users.location', 'LIKE',"%{$location}%");
            }
           
            $totalcount  = $ddd1->select('users.*','doctor.price_of_ticket')->distinct()->get();
            $totalcount = count($totalcount);
            // echo $totalcount; exit;
            // echo count($allresults); exit;
            $re = array();
            if (count($allresults)) 
            {
                $re = array();
                foreach ($allresults as $key => $results)
                {
           
                    $result = array();
                    $userID = $results->id;            
                    $doctor = DB::table('doctor')->select('*')->where('user_id',$userID)->first();                    
                    $doctor_clinic_images = DB::table('doctor_clinic_images')->select('*')->where('user_id',$userID)->get();            
                    $doctor_specialist = DB::table('doctor_specialist')->select('*')->where('user_id',$userID)->get();
                    $doctor_time = DB::table('doctor_time')->select('*')->where('user_id',$userID)->get();                    
                    
                    $result['userId'] = $userID;
                    $result['name'] = $results->name;
                    $result['name_ar'] = !empty($results->name_ar) ? $results->name_ar : '' ;
                    $result['email'] = $results->email;
                    $result['remember_token'] = !empty($results->remember_token) ? $results->remember_token : '' ;
                    $result['phone'] = $results->phone;
                    $result['status'] = $results->status;
                    $result['registered_at'] = !empty($results->registered_at) ? $results->registered_at : '' ;
                    $result['address'] = !empty($results->address) ? $results->address : '' ;
                    $result['location']=!empty($results->location) ? $results->location : '0' ;
                    $result['latitude'] = !empty($results->latitude) ? $results->latitude : '0' ;
                    $result['longitude'] = !empty($results->longitude) ? $results->longitude : '0' ;
                    $result['roleId'] = $results->role;

                    $rating = DB::table('rating')->where('doc_id',$userID)->sum('rating');
                    $count = DB::table('rating')->where('doc_id',$userID)->count();
                    if(!empty($rating))
                    {
                      $vall = $rating / $count;
                      $result['rating'] = intval($vall);
                    }
                    else
                    {
                      $result['rating'] = 0;
                    }
                   
                  
                    $result['price'] = !empty($results->price_of_ticket) ? $results->price_of_ticket : '0' ;

                    if ($results->role == '1') {
                      $result['role'] = "Doctor";  
                    } elseif ($results->role == '2') {
                      $result['role'] = "Patient";  
                    } 
                    
                    if ($results->profile_pic != "") {
                        $result['profileImage']=$new.'/public/profileImage/'.$results->profile_pic;  
                    } else {
                        $result['profileImage']= $new.'/public/image/default-image.jpeg';  
                    }
                
                    if(!empty($doctor)){
                  
                        if ($doctor->certificates != "") {
                              $result['certificates']=$new.'/public/certificates/'.$doctor->certificates;  
                        } else {
                              $result['certificates']= $new.'/public/image/default-image.jpeg';  
                        }
                  
                        $result['work_hour'] = $doctor->work_hour;
                        $result['price_of_ticket'] = $doctor->price_of_ticket;
                        $result['picture_clinic'] = $doctor->picture_clinic;
                        $result['discounts'] = $doctor->discounts;
                        $result['insta_link'] = $doctor->insta_link;
                        $result['facebook_link'] = $doctor->facebook_link;  
                    }
                
                    $result['clinicImg'] = [];
                    if (!empty($doctor_clinic_images)) {
                      foreach ($doctor_clinic_images as $key => $getcImg) {
                        if ($getcImg->image != "") {
                           $result['clinicImg'][] =$new.'/public/clinic/'.$getcImg->image;  
                        } else {
                            $result['clinicImg'][]= $new.'/public/image/default-image.jpeg';  
                        }
                        
                      } 
                    }

                    $result['specialist'] = [];
                    if (!empty($doctor_specialist)) {
                      foreach ($doctor_specialist as $key => $getspe) {
                        $doctor_specialistName = DB::table('specialist')->select('*')->where('id',$getspe->specialist_id)->first();

                        if ($doctor_specialistName->name != "") {
                          if($post['language']=="en")
                          {
                            $result['specialist'][] = $doctor_specialistName->name;
                          }
                          else
                          {
                            $result['specialist'][] = $doctor_specialistName->name_ar;
                          }
                        } else {
                            $result['specialist'][] = "";
                        }
                      }   
                    }
                
                
                    if (!empty($doctor_time)) {
                      $days = array();
                      $result['workHour']=[];
                      foreach ($doctor_time as $key => $gettime) {
                        $daysName =  Config::get('commonVariable.weekName.'.$gettime->day_id);
                        if ($daysName != "") {
                            $worktime['days'] = $daysName;
                        } else {
                            $worktime['days'] = "";
                        }
                        $worktime['start_time'] = $gettime->start_time;
                        $worktime['end_time'] = $gettime->end_time;
                        $result['workHour'][] = $worktime;
                      }   
                    }                      
                
                    $re[]=$result; 
                }  
            }

            $response = array('success' => 1, 'message' => trans('labels.getdoctorlist'),'totalcount'=> $totalcount , 'result' => $re);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function bookAppointment(Request $request)  //-- book appointment -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
      
        try
        {           
          if( empty($post['docId']) || empty($post['userId']) || empty($post['bookdate']) || empty($post['booktime']) || empty($post['bookday']) )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }    

          $results = DB::table('appointment')->select('*')->where('doc_id',$post['docId'])->where('user_id',$post['userId'])->where('book_date',$post['bookdate'])->where('book_time',$post['booktime'])->where('book_day',$post['bookday'])->where('status','!=','2')->first();      

          if(!empty($results))
          {
            $response = array('success' => 0, 'message' => trans('labels.alreadybookthisappointment'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          } 
         
          $doctordata = Doctor::select('*')->where('user_id',$post['docId'])->first();

          $discount = !empty($doctordata) && !empty($doctordata->discounts) ? $doctordata->discounts : 0 ; 
          $price = !empty($doctordata) && !empty($doctordata->price_of_ticket) ? $doctordata->price_of_ticket : 0 ;      

          $totalprice = $price - ( $price * ($discount / 100));

          $appointId =DB::table('appointment')->insertGetId([
            'doc_id'    =>  $post['docId'],
            'user_id'   =>  $post['userId'],
            'book_date' =>  $post['bookdate'],
            'book_time' =>  $post['booktime'],
            'book_day'  =>  $post['bookday'],
            'discount'  =>  $discount,
            'price'  =>  $price,
            'totalprice'  =>  $totalprice,
          ]);

          //  book appointment doctor side
          $docuser = User::where('id',$post['docId'])->select('*')->first();
          $patientuser = User::where('id',$post['userId'])->select('*')->first();

          if(!empty($docuser->device || !empty($docuser->deviceToken)))
          {
            $this->setnotification("New appointment request" ,  $patientuser->name." "."has sent you a new appointment request", $post['docId'] ,$post['userId'] , '1' , $appointId ,$docuser->device , $docuser->deviceToken , $patientuser->name." "."has sent you a new appointment request");
          }  

          if(!empty($patientuser->device || !empty($patientuser->deviceToken)))
          {
            $this->setnotification("Appointment booking" , "your appointment has been booked sucessfully.", $post['docId'] ,$post['userId'] , '2' , $appointId ,$patientuser->device , $patientuser->deviceToken , "your appointment has been booked sucessfully.");
          }  


          if(!empty($appointId))
          {      
            $response = array('success' => 1, 'message' => trans('labels.appointmentbookSuccessfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function cancelAppointment(Request $request)  //-- cancel appointment -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
      
        try
        {           
          if( empty($post['Id']))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }    

          $results = DB::table('appointment')->select('*')->where('id','=',$post['Id'])->first();      

          if(!empty($results))
          {
              $datauser = DB::table('appointment')->where('id','=',$results->id)->update([
              'status'    => 2,                
              ]); 

            // $user = DB::table('appointment')->where('id', $results->id)->delete();   
            $response = array('success' => 1, 'message' => trans('labels.cancelbookingappointment'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          } 
          else
          {
             $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function doctorbookAppointment(Request $request)  //-- book appointment -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
      
        try
        {           
          if( empty($post['docId']) || empty($post['name']) || empty($post['email']) || empty($post['phone']) || empty($post['bookdate']) || empty($post['booktime']) || empty($post['bookday']) )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }    

          $results = DB::table('appointment')->select('*')->where('doc_id',$post['docId'])->where('book_date',$post['bookdate'])->where('book_time',$post['booktime'])->where('book_day',$post['bookday'])->first();      

          if(!empty($results))
          {
            $response = array('success' => 0, 'message' => trans('labels.alreadybookthisappointment'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          } 
         
          $doctordata = Doctor::select('*')->where('user_id',$post['docId'])->first();

          $discount = !empty($doctordata) && !empty($doctordata->discounts) ? $doctordata->discounts : 0 ; 
          $price = !empty($doctordata) && !empty($doctordata->price_of_ticket) ? $doctordata->price_of_ticket : 0 ;      

          $totalprice = $price - ( $price * ($discount / 100));

          $appointId =DB::table('appointment')->insertGetId([
            'doc_id'    =>  $post['docId'],
            'name'   =>  $post['name'],
            'email'   =>  $post['email'],
            'phone'   =>  $post['phone'],
            'direct'   =>  1,
            'book_date' =>  $post['bookdate'],
            'book_time' =>  $post['booktime'],
            'book_day'  =>  $post['bookday'],
            'discount'  =>  $discount,
            'price'  =>  $price,
            'totalprice'  =>  $totalprice,
          ]);

          //  book appointment doctor side
          $docuser = User::where('id',$post['docId'])->select('*')->first();
          
          if(!empty($docuser->device || !empty($docuser->deviceToken)))
          {
            $this->setnotification("New appointment request" ,  $post['name']." "."has sent you a new appointment request", $post['docId'] ,$post['userId'] , '1' , $appointId ,$docuser->device , $docuser->deviceToken , $post['name']." "."has sent you a new appointment request");
          }  

          // if(!empty($patientuser->device || !empty($patientuser->deviceToken)))
          // {
          //   $this->setnotification("Appointment booking" , "your appointment has been booked sucessfully.", $post['docId'] ,$post['userId'] , '2' , $appointId ,$patientuser->device , $patientuser->deviceToken , "your appointment has been booked sucessfully.");
          // }  


          if(!empty($appointId))
          {      
            $response = array('success' => 1, 'message' => trans('labels.appointmentbookSuccessfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function askDoctor(Request $request)  //-- ask doctor -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
      
        try
        {           
          if( empty($post['specialistId']) || empty($post['userId'])  || empty($post['description'])  )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $site_settings = DB::table('site_settings')->select('*')->first();   

          $appointId =DB::table('askdoctor')->insertGetId([
            'specialistId'    =>  $post['specialistId'],
            'userId'   =>  $post['userId'],
            'description' =>  $post['description'],
            'assign_docId' => $site_settings->docId,  
          ]);

          $docuser = User::where('id',$site_settings->docId)->select('*')->first();
          $patientuser = User::where('id',$post['userId'])->select('*')->first();

          if(!empty($docuser->device || !empty($docuser->deviceToken)))
          {
            $this->setnotification("New inquiry request" ,  $patientuser->name." "."has sent you a new inquiry request", $site_settings->docId ,$post['userId'] , '3' , $appointId ,$docuser->device , $docuser->deviceToken , $patientuser->name." "."has sent you a new appointment request");
          }  

          if(!empty($patientuser->device || !empty($patientuser->deviceToken)))
          {
            $this->setnotification("Booking inquiry" , "Thank you for submit inquiry. As soon as possible our doctor conatct with you.", $site_settings->docId ,$post['userId'] , '4' , $appointId ,$patientuser->device , $patientuser->deviceToken , "Thank you for submit inquiry. As soon as possible doctor conatct with you.");
          }  

          if(!empty($appointId))
          {      
            $response = array('success' => 1, 'message' => trans('labels.asktodoctorsuccessfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function bookList(Request $request)  //-- doctor book list -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew); 
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);   
          
        try
        {           
          if(empty($post['userId']))   // doctor id 
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $appointment = DB::table('appointment')->where('doc_id',$post['userId'])->select('*')->orderBy('book_date','desc')->get();  

          $datearray = array();
          foreach ($appointment as $key => $value1){
            if(!in_array($value1->book_date, $datearray))
              $datearray[]=$value1->book_date;
          }
       
          if(!empty($appointment))
          {      
            $re = array();         
              
            foreach($datearray as $value)
            {          
              
              $appoints = DB::table('appointment')->select('*')->where('doc_id',$post['userId'])->where('book_date',$value)->get();   
              $result1= array();        
              foreach($appoints as $appoint)
              {
                $result12['userId'] = !empty($appoint->user_id) ? $appoint->user_id : '0';

                 $userdata = User::select('*')->where('id','=', $appoint->user_id)->first(); 
                 if($appoint->direct=="1")
                 {
                    $result12['name'] = !empty($appoint->name) ? $appoint->name : '';
                 }
                 else
                 {
                    $result12['name'] = !empty($userdata->name) ? $userdata->name : '';
                 }
                
                  $result12['name_ar'] = !empty($userdata->name_ar) ? $userdata->name_ar : '';
                  if ((!empty($userdata->profile_pic)) && (!empty($userdata))) 
                  {
                    $result12['profileImage']=$new.'/public/patient/'.$userdata->profile_pic;  
                  } else {
                    $result12['profileImage']= $new.'/public/image/default-image.jpeg';  
                  }
                $result12['bookId'] = $appoint->id;
                $result12['book_date'] = $appoint->book_date;
                $result12['book_time'] = $appoint->book_time;
                $result12['price'] = $appoint->price;
                $result12['discount'] = $appoint->discount;
                $result12['finalprice'] = $appoint->totalprice;
               
                $result12['status'] = $appoint->status;
                

               if($appoint->status==0){
                $result12['statuscode'] = trans('labels.active');
                  } else if($appoint->status==1){
                $result12['statuscode'] = trans('labels.visited');
                } else{
                $result12['statuscode'] = trans('labels.cancelled');  
                }
                $result1[] = $result12;
                
              }   
              $newapp['date']= $value;   
              $newapp['data'] = $result1;
                          
              $re[] = $newapp;
            }
           
        
            $response = array('success' => 1, 'message' => trans('labels.getbookinglist'), 'result' => $re);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
           
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function visiterBookList(Request $request)  //-- doctor book list -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew); 
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);   
          
        try
        {           
          if(empty($post['userId']))   // doctor id 
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $appointment = DB::table('appointment')->where('doc_id',$post['userId'])->where('status','1')->select('*')->orderBy('book_date','desc')->get();  

          $datearray = array();
          foreach ($appointment as $key => $value1){
            if(!in_array($value1->book_date, $datearray))
              $datearray[]=$value1->book_date;
          }
       
          if(!empty($appointment))
          {      
            $re = array();         
              
            foreach($datearray as $value)
            {          
              
              $appoints = DB::table('appointment')->select('*')->where('doc_id',$post['userId'])->where('book_date',$value)->get();   
              $result1= array();        
              foreach($appoints as $appoint)
              {
                $result12['userId'] = !empty($appoint->user_id) ? $appoint->user_id : '0';

                 $userdata = User::select('*')->where('id','=', $appoint->user_id)->first(); 
                 if($appoint->direct=="1")
                 {
                    $result12['name'] = !empty($appoint->name) ? $appoint->name : '';
                 }
                 else
                 {
                    $result12['name'] = !empty($userdata->name) ? $userdata->name : '';
                 }
                
                  $result12['name_ar'] = !empty($userdata->name_ar) ? $userdata->name_ar : '';
                  if ((!empty($userdata->profile_pic)) && (!empty($userdata))) 
                  {
                    $result12['profileImage']=$new.'/public/patient/'.$userdata->profile_pic;  
                  } else {
                    $result12['profileImage']= $new.'/public/image/default-image.jpeg';  
                  }
                $result12['bookId'] = $appoint->id;
                $result12['book_date'] = $appoint->book_date;
                $result12['book_time'] = $appoint->book_time;
                $result12['price'] = $appoint->price;
                $result12['discount'] = $appoint->discount;
                $result12['finalprice'] = $appoint->totalprice;
               if($appoint->status==0){
                $result12['status'] = trans('labels.active');
                  } else if($appoint->status==1){
                $result12['status'] = trans('labels.visited');
                } else{
                $result12['status'] = trans('labels.cancelled');  
                }
                $result1[] = $result12;
                
              }   
              $newapp['date']= $value;   
              $newapp['data'] = $result1;
                          
              $re[] = $newapp;
            }
           
        
            $response = array('success' => 1, 'message' => trans('labels.getbookinglist'), 'result' => $re);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
           
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function DoctorhistoryList(Request $request)  //-- doctor history list -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew); 
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);   
          
        try
        {           
          if(empty($post['userId']))   // doctor id 
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $appointment = DB::table('appointment')->where('doc_id',$post['userId'])->whereIN('status',array('1','2'))->select('*')->orderBy('book_date','desc')->get();  


          $datearray = array();
          foreach ($appointment as $key => $value1){
            if(!in_array($value1->book_date, $datearray))
              $datearray[]=$value1->book_date;
          }
       
          if(!empty($appointment))
          {      
            $re = array();         
              
            foreach($datearray as $value)
            {          
              
              $appoints = DB::table('appointment')->select('*')->whereIN('status',array('1','2'))->where('doc_id',$post['userId'])->where('book_date',$value)->get();   
              $result1= array();        
              foreach($appoints as $appoint)
              {
                $result12['userId'] = !empty($appoint->user_id) ? $appoint->user_id : '0';

                 $userdata = User::select('*')->where('id','=', $appoint->user_id)->first(); 
                 if($appoint->direct=="1")
                 {
                    $result12['name'] = !empty($appoint->name) ? $appoint->name : '';
                 }
                 else
                 {
                    $result12['name'] = !empty($userdata->name) ? $userdata->name : '';
                 }
                
                  $result12['name_ar'] = !empty($userdata->name_ar) ? $userdata->name_ar : '';
                  if ((!empty($userdata->profile_pic)) && (!empty($userdata))) 
                  {
                    $result12['profileImage']=$new.'/public/patient/'.$userdata->profile_pic;  
                  } else {
                    $result12['profileImage']= $new.'/public/image/default-image.jpeg';  
                  }
                $result12['bookId'] = $appoint->id;
                $result12['heartRate'] = $appoint->heart_rate;
                $result12['pulse'] = $appoint->pulse;
                $result12['weight'] = $appoint->weight;
                $result12['book_date'] = $appoint->book_date;
                $result12['book_time'] = $appoint->book_time;
                $result12['price'] = $appoint->price;
                $result12['discount'] = $appoint->discount;
                $result12['finalprice'] = $appoint->totalprice;
               if($appoint->status==0){
                $result12['status'] = "0";
                  } else if($appoint->status==1){
                $result12['status'] = "1";
                } else{
                $result12['status'] = "2";  
                }
                $result1[] = $result12;
                
              }   
              $newapp['date']= $value;   
              $newapp['data'] = $result1;
                          
              $re[] = $newapp;
            }
           
        
            $response = array('success' => 1, 'message' => trans('labels.getbookinglist'), 'result' => $re);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
           
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function bookDetail(Request $request)  //-- doctor book detail -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
          
        try
        {           
          if(empty($post['bookId']))   // doctor id 
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

        //   $appoint = DB::table('appointment')->where('id',$post['bookId'])->select('*')->first();  
          $appoint = Appointment::with('hasManyAppointmentDocuments')->find($post['bookId']);

          
          if(!empty($appoint))
          {          
            $result['userId'] = !empty($appoint->user_id) ? $appoint->user_id :'' ;
            $result['docId'] = $appoint->doc_id;

            $userdata = User::select('*')->where('id','=', $appoint->user_id)->first();

            if($appoint->direct=="1")
           {
              $result['name'] = !empty($appoint->name) ? $appoint->name : '';
              $result['phone'] = !empty($appoint->phone) ? $appoint->phone : '';
              $result['email'] = !empty($appoint->email) ? $appoint->email : '';
           }
           else
           {
              $result['name'] = !empty($userdata->name) ? $userdata->name : '';
              $result['phone'] = $userdata->phone ? $userdata->phone : '';
              $result['email'] = $userdata->email ? $userdata->email : '';
           } 
            $appointmentDocumentsObjs = $appoint->hasManyAppointmentDocuments ? $appoint->hasManyAppointmentDocuments : [];
            $result['appoinment_docs'] = [];
            if(count($appointmentDocumentsObjs)){
                foreach($appointmentDocumentsObjs as $appointmentDocumentsObj){
                    $appoinment_docs = [];
                    $appoinment_docs['doc_name'] = $appointmentDocumentsObj->doc_name ? substr($appointmentDocumentsObj->doc_name, 11) : '';
                    $appoinment_docs['doc_url'] = $appointmentDocumentsObj->doc_name ? $new.'/public/appointment_files/'.$appointmentDocumentsObj->doc_name : '';
                    $result['appoinment_docs'][] = $appoinment_docs;         
                }
            }
          
          $result['name_ar'] = !empty($userdata->name_ar) ? $userdata->name_ar : '';        
           
            $result['role'] = !empty($userdata->role) ? $userdata->role: '';
            $result['status'] = !empty($userdata->status) ? $userdata->status: '';
            $result['address'] =  !empty($userdata->address) ? $userdata->address: '';
            $result['location'] = !empty($userdata->location) ? $userdata->location: '0';
            $result['latitude'] =  !empty($userdata->latitude) ? $userdata->latitude: '0';
            $result['longitude'] = !empty($userdata->longitude) ? $userdata->longitude: '0';

            if (!empty($userdata->profile_pic))
            {
              $result['profileImage']=$new.'/public/patient/'.$userdata->profile_pic;  
            } else {
              $result['profileImage']= $new.'/public/image/default-image.jpeg';  
            }
            $result['heartRate'] = $appoint->heart_rate;
            $result['pulse'] = $appoint->pulse;
            $result['weight'] = $appoint->weight;
            $result['book_date'] = $appoint->book_date;
            $result['book_time'] = $appoint->book_time;
            $result['price'] = $appoint->price;
            $result['discount'] = $appoint->discount;
            $result['finalprice'] = $appoint->totalprice;    
            $result['notes'] = $appoint->notes ? $appoint->notes : '';    
            
            if($appoint->status==0){
          $result['status'] = trans('labels.active');
            } else if($appoint->status==1){
          $result['status'] = trans('labels.visited');
          } else{
          $result['status'] = trans('labels.cancelled');  
          }

            if($appoint->direct == "1")
            {
                $result['bloodTypeId'] =  '';
                $result['bloodType'] = ''; 
                $result['dateOfBirth'] = ''; 
                $result['chronicDiseases'] = [];
                $result['dragsAllergy'] = [];
                $result['foodAllergy'] = [];
            }
            else
            {
              $user = User::with('hasOnePatient','hasManyFoodallergy','hasManyDragsallergy','hasManyChronicDiseases')->find($appoint->user_id);
              $patientObj = $user->hasOnePatient ? $user->hasOnePatient : '';
              $bloodTypeObj = $patientObj ? $patientObj->hasOneBloodType : '';
              $result['bloodTypeId'] = $patientObj ? $patientObj->blood_type_id : '';
              $result['bloodType'] = $bloodTypeObj ? $bloodTypeObj->name : ''; 
              $result['dateOfBirth'] = $patientObj ? $patientObj->birth_date : ''; 
              $foodAllergys = $user->hasManyFoodallergy ? $user->hasManyFoodallergy : [];
              $dragsAllergys = $user->hasManyDragsallergy ? $user->hasManyDragsallergy : [];
              $chronicDiseasess = $user->hasManyChronicDiseases ? $user->hasManyChronicDiseases : [];
              $result['chronicDiseases'] = [];
              foreach($chronicDiseasess as $chronicDiseases){
                $oneChronicDiseases = $chronicDiseases->hasOneChronicDiseases ? $chronicDiseases->hasOneChronicDiseases : '';
                $chronicDiseaseObj = [];
                $chronicDiseaseObj['id'] = $chronicDiseases->allergy_id ? $chronicDiseases->allergy_id : '';
                $chronicDiseaseObj['name'] = $oneChronicDiseases ? $oneChronicDiseases->name : '';
                $result['chronicDiseases'][] = $chronicDiseaseObj;
              }
              $result['dragsAllergy'] = [];
              foreach($dragsAllergys as $dragsAllergy){
                $oneDragsAllergy = $dragsAllergy->hasOneDragsAllergy ? $dragsAllergy->hasOneDragsAllergy : '';
                $dragsAllergyObj = [];
                $dragsAllergyObj['id'] = $dragsAllergy->allergy_id ? $dragsAllergy->allergy_id : '';
                $dragsAllergyObj['name'] = $oneDragsAllergy ? $oneDragsAllergy->name : '';
                $result['dragsAllergy'][] = $dragsAllergyObj;
              }
              $result['foodAllergy'] = [];
              foreach($foodAllergys as $foodAllergy){
                $oneFoodAllergy = $foodAllergy->hasOneFoodAllergy ? $foodAllergy->hasOneFoodAllergy : '';
                $foodAllergyObj = [];

                $foodAllergyObj['id'] = $foodAllergy->allergy_id ? $foodAllergy->allergy_id : '';
                $foodAllergyObj['name'] = $oneFoodAllergy ? $oneFoodAllergy->name : '';
                $result['foodAllergy'][] = $foodAllergyObj;
              } 
      

            }

          
            $response = array('success' => 1, 'message' => trans('labels.getbookingdetail'), 'result' => $result);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function patientBookList(Request $request)  //-- patient  book list -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
      
        try
        {           
          if(empty($post['userId']))   // patient user id 
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $appointment = DB::table('appointment')->where('user_id',$post['userId'])->select('*')->orderBy('book_date','asc')->get();  

          $datearray = array();
          foreach ($appointment as $key => $value1){
            if(!in_array($value1->book_date, $datearray))
              $datearray[]=$value1->book_date;
          }
       
          if(!empty($appointment))
          {      
            $re = array();         
              
            foreach($datearray as $value)
            {          
              
              $appoints = DB::table('appointment')->select('*')->where('user_id',$post['userId'])->where('book_date',$value)->get();   
              $result1= array();        
              foreach($appoints as $appoint)
              {
                $result12['userId'] = $appoint->user_id;

                 $userdata = User::select('*')->where('id','=', $appoint->doc_id)->first(); 
                  $result12['name'] = !empty($userdata->name) ? $userdata->name : '';
                  $result12['name_ar'] = !empty($userdata->name_ar) ? $userdata->name_ar : '';
                  if ($userdata->profile_pic != "") 
                  {
                    $result12['profileImage']=$new.'/public/profileImage/'.$userdata->profile_pic;  
                  } else {
                    $result12['profileImage']= $new.'/public/image/default-image.jpeg';  
                  }
                $result12['bookId'] = $appoint->id;
                $result12['book_date'] = $appoint->book_date;
                $result12['book_time'] = $appoint->book_time;
                $result12['price'] = $appoint->price;
                $result12['discount'] = $appoint->discount;
                 $result12['status'] = $appoint->status;

                //       if($appoint->status==0){
                // $result12['status'] = trans('labels.active');
                //   } else if($appoint->status==1){
                // $result12['status'] = trans('labels.visited');
                // } else{
                // $result12['status'] = trans('labels.cancelled');  
                // }
                $result12['finalprice'] = $appoint->totalprice;
               
                $result1[] = $result12;
                
              }   
              $newapp['date']= $value;   
              $newapp['data'] = $result1;
                          
              $re[] = $newapp;
            }
           
        
            $response = array('success' => 1, 'message' => trans('labels.getbookinglist'), 'result' => $re);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
           
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function patientBookDetail(Request $request)  //-- patient book detail -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);  
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
      
        try
        {           
          if(empty($post['bookId']))   // doctor id 
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $appoint = DB::table('appointment')->where('id',$post['bookId'])->select('*')->first();  

          
          if(!empty($appoint))
          {          
            $result['userId'] = $appoint->user_id;
            $result['docId'] = $appoint->doc_id;
            $result['book_date'] = $appoint->book_date;
            $result['book_time'] = $appoint->book_time;
            $result['price'] = $appoint->price;
            $result['discount'] = $appoint->discount;
            $result['finalprice'] = $appoint->totalprice;  

            $userdata = User::select('*')->where('id','=', $appoint->doc_id)->first(); 
            $result['name'] = !empty($userdata->name) ? $userdata->name : '';
            $result['name_ar'] = !empty($userdata->name_ar) ? $userdata->name_ar : '';
            $result['phone'] = $userdata->phone;
            $result['email'] = $userdata->email;
            $result['role'] = $userdata->role;
            $result['status'] = $appoint->status;
            $result['address'] = $userdata->address;
            $result['location'] = !empty($userdata->location) ? $userdata->location : '0';
            $result['latitude'] = !empty($userdata->latitude) ? $userdata->latitude : '0';
            $result['longitude'] =!empty($userdata->longitude) ? $userdata->longitude : '0'; 

            if ($userdata->profile_pic != "") 
            {
              $result['profileImage']=$new.'/public/profileImage/'.$userdata->profile_pic;  
            } else {
              $result['profileImage']= $new.'/public/image/default-image.jpeg';  
            }


              $doctor = DB::table('doctor')->select('*')->where('user_id',$appoint->doc_id)->first();                    
              $doctor_clinic_images = DB::table('doctor_clinic_images')->select('*')->where('user_id',$appoint->doc_id)->get();          
              $doctor_specialist = DB::table('doctor_specialist')->select('*')->where('user_id',$appoint->doc_id)->get();
              $doctor_time = DB::table('doctor_time')->select('*')->where('user_id',$appoint->doc_id)->get();
                $result['certificates'] = !empty($doctor->certificates) ? $doctor->certificates : '';
                $result['averagehour'] = !empty($doctor->averagehour) ? $doctor->averagehour :'';
                $result['hourlyRate'] = !empty($doctor->price_of_ticket) ? $doctor->price_of_ticket :'' ;
                $result['discounts'] = !empty($doctor->discounts) ? $doctor->discounts :'' ;
                $result['discount_rule'] = !empty($doctor->discount_rule) ? $doctor->discount_rule   :'' ;
                $result['instagramLink'] = !empty($doctor->insta_link) ? $doctor->insta_link :'' ;
                $result['facebookLink'] = !empty($doctor->facebook_link) ? $doctor->facebook_link :'' ; 
                $result['info'] = !empty($doctor->info) ? $doctor->info :'' ; 
                $result['yearofexp'] =!empty($doctor->yearofexp) ? $doctor->yearofexp :'0' ; 
            
              
              $result['clinicImg'] = [];
              $cliimage1 =array();
              if (!empty($doctor_clinic_images)) {
                foreach ($doctor_clinic_images as $key => $getcImg) {
                  if ($getcImg->image != "") {
                    $cliimage['id'] =$getcImg->Id;
                    $cliimage['image'] =$new.'/public/clinic/'.$getcImg->image;  
                  } else {
                      $cliimage['id'] =$getcImg->Id;
                      $cliimage['image']= $new.'/public/image/default-image.jpeg';  
                  }
                  $cliimage1[] = $cliimage;
                } 
              }
              $result['clinicImg'] = $cliimage1;
                $result['specialist'] = [];
                  $newspecialist =array();
                  if (!empty($doctor_specialist)) {
                    foreach ($doctor_specialist as $key => $getspe) {
                      $doctor_specialistName = DB::table('specialist')->select('*')->where('id',$getspe->specialist_id)->first();
                      if ($doctor_specialistName->name != "") {
                          $speci['id']= $doctor_specialistName->id;
                          if($post['language']=="en")
                          {
                             $speci['name']= $doctor_specialistName->name;
                          }
                          else
                          {
                             $speci['name']= $doctor_specialistName->name_ar;
                          }
                         
                        
                      } else {
                          $speci['id']=[];
                          $speci['name']= [];
                      }
                      $newspecialist[] = $speci;
                    }   
                  }
                   $result['specialist'] = $newspecialist;
      
            $response = array('success' => 1, 'message' => trans('labels.getbookingdetail'), 'result' => $result);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function rating(Request $request)  //-- rating doctor -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew); 
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);   
      
        try
        {           
          if( empty($post['docId']) || empty($post['userId']) || empty($post['rating']) || empty($post['comment']))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }    

          $results = DB::table('rating')->select('*')->where('doc_id',$post['docId'])->where('user_id',$post['userId'])->first();      

          if(!empty($results))
          {
            $response = array('success' => 0, 'message' => trans('labels.alreadyratethisdoctor'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          } 
         

          $appointId =DB::table('rating')->insertGetId([
            'doc_id'    =>  $post['docId'],
            'user_id'   =>  $post['userId'],
            'rating' =>  $post['rating'],
            'comment' => $post['comment'],
          ]);


          if(!empty($appointId))
          {      
            $response = array('success' => 1, 'message' => trans('labels.ratingSuccessfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function doctorClinicDelete(Request $request)  // delete doctor clicnic image  //done 
    {
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);   
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);      

        try
        {          
            if((!isset($post['imageId'])) || (empty($post['imageId'])))
            {
              $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }

            $user = DB::table('doctor_clinic_images')->where('id', $post['imageId'])->delete();     

            $response = array('success' => 1, 'message' => trans('labels.UserimageDeleteSuccessfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;       
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function patientInsert(Request $request)   // update user profile
    { 
        $post = $request->all();
        $decode = json_decode($post['json_content']);
        
        if(empty($decode->language))
        {
          $decode->language='en';
        }

        App::setLocale($decode->language);  
        try
        {           
            if((empty($decode->userId)) || (empty($decode->name)) || (empty($decode->nameAr)) || (empty($decode->email)) || (empty($decode->phone)) || (empty($decode->address)) || (empty($decode->location)) || (empty($decode->latitude)) || (empty($decode->longitude)) || (empty($decode->bloodType))  || (empty($decode->dateOfBirth)))
            {
              // echo "dsfg";
              $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
            }
            $checkUser = User::where('email',$decode->email)->where('id','!=',$decode->userId)->first();
            if(!empty($checkUser)){
              $response = array('success' => 0, 'message' => trans('labels.emailAlreadyExisting'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
            }
            $newUser = User::find($decode->userId);
            if(empty($newUser)){
              $response = array('success' => 0, 'message' => trans('labels.usernotExisting!'));
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
            }
            $newUser->name = $decode->name;
            $newUser->name_ar = $decode->nameAr;
            $newUser->email = $decode->email;
            // $newUser->password = Hash::make($decode->password);
            $newUser->phone = $decode->phone;
            // $newUser->status = 1;
            // $newUser->registered_at = get_timestamp();
            $newUser->address = $decode->address;
            $newUser->location = $decode->location;
            $newUser->latitude = $decode->latitude;
            $newUser->longitude = $decode->longitude;
            // $newUser->role = 2;
            if(!empty($post['image']))
            {   
                $file = $request->file('image'); 
                $image_name = str_replace(' ', '-', $file->getClientOriginalName());
                $picture = time() . "." . $image_name;
                $destinationPath = public_path('patient/');
                $file->move($destinationPath, $picture); 
                $newUser->profile_pic ? old_file_remove('patient',$newUser->profile_pic) : '';
                $newUser->profile_pic = $picture;
            }
            $newUser->save();
            $newPatient = Patient::where('user_id',$newUser->id)->first();
            $newPatient = !empty($newPatient) ? $newPatient : new Patient;
            $newPatient->user_id = $newUser->id;
            $newPatient->blood_type_id = $decode->bloodType;
            $newPatient->birth_date = $decode->dateOfBirth;
            $newPatient->save();

            // $dragsAllergys = $decode->dragsAllergy;
            $dragsAllergys = $decode->dragsAllergy;
            $this->insertMultipleAllergy($newUser->id ,$type=2 , $dragsAllergys); //id=patientId, type=allergy type, allergys = allergyArray

            $foodAllergys = $decode->foodAllergy;
            $this->insertMultipleAllergy($newUser->id ,$type=1 , $foodAllergys);
            
            $chronicDiseasess = $decode->chronicDiseases;
            $this->insertMultipleAllergy($newUser->id ,$type=3 , $chronicDiseasess);

            $user = User::with('hasOnePatient','hasManyFoodallergy','hasManyDragsallergy','hasManyChronicDiseases')->find($newUser->id);
            $result['id'] = $user->id ? $user->id : ''; 
            $result['name'] = $user->name ? $user->name : ''; 
            $result['nameAr'] = $user->name_ar ? $user->name_ar : ''; 
            $result['email'] = $user->email ? $user->email : ''; 
            // $result['password'] = $user->password ? $user->password : ''; 
            $result['phone'] = $user->phone ? $user->phone : ''; 
            $result['address'] = $user->address ? $user->address : ''; 
            $result['location'] = $user->location ? $user->location : '0'; 
            $result['latitude'] = $user->latitude ? $user->latitude : '0'; 
            $result['longitude'] = $user->longitude ? $user->longitude : '0'; 
            $result['image'] = $user->profile_pic ? file_exists_in_folder('patient',$user->profile_pic) : file_exists_in_folder('patient',''); 
            $patientObj = $user->hasOnePatient ? $user->hasOnePatient : '';
            $bloodTypeObj = $patientObj ? $patientObj->hasOneBloodType : '';
            $result['bloodTypeId'] = $patientObj ? $patientObj->blood_type_id : '';

            $result['bloodType'] = $bloodTypeObj ? $bloodTypeObj->name : ''; 
            $result['dateOfBirth'] = $patientObj ? $patientObj->birth_date : ''; 
            $foodAllergys = $user->hasManyFoodallergy ? $user->hasManyFoodallergy : [];
            $dragsAllergys = $user->hasManyDragsallergy ? $user->hasManyDragsallergy : [];
            $chronicDiseasess = $user->hasManyChronicDiseases ? $user->hasManyChronicDiseases : [];
            $result['chronicDiseases'] = [];
            foreach($chronicDiseasess as $chronicDiseases){
              $oneChronicDiseases = $chronicDiseases->hasOneChronicDiseases ? $chronicDiseases->hasOneChronicDiseases : '';
              $chronicDiseaseObj = [];
              $chronicDiseaseObj['id'] = $chronicDiseases->allergy_id ? $chronicDiseases->allergy_id : '';
              $chronicDiseaseObj['name'] = $oneChronicDiseases ? $oneChronicDiseases->name : '';
              $result['chronicDiseases'][] = $chronicDiseaseObj;
            }
            $result['dragsAllergy'] = [];
            foreach($dragsAllergys as $dragsAllergy){
              $oneDragsAllergy = $dragsAllergy->hasOneDragsAllergy ? $dragsAllergy->hasOneDragsAllergy : '';
              $dragsAllergyObj = [];
              $dragsAllergyObj['id'] = $dragsAllergy->allergy_id ? $dragsAllergy->allergy_id : '';
              $dragsAllergyObj['name'] = $oneDragsAllergy ? $oneDragsAllergy->name : '';
              $result['dragsAllergy'][] = $dragsAllergyObj;
            }
            $result['foodAllergy'] = [];
            foreach($foodAllergys as $foodAllergy){
              $oneFoodAllergy = $foodAllergy->hasOneFoodAllergy ? $foodAllergy->hasOneFoodAllergy : '';
              $foodAllergyObj = [];

              $foodAllergyObj['id'] = $foodAllergy->allergy_id ? $foodAllergy->allergy_id : '';
              $foodAllergyObj['name'] = $oneFoodAllergy ? $oneFoodAllergy->name : '';
              $result['foodAllergy'][] = $foodAllergyObj;
            }
            $response = array('success' => 1, 'message' => trans('labels.patientUpdatedSuccessfully'), 'result'=>$result);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
        
        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }   
    }

    public function insertMultipleAllergy($id ,$type , $allergys = [])
    { 
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

    public function patientDelete(Request $request)
    {   
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);  
        try
        {           
          if(!isset($post['id']) || (empty($post['id'])))
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }
          $patient = User::findOrFail($post['id']);
          
          if(empty($patient))
          {
            $response = array('success' => 0, 'message' => trans('labels.usernotExisting'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }
          $patientDetail = Patient::where('user_id',$post['id'])->first();
          !empty($patientDetail)? $patientDetail->delete() : '';
          
          $userAllergys = UserAllergy::where('user_id',$post['id'])->get();
          foreach($userAllergys as $userAllergy){
              $userAllergy->delete();
          }
          old_file_remove('patient',$patient->profile_pic);
          $patient->delete();

          $response = array('success' => 0, 'message' => trans('labels.userDeletedSuccessfully'));
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function doctorDelete(Request $request)
    {
        $post = $request->all();
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew);   
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);      

        try
        {       

            if((!isset($post['userId'])) || (empty($post['userId'])))
            {
              $response = array('success' => 0, 'message' => 'Please fill in all the required fields.');
              echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }

            $userId = $post['userId'];
            $user = DB::table('users')->where('id', $userId)->delete();
            $user = DB::table('doctor')->where('user_id', $userId)->delete();
            $user = DB::table('doctor_clinic_images')->where('user_id', $userId)->delete();
            $user = DB::table('doctor_specialist')->where('user_id', $userId)->delete();
            $user = DB::table('doctor_time')->where('user_id', $userId)->delete();

            $response = array('success' => 1, 'message' => trans('labels.User Delete Successfully'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;       
        }
        catch(Exception $e)
        {
            $response = array('success' => 0, 'message' => $e->getMessage());
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function notificationList(Request $request)
    {
      $input = file_get_contents('php://input');
      $post = json_decode($input, true);   
      $urlnew = url(''); 
      $new = str_replace('index.php', '', $urlnew);   
      if(empty($post['language']))
      {
        $post['language']='en';
      }      
      App::setLocale($post['language']);   

      try
      {   

        if(empty($post['userId']))
        {
          $response = array('success' => 0, 'message' => 'Please fill in all the required fields.');
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }

        $users = user::where('id',$post['userId'])->select('*')->first();

        if(!empty($users))
        {
          $re= array();
          if($users->role==1)
          { 
            $table= DB::Table('notification')->select('*')->where('doc_id',$users->id)->whereIn('type',array(1,3))->get(); 
            if(count($table) > 0)
            {
                foreach($table as $noti)
                {
                  $result =array();
                   $result['id'] = $noti->id;
                  $result['title'] = $noti->title;
                  $result['text'] = $noti->text;
                  $result['doc_id'] = $noti->doc_id;
                  $result['user_id'] = $noti->user_id;
                  $result['type'] = $noti->type;
                  $result['bookid'] = $noti->bookid;
                  $result['isread'] = $noti->isread;
                  $re[] =$result;
                }
            }
            else{
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            }    
          }
          else
          {
            $table= DB::Table('notification')->select('*')->where('user_id',$users->id)->whereIn('type',array(2,4))->get(); 
            if(count($table) > 0)
            {
              foreach($table as $noti)
              {
                $result =array();
                $result['id'] = $noti->id;
                $result['title'] = $noti->title;
                $result['text'] = $noti->text;
                $result['doc_id'] = $noti->doc_id;
                $result['user_id'] = $noti->user_id;
                $result['type'] = $noti->type;
                $result['bookid'] = $noti->bookid;
                $result['isread'] = $noti->isread;
                $re[] =$result;
              }
            }
            else{
              $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
                echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
            } 

          }

          $response = array('success' => 1, 'message' => trans('labels.getnotificationList'),'result' => $re );
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }
        else
        {
          $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
                
      }
      catch(Exception $e)
      {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
      } 
    }

    public function isRead(Request $request)
    {
      $input = file_get_contents('php://input');
      $post = json_decode($input, true);   
      $urlnew = url(''); 
      $new = str_replace('index.php', '', $urlnew);   
      if(empty($post['language']))
      {
        $post['language']='en';
      }      
      App::setLocale($post['language']);   

      try
      {   

        if(empty($post['Id']) || empty($post['flag']) )
        {
          $response = array('success' => 0, 'message' => 'Please fill in all the required fields.');
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }

        $datauser = DB::table('notification')->where('id','=',$post['Id'])->update([
          'isread'    => $post['flag'],                
        ]); 

        if($datauser)
        {       
          $response = array('success' => 1);
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }
        else
        {
          $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
                
      }
      catch(Exception $e)
      {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
      } 
    }

    public function setnotification($title , $text, $doc_id ,$user_id , $type , $bookid ,$device , $deviceToken , $message) 
    {

        $notifications = DB::table('notification')->insertGetId([
          'title' => $title,
          'text' => $text,
          'doc_id'=>  $doc_id,
          'user_id' => $user_id,
          'type'=>  $type,
          'bookid' => $bookid,   
          'isread' => 0 ,                                        
        ]);  

        $url = 'https://fcm.googleapis.com/fcm/send'; 
    

        if($device==1)
        {             
          $fields = array (
            'registration_ids' => array ($deviceToken),
            'data' => array (
            "title" => $title,
            "id"=>$notifications,
            "bookId"=>$bookid,
            "userId"=>$user_id,
            "docId"=>$doc_id,
            "message" => $message,
            "nType"=>$type
            )
          );
        }
        else
        {               
          $fields = array (
            'registration_ids' => array ($deviceToken),
            'notification' => array (
            "sound" => "default",
            "title" =>  $title,
            "id"=>$notifications,
            "bookId"=>$bookid,
            "userId"=>$user_id,
            "docId"=>$doc_id,
            "body" => $message,
            "nType"=>$type
            )
          );
        }

        $fields = json_encode ($fields);
        $headers = array ('Authorization: key=' ."AAAAH58Ibk8:APA91bH_mLch8ABm8q-texuyRT7teXkAYHUmgJvMEWHgfdG26aF2KCZQK3dQb2f-ALhJ23fggc7CluQpcoJqmUK2CgeK4pMZ0vz49AG8PVP2b7dzh9q7pBGl6YxIATY253hDaCIVgojZ",'Content-Type: application/json');
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
    }   
    
    
    public function changebookingstatus(Request $request)
    {
      $decode = $request->all();
      
      $post = json_decode($decode['json_content']);
      $urlnew = url(''); 
      $new = str_replace('index.php', '', $urlnew);  
      //   if(empty($post['language']))
      //   {
      //      $post['language']='en';
      //   }
      
      if(empty($post->language))
      {
          $post->language='en';
      }
      App::setLocale($post->language);    

      try
      {   
        if(empty($post->id) || !isset($post->notes))
        {
          $response = array('success' => 0, 'message' => 'Please fill in all the required fields.');
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }
        $appointmentData['status'] = $post->status;
        $appointmentData['heart_rate'] = isset($post->heartRate) ? $post->heartRate : '' ;
        $appointmentData['pulse'] = isset($post->pulse) ? $post->pulse : '';
        $appointmentData['weight'] = isset($post->weight) ? $post->weight : '';
        if($post->status == 1){
            $appointmentData['notes'] =$post->notes;
           
            if(count($request->files)>0){
            $files = $request->file('files'); 
            
            if(count($files))
            {   
                foreach($files as $file){
                    $file_name = str_replace(' ', '-', $file->getClientOriginalName());
                    $fileName = time() . "." . $file_name;
                    $destinationPath = public_path('appointment_files/');
                    $file->move($destinationPath, $fileName); 
                    $appointmentDocuments = new AppointmentDocuments;
                    $appointmentDocuments->doc_name = $fileName;
                    $appointmentDocuments->appointment_id = $post->id;
                    $appointmentDocuments->save();
                }
            }
            }
        }
        $datauser = DB::table('appointment')->where('id',$post->id)->update($appointmentData);
        if($datauser)
        {       
          $response = array('success' => 1,'message'=>trans('labels.bookingStatusUpdated'));
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }
        else
        {
          $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
                
      }
      catch(Exception $e)
      {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
      } 
    }

    public function transferbooking(Request $request)
    {
      $input = file_get_contents('php://input');
      $post = json_decode($input, true);   
     
      $urlnew = url(''); 
      $new = str_replace('index.php', '', $urlnew);   
      if(empty($post['language']))
      {
        $post['language']='en';
      }      
      App::setLocale($post['language']);     

      try
      {   
          
         if( !isset($post['id']) || !isset($post['senderId'])  || !isset($post['docId']) || !isset($post['trans_type']) )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }
         if( $post['id']=='' || $post['senderId']==''  || $post['docId']=='' || $post['trans_type']=='' )
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          } 
        
        $datauser = DB::table('appointment_transfer')->insertGetId([
          'appointment_id' => $post['id'],
          'senderId' => $post['senderId'],
          'docId'=>  $post['docId'],
          'type' => $post['trans_type'],
                                               
        ]);
        
        if($datauser)
        {       
          $response = array('success' => 1,'message'=>trans('labels.transfersuccessfull'));
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }
        else
        {
          $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        }      
                
      }
      catch(Exception $e)
      {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
      } 
    }
    
    public function transferbookList(Request $request)  //-- doctor book list -done
    {            
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);   
        $urlnew = url(''); 
        $new = str_replace('index.php', '', $urlnew); 
        if(empty($post['language']))
        {
          $post['language']='en';
        }      
        App::setLocale($post['language']);   
          
        try
        {           
          if(empty($post['userId']))   // doctor id 
          {
            $response = array('success' => 0, 'message' => trans('labels.pleasefillallrequired'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;     
          }        

          $appointment = DB::table('appointment')->select('appointment.*','appointment_transfer.type','appointment_transfer.senderId')->join('appointment_transfer','appointment_transfer.appointment_id','=','appointment.id')->where('appointment_transfer.docId',$post['userId'])->orderBy('appointment.book_date','desc')->get(); 

          $datearray = array();
          foreach ($appointment as $key => $value1){
            if(!in_array($value1->book_date, $datearray))
              $datearray[]=$value1->book_date;
          }
       
          if(!empty($appointment))
          {      
            $re = array();         
              
            foreach($datearray as $value)
            {          
              
              $appoints = DB::table('appointment')->select('appointment.*','appointment_transfer.type','appointment_transfer.senderId')->join('appointment_transfer','appointment_transfer.appointment_id','=','appointment.id')->where('appointment_transfer.docId',$post['userId'])->where('appointment.book_date',$value)->get();   
              $result1= array();        
              foreach($appoints as $appoint)
              {
                $result12['userId'] = !empty($appoint->user_id) ? $appoint->user_id : '0';

                 $userdata = User::select('*')->where('id','=', $appoint->user_id)->first(); 
                 if($appoint->direct=="1")
                 {
                    $result12['name'] = !empty($appoint->name) ? $appoint->name : '';
                 }
                 else
                 {
                    $result12['name'] = !empty($userdata->name) ? $userdata->name : '';
                 }
                
                  $result12['name_ar'] = !empty($userdata->name_ar) ? $userdata->name_ar : '';
                  if ((!empty($userdata->profile_pic)) && (!empty($userdata))) 
                  {
                    $result12['profileImage']=$new.'/public/patient/'.$userdata->profile_pic;  
                  } else {
                    $result12['profileImage']= $new.'/public/image/default-image.jpeg';  
                  }
                $result12['bookId'] = $appoint->id;
                $result12['heartRate'] = $appoint->heart_rate;
                $result12['pulse'] = $appoint->pulse;
                $result12['weight'] = $appoint->weight;
                $result12['book_date'] = $appoint->book_date;
                $result12['book_time'] = $appoint->book_time;
                $result12['price'] = $appoint->price;
                $result12['discount'] = $appoint->discount;
                $result12['finalprice'] = $appoint->totalprice;
               if($appoint->status==0){
        $result12['status'] = trans('labels.active');
          } else if($appoint->status==1){
        $result12['status'] = trans('labels.visited');
        } else{
        $result12['status'] = trans('labels.cancelled');  
        }
                
        $senderdata = User::select('*')->where('id','=', $appoint->senderId)->first(); 
        $result12['sendByname'] = $senderdata->name;
        $result12['sendByname_ar'] = $senderdata->name_ar;
        $result12['transType'] = $appoint->type;
        $result12['notes'] = !empty($appoint->notes) ? $appoint->notes : '';
        
        $result12['appoinment_docs'] = [];
        
        $appointsdocuments = DB::table('appointment_documents')->select('*')->where('appointment_id',$appoint->id)->get(); 
        
            if(count($appointsdocuments)>0){
                foreach($appointsdocuments as $appointmentDocumentsObj){
                    $appoinment_docs = [];
                    $appoinment_docs['doc_name'] = $appointmentDocumentsObj->doc_name ? substr($appointmentDocumentsObj->doc_name, 11) : '';
                    $appoinment_docs['doc_url'] = $appointmentDocumentsObj->doc_name ? $new.'/public/appointment_files/'.$appointmentDocumentsObj->doc_name : '';
                    $result12['appoinment_docs'][] = $appoinment_docs;         
                }
            }
            
                $result1[] = $result12;
              }   
              $newapp['date']= $value;   
              $newapp['data'] = $result1;
                          
              $re[] = $newapp;
            }
           
        
            $response = array('success' => 1, 'message' => trans('labels.getbookinglist'), 'result' => $re);
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit; 
           
          }
          else
          {
            $response = array('success' => 0, 'message' => trans('labels.noResultFound'));
            echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
          }

        }
        catch(Exception $e)
        {
          $response = array('success' => 0, 'message' => $e->getMessage());
          echo json_encode($response,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_AMP);exit;
        } 
    }

    public function cronjob(Request $request)  //for the unblock user  
    {           
        $nowdata = date('yy-mm-dd');                      
        $senderdata = User::select('*')->where('status','=',0)->where('to_date','>=',$nowdata)->get();
        foreach($senderdata as $val)
        {
            $datauser = User::where('id','=',$val->id)->update([
                'status' => 1,                
            ]); 
        }     
        
        echo "1"; exit;
    }
 
}

?>