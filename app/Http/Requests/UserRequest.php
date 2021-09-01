<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
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
    public function rules() {
        return [
            'u_username'        => 'required|min:3',
            'u_email'           => 'required|email',            
            //'u_password'        => 'required|min:6',
            'u_fname'           => 'required',
            'u_mname'           => 'required',
            'u_lname'           => 'required',
            'u_mobile'          => 'required|regex:/^639/|digits:12'
        ];
    }

    public function attributes() {
        return [
            'u_username'        => 'Username',
            'u_email'           => 'E-mail Address',            
            //'u_password'        => 'Password',
            'u_fname'           => 'First Name',
            'u_mname'           => 'Middle Name',
            'u_lname'           => 'Last Name',
            'u_mobile'          => 'Mobile Number'
        ];
    }

    public function messages() {
        return [
            'regex' => 'The Mobile Number must start with 639'
        ];
    }
}
