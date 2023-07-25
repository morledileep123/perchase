<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'prch_brands';

    public function category(){
    	return $this->belongsTo('App\item_category', 'category_id','id');
    }
}
