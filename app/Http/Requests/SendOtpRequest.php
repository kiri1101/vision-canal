<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Http\Traits\Helpers;
use App\Notifications\SendOtp;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOtpRequest extends FormRequest
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
            'email' => 'email:rfc,dns'
        ];
    }

    public function dispatchOtp(): JsonResponse
    {
        try {
            Notification::route('mail', 'taylor@example.com')
                ->notify(new SendOtp($this->input('email')));

            return $this->successResponse('OTP sent successfully');
        } catch (Exception $e) {
            Log::emergency('Failed to send OTP code via email channel', [
                'trace' => $e->getMessage()
            ]);

            return $this->errorResponse('Operation failed. Please try again');
        }
    }
}
