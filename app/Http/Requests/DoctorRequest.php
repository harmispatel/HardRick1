<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->id=FormRequest::input('id');
        $rules = [
        //
        'name' => 'required',
        'name_ar' => 'required',
        'address' => 'required',
        'price_of_ticket' => 'required',
        'status_data' => 'required'    
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
}
