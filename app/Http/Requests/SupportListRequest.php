<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Http\Traits\Helpers;
use App\Models\Support;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SupportListRequest extends FormRequest
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
            'start' => strlen($this->input('start')) > 0 ? 'date' : '',
            'end' => strlen($this->input('end')) > 0 ? 'date' : ''
        ];
    }

    public function search(): JsonResponse
    {
        $startDate = strlen($this->input('start')) > 0 ? Carbon::createFromFormat('Y-m-d', $this->input('start')) : '';
        $endDate = strlen($this->input('end')) > 0 ? Carbon::createFromFormat('Y-m-d', $this->input('end')) : '';

        $query = Support::query();

        if (strlen($startDate) === 0 && strlen($endDate) === 0) {
            $query->latest('created_at')->take(100)->get();
        } else if (strlen($startDate) === 0 && strlen($endDate) > 0) {
            $query->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        } else if (strlen($startDate) > 0 && strlen($endDate) === 0) {
            $query->whereDate('created_at', '>=', $startDate)->latest('created_at')->take(100)->get();
        } else {
            $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        }

        $supportList = $query->get();

        return $this->successResponse('Support list', [
            'number' => $supportList->count(),
            'list' => $supportList->map(fn ($support) => [
                'id' => $support->id,
                'name' => $support->name,
                'tel' => $support->phone,
                'message' => $support->message,
                'created' => $support->created_at->format('d/m/Y \a H:i')
            ])->all()
        ]);
    }
}
