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
    public function saveImage($request)
    {
        $image = $request['image'];
        $destinationPath = public_path('specialist/'); // upload path
        $file_og_name = str_replace(' ','_',$image->getClientOriginalName());
        $specialistImage = time() . "." . $file_og_name;
        $image->move($destinationPath, $specialistImage);
        return  $specialistImage;
        
       
    }

    

}