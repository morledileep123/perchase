<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acco_Qty_Used extends Model
{
    use SoftDeletes;
     protected $table = 'acco_qty_used';
     protected $guarded = [];

       public function item(){
      return $this->belongsTo('App\item', 'item_number','item_number');
    }
}
