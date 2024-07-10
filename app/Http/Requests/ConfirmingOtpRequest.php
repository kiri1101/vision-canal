<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Tzsk\Otp\Facades\Otp;

class ConfirmingOtpRequest extends FormRequest
{
    use Helpers;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'otp' => 'required|string|max:6',
            'email' => 'email:rfc,dns'
        ];
    }

    public function confirm(): JsonResponse
    {
        $valid = Otp::match($this->input('otp'), $this->input('email'));

        if ($valid) {
            return $this->successResponse('Opt valid. Please entre your new password');
        } else {
            return $this->errorResponse('Otp validation failed. Please try again');
        }
    }
}
