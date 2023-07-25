<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'invoice_no', 'item_number', 'quantity'
    ];

    public function item_name(){
    	return $this->hasOne('App\item', 'item_number', 'item_number');
    }
}
