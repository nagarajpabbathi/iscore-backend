<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = auth()->id();

        return [
            'name' => 'required|min:3|max:250',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|unique:users,username,' . $id,
            'password' => 'nullable|min:6',
            'confirm_password' => 'nullable|same:password',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,' . $id,
            'profile_image' => 'nullable|image|max:2048|mimes:jpg,png,jpeg,gif'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message'   => $validator->errors()->first(),
            'errors' => $validator->errors(),
            'response'  => false
        ], 422);

        throw new HttpResponseException($response);
    }
}
