<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateUserRequest extends FormRequest
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
        if($this->segment(3)!="") {
            $user = User::find($this->segment(3));
        }else{
            $user = null;
        }

        $rules = User::$rules;

        if($user){
            $rules['email'] = $rules['email'].$user->id;
        }
        
        unset($rules['password']);
        return $rules;
    }
}
