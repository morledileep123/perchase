<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'prch_warehouses';

    public function warehouse(){
    	return $this->belongsTo('App\Model\store_inventory', 'warehouse_id', 'id');
    }
}
