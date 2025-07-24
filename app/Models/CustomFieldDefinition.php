<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldDefinition extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'options', 'model_type'];

    protected $casts = [
        'options' => 'array',
    ];
}
