<?php

use App\Http\Controllers\Admin\{DashboardController, ChronicDiseasesController, BloodTypeController, DragsAllergyController, FoodAllergyController, HospitalController, SubscriptionController, SpecialistController, AskDoctorController};
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\{Auth, Route};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Frontend
Route::get('/', function ()
{
    return view('frontend.welcome');
});

// ADMIN ROUTES
Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'admin'], function()
{
    Route::get('/', function ()
    {
        return redirect()->route('adminlogin');
    });

    Route::get('/login',[LoginController::class,'adminLogin'])->name('adminlogin');

    Route::group(['middleware' => 'is_admin'], function ()
    {
        // Dashboard
        Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');

        // Chronic diseases
        Route::get('Chronic-diseases',[ChronicDiseasesController::class,'index'])->name('Chronic-disease');
        Route::get('/Chronic-diseases-data', [ChronicDiseasesController::class,'loadChronicDiseasesData'])->name('loadChronicDiseases-data');
        Route::post('/store-Chronic-diseases', [ChronicDiseasesController::class,'store'])->name('store-Chronic-diseases');
        Route::post('/delete-Chronic-diseases', [ChronicDiseasesController::class,'destroy'])->name('delete-Chronic-diseases');
        Route::post('/edit-Chronic-diseases', [ChronicDiseasesController::class,'edit'])->name('edit-Chronic-diseases');

        // Blood Type
        Route::get('blood-type',[BloodTypeController::class,'index'])->name('blood-type');
        Route::get('/Blood-type-data', [BloodTypeController::class,'loadBloodtypeData'])->name('loadBloodtype-data');
        Route::post('/store-Blood-type', [BloodTypeController::class,'store'])->name('store-Blood-type');
        Route::post('/delete-Blood-type', [BloodTypeController::class,'destroy'])->name('delete-Blood-type');
        Route::post('/edit-Blood-type', [BloodTypeController::class,'edit'])->name('edit-Blood-type');

        // Drags Allergy
        Route::get('Drags-allergy',[DragsAllergyController::class,'index'])->name('Drags-allergy');
        Route::get('/Drags-allergy-data', [DragsAllergyController::class,'loadDeagsallergyData'])->name('loadDeagsallrgy-data');
        Route::post('/store-Drags-allergy', [DragsAllergyController::class,'store'])->name('store-Drags-allergy');
        Route::post('/delete-Drags-allergy', [DragsAllergyController::class,'destroy'])->name('delete-Drags-allergy');
        Route::post('/edit-Drags-allergy', [DragsAllergyController::class,'edit'])->name('edit-Drags-allergy');
        
        // Food Allergy
        Route::get('Food-allergy',[FoodAllergyController::class,'index'])->name('Food-allergy');
        Route::get('/Food-allergy-data', [FoodAllergyController::class,'loadFoodallergyData'])->name('loadFoodallrgy-data');
        Route::post('/store-Food-allergy', [FoodAllergyController::class,'store'])->name('store-Food-allergy');
        Route::post('/delete-Food-allergy', [FoodAllergyController::class,'destroy'])->name('delete-Food-allergy');
        Route::post('/edit-Food-allergy', [FoodAllergyController::class,'edit'])->name('edit-Food-allergy');

        // hospital
        Route::get('Hospital',[HospitalController::class,'index'])->name('Hospital');
        Route::get('/Hospital-data', [HospitalController::class,'loadHospitalData'])->name('loadHospital-data');
        Route::post('/store-Hospital', [HospitalController::class,'store'])->name('store-Hospital');
        Route::post('/delete-Hospital', [HospitalController::class,'destroy'])->name('delete-Hospital');
        Route::post('/edit-Hospital', [HospitalController::class,'edit'])->name('edit-Hospital');

        // Subscription
         Route::get('Subscription',[SubscriptionController::class,'index'])->name('Subscription');
         Route::get('/Subscription-data', [SubscriptionController::class,'loadSubscriptionData'])->name('loadSubscription-data');
         Route::post('/store-Subscription', [SubscriptionController::class,'store'])->name('store-Subscription');
         Route::post('/delete-Subscription', [SubscriptionController::class,'destroy'])->name('delete-Subscription');
         Route::post('/edit-Subscription', [SubscriptionController::class,'edit'])->name('edit-Subscription');

        // Specialist
         Route::get('Specialist',[SpecialistController::class,'index'])->name('Specialist');
         Route::get('/Specialist-data', [SpecialistController::class,'loadSpecialistData'])->name('loadSpecialist-data');
         Route::post('/store-Specialist', [SpecialistController::class,'store'])->name('store-Specialist');
         Route::post('/delete-Specialist', [SpecialistController::class,'destroy'])->name('delete-Specialist');
         Route::post('/edit-Specialist', [SpecialistController::class,'edit'])->name('edit-Specialist');

        // Askdoctor
        Route::get('Askdoctor',[AskDoctorController::class,'index'])->name('Askdoctor');
        Route::get('/Askdoctor-data', [AskDoctorController::class,'loadAskdoctorData'])->name('loadAskdoctor-data');
        Route::post('/Askdoctor-change', [AskDoctorController::class,'assign_doc'])->name('Askdoctor-change');


        

        


        // Logout Admin
        Route::get('/logout',[DashboardController::class,'adminLogout'])->name('adminlogout');

    });
});
