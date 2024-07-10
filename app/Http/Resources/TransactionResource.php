<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'type' => $this->formatType($this->type),
            'amount' => $this->amount,
            'commission' => $this->commission,
            'sender' => $this->sender()->withTrashed()->first()->name,
            'sender_account' => $this->formatAccount($this->sender_account),
            'receiver' => $this->receiver()->withTrashed()->first()->name,
            'receiver_account' => $this->formatAccount($this->receiver_account),
            // 'date_created' => $this->created_at->format('Y-m-d h:i:s')
            'date_created' => $this->created_at->format('d/m/Y \a\t H:i')
        ];
    }

    private function formatType(string $type): string
    {
        if ($type === '1') {
            return 'Deposit';
        } elseif ($type === '2') {
            return 'Withdrawal';
        } else {
            return 'NAN';
        }
    }

    private function formatAccount(string $account): string
    {
        if ($account === '1') {
            return 'Main';
        } elseif ($account === '2') {
            return 'Commission';
        } else {
            return 'Cash';
        }
    }
}
