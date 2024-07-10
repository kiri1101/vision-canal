<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'uuid', 'fee', 'is_active', 'is_special'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_special' => 'boolean',
        'is_active' => 'boolean',
    ];

    // SCOPES
    public function scopeIsActive(Builder $query)
    {
        $query->where('is_active', true);
    }

    public function scopeIsSpecial(Builder $query)
    {
        $query->where('is_special', true);
    }

    public function scopeIsNotSpecial(Builder $query)
    {
        $query->where('is_special', false);
    }

    public function scopeCategoryByUUID(Builder $query, string $uuid)
    {
        $query->where('uuid', $uuid);
    }
}
