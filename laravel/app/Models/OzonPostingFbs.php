<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonPostingFbs extends Model
{
    use HasFactory;

    protected $table = 'oz_posting_fbs';

    protected $casts = [
        'mandatory_mark' => 'json',
        'addressee' => 'json',
        'barcodes' => 'json',
        'actions' => 'json',
        'picking' => 'json',
        'products_requiring_gtd' => 'json',
        'products_requiring_country' => 'json',
        'products_requiring_mandatory_mark' => 'json',
        'products_requiring_rnpt' => 'json',
        'available_actions' => 'json',
    ];
}
