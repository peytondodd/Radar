<?php

namespace App\Http\Requests\Apis;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SignUpUser extends FormRequest
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
       // ^([\+0]([0-9]+[\- ]?)+)$
        return [

            'full_name'=>'required',
            'phone_number'=>['required','regex:/^(\+|00[\d]{1,4}[\d]{5,11})|([\d]{1,4}[\d]{5,11})$/'],
            'password'=>'required',
            'email'=>'required | unique:users,email',

        ];
    }

      public function formatErrors(Validator $validator)
    {
        $error=array();

        foreach($validator->getMessageBag()->toArray() as $m => $key){

            array_push($error,$key[0]);
        }

        return (['error'=>$error]);
    }
}
