<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            'identifier' => 'required|exists:users,uuid',
            'name' => 'required|string',
            'phone' => 'required|string',
            'region' => 'required|string',
            'town' => 'required|string',
            'street' => 'required|string',
        ];
    }

    public function store(): JsonResponse
    {
        DB::beginTransaction();

        try {
            Subscription::create([
                'uuid' => Str::uuid(),
                'user_id' => User::userByUUID($this->input('identifier'))->get()->value('id'),
                'name' => trim($this->input('name')),
                'phone' => trim($this->input('phone')),
                'region' => trim($this->input('region')),
                'town' => trim($this->input('town')),
                'street' => trim($this->input('street'))
            ]);

            // inform admin through event
            DB::commit();

            return $this->successResponse(Lang::get('messages.success.subscription_saved', [], 'en'), []);
        } catch (Exception $e) {
            DB::rollBack();

            Log::critical($e->getMessage(), [
                'trace' => $e
            ]);

            return $this->errorResponse('operation failed');
        }
    }
}
