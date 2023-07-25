<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationApprovals extends Model
{
    protected $guarded = [];
    protected $table = 'prch_quotation_approvals';

    /*public function vendors_details(){
    		return $this->belongsTo('App\vendor', 'vendor_id', 'id');
    }*/
    public function vendors_mail_items(){
    		return $this->belongsTo('App\VendorsMailSend', 'quote_id', 'id');
    }

    public function QuotationReceived(){
    		return $this->belongsTo('App\QuotationReceived', 'quote_id', 'quotion_sends_id');
    }

    public function rfi_status(){
        return $this->belongsTo("App\PO_SendToVendors", "rfi_id", "app_rfi")->withDefault([
          'rfi_id' => 0
        ]);

    }
    public function rfuser(){
        return $this->belongsTo("App\RfiUsers", "rfi_id", "id");
    }
    public function prchitemres(){
        return $this->belongsTo("App\prch_itemwise_requs", "rfi_id", "prch_rfi_users_id");
    }

    public function vendordettl(){
        return $this->belongsTo("App\Vendormain", "vendor_id", "id")->withDefault([
          'firm_name' => 'No Vendor found'
        ]);

}
    public function venadmin(){
        return $this->belongsTo("App\Vendormain", "vendor_id", "id")->select('firm_name','id')->withDefault([
          'firm_name' => 'No Vendor found'
        ]);
    }

     public function QuotationRadmin(){
            return $this->belongsTo('App\QuotationReceived', 'quote_id', 'quotion_sends_id')->select('items','quotion_sends_id')->withDefault([
          'items' => 'No items found'
        ]);;
    }

    // public function wareous(){
    //     return $this->hasOneThrough("App\Warehouse", "App\QuotationApprovals",'rfi_id','address_wareh_id','id','id');
    // }
}
