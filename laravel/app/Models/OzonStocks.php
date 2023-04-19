<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonStocks extends Model
{
    use HasFactory;

    protected $table = 'oz_info_stocks';

    public $timestamps = false;
}
