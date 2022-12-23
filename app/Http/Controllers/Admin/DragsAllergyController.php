<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DragsAllergy;
use App\Http\Requests\DragsAllergyRequest;




class DragsAllergyController extends Controller
{
    
    // Display a listing of the BloodType
   public function index()
   {
       return view('admin.Dragsallergy.Dragsallergy_list');
   }

      // Load bloodtype Data.
      public function loadDeagsallergyData(Request $request)
      {
          if ($request->ajax()) {
              $Dragallergy = DragsAllergy::get();
  
              return DataTables::of($Dragallergy)
              ->addIndexColumn()
              ->addColumn('actions', function ($row) {
                  $dragallergy_id = isset($row->id) ? $row->id : 0;
                  $action_html = '';
                  $action_html .= '<a onclick="openaddmodel(\'Edit-Drag-allergy\','.$dragallergy_id.',\'edit-Drags-allergy\',\'#newDragsAllergyForm\',\'#dragsAllergyModalLabel\',\'#dragsAllergyModal\',\'update-Drags-allergy\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                  $action_html .= '<a onclick="deletedata(\'1\','.$dragallergy_id.',\'delete-Drags-allergy\',\'#DragsAllergyTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                  return $action_html;
              })
              ->addColumn('checkbox', function ($row) {
                  $dragallergy_id = isset($row->id) ? $row->id : '';
  
                  return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$dragallergy_id.'" aria-label="...">';
              })
              ->rawColumns(['checkbox','actions'])
              ->make(true);
          }
      }

      public function store(DragsAllergyRequest $request)
      {
          try {
              $id =  $request->id;
              $input = $request->except('_token', 'id');
              if ($id == 0) {
                DragsAllergy::insert($input);
              } else {
                DragsAllergy::find($id)->update($input);
              }
              $message = $id ? "Drags Allergy Updated Successfully" : "New Drags Allergy Created Successfully";
             
              return $this->sendResponse(true, $message, $input);
          } catch (\Throwable $th) {
              dd($th);
              return $this->sendResponse(false, "500, Internal Server Error!");
          }
      }

       // Show the form for editing the specified Bloodtype.
     public function edit(Request $request)
     {
         $dragallergy_id = $request->id;

         try {
             $data = DragsAllergy::where('id', $dragallergy_id)->first();
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
                $dragallergy_id = $request->id;
                DragsAllergy::where('id', $dragallergy_id)->delete();
                return $this->sendResponse(true, "bloodtype has been Deleted SuccessFully", $Type);
            } else {
                
                $ids = $request->id;
                DragsAllergy::whereIn('id',$ids)->delete();
                return $this->sendResponse(true, "bloodtype has been Deleted SuccessFully", $Type);
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }

}
