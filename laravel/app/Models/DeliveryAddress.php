<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryAddress extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'street', 'building_number', 'city', 'postal_code', 'country', 'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default'  => 'boolean',
            'created_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
