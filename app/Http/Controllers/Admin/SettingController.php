<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\MediaTrait;
use App\Http\Requests\SettingRequest;

use App\Models\{User, Setting};


class SettingController extends Controller
{
    use MediaTrait;

    //
    // public function form(){
    //     return view('admin.Setting.Setting');
    // }
    public function form(Request $request)
    {   
        $siteSettings = Setting::first();
        
        $doctor =User::where('role','1')->get();
        $siteSettings = empty($siteSettings) ? new SiteSettings : $siteSettings;
        return view("admin.Setting.Setting",['siteSettings'=>$siteSettings, 'doctor'=>$doctor]);  
    }

    public function store(SettingRequest $request)
    {
        $id = $request->id;
        $input = $request->except('_token','id','submit','image');
        
        if ($request->has('image')) {
            $input['logo'] = $this->saveImage($request,'site_settings');
        }
        
        if ($id == 0 || null) {
            Settings::insert($input);
        }else{
            Setting::find($id)->update($input);
        }
        $message = $request->id ? "Setting Updated Successfully" :"New Setting Created Successfully";
        return redirect()->route('Setting')->with('message', $message );
    }

}
