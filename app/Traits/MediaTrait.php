<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait MediaTrait {

    /**
     * Save Profile Picture
     * 
     * @param Request $request
     * @return string $image_path
     */
    public function saveImage($request,$folderName)
    {
        // dd($folder_name);
        $image = $request['image'];
        $destinationPath = public_path($folderName); // upload path
        $file_og_name = str_replace(' ','%',$image->getClientOriginalName());
        $Image = time() . "." . $file_og_name;
        $image->move($destinationPath, $Image);   
        return  $Image;
    }

    public function clinicImages($request)
    {
        // dd($folder_name);
        $image = $request['clinicImage'];
        $destinationPath = public_path('clinic/'); // upload path
        $file_og_name = str_replace(' ','%',$image->getClientOriginalName());
        $clinicImage = time() . "." . $file_og_name;
        $image->move($destinationPath, $clinicImage);   
        return  $clinicImage;
    }

    function old_file_remove($fileName,$directory){
        if($fileName !="" && file_exists(public_path($directory.'/'.$fileName)))
        {
            return unlink(public_path($directory.'/'.$fileName));
        }
    }


     

}