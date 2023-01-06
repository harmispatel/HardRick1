<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebserviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/registration',[WebserviceController::class,'registration']);
Route::post('/applogin',[WebserviceController::class,'applogin']);
Route::post('/getuserinfo',[WebserviceController::class,'getuserinfo']);



Route::get('/cronjob',[WebserviceController::class,'cronjob']);
Route::post('/chnagePassword',[WebserviceController::class,'chnagePassword']);
Route::post('/forgotPwd',[WebserviceController::class,'forgotPwd']);
Route::post('/bloodType',[WebserviceController::class,'bloodType']);
Route::post('/specialistType',[WebserviceController::class,'specialistType']);

Route::post('/allTypeList',[WebserviceController::class,'allTypeList']);
Route::post('/doctorInsert',[WebserviceController::class,'doctorInsert']);
Route::post('/doctorUpdate',[WebserviceController::class,'doctorUpdate']);
Route::post('/doctorDelete',[WebserviceController::class,'doctorDelete']);
Route::post('/doctorList',[WebserviceController::class,'doctorList']);
Route::post('/foodAllergy',[WebserviceController::class,'foodAllergy']);
Route::post('/drugAllergy',[WebserviceController::class,'drugAllergy']);
Route::post('/chronicDiseases',[WebserviceController::class,'chronicDiseases']);
Route::post('/doctorDetail',[WebserviceController::class,'doctorDetail']);
Route::post('/bookAppointment',[WebserviceController::class,'bookAppointment']);
Route::post('/doctorClinicDelete',[WebserviceController::class,'doctorClinicDelete']);
Route::post('/askDoctor',[WebserviceController::class,'askDoctor']);
Route::post('/bookList',[WebserviceController::class,'bookList']);
Route::post('/patientBookList',[WebserviceController::class,'patientBookList']);
Route::post('/bookDetail',[WebserviceController::class,'bookDetail']);
Route::post('/patientBookDetail',[WebserviceController::class,'patientBookDetail']);
Route::post('/rating',[WebserviceController::class,'rating']);
Route::post('/hospital',[WebserviceController::class,'hospital']);
Route::post('/userchatList',[WebserviceController::class,'userchatList']);
Route::post('/notificationList',[WebserviceController::class,'notificationList']);
Route::post('/doctorbookAppointment',[WebserviceController::class,'doctorbookAppointment']);
Route::post('/isRead',[WebserviceController::class,'isRead']);
Route::post('/cancelAppointment',[WebserviceController::class,'cancelAppointment']);
Route::post('/visiterBookList',[WebserviceController::class,'visiterBookList']);
Route::post('/DoctorhistoryList',[WebserviceController::class,'DoctorhistoryList']);


Route::post('/patientInsert',[WebserviceController::class,'patientInsert']);
Route::post('/patientUpdate',[WebserviceController::class,'patientUpdate']);
Route::post('/patientDelete',[WebserviceController::class,'patientDelete']);

Route::post('/changebookingstatus',[WebserviceController::class,'changebookingstatus']);
Route::post('/transferbooking',[WebserviceController::class,'transferbooking']);
Route::post('/transferbookList',[WebserviceController::class,'transferbookList']);
Route::post('/verifymobile',[WebserviceController::class,'verifyMobile']);








