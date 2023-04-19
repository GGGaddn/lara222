<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonPostingFbo extends Model
{
    use HasFactory;

    protected $table = 'oz_posting_fbo';

    protected $casts = [
        'digital_codes' => 'json',
        'actions' => 'json',
        'picking' => 'json',
        'analytics_data' => 'json',
        'financial_data' => 'json',
        'additional_data' => 'json',
    ];
}
