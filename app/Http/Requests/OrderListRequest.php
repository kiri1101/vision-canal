<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Http\Traits\Helpers;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class OrderListRequest extends FormRequest
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
        $endDate = strlen($this->input('end')) > 0 ? Carbon::createFromFormat('Y-m-d', $this->input('end')) : '';

        $query = Order::query();

        if (strlen($startDate) === 0 && strlen($endDate) === 0) {
            $query->latest('created_at')->take(100)->get();
        } else if (strlen($startDate) === 0 && strlen($endDate) > 0) {
            $query->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        } else if (strlen($startDate) > 0 && strlen($endDate) === 0) {
            $query->whereDate('created_at', '>=', $startDate)->latest('created_at')->take(100)->get();
        } else {
            $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        }

        $orderList = $query->get();

        return $this->successResponse('Orders list', [
            'number' => $orderList->count(),
            'list' => $orderList->map(fn ($order) => [
                'id' => $order->uuid,
                'user' => $order->user->name,
                'tel' => $order->user->phone,
                'article' => $order->article->title,
                'status' => $order->status,
                'created' => $order->created_at->format('d/m/Y \a H:i')
            ])->all()
        ]);
    }
}
