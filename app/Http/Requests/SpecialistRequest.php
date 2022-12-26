<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{ Route, Auth };


class SpecialistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            //
            'name' => 'required',
            'name_ar' => 'required',
            
        ];
        if (FormRequest::input('id') == '' || '0') {
           $rules += [
            'image' => 'required|mimes:jpeg,jpg,png,gif,PNG',
           ];
        }else{
            $rules += [
                'image' => 're|mimes:jpeg,jpg,png,gif,PNG',
               ];
        }
        
        return $rules;
    }
}
