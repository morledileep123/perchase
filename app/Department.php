<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Department extends Model
{
     use SoftDeletes;
     
    protected $guarded = [];
    protected $table = 'prch_departments';
}
