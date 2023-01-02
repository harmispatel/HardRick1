<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Appointment;



class AppointmentController extends Controller
{
    //
     // Display a listing of the Appointment
     public function index()
     {
         return view('admin.Appointment.Appointment_list');
     }

       // Load bloodtype Data.
       public function loadAppointmentData(Request $request)
       {
           if ($request->ajax()) {
               $AskDoctor = Appointment::with(['user','doctor'])->get();
               return DataTables::of($AskDoctor)
               ->addIndexColumn()
               
               ->addColumn('username', function ($row) {
                    $username = isset($row->user['name']) ? $row->user['name'] : "-" ;  
                   return $username;
               })
               ->addColumn('location', function ($row){
                    $location = isset($row->user['location']) ? $row->user['location'] : "-";
                    return $location;
               })
               ->addColumn('doctor', function ($row) {
                   return $row->doctor['name'];
               })
               ->addColumn('checkbox', function ($row) {
                   $bloodtype_id = isset($row->id) ? $row->id : '';
  
                   return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$bloodtype_id.'" aria-label="...">';
               })
               ->rawColumns(['checkbox','username','doctor','location'])
               ->make(true);
           }
       }

}
