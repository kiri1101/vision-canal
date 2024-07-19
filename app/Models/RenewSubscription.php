<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RenewSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'transaction_id',
        'decoder',
        'name',
        'formula_id',
        'option_id',
        'method_id',
        'duration',
        'phone',
        'method',
        'amount'
    ];

    // RELATIONSHIP

    /**
     * Get the user that owns the RenewSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formula that owns the RenewSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function formula(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'formula_id', 'id');
    }

    /**
     * Get the option that owns the RenewSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'option_id', 'id');
    }

    /**
     * Get the paymentMethod that owns the RenewSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id', 'id');
    }

    // SCOPES
    public function scopeUserByUUID(Builder $query, string $uuid)
    {
        $query->where('uuid', $uuid);
    }
}
