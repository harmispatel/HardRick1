<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chronicdiseases;
use App\Http\Requests\ChronicDiseasesRequest;
use Yajra\DataTables\Facades\DataTables;

class ChronicDiseasesController extends Controller
{
    // Display a listing of the ChronicDiseases
    public function index()
    {
        return view('admin.Chronicdiseases.Chronic _diseases_list');
    }


    // Load ChronicDiseases Data.
    public function loadChronicDiseasesData(Request $request)
    {
        if ($request->ajax()) {
            $Chronicdiseases = Chronicdiseases::get();

            return DataTables::of($Chronicdiseases)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                $chronicdiseases_id = isset($row->id) ? $row->id : 0;
                $action_html = '';
                $action_html .= '<a onclick="openaddmodel(\'Edit Chronic-diseases\','.$chronicdiseases_id.',\'edit-Chronic-diseases\',\'#newChronicDiseasesForm\',\'#chronicDoseasesModalLabel\',\'#chronicDiseaseModal\',\'update-Chronic-diseases\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                $action_html .= '<a onclick="deletedata(\'1\','.$chronicdiseases_id.',\'delete-Chronic-diseases\',\'#ChronicDiseaseTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                return $action_html;
            })
            ->addColumn('checkbox', function ($row) {
                $chronicdiseases_id = isset($row->id) ? $row->id : '';

                return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$chronicdiseases_id.'" aria-label="...">';
            })
            ->rawColumns(['checkbox','actions'])
            ->make(true);
        }
    }


    public function store(ChronicDiseasesRequest $request)
    {
        try {
            $id =  $request->id;
            $input = $request->except('_token', 'id');
            if ($id == 0) {
                Chronicdiseases::insert($input);
            } else {
                Chronicdiseases::find($id)->update($input);
            }
            $message = $id ? "Chronic Diseases Updated Successfully" : "New Chronic Diseases Created Successfully";
           
            return $this->sendResponse(true, $message, $input);
        } catch (\Throwable $th) {
            dd($th);
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }

      // Show the form for editing the specified Vendor.
      public function edit(Request $request)
      {
          $chronicdiseases_id = $request->id;

          try {
              $data = Chronicdiseases::where('id', $chronicdiseases_id)->first();
              return $this->sendResponse(true, "chronicdiseases has been Retrive SuccessFully", $data);
          } catch (\Throwable $th) {
              return $this->sendResponse(false, "500, Internal Server Error!");
          }
      }

       // Remove (Delete) the specified Vendor.
    public function destroy(Request $request)
    {
        $Type = $request->type;
        try {
            if ($Type == 1) {
                $chronicdiseases_id = $request->id;
                Chronicdiseases::where('id', $chronicdiseases_id)->delete();
                return $this->sendResponse(true, "chronicdiseases has been Deleted SuccessFully", $Type);
            } else {
                
                $ids = $request->id;
                Chronicdiseases::whereIn('id',$ids)->delete();
                return $this->sendResponse(true, "chronicdiseases has been Deleted SuccessFully", $Type);
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }
}
