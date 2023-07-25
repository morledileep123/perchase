<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class item extends Model
{
     use SoftDeletes;
    /*protected $fillable = [
        'item_number', 'description', 'unit_id', 'category_id', 'title', 'brand', 'department'
    ];*/
    protected $guarded = [];
    protected $table = 'prch_items';
    // protected $dateFormat = 'Y-m-d H:i:sO';

    public function brand_name(){
    	return $this->belongsTo('App\Brand', 'brand');
    }
    public function department_name(){
    	return $this->belongsTo('App\Department', 'department');
    }
    public function category(){
    	return $this->belongsTo('App\item_category', 'category_id')->withDefault(['name'=>'not-found']);
    }
    public function unit(){
    	return $this->belongsTo('App\unitofmeasurement', 'unit_id');
    }
    public function items_qty(){
        return $this->belongsTo('App\Model\store_inventory\StoreItem', 'id', 'item_id');
    }
    public function consum(){
        return $this->belongsTo('App\itemconsumable', 'cons_id', 'id');
    }

    public function wonee(){
        return $this->belongsTo('App\Model\store_inventory\StoreItem', 'id', 'item_id')->where('warehouse_id',1);
    }
    public function wtwoo(){
        return $this->belongsTo('App\Model\store_inventory\StoreItem', 'id', 'item_id')->where('warehouse_id',2);
    }

     public function purchaseStoreQty(){
        return $this->hasMany('App\purchase_stored_item', 'item_number', 'item_number');
    }

    public function getDescriptionPartialAttribute()
    {
        return Str::words($this->description, '8');
    }
}
