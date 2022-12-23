<?php 
use App\Model\SiteSettings;
    function app_url(){
        return URL::To('');
    }
    function public_url($fileUrl = ""){
        return $fileUrl == "" ? asset('/public') : asset('/public'.$fileUrl);
    }
    function file_exists_in_folder($directory,$fileName){
        if($fileName!="" && file_exists(public_path($directory.'/'.$fileName)))
        {
            return public_url().'/'.$directory.'/'.$fileName;
        }
        else{
            if($directory == "doctor"){
                return public_url().'/image/default-image.jpeg';
            }
            elseif($directory == "category"){
                return public_url().'/default_images/logo.png';
            }
            elseif($directory == "site_settings"){
                return public_url().'/default_images/logo.png';
            }
        }
    }
    function get_site_setting($columnName = ""){
        $siteSettings = SiteSettings::first();
        if($columnName == 'api_key'){
            return !empty($siteSettings) && $siteSettings->api_key ? $siteSettings->api_key : '';
        }
        elseif($columnName == 'logo'){
            // echo "<pre>";print_r($siteSettings);exit;
            return !empty($siteSettings) && $siteSettings->logo  ? file_exists_in_folder('site_settings',$siteSettings->logo) : public_url().'/image/logo/logo.png';
        }
    }
    function old_file_remove($directory,$fileName){
        if($fileName !="" && file_exists(public_path($directory.'/'.$fileName)))
        {
            return unlink(public_path($directory.'/'.$fileName));
        }
    }
    
    function get_timestamp($time=""){
        return $time = $time == "" ? strtotime("now") : strtotime($time);

    }
    function get_time($timestamp="", $type=""){
        if($timestamp == ""){
            return $time = $type == "" ? date("Y/m/d") : date($type);
        }
        else{
            return $time = $type == "" ? date("Y/m/d",$timestamp) : date($type,$timestamp);
        }
    }
    