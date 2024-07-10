<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'short_code',
        'img_path',
        'img_alt'
    ];

    // SCOPES
    public function scopeSearchByUUID(Builder $query, string $uuid)
    {
        $query->where('uuid', $uuid);
    }
}
