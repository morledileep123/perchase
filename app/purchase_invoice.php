<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchase_invoice extends Model
{
    protected $fillable = [
        'invoice_no', 'items'
    ];
}
