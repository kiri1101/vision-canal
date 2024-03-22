<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AuthorizationToken extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'app_id',
        'app_secret',
        'is_active',
        'device_manufacturer',
        'device_model',
        'device_name',
        'android_os',
        'android_version',
        'device_platform',
        'ip_address',
        'locale'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // RELATIONSHIPS

    /**
     * Get all of the users for the AuthorizationToken
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'token_id', 'id');
    }

    // SCOPES
    public function scopeIsActive(Builder $query)
    {
        $query->where('is_active', true);
    }

    public function scopeGetAuthToken(Builder $query, string $uuid, string $manufacturer, string $model, string $name): void
    {
        $query->firstWhere('app_id', $uuid)
            ->firstWhere('device_manufacturer', $manufacturer)
            ->firstWhere('device_model', $model)
            ->firstWhere('device_name', $name);
    }

    public function scopeIsValid(Builder $query, string $token): void
    {
        $query->firstWhere('app_secret', $token);
    }
}
