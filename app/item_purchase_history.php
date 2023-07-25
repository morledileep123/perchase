<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_purchase_history extends Model
{
    //
		public function item_name(){
    	return $this->hasOne('App\item', 'item_number', 'item_number');
    }
}
