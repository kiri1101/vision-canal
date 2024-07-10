<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use Illuminate\Support\Str;
use App\Models\AuthorizationToken;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class AuthorizationTokenRequest extends FormRequest
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
            'manufacturer' => 'required|string',
            'model' => 'required|string',
            'name' => 'required|string',
            'os' => 'required|string',
            'version' => 'required|string',
            'platform' => 'required|string',
            'uuid' => 'required|string',
            'lang' => 'required|string',
        ];
    }

    public function saveUserDeviceInfo()
    {
        try {
            $record = AuthorizationToken::updateOrCreate(
                [
                    'app_id' => $this->input('uuid'),
                    'device_manufacturer' => $this->input('manufacturer'),
                    'device_model' => $this->input('model'),
                    'device_name' => $this->input('name'),
                ],
                [
                    'app_secret' => Str::uuid(),
                    'is_active' => true,
                    'android_os' => $this->input('os'),
                    'android_version' => $this->input('version'),
                    'device_platform' => $this->input('platform'),
                    'ip_address' => $this->ip(),
                    'locale' => $this->input('lang')
                ]
            );

            return $this->successResponse(Lang::get('messages.success.authorization_token', [], $record->locale), $record->app_secret);
        } catch (Exception $e) {
            Log::critical('failed to register user account', [
                'params' => $this->toArray(),
                'trace' => $e
            ]);

            return $this->errorResponse(Lang::get('messages.error.server_error.failed_register', [], $this->input('lang')));
        }
    }
}
