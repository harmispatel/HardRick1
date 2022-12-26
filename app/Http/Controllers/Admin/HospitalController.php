<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Hospital;
use App\Http\Requests\HospitalRequest;


class HospitalController extends Controller
{
    //
    public function index()
    {
        return view('admin.Hospital.Hospital_list');
    }

       // Load Hospital Data.
       public function loadHospitalData(Request $request)
       {
           if ($request->ajax()) {
               $Hospital = Hospital::get();

               return DataTables::of($Hospital)
               ->addIndexColumn()
               ->addColumn('actions', function ($row) {
                   $hospital_id = isset($row->id) ? $row->id : 0;
                   $action_html = '';
                   $action_html .= '<a onclick="openaddmodel(\'Edit-Hospital\','.$hospital_id.',\'edit-Hospital\',\'#newHospitalForm\',\'#hospitalModalLabel\',\'#hospitalModal\',\'update-Hospital\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                   $action_html .= '<a onclick="deletedata(\'1\','.$hospital_id.',\'delete-Hospital\',\'#HospitalTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                   return $action_html;
               })
               ->addColumn('checkbox', function ($row) {
                   $hospital_id = isset($row->id) ? $row->id : '';

                   return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$hospital_id.'" aria-label="...">';
               })
               ->rawColumns(['checkbox','actions'])
               ->make(true);
           }
       }

       public function store(HospitalRequest $request)
       {
           try {
               $id =  $request->id;
               $input = $request->except('_token', 'id');
               if ($id == 0) {
                   Hospital::insert($input);
               } else {
                   Hospital::find($id)->update($input);
               }
               $message = $id ? "Hospital Updated Successfully" : "New Hospital Created Successfully";

               return $this->sendResponse(true, $message, $input);
           } catch (\Throwable $th) {
               return $this->sendResponse(false, "500, Internal Server Error!");
           }
       }


        // Show the form for editing the specified Hospital.
     public function edit(Request $request)
     {
         $hospital_id = $request->id;

         try {
             $data = Hospital::where('id', $hospital_id)->first();
             return $this->sendResponse(true, "Hospital has been Retrive SuccessFully", $data);
         } catch (\Throwable $th) {
             return $this->sendResponse(false, "500, Internal Server Error!");
         }
     }

          // Remove (Delete) the specified and all Hospital.
    public function destroy(Request $request)
    {
        $Type = $request->type;
        try {
            if ($Type == 1) {
                $hospital_id = $request->id;
                Hospital::where('id', $hospital_id)->delete();
                return $this->sendResponse(true, "Hospital has been Deleted SuccessFully", $Type);
            } else {
                
                $ids = $request->id;
                Hospital::whereIn('id',$ids)->delete();
                return $this->sendResponse(true, "Hospital has been Deleted SuccessFully", $Type);
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }
}
