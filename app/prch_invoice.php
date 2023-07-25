<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class prch_invoice extends Model
{
    use SoftDeletes;
    protected $table= 'prch_invoice_details';
    protected $guarded = [];
    public $timestamps = true;
}
