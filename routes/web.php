<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

        // Logout Admin
        Route::get('/logout',[DashboardController::class,'adminLogout'])->name('adminlogout');

    });
});
