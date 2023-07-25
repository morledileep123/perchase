<?php

namespace App\Http\Controllers;

use App\StoreManagement;
use App\PO_SendToVendors;
use App\vendor;
use App\Vendormain;
use App\QuotationReceived;
use App\QuotationApprovals;
use App\item;
use App\prch_itemwise_requs;
use App\item_quantity;
use App\item_quantity_hsty;
use App\sites;
use Illuminate\Support\Str;
use App\Model\store_inventory\StoreItem;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	    $data = DB::table('prch_po_send_to_vendors')
            ->join('acco_vendor_mast', 'prch_po_send_to_vendors.vendor_id', '=', 'acco_vendor_mast.id')
            ->orderBy('prch_po_send_to_vendors.created_at', 'desc')
            ->select('prch_po_send_to_vendors.*', 'acco_vendor_mast.name','acco_vendor_mast.firm_name','acco_vendor_mast.mobile')
            ->where('prch_po_send_to_vendors.po_accept_status', '=', '1')->paginate(10);
           //dd($data);

        return view("store_management.index",compact("data"))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function ViewAcceptedPO($id){
      
        $rfik = $id;
        $data = QuotationApprovals::with('vendors_mail_items','QuotationReceived.vendorsDetail','prchitemres')
                        ->where('id', '=', $id)->paginate(10);
        $pritem = $data[0]->prchitemres->prch_rfi_users_id;
                      // return $data[0]['prchitemres'];
        // return view("store_management.view_accepted_po", compact('data','rfik','pritem'));
        return view("store_management.guardStoreitem",compact('data'));
    }

    public function viewstored($id){
      
        $rfik = $id;
        $data = QuotationApprovals::with('vendors_mail_items','QuotationReceived.vendorsDetail','prchitemres')
                        ->where('id', '=', $id)->paginate(10);
        $pritem = $data[0]->prchitemres->prch_rfi_users_id;
        return view("store_management.viewstored",compact('data'));
    }



    // Fetch GRN for store manager
    public function csitem($id){

      return $id."-code for stored pos here";
    } 
    public function FetchAllGRN(){
    		$data = '';
    		return view("store_management.view_grn");
    }

    public function upstock(Request $request){
         //$request->all();
        $upte['po_stored'] = 1;
        $upte['w_id'] = $request->warehouse;
        $upte['grcode'] = "Gr:Code0".StoreItem::max('id').$request->warehouse;
        // return $request->quoapp;
          $quoid = QuotationApprovals::find($request->quoapp)->quote_id;
          PO_SendToVendors::where('approval_quotation_id',$request->quoapp)->update(['po_stored' => 1]);
          QuotationReceived::where('quotion_sends_id',$quoid)->update($upte);
          // $data['itemno'] = $request->item;
          // $data['qty'] = $request->quantity;
          $count = count($request->item);
          for($i=0; $i<$count; $i++){
              //$icode = str::after($data['itemno'][$i],'|');
             $code = str::after($request->item[$i],'|');
             $qty = intval($request->quantity[$i]);
             $sqty = StoreItem::where('item_number',$code)->where('warehouse_id',$request->warehouse)->first();
            if($sqty == NULL){
              $storedata = New StoreItem;
              $storedata->item_number = $code;
              $storedata->quantity  = $qty;
              $storedata->warehouse_id  = $request->warehouse;
              $storedata->save();

            if($request->warehouse == 1){   
              $quantity = 0;
              $warid = 2;
              }

              if($request->warehouse == 2){   
              $quantity = 0;
              $warid = 1;
              }
              $storedata = New StoreItem;
              $storedata->item_number = $code;
              $storedata->quantity  = $quantity;
              $storedata->warehouse_id  = $warid;
              $storedata->save();
            }else{

            $update = StoreItem::where('item_number',$code)->where('warehouse_id',$request->warehouse)->increment('quantity',$qty);
              
           
           }
           
          $sites = sites::pluck('id');
        foreach ($sites as $site) {
           $sitemq= item_quantity::where('site_id',$site)->where('item_number',$code)->get();
          if(count($sitemq) == NULL){
            $ziq['quantity'] = 0;
            $ziq['current_date'] = date('Y-m-d');
            $ziq['site_id'] = $site;
            $ziq['item_number'] = $code;
            $ziq['wareh_id'] = 1;
            $ziqw['quantity'] = 0;
            $ziqw['current_date'] = date('Y-m-d');
            $ziqw['site_id'] = $site;
            $ziqw['item_number'] = $code;
            $ziqw['wareh_id'] = 2;
            item_quantity::create($ziq);
            item_quantity::create($ziqw);
          }
             
          }



         
}
          return redirect()->route('store_management.index');
         




    }

    public function upwareqty(Request $request){
      //return $request->ids;
      //return $request->all();
        $data = $request->validate([
        //'item_number'=>'required',
        'quantity'=>'required',
        'warehouse_id'=>'required',

    ]);
        if($request->warehouse_id == 1){   
        $dataw['quantity' ] = 0;
        $dataw['warehouse_id' ] = 2;
        }

        if($request->warehouse_id == 2){   
        $dataw['quantity' ] = 0;
        $dataw['warehouse_id' ] = 1;
        }
       $iqty['item_id'] = $request->item_number;
       $iqty['quantity'] = $request->sum;
       $iqty['current_date'] = date('Y-m-d');
       $hiqty['item_id'] = $request->item_number;
       $hiqty['quantity'] = $request->sum;
       $hiqty['current_date'] = date('Y-m-d');
       $hiqty['login_user_id'] = Auth::user()->id;
       $hiqty['wareh_id'] = $request->warehouse_id;
       // $hiqty['current_date'] = date('y-m-d');
       $hiqty['transection_id'] = 1;
        item_quantity::create($iqty);
        item_quantity_hsty::create($hiqty);
       $unit = item::where('item_number',$request->item_number)->first('unit_id');
        $sites = sites::pluck('id');
        foreach ($sites as $site) {
          $sitemq= item_quantity::where('site_id',$site)->where('item_id',$request->item_number)->get();
          if(count($sitemq) == 0){
            $ziq['quantity'] = 0;
            $ziq['current_date'] = date('Y-m-d');
            $ziq['site_id'] = $site;
            $ziq['item_id'] = $request->item_number;
            $ziq['unit_id'] = $unit->unit_id;
            $ziq['wareh_id'] = 1;
            //return $ziq;
            $ziqw['quantity'] = 0;
            $ziqw['current_date'] = date('Y-m-d');
            $ziqw['site_id'] = $site;
            $ziqw['item_id'] = $request->item_number;
            $ziqw['unit_id'] = $unit->unit_id;
            $ziqw['wareh_id'] = 2;
            item_quantity::create($ziq);
            item_quantity::create($ziqw);
          }
        }
       //return $hiqty;

        $iditem = $request->item_number;
        $data['item_number'] = $iditem;
        $dataw['item_number'] = $iditem;
        //return $dataw;
       $item['quantity']= $request->quantity;
       $itemid = item::where('item_number',$iditem)->first();
       $data['item_id'] = $itemid->id;
       $dataw['item_id'] = $itemid->id;
       $qty = StoreItem::where('item_number',$iditem)->where('warehouse_id',$request->warehouse_id)->first();
       if($qty == null){
        StoreItem::create($data);
        StoreItem::create($dataw);
        $purch = prch_itemwise_requs::where(['prch_rfi_users_id'=>$request->ids,'item_no'=>$request->item_number])->update(['ready_to_dispatch'=>4,'dispatch_status'=>1,'from_warehouse'=>$request->warehouse_id]); //ready_to_dispatch ==4 means item now avaible to dispatch 
      }else{
        StoreItem::where('item_number',$iditem)->where('warehouse_id',$request->warehouse_id)->increment('quantity',$request->quantity);
        $purch = prch_itemwise_requs::where(['prch_rfi_users_id'=>$request->ids,'item_no'=>$request->item_number])->update(['ready_to_dispatch'=>4,'dispatch_status'=>1,'from_warehouse'=>$request->warehouse_id]); //ready_to_dispatch ==4 means item now avaible to dispatch 
      }


        return redirect()->back();

    }

    public function getwaredetails(Request $request){
      
       $qty = StoreItem::where('item_number',$request->it_no)->where('warehouse_id',$request->ware_id)->first();
        if($qty != ''){
          $data['add'] = $qty->quantity+$request->qty;
          $data['mgs'] = "Total Quantity In This warehouse will now ".$data['add'];
          return $data;
    }else{ 
       $data['add'] = $request->qty;
       $data['mgs'] = "Total Quantity In This warehouse will ".$request->qty;

       return $data;
    }


    }
    

    
}
