<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class prch_itemwise_requs extends Model
{
	use SoftDeletes;
    protected $table= 'prch_req_itemwise';
    protected $guarded = [];
    public $timestamps = true;

    public function username(){
        return $this->belongsTo('App\User','user_id');
    }
     public function manager(){
        return $this->belongsTo('App\User','manager_id');
    }

    public function indentname(){
        return $this->belongsTo('App\IndentReq','indent','indent_id');
    }

    public function address(){

        return $this->belongsTo('App\Warehouse','unavaible_in_wh','id');
    }

    public function quantitystored(){
        return $this->hasMany('App\Model\store_inventory\StoreItem','item_number','item_no');
    }

    
}
