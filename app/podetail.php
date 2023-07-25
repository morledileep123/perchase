<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class podetail extends Model
{
     use SoftDeletes;
    protected $table= 'prch_acco_podetail';
    protected $guarded = [];
    public $timestamps = true;

    public function vendor(){
        return $this->belongsTo('App\Vendormain','vendor_id')->select(['id','firm_name']);
    }

    public function site(){
        return $this->belongsTo('App\sites','site_id')->select(['id','job_describe']);
    }

    public function itemname(){
        return $this->belongsTo('App\item','item_number','item_number')->select(['item_number','title']);
    }
}
