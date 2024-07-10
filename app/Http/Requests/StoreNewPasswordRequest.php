<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Http\Traits\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class StoreNewPasswordRequest extends FormRequest
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
            'password' => 'required|string|confirmed|min:4',
            'password_confirmation' => 'required|string|min:4'
        ];
    }

    public function savePassword(User $user): JsonResponse
    {
        try {
            $password = Hash::make($this->input('password'));

            $user->update([
                'password' => $password
            ]);

            return $this->successResponse('Account info updated successfully');
        } catch (Exception $e) {
            Log::emergency("Password update failed for user with id : {$user->id}", [
                'trace' => $e->getMessage()
            ]);

            return $this->errorResponse('Operation failed. Please try again');
        }
    }
}
