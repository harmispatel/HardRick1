<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\{AskDoctor, User};

class AskDoctorController extends Controller
{
    // Display a listing of the BloodType
    public function index()
    {
        return view('admin.Askdoctor.Askdoctor_list');
    }

     // Load bloodtype Data.
     public function loadAskdoctorData(Request $request)
     {
         if ($request->ajax()) {
             $AskDoctor = AskDoctor::with(['specialist','user','doctor'])->get();

             $doctorsData = User::where('role', 1)->get();
             return DataTables::of($AskDoctor)
             ->addIndexColumn()
             ->addColumn('specialist', function ($row) {
                 return $row->specialist['name'];
             })
             ->addColumn('user', function ($row) {
                 return $row->user['name'];
             })
             ->addColumn('doctor', function ($row) use ($doctorsData) {
                 $html='';
                 $html.='<select class="form-select" onchange="assign_doc(this.value,'.$row->id.')">';
                 foreach ($doctorsData as  $value) {
                     $html.='<option value="'.$value->id.'"';
                     if ($row->doctor['id'] == $value->id) {
                         $html .="selected";
                     }
                     $html .= '>'.$value->name.'</option>';
                 }
                 $html.='</select>';
                 return $html;
             })

             ->addColumn('checkbox', function ($row) {
                 $bloodtype_id = isset($row->id) ? $row->id : '';

                 return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$bloodtype_id.'" aria-label="...">';
             })
             ->rawColumns(['checkbox','specialist','user','doctor'])
             ->make(true);
         }
     }

     public function assign_doc(Request $request)
     {
         $id = $request->askdId;
         $doctor = $request->assigndoc;
         try {
             $askdoctor = AskDoctor::find($id);
             $askdoctor->assign_docId = $doctor;
             $askdoctor->update();
             return $this->sendResponse(true, "Doctor has been Changed Successfully...");
         } catch (\Throwable $th) {
             return $this->sendResponse(false, "500, Internal Server Error!");
         }
     }
}
