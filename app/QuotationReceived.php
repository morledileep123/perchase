<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationReceived extends Model
{
    protected $guarded = [];
    protected $table = 'prch_quotation_receiveds';

    public function vendorsDetail(){
    	return $this->hasOne('App\Vendormain', 'id', 'vender_id');
    }

    public function site(){

     return $this->hasOne('App\sites', 'id', 'site_id');
    }

    public function userinfo(){

     return $this->hasOne('App\prch_itemwise_requs', 'prch_rfi_users_id', 'rfi_id');
    }

    public function resuser(){
      return $this->hasOne('App\ResponsibleSiteUser', 'qid','id');
    }

  
}
