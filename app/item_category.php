<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class item_category extends Model
{

    use SoftDeletes;
    /*protected $fillable = [
        'name', 'description'
    ];*/
    protected $guarded = [];
    protected $table = 'prch_item_categories';
}
