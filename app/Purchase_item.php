<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase_item extends Model
{
    protected $fillable = [
        'invoice_no', 'items'
    ];

    public function item_name(){
    	return $this->hasOne('App\item', 'item_number', 'item_number');
    }
}
