<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Requests\RenewSubscriptionRequest;

class SubscriptionController extends BaseController
{
    public function subscribeStore(SubscriptionRequest $request): JsonResponse
    {
        return $request->store();
    }

    public function renewSubscribeStore(RenewSubscriptionRequest $request): JsonResponse
    {
        return $request->store();
    }

    public function subscriptionList(User $user): JsonResponse
    {
        $subscriptions = $user->subscriptions()->get();

        if ($subscriptions->count() > 0) {
            return $this->successResponse('', $subscriptions->map(fn ($subscribe) => [
                'full_name' => $subscribe->name,
                'phone_number' => $subscribe->phone,
                'address' => $subscribe->street . '-' . $subscribe->town,
                'date' => Carbon::parse($subscribe->created_at)->format('Y-m-d h:i:s')
            ]));
        } else {
            return $this->errorResponse('No records found');
        }
    }

    public function subscriptionRenewalList(User $user): JsonResponse
    {
        $subscriptionRenewals = $user->subscriptionRenewals()->get();

        if ($subscriptionRenewals->count() > 0) {
            return $this->successResponse('', $subscriptionRenewals->map(fn ($subscribe) => [
                'full_name' => $subscribe->name,
                'phone_number' => $subscribe->phone,
                'chosen_formula' => $subscribe->formula()->value('name'),
                'chosen_option' => empty($subscribe->option) ? 'N/A' : $subscribe->option()->value('name'),
                'payment_method' => $subscribe->paymentMethod()->value('name'),
                'date' => Carbon::parse($subscribe->created_at)->format('Y-m-d h:i:s'),
                // 'decoder_number' => $subscribe->decoder,
                // 'period' => $subscribe->duration,
                // 'amount_to_pay' => $subscribe->amount
            ]));
        } else {
            return $this->errorResponse('No records found');
        }
    }
}
