<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\BloodType;
use App\Http\Requests\BloodTypeRequest; 




class BloodTypeController extends Controller
{
  
   // Display a listing of the BloodType
   public function index()
   {
       return view('admin.Bloodtype.Bloodtype_list');
   }

    // Load bloodtype Data.
    public function loadBloodtypeData(Request $request)
    {
        if ($request->ajax()) {
            $Bloodtype = BloodType::get();

            return DataTables::of($Bloodtype)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                $bloodtype_id = isset($row->id) ? $row->id : 0;
                $action_html = '';
                $action_html .= '<a onclick="openaddmodel(\'Edit-Blood-type\','.$bloodtype_id.',\'edit-Blood-type\',\'#newBloodTypeForm\',\'#bloodTypeModalLabel\',\'#bloodTypeModal\',\'update-Blood-type\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                $action_html .= '<a onclick="deletedata(\'1\','.$bloodtype_id.',\'delete-Blood-type\',\'#BloodTypeTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                return $action_html;
            })
            ->addColumn('checkbox', function ($row) {
                $bloodtype_id = isset($row->id) ? $row->id : '';

                return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$bloodtype_id.'" aria-label="...">';
            })
            ->rawColumns(['checkbox','actions'])
            ->make(true);
        }
    }

    public function store(BloodTypeRequest   $request)
    {
        try {
            $id =  $request->id;
            $input = $request->except('_token', 'id');
            if ($id == 0) {
                BloodType::insert($input);
            } else {
                BloodType::find($id)->update($input);
            }
            $message = $id ? "Blood Type Updated Successfully" : "New Blood Type Created Successfully";
           
            return $this->sendResponse(true, $message, $input);
        } catch (\Throwable $th) {
            dd($th);
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }

     // Show the form for editing the specified Bloodtype.
     public function edit(Request $request)
     {
         $bloodtype_id = $request->id;

         try {
             $data = BloodType::where('id', $bloodtype_id)->first();
             return $this->sendResponse(true, "Bloodtype has been Retrive SuccessFully", $data);
         } catch (\Throwable $th) {
             return $this->sendResponse(false, "500, Internal Server Error!");
         }

     }

        // Remove (Delete) the specified and all Bloodtype.
    public function destroy(Request $request)
    {
        $Type = $request->type;
        try {
            if ($Type == 1) {
                $bloodtype_id = $request->id;
                BloodType::where('id', $bloodtype_id)->delete();
                return $this->sendResponse(true, "bloodtype has been Deleted SuccessFully", $Type);
            } else {
                
                $ids = $request->id;
                BloodType::whereIn('id',$ids)->delete();
                return $this->sendResponse(true, "bloodtype has been Deleted SuccessFully", $Type);
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }

}
