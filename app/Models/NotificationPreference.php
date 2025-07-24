<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'is_muted',
    ];

    protected $casts = [
        'is_muted' => 'boolean',
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
