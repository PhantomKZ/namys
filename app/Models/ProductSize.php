<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSize extends Pivot
{
    protected $table = 'product_size';

    protected $fillable = ['product_id', 'size_id', 'quantity'];
}
