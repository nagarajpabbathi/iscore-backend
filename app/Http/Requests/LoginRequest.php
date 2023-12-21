<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
        $username = filter_var($this->username, FILTER_SANITIZE_EMAIL);

        if (!filter_var($username, FILTER_VALIDATE_EMAIL) === false) {

            return [
                'username' => 'required|exists:users,email',
                'password' => 'required|min:6'
            ];
        } else {

            return [
                'username' => 'required|exists:users,username',
                'password' => 'required|min:6'
            ];
        }
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
