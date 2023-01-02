<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{ Route, Auth };


class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    //     $rules = [
    //         'name' => 'required',
    //         'name_ar' => 'requied',
    //         'address' => 'required',
    //         'location' => 'required',
    //         'blood_type_id' => 'required',
    //         'birth_date' => 'required',
    //         'status' => 'required',
    //     ];
    //     // if (FormRequest::input('id') == null || '0'){
    //     //     $rules += [
    //     //         'email' => 'required|email|unique:users,email',
    //     //         'password' => 'required|min:6',
    //     //         'phone' => 'required|numeric|min:11|max:10|unique:users,phone',
                
    //     //     ];
    //     // }else{
    //     //     $rules += [
    //     //         'email' => 'required|email|unique:users,email,'.$this->$id,
    //     //         'phone' => 'required|numeric|min:11|max:10|unique:users,phone,'.$this->$id,
    //     //     ];
    //     // }
    //     // dd($rules);
    $rules = [
        //
        'name' => 'required',
        'name_ar' => 'required',
        'address' => 'required',
        'blood_type_id' => 'required',
        'birth_date' => 'required',
        'dragsAllergy' => 'required|array',
        'FoodAllergy' => 'required|array',
        'ChronicDiseases' => 'required|array',

        
    ];
    if (FormRequest::input('id') == '' || '0') {
       $rules += [
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|numeric|min:11|unique:users,phone',
        'image' => 'required|mimes:jpeg,jpg,png,gif,PNG',
        'password' => 'required|min:6',

       ];
    }else{
        $rules += [
        'email' => 'required|email|unique:users,email,' . $this->id,
        'phone' => 'required|numeric|min:11|unique:users,phone,' . $this->id,
        'image' => 'mimes:jpeg,jpg,png,gif,PNG',
           ];
    }
    return $rules;
}
    //     return $rules;
    // }
}
