<?php

namespace App\Http\Requests;

use Exception;
use App\Models\Support;
use App\Http\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;

class SupportStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'message' => 'required|string'
        ];
    }

    public function store(): JsonResponse
    {
        try {
            DB::beginTransaction();

            $support = Support::create([
                'name' => $this->input('name'),
                'phone' => $this->input('phone'),
                'message' => $this->input('message')
            ]);

            DB::commit();

            return $this->successResponse(Lang::get('messages.success.support_saved', [], 'en'), $support);
        } catch (Exception $e) {
            DB::rollBack();

            Log::critical('failed to store technical support payload', [
                'params' => $this->toArray(),
                'trace' => $e
            ]);

            return $this->errorResponse(Lang::get('messages.error.server_error.support_failed', [], 'en'));
        }
    }
}
