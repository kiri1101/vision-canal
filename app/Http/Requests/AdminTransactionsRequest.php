<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Rules\Date;
use App\Models\Transaction;
use App\Http\Traits\Helpers;
use App\Http\Resources\TransactionResource;
use Illuminate\Foundation\Http\FormRequest;

class AdminTransactionsRequest extends FormRequest
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

    public function search()
    {
        $startDate = strlen($this->input('start')) > 0 ? Carbon::createFromFormat('Y-m-d', $this->input('start')) : '';
        $endDate = strlen($this->input('end')) ? Carbon::createFromFormat('Y-m-d', $this->input('end')) : '';

        $query = Transaction::query();

        if (strlen($this->input('start')) === 0 && strlen($this->input('end')) === 0) {
            $query->latest('created_at')->take(100)->get();
        } else if (strlen($this->input('start')) === 0 && strlen($this->input('end')) > 0) {
            $query->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        } else if (strlen($this->input('start')) > 0 && strlen($this->input('end')) === 0) {
            $query->whereDate('created_at', '>=', $startDate)->latest('created_at')->take(100)->get();
        } else {
            $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        }

        $transactionList = $query->get();

        return $this->successResponse('Transactions list', [
            'number' => $transactionList->count(),
            'list' => TransactionResource::collection($transactionList->all())
        ]);
    }
}
