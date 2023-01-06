<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{ Route, Auth };


class HospitalRequest extends FormRequest
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
        $this->id=FormRequest::input('id');
        $rules = [
            'name' => 'required',

        ];

        if(FormRequest::input('id') == '0')
        {
            $rules += [

                'phone' => 'required|numeric|digits:10|unique:hospital,phone',
            ];
        }
        $rules += [
            'phone' => 'required|numeric|digits:10|unique:hospital,phone,'.$this->id,

        ];
        return $rules;
    }
}
