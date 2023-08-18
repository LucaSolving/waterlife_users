<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'num_doc' => 'required|unique:users,num_doc',
            'firts_name' => 'required',
            'last_name' => 'required',
            'mother_last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'num_doc.unique' => 'El número de documento ya ha sido utilizado',
            'email.unique' => 'El correo electrónico ya ha sido utilizado'
        ];
    }
}
