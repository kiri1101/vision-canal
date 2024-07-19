<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Http\Traits\Helpers;
use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;

class AdminSubscriptionRequest extends FormRequest
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

        $query = Subscription::query();

        if (strlen($startDate) === 0 && strlen($endDate) === 0) {
            $query->latest('created_at')->take(100)->get();
        } else if (strlen($startDate) === 0 && strlen($endDate) > 0) {
            $query->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        } else if (strlen($startDate) > 0 && strlen($endDate) === 0) {
            $query->whereDate('created_at', '>=', $startDate)->latest('created_at')->take(100)->get();
        } else {
            $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->latest('created_at')->take(100)->get();
        }

        $subscriptionList = $query->get();

        return $this->successResponse('Subscription list', [
            'number' => $subscriptionList->count(),
            'list' => $subscriptionList->map(fn ($subscribe) => [
                'id' => $subscribe->uuid,
                'label' => $subscribe->name,
                'tel' => $subscribe->phone,
                'province' => $subscribe->region,
                'headquarter' => $subscribe->town,
                'street' => $subscribe->street,
                'created' => $subscribe->created_at->format('d/m/Y \a H:i')
            ])->all()
        ]);
    }
}
