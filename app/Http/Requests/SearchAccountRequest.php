<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SearchAccountRequest extends FormRequest
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
            'query' => 'required|string|max:255',
            'type' => 'required|string|in:email,decoder,phone,abonner'
        ];
    }

    public function search()
    {
        try {
            $response = $this->searchAccount(trim($this->input('query')), trim($this->input('type')));

            if ($response->code !== 0 && collect($response->data)->isEmpty()) {
                return $this->errorResponse('Account search failed. Please try again!');
            }

            return $this->successResponse('Account details', collect($response->data)->map(fn ($account) => [
                'decoder' => $account->clabo,
                'name' => $account->nomabo . ' ' . $account->prenomabo,
                'formula' => strtolower($account->optionmajeureabo),
                'phone' => substr($account->telephoneabo, 5)
            ])->first());
        } catch (Exception $e) {
            return $this->errorResponse('Operation failed. Please try again!');
        }
    }
}
