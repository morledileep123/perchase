<?php

namespace App\Http\Controllers;

use App\RequestForItem;
use Illuminate\Http\Request;
use Auth;
use App\Member;
use App\vendor;
use App\Vendormain;
use App\item;
use App\unitofmeasurement;
use App\RfiUsers;
use App\RfiManager;
use App\RfiDiscardReason;
use App\User;
use App\VendorsMailSend;
use App\Warehouse;
use App\IndentReq;
use App\sites;
use App\prch_itemwise_requs;
use App\QuotationReceived;
use App\Model\store_inventory\StoreItem;
use App\Notifications\RFQ_Notification;
use \App\Mail\SendMailToVendors;
use \App\Mail\SendMailToMang;
use \App\Mail\SendMailToAdmin;
use Laravel\LegacyEncrypter\McryptEncrypter;
use PDF;
use DB;
use Helper;

class RequestForItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
     $user_id = Auth::user()->id;
     $request_for_items = DB::table('prch_req_itemwise')
                 ->select("prch_rfi_users_id as id","created_at",DB::raw("string_agg(item_name,',') as item"))
                 ->where(['user_id'=>$user_id])
                 ->groupBy('prch_rfi_users_id','created_at')
                 ->get();
      
        return view('request_for_item.index', compact('request_for_items')); 

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $site = sites::where('deleted_at',NULL)->orderBy('job_describe')->get();
        return view('request_for_item.create', compact('site'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // $requestby = Auth::user()->name;
       

        $valid = $request->validate([
           'site_id' => 'required|integer',
        ]);

       $data =  $this->validate($request,[
         'item_name.*'   => 'required|distinct',
         'quantity.*'   => 'required|integer',
         'item_no.*'   => 'required',
         'expected_date.*'   => 'required|date',
        ]); 

        $id = Auth::user()->id;
        $user = User::find(Auth::user()->id);
        $rolename = $user->hasRole('purchase_user'); //$role[0]->name;

      	$count = count($request->item_name);	
		  	if($count != 0){
		  	 	$x = 0;
		  	 	$data = array();
		  	 	while($x < $count){
		  	 		if($request->item_name[$x] !=''){
						  $RequestForItem = array(
				  'item_name' => $request->item_name[$x],
			      'quantity' => $request->quantity[$x],
                  'item_no' => $request->item_no[$x],
			      'description' => $request->description[$x],
			      'user_id' => $request->user_id[$x],
                  'expected_date' => $request->expected_date[$x],
					);
					 	 $data[] = $RequestForItem;

						}
                     
		  	 		$x++;
		  	 	}

      
		  	 	$request_data = new RfiUsers;
    			$request_data->requested_data = json_encode($data);
    			$request_data->user_id = $id;
    			$request_data->requested_role = ($rolename == 'true') ? 'Users' : 'Manager';
    			$request_data->save();
                $lastid = $request_data->id;
        
                
    			$request_data = new RfiManager;
    			$request_data->data = json_encode($data);
    			$request_data->requested_id = $id;
    			$request_data->save();
       
               $i = 0;
          while($i < $count){
            if($request->item_name[$i] !=''){
              $itno = explode("|",$request->item_name[$i]);
            
              $itonly = intval($itno[1]);
              if(strlen($itonly) == 7){
                $itemns = '0'.$itonly;
              }else{
                $itemns = $itonly;
              }
            
              $itname = $itno[0];
              $idmain = DB::table('prch_items')->where('item_number', $request->item_no[$i])->value('id');
              $newdata = array(
                  'item_no' => $request->item_no[$i],
                  'quantity' => $request->quantity[$i],
                  'description' => $request->description[$i],
                  'user_id' => $request->user_id[$i],
                  'item_name' => $itname,
                  'prch_rfi_users_id' => $lastid,
                  'item_id' => $idmain,
                  'expected_date' => $request->expected_date[$i],
                  'requested_role' => ($rolename == 'true') ? 'Users' : 'Manager',
                  //'manager_status' => ($rolename == 'true') ? 0 : 1,
                  'squantity' => ($rolename != 'true') ? $request->quantity[$i] : 0,
                  'site_id' => $request->site_id,

              );

            prch_itemwise_requs::create($newdata);

            }       
            $i++;
          }
           $usersmail = User::has('prchmanager')->get();
       foreach($usersmail as $mail){
         $uname = $mail->name;
        \Mail::to($mail->email)->send(new SendMailToMang($uname));
        }

    		$member_details = $request_data->requested_id;
	        $details = Member::where('user_id',$member_details)->get();
	        $users = Member::whereIn('role_id',['22','23'])->get();
	        foreach ($users as $user) {
	        	$send_users = User::find($user->user_id);
	        	$send_users->notify(new RFQ_Notification($details));
	        }
		  	}
      	return redirect()->route('request_for_item.index')->with('success','Your RFI Added and mailed it to Manager successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequestForItem  $requestForItem
     * @return \Illuminate\Http\Response
     */
    public function show(RequestForItem $requestForItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RequestForItem  $requestForItem
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
    	 //$data = RfiUsers::with('discardReason')->where('id',$id)->first();
         $data = prch_itemwise_requs::where('prch_rfi_users_id',$id)->get();
      $requestForItem = $data;
       
        	return view('request_for_item.edit',compact('requestForItem'));
        //}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequestForItem  $requestForItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestForItem $requestForItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequestForItem  $requestForItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RfiUsers::find($id)->delete();
        return redirect()->route('request_for_item.index')->with('success','Your RFI deleted successfully');
    }

    public function UsersRequest()
    {
    		$uid = Auth::user()->id;
    		$request_for_items = DB::table('prch_req_itemwise')
                 ->select("user_id","prch_rfi_users_id as id","created_at",DB::raw("string_agg(item_name,',') as item"))
                 ->where('requested_role','Users')->where('remove_item_status','=',0)
                 ->groupBy('user_id','prch_rfi_users_id','created_at')
                 ->orderBy('created_at','DESC')
                 ->paginate(10); 
                 
            $MailStatus = VendorsMailSend::all();
            return view('request_for_item.user_request', compact('request_for_items', 'MailStatus'));   

          

      
      
    }

    public function dispatchitem(){
       
      // $request_for_items = RfiUsers::where('requested_role','Users')->where('level1_status','1')->where('level2_status','1')->latest()->paginate(10);
      $prch_item = prch_itemwise_requs::groupBy('prch_rfi_users_id')->selectRaw('count(*) as prch_rfi_users_id')->select('prch_rfi_users_id')->paginate(10);

         
      return view('request_for_item.dispatch_list',compact('prch_item'));
    }

    public function showdisitem($id){
        
      $items = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'ready_to_dispatch'=>4,'dispatch_status' =>1])->get();
     // dd($items);
      $user = DB::table('prch_req_itemwise')->where('prch_rfi_users_id', $id)->value('user_id');
      //return  $items;
      if($items->isEmpty() == false){
        $chk = '';
      return view('request_for_item.dispatch_item_detail',compact('items','user','chk'));
      }else{
        $chk = "item hasbeen Dispatch";
        return view('request_for_item.dispatch_item_detail',compact('items','user','chk'));

    }
    }

    public function dispatchtouser(Request $request,$id){
      $itemid = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'ready_to_dispatch'=>4,'dispatch_status' =>1])->get();
       foreach($itemid as $item){
       
        // if($item->item_no == 8 ){
        //     $iditem = $item->item_no;
        // }else{
        //    $iditem = "0".$item->item_no;
        // }
       $iditem = $item->item_no;
       $itemins1 = StoreItem::where(['item_number'=>$iditem,'warehouse_id'=>$item->from_warehouse])->first();
        if($itemins1 == ''){
          $qty13 = 0;
        }else{
           $qty13 = $itemins1->quantity;
        }
       
        if($qty13 >= $item->squantity){
       
           $itemleft1 = $itemins1->quantity - $item->squantity;//9000
            "warehouse1";
           $sqty = StoreItem::where(['item_number'=>$iditem,'warehouse_id'=>$item->from_warehouse])->decrement('quantity',$item->squantity);
           prch_itemwise_requs::where('prch_rfi_users_id',$id)->where('item_no',$item->item_no)->update(['dispatch_status'=>'2','dispatch_date'=>date('Y-m-d')]);
           $mgs = "Item Dispatch successfully";
        }

        else{

          $mgs  = "Cant Dispatch Item Is Not In Stock";

      }

      }
       if($mgs != 'Cant Dispatch Item Is Not In Stock' ){
      
      return redirect()->back()->with('success',$mgs);
    }else{
      return redirect()->back()->with('success',$mgs);
    }
   
    }

    public function disabletodispatch(){
      $prch_item = DB::table('prch_req_itemwise')
                 ->select("user_id","prch_rfi_users_id as id","created_at",DB::raw("string_agg(item_name,',') as item"))
                 ->where('requested_role','Users',)->where('remove_item_status','=',0)
                 // ->where('squantity','!=',0)
                 ->groupBy('user_id','prch_rfi_users_id','created_at')
                 ->orderBy('created_at','DESC')
                 ->paginate(10); 
      //dd($prch_item);

      return view('request_for_item.newquotation_list',compact('prch_item'));
    }

    public function showdisbleForquo($id){

      $items = prch_itemwise_requs::with('username')->where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0','form_wh' => '0'])->get();
      // dd($items);
      $notin = prch_itemwise_requs::with('username')->where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0'])->where('form_wh','!=','0')->get();
      // dd($notin);
       // return $notin;
      $MailStatus = VendorsMailSend::where('quotion_sent_id',$id)->first();
      return view('request_for_item.itemQuoation',compact('items','MailStatus','notin'));
    }

    public function directtosite($id){
       prch_itemwise_requs::with('username')->where('prch_rfi_users_id',$id)->where('remove_item_status','=',0)->update(['direct_send' => 1]);
         $data = prch_itemwise_requs::with('username')->where('prch_rfi_users_id',$id)->where('remove_item_status','=',0)->get();
               $whouse = $data[0]->form_wh;
              foreach($data as $data){
                    
                          $quotationItemsTable[] = array(
                                    'item_name' => $data->item_name."|".$data->item_no,
                                    'item_quantity' => $data->squantity,
                                    'item_price' => 0,
                                    'item_actual_amount' => 0,
                                    'item_tax1_rate' => 0,
                                    'item_tax1_amount' => 0,
                                    'item_total_amount' => 0,
                                );
                         $quotationItemsTable;
                            $record = array(
                                'items' => json_encode($quotationItemsTable), 
                                'quotion_id' => 00,
                                'quotion_sends_id' => 00,
                                'vender_id' => '137',
                                'terms' => '00-direct-send--00',
                                'rfi_id' => $id,
                                'site_id' => $data->site_id,
                                'po_stored' => '1',
                                'not_prch' => 0,
                                'w_id' => $whouse,
                            );
                        }
                       QuotationReceived::create($record);

                   
            
        return redirect()->back()->with('success','Item Send status updateed');
    }

    public function itemofstock(){

       $uhi = prch_itemwise_requs::where('user_id',Auth::id())->where(['dispatch_status'=>2])->paginate(10);

       return view('request_for_item.stockwithuser',compact('uhi'));
    }

    public function unitemofstock(){

      $uhi = prch_itemwise_requs::where('user_id',Auth::id())->where(['dispatch_status'=>1])->paginate(10);

       return view('request_for_item.unstockwithuser',compact('uhi'));
    }


    public function backtostore(Request $request, $id){
         $qis = intval($request->squantityuser);
         $getitmno = prch_itemwise_requs::find($id);
                    StoreItem::where(['item_number'=>$getitmno->item_no,'warehouse_id'=>$getitmno->from_warehouse])->increment('quantity',$qis);
                    prch_itemwise_requs::where('id',$id)->where('item_no',$getitmno->item_no)->decrement('squantity',$qis,['received_date' => date('yy-m-d')]); 
          return redirect()->back()->with('success','Your Item has been succefully store to wahouse'); 

    }

    public function ManagerRequest()
    {
        $uid =               Auth::user()->id;
        $request_for_items = RfiUsers::where('requested_role','Manager')->latest()->paginate(10);
        $MailStatus =        VendorsMailSend::all();
        return view('request_for_item.manager_request', compact('request_for_items', 'MailStatus'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function UsersRequestStatus(Request $request, $id)
    {
        $loggedin_Id = Auth::user()->id;
        $data = RfiUsers::with('discardReason')->where('id',$id)->get();
        $unit = unitofmeasurement::get();
            foreach ($data as $requestForItem) {
              $requested_user_id = $requestForItem->user_id; 
              $poid = $requestForItem->id; 
               $mem = User::where('id',$requested_user_id)->get();

              foreach ($mem as $mem_details) {
                return view('request_for_item.user_req_status',compact('requestForItem', 'mem_details', 'unit' ,'poid'));
              }
        }
    }

    public function CheckUsersRFI(Request $request, $id)
    {
    
        // $data = RfiUsers::with('discardReason')->where('id',$id)->get();
        $data = prch_itemwise_requs::with('quantitystored')->where(['prch_rfi_users_id'=>$id,'remove_item_status'=>0])->get();



         
        return view('request_for_item.check_users_rfi',compact('data'));
    }

    public function UsersRequestUpdate(Request $request, $id){
            $request->validate([
                'status' => 'required'
            ]);
 
            $count = count($request->item_name);    
            if($count != 0){
                $x = 0;
                $data = array();
                while($x < $count){
                    if($request->item_name[$x] !=''){
                      $RequestForItem = array(
                        'item_name' => $request->item_name[$x],
                        'quantity' => $request->quantity[$x],
                        'unit_id' => $request->unit[$x],
                        'description' => $request->description[$x],
                        'user_id' => $request->user_id,
                      );
                      $data[] = $RequestForItem;
                    }             
                    $x++;
                }
                $update_data = array(
                    'requested_data'    =>      json_encode($data),
                    'user_id'           =>      $request->user_id,
                    'manager_status'    =>      $request->status,
                );
                $manager_status =   $request->status;
                if($manager_status == 2){
                    $request->validate([
                        'discardReason' => 'required'
                    ]);
                    $reason = array(
                        'rfi_id' =>  $id,
                        'discard_reason'  =>  $request->discardReason,
                    );
                    RfiDiscardReason::create($reason);
                }
                RfiUsers::where('id', $id)->update(['requested_data'=> json_encode($data), 'user_id' => $request->user_id, 'manager_status' => $request->status,]);
            }
            return redirect()->route('user_request')->with('success','Your status has been updated');
    }

    public function ApplyForQuotation($id)
     {    
    // return "Abhishek" ;
          $quo = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0','form_wh' => '0'])->where('mstatusfq',1)->get();
    		 $vendor = Vendormain::orderBy('firm_name')->get();
	  		 $role = $quo[0]->requested_role;
    	  		if($role == 'Manager'){
    	  			$status = 0;
    	  		}else{
    	  			$status = 1;
    	  		}
	  		return view('request_for_item.applyforquotation',compact('quo','vendor'));
    }

    public function RfiQuotationToMail(Request $request, $id){
            //return $request->site_id;
    		$vendor_id = $request->vendor_id;
    		foreach ($vendor_id as $vendor_ids) {
    		 $vendor = vendormain::find($vendor_ids);
    			$tbl = $request->table;
    			$pdf = PDF::loadView('request_for_item.rfi_quotation', compact('tbl'));
    			$pdf = $pdf->Output('', 'S');
                $lft_item = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0'])->get();
                $uwh = prch_itemwise_requs::where('prch_rfi_users_id',$id)->update(['unavaible_in_wh'=>$request->warehouse_id]);
    		    $autoId = VendorsMailSend::max('id');
  				$nextval = $autoId+1;
    			$data = array(
    					'email'		=>		$vendor->email,
    					'quotion_id'	=>	'#RFQ0'.$nextval,
    					'quotion_sent_id' => $id
    					//'item_list'		=>	$rfq->requested_data
    			);

    			$datas = VendorsMailSend::create($data);
    			$quotion_id = $datas->id;
    			$details = array(
    				'table' => $request->table,
    				'name' => $vendor->firm_name,
    				'email' => $vendor->email,
    				'pdf' => $pdf,
    				'quotion_id' => $quotion_id,
    				'vendor_id' => $vendor->id,
                    'site_id' => $request->site_id,
                    'pitemnew' => $id,
    			);
        //var_dump($details); die;
    			\Mail::to($vendor->email)->send(new SendMailToVendors($details));
    		}
    		return redirect()->route('user_request')->with('success','Mail sends successfully');
    }

    public function fetch(Request $request)
    {
      if($request->get('query'))
      {

        $query = $request->get('query');
        $data = item::where('title', 'ILIKE', "%{$query}%")->orWhere('item_number', 'LIKE', "%{$query}%")->get();
        $output = '<ul class="dropdown-menu items-dropdown" style="display:block; position:relative">';
        if(count($data) != null)
        {
          foreach($data as $row)
          {
            $output .= '<li><a id="getItemID" href="?itemId='.$row->id.'" style="pointer-events: none;" value="'.$row->id.'">'.$row->title .' | '.$row->item_number.'|'.$row->description.'</a></li>';

          }
        }
        else
        {
          $output .= '<li><a href="JavaScript:void(0);">No Items available</a></li>';
        }
        $output .= '</ul>';
        echo $output;
      }
    }

    public function SetWareHouse(Request $request)
    { 
      //dd($request);
      $item_number = $request->item_num;
      $warehouse_id = $request->warehouse_id;
      $req_qty = $request->req_qty;

      $store_items = StoreItem::where('item_number', $item_number)->get();
      if(count($store_items) !== 0){
        foreach ($store_items as $store) {
          $wid = json_decode($store->warehouse_id);
          $wid1 = json_decode($store->warehouse_id);
          $quantity = json_decode($store->quantity);
          $count = count($wid);
          for ($i=0; $i < $count; $i++) { 
            if($warehouse_id == $wid[$i]){
              if($quantity[$i] >= $req_qty){
                return "";
              }elseif($quantity[$i] === "0"){
                return '0 item';
              }else{
                      if($i == 0){

                        return "only".$quantity[$i]." item is available in Indore and ".$quantity[1]."in Ratlam Warehouse";
                      }else {
                         return "only".$quantity[1]." item is available in Indore and ".$quantity[$i]."in Ratlam Warehouse";
                      }
                // return ' '.$lqty.'';
              }
            }
          }
        }
      }else{
        return $requsted_qty = 'item not available';
      }
    }

    public function managrapv(Request $request){
       
       $usersmail = User::has('prchadmin')->get();
       foreach($usersmail as $mail){
         $uname = $mail->name;
        \Mail::to($mail->email)->send(new SendMailToAdmin($uname));
        }

       $request->pid;
       $data['manager_status'] = '1';
       $mang['manager_status'] = '1';
       $mang['mstatusfq'] = '1';
       $mang['m_approve_date'] = date('Y-m-d');
       $mang['manager_id'] = Auth::user()->id;
       RfiUsers::where('id',$request->pid)->update($data);
       prch_itemwise_requs::where('prch_rfi_users_id',$request->pid)->update($mang);

        return true;
    }

    public function removereqitem($id){
        
        prch_itemwise_requs::where('id',$id)->update(['remove_item_status'=>1]);
         return redirect()->back()->with('success','Item is successfully Removed');
    }


    public function filterdisquo(Request $request ,$id){
       //return $request->squantity[0];
       $valid = $request->validate([
           'squantity.*' => 'required|numeric',
        ]);

      //return $valid;
       $count = count($request->item_no);
            for($x=0;$x < $count; $x++){
            $pow = StoreItem::where('item_number',$request->item_no[$x])->where('quantity','>=',$request->squantity[$x])->first();
             if(!is_null($pow)){
                    $data['squantity'] = $request->squantity[$x];
                    $data['able_status'] = 'present';
                    $data['form_wh'] = $pow->warehouse_id;
                    $data['mng_squantity_status'] = 1;
                    prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'item_no'=>$request->item_no[$x]])->update($data);
                  }else{
                    $data['squantity'] = $request->squantity[$x];
                    $data['able_status'] = 'absent';
                    $data['form_wh'] = 0;
                    $data['mng_squantity_status'] = 1;

                     prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'item_no'=>$request->item_no[$x]])->update($data);
                  }


                }
             
                 return redirect()->back();

    }

    public function getstoreinfo(Request $request){
           // return $item_no = $request->prchid;
           $quanty = StoreItem::where('item_number',$item_no)->get();
           foreach($quanty as $quanty){
            prch_itemwise_requs::where(['prch_rfi_users_id'=>$request->prchid,'item_no'=>$request->item_no])->update(['squantity'=>$request->squantity[$x]]);

           }

        
    }

  

    public function uprfi_le_one(Request $request){
        $up['manager_status'] = '1';
        $status = RfiUsers::where('id',$request->id)->update($up);
        if(strlen($request->itemid != 8)){
        $warehouse = StoreItem::where('item_number','0'.$request->itemid)->where('warehouse_id',$request->wid)->first();
      }else{
        $warehouse = StoreItem::where('item_number', $request->itemid)->where('warehouse_id',$request->wid)->first();
      }
        if($warehouse == ''){
          $qty = 0; 
        }else{
          $qty = $warehouse->quantity; 
        }
          return $qty."||".$request->mgs;

    }

   public function uprfiaddress(Request $request){
          $data['address_wareh_id'] = $request->id;
          return RfiUsers::where('id',$request->itemid)->update($data);

   } 
}
