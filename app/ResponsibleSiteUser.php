<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResponsibleSiteUser extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'prch_acco_responsible_site_user';

    public function users(){
      return $this->belongsTo('App\User', 'user_id');
    }

     public function musers(){
      return $this->belongsTo('App\User', 'manager_id');
    }

     public function site(){
      return $this->belongsTo('App\sites', 'site_id');
    }

    public function qtty()
    {
        return $this->hasMany('App\Acco_Qty_Used','ref_id','id');
    }
}
