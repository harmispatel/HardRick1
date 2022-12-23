<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionRequest;

class SubscriptionController extends Controller
{
    //
    public function index()
    {
        return view('admin.Subscription.Subscription_list');
    }

     // Load Foodallergy Data.
     public function loadSubscriptionData(Request $request)
     {
         if ($request->ajax()) {
             $Subscription = Subscription::get();
 
             return DataTables::of($Subscription)
             ->addIndexColumn()
             ->addColumn('actions', function ($row) {
                 $subscription_id = isset($row->id) ? $row->id : 0;
                 $action_html = '';
                 $action_html .= '<a onclick="openaddmodel(\'Edit-Subscription\','.$subscription_id.',\'edit-Subscription\',\'#newSubscriptionForm\',\'#subscriptionModalLabel\',\'#subscriptionModal\',\'update-Subscription\')" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                 $action_html .= '<a onclick="deletedata(\'1\','.$subscription_id.',\'delete-Subscription\',\'#SubscriptionTable\')" class="btn btn-sm btn-danger me-1"><i class="bi bi-trash"></i></a>';
                 return $action_html;
             })
             ->addColumn('checkbox', function ($row) {
                 $subscription_id = isset($row->id) ? $row->id : '';
 
                 return '<input class="form-check-input sub_chk" type="checkbox" name="case"  value="'.$subscription_id.'" aria-label="...">';
             })
             ->rawColumns(['checkbox','actions'])
             ->make(true);
         }
     }

     
     public function store(SubscriptionRequest $request)
     {
         try {
             $id =  $request->id;
             $input = $request->except('_token', 'id');
             if ($id == 0) {
                Subscription::insert($input);
             } else {
                Subscription::find($id)->update($input);
             }
             $message = $id ? "Subscription Updated Successfully" : "New Subscription Created Successfully";
            
             return $this->sendResponse(true, $message, $input);
         } catch (\Throwable $th) {
             return $this->sendResponse(false, "500, Internal Server Error!");
         }
     }

          // Show the form for editing the specified foodAllergy.
      public function edit(Request $request)
      {
          $subscription_id = $request->id;

          try {
              $data = Subscription::where('id', $subscription_id)->first();
              return $this->sendResponse(true, "Subscription has been Retrive SuccessFully", $data);
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
              $subscription_id = $request->id;
              Subscription::where('id', $subscription_id)->delete();
              return $this->sendResponse(true, "Subscription has been Deleted SuccessFully", $Type);
          } else {
              
              $ids = $request->id;
              Subscription::whereIn('id',$ids)->delete();
              return $this->sendResponse(true, "Subscription has been Deleted SuccessFully", $Type);
          }
      } catch (\Throwable $th) {
          return $this->sendResponse(false, "500, Internal Server Error!");
      }
  }

}
