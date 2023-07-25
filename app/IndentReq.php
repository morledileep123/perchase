<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndentReq extends Model
{
    protected $guarded = [];
    protected $table = 'prch_indent_request';
    protected $primaryKey = 'indent_id';
 }
