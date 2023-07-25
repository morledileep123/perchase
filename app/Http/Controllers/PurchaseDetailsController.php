<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\podetail;
use App\sites;

class PurchaseDetailsController extends Controller
{
    

    public function pucharsedetails(Request $request){
        //return $request->all();site,vendor,item
        if($request->has('_token')) {
        if($request->site == null && $request->item == null){
             $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['vendor_id', '=', $request->vendor],
          ])->get();
        }
         if($request->vendor == null && $request->item == null){
             $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['site_id', '=', $request->site],
          ])->get();
        }
        if($request->vendor == null && $request->site == null){
             $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['item_number', '=', $request->item],
          ])->get();
        }
         if($request->vendor != null && $request->site != null){
             $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['vendor_id', '=', $request->vendor],
            ['site_id', '=', $request->site],
          ])->get();
        }
         if($request->vendor != null && $request->item != null){
             $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['vendor_id', '=', $request->vendor],
            ['item_number', '=', $request->item],
          ])->get();
        }
         if($request->site != null && $request->item != null){
             $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['site_id', '=', $request->site],
            ['item_number', '=', $request->item],
          ])->get();
        }
          if($request->vendor != null && $request->site != null && $request->item != null){
             $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['vendor_id', '=', $request->vendor],
            ['site_id', '=', $request->site],
            ['item_number', '=', $request->item],
          ])->get();
       }
      }
       else{
          
         $details = podetail::has('vendor')->with('vendor','site','itemname')->where('send_from','0')->get();
        }
        $vendors = podetail::with('vendor')->get()->unique('vendor_id'); 
         $sites = podetail::with('site')->get()->unique('site_id');
         $items = podetail::with('itemname')->get()->unique('item_number');
        

         return view('Pdetails.index',compact('details','vendors','sites','items'));
       }


       public function prchvendor(Request $request){

        return $vdata = podetail::with('site')->where('vendor_id',$request->id)->get()->unique('site_id');

       }

       public function prchsite(Request $request){
        
          return $sdata = podetail::with('itemname')->where([
            ['vendor_id', '=', $request->vendrid ],
            ['site_id', '=', $request->siteid],
          ])->get()->unique('item_number');
       }

       public function pofilter(Request $request){
          //return $request->all();
          $details = podetail::has('vendor')->with('vendor','site','itemname')->where([
            ['send_from', '=', '0'],
            ['vendor_id', '=', $request->vendor],
            ['site_id', '=', $request->site],
            ['item_number', '=', $request->item],
          ])->get();
          //return $details;
         $vendors = $details->pluck('vendor')->unique(); 
         $sites = $details->pluck('site')->unique();
         $items = $details->pluck('itemname')->unique();
          return view('Pdetails.index',compact('details','vendors','sites','items'));
       }
}

 