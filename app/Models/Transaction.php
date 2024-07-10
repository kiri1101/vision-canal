<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid', 'type', 'amount', 'commission', 'method_id', 'sender_id', 'sender_account', 'sender_account_balance', 'receiver_id', 'receiver_account', 'receiver_account_balance'
    ];

    // RELATIONSHIPS
    /**
     * Get the renewSubscription associated with the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function renewSubscription(): HasOne
    {
        return $this->hasOne(RenewSubscription::class);
    }

    /**
     * Get the method that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id', 'id');
    }

    /**
     * Get the sender that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    /**
     * Get the receiver that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    // SCOPES
    public function scopeTransactionByUUID(Builder $query, string $uuid)
    {
        $query->where('uuid', $uuid);
    }

    public function scopeAllDeposits(Builder $query)
    {
        $query->where('type', 1);
    }

    public function scopeAllWithdrawals(Builder $query)
    {
        $query->where('type', 2);
    }

    public function scopeTransactionsByUser(Builder $query, int $userId)
    {
        $query->where('sender_id', $userId)->orWhere('receiver_id', $userId);
    }
}
