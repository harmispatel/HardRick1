<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Specialist;
use App\Traits\MediaTrait;
use App\Http\Requests\SpecialistRequest;




class SpecialistController extends Controller
{
    use MediaTrait;

    //

    public function index()
    {
        return view('admin.Specialist.Specialist_list');
    }

     // Load Specialist Data.
     public function loadSpecialistData(Request $request)
     {
         if ($request->ajax()) {
             $Specialist = Specialist::get();
             return DataTables::of($Specialist)
             ->addIndexColumn()
             ->addColumn('Image', function ($row){
                $default_image = asset("/public/image/not-found4.png");
                $images = asset("public/specialist/$row->image");

                $specialist_image = ($row->image) ? $images : $default_image;
                $data = '';
                $data .= '<img src="'.$specialist_image.'" width="100">';
                return $data;
                
             })
             ->addColumn('actions', function ($row) {
                 $specialist_id = isset($row->id) ? $row->id : 0;
                 $action_html = '';
                 $action_html .= '<a onclick="openaddmodel(\'Edit-Specialist\','.$specialist_id.',\'edit-Specialist\',\'#newSpecialistForm\',\'#specialistModalLabel\',\'#specialistModal\',\'update-Specialist\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                 $action_html .= '<a onclick="deletedata(\'1\','.$specialist_id.',\'delete-Specialist\',\'#SpecialistTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                 return $action_html;
             })
             ->addColumn('checkbox', function ($row) {
                 $specialist_id = isset($row->id) ? $row->id : '';

                 return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$specialist_id.'" aria-label="...">';
             })
             ->rawColumns(['checkbox','actions','Image'])
             ->make(true);
         }

     }

     public function store(SpecialistRequest $request)
     {
         try {

            $id =  $request->id;
            $data=Specialist::find($id);
            $input = $request->except('_token', 'id');
           
            if($request->has('image')) {
                $input['image'] = $this->saveImage($request,'specialist');
               
            }

            if ($id == 0) {
                Specialist::insert($input);
            } else {
                $this->old_file_remove($data->image,'specialist');
                Specialist::find($id)->update($input);
            }
            $message = $id ? "Specialist Updated Successfully" : "New Specialist Created Successfully";
            return $this->sendResponse(true, $message, $input);
         } catch (\Throwable $th) {
            dd($th);
             return $this->sendResponse(false, "500, Internal Server Error!");
         }
     }

     
        // Show the form for editing the specified Specialist.
        public function edit(Request $request)
        {
            $specialist_id = $request->id;
   
            try {
                $data = Specialist::where('id', $specialist_id)->first();
                $default_image = asset("/public/image/default-image.jpeg");
                $path =  asset("public/specialist/$data->image");
                $data['image'] = ($data->image) ? $path : $default_image;
               
                return $this->sendResponse(true, "Hospital has been Retrive SuccessFully", $data);
            } catch (\Throwable $th) {
                return $this->sendResponse(false, "500, Internal Server Error!");
            }
        }

          // Remove (Delete) the specified and all Specialist.
    public function destroy(Request $request)
    {
        $Type = $request->type;
        try {
            if ($Type == 1) {
                $specialist_id = $request->id;
                Specialist::where('id', $specialist_id)->delete();
                return $this->sendResponse(true, "Hospital has been Deleted SuccessFully", $Type);
            } else {
                
                $ids = $request->id;
                Specialist::whereIn('id',$ids)->delete();
                return $this->sendResponse(true, "Hospital has been Deleted SuccessFully", $Type);
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(false, "500, Internal Server Error!");
        }
    }

}
