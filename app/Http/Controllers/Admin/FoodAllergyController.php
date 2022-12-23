<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FoodAllergy;
use App\Http\Requests\FoodAllergyRequest;




class FoodAllergyController extends Controller
{
    //
    public function index()
    {
        return view('admin.Foodallergy.Foodallergy_list');
    }

       // Load Foodallergy Data.
       public function loadFoodallergyData(Request $request)
       {
           if ($request->ajax()) {
               $Foodallergy = FoodAllergy::get();
   
               return DataTables::of($Foodallergy)
               ->addIndexColumn()
               ->addColumn('actions', function ($row) {
                   $foodallergy_id = isset($row->id) ? $row->id : 0;
                   $action_html = '';
                   $action_html .= '<a onclick="openaddmodel(\'Edit-Food-allergy\','.$foodallergy_id.',\'edit-Food-allergy\',\'#newFoodAllergyForm\',\'#foodAllergyModalLabel\',\'#foodAllergyModal\',\'update-Food-allergy\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                   $action_html .= '<a onclick="deletedata(\'1\','.$foodallergy_id.',\'delete-Food-allergy\',\'#FoodAllergyTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                   return $action_html;
               })
               ->addColumn('checkbox', function ($row) {
                   $foodallergy_id = isset($row->id) ? $row->id : '';
   
                   return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$foodallergy_id.'" aria-label="...">';
               })
               ->rawColumns(['checkbox','actions'])
               ->make(true);
           }
       }

       public function store(FoodAllergyRequest $request)
       {
           try {
               $id =  $request->id;
               $input = $request->except('_token', 'id');
               if ($id == 0) {
                FoodAllergy::insert($input);
               } else {
                FoodAllergy::find($id)->update($input);
               }
               $message = $id ? "Food Allergy Updated Successfully" : "New Food Allergy Created Successfully";
              
               return $this->sendResponse(true, $message, $input);
           } catch (\Throwable $th) {
               dd($th);
               return $this->sendResponse(false, "500, Internal Server Error!");
           }
       }

            // Show the form for editing the specified foodAllergy.
        public function edit(Request $request)
        {
            $foodallergy_id = $request->id;

            try {
                $data = FoodAllergy::where('id', $foodallergy_id)->first();
                return $this->sendResponse(true, "Bloodtype has been Retrive SuccessFully", $data);
            } catch (\Throwable $th) {
                return $this->sendResponse(false, "500, Internal Server Error!");
            }

        }

         // Remove (Delete) the specified and all foodAllergy.
    public function destroy(Request $request)
    {
        $Type = $request->type;
        try {
            if ($Type == 1) {
                $foodallergy_id = $request->id;
                FoodAllergy::where('id', $foodallergy_id)->delete();
                return $this->sendResponse(true, "bloodtype has been Deleted SuccessFully", $Type);
            } else {
                
                $ids = $request->id;
                FoodAllergy::whereIn('id',$ids)->delete();
                return $this->sendResponse(true, "bloodtype has been Deleted SuccessFully", $Type);
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }
}
