<?php

namespace App\Model\store_inventory;

use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    protected $guarded = [];
    // protected $table = 'prch_store_item';
    protected $table = 'prch_store_item_new';

    /*public function items_details(){
    	return $this->belongsTo('App\item', 'item_id', 'id');
    }*/

    public function store_warehouse(){
    	return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }


    public function itemdetails(){
        return $this->belongsTo('App\item', 'item_number', 'item_number');
    }

    // public function getvendor(){
    //     return $this->belongsTo('App\r','rfi_id')
    // }

}
