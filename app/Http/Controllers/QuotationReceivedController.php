<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;
use Illuminate\Support\Str;
use App\ResponsibleSiteUser;
use App\Member;
use App\VendorsMailSend;
use App\QuotationReceived;
use App\User;
use App\Vendormain;
use App\QuotationApprovals;
use App\QuotationApprovedById;
use App\PO_SendToVendors;
use App\prch_itemwise_requs;
use App\podetail;
use App\item_quantity;
use App\Acco_Qty_Used;
use App\EmpMast;
use App\Model\store_inventory\StoreItem;
use App\Notifications\RFQ_Notification;
use App\Mail\PO_SandsToVendor;
use App\Mail\SendMailAPProveQO;
use App\Mail\SendMailSAAPProveQo;
use App\Mail\SendMailToMangQuoApp;
use App\PurchaseStoreItem;

class QuotationReceivedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => array('VendorRFQFormData', 'VendorRFQFormDataStore')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
            
    		 $rfq = DB::table('prch_vendors_mail_sends')->distinct(['quotion_sent_id'])->paginate(10);
    		 $data = QuotationApprovals::with('rfi_status','vendordettl')->distinct(['quotation_id'])->paginate(10);
             return view('rfq.index',compact('rfq','data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorsMailSend  $VendorsMailSend
     * @return \Illuminate\Http\Response
     */
    public function show(VendorsMailSend $VendorsMailSend, $id)
    {

    //return $id;
        $requested = VendorsMailSend::Where('id',$id)->first();
        //$requestt = VendorsMailSend::Where('id',$id)->first();
        $pqitems = prch_itemwise_requs::where(['prch_rfi_users_id' =>$requested->quotion_sent_id,'remove_item_status'=>'0','form_wh' => '0'])->get();
        
        return view('rfq.show',compact('requested','pqitems'));
    }

    public function ReceivedQuotation($id){

    	   $vendor = QuotationApprovals::with('QuotationReceived.vendorsDetail')->where('rfi_id',$id)->get();
                return view('rfq.receivedQuotation',compact('vendor'));
    }

    public function VendorRFQFormData($id, $vid,$pidnew){
      //return $pidnew;
    		return view('rfq.vendor_form',compact('pidnew'));
    }

    public function VendorRFQFormDataStore(Request $request){
    		$count = count($request->item_name);	
		  	if($count != 0){
		  	 	$x = 0;
		  	 	while($x < $count){
		  	 		if($request->item_name[$x] !=''){
						  $quotationItemsTable[] = array(
					 				'item_name' => $request->item_name[$x]."|".$request->item_no[$x],
			            'item_quantity' => $request->item_quantity[$x],
			            'item_price' => $request->item_price[$x],
			            'item_actual_amount' => $request->item_actual_amount[$x],
			            'item_tax1_rate' => $request->item_tax1_rate[$x],
			            'item_tax1_amount' => $request->item_tax1_amount[$x],
			            'item_total_amount' => $request->item_total_amount[$x]
					 		);
					 		$data = array(
					 			'items' => json_encode($quotationItemsTable), 
					 			'quotion_id' => $request->quotion_id,
					 			'quotion_sends_id' => $request->quotion_sends_id,
					 			'vender_id' => $request->vender_id,
					 			'terms' => $request->terms,
					 			'rfi_id' => $request->rfi_id,
                                'site_id' => $request->site_id,

					 		);
						}			  
		  	 		$x++;
		  	 	}
                prch_itemwise_requs::where(['prch_rfi_users_id' =>$request->rfi_id,'site_id'=>$request->site_id])->update(['quotation_status' => 1]);
		  	 	QuotationReceived::create($data);
		  	 	$data1 = array(
			 			'quotation_id' => $request->quotion_id,
			 			'quote_id' => $request->quotion_sends_id,
			 			'vendor_id' => $request->vender_id,
			 			'rfi_id' => $request->rfi_id,
                        'site_id' => $request->site_id,
			 		);
                //return $data1;
			    QuotationApprovals::create($data1);
		  	}


    		return back()->with('success','Thank You for quotation, we will get back to you soon');
    }

    public function QuotationApproval(Request $request){
          //return $request->quotion_id;
          $usersmail = User::has('prchadmin')->get();
       foreach($usersmail as $mail){
         $uname = $mail->name;
        \Mail::to($mail->email)->send(new SendMailAPProveQo($uname));
        }

    		$manager_status = $request->manager_status;
    		$id = $request->quotion_id;
    		$arr = array(
    				'quotation_approval_id' => $id
    		);
    		QuotationApprovedById::create($arr);
    		QuotationApprovals::where('id', $id)->update(['manager_status'=> $manager_status]);
    }

    public function QuotationReceivedAtLevelOne(){
    		
        // $data = DB::table('prch_quotation_approvals')
        //     ->join('acco_vendor_mast', 'prch_quotation_approvals.vendor_id', '=', 'acco_vendor_mast.id')
        //     ->join('prch_quotation_receiveds', 'prch_quotation_approvals.quote_id', '=', 'prch_quotation_receiveds.quotion_sends_id')
        //     ->select('prch_quotation_approvals.*', 'acco_vendor_mast.*', 'prch_quotation_receiveds.*')
        //     ->where('prch_quotation_approvals.manager_status', '=', 1)->get(); //old before grop by
       $data = QuotationApprovals::with('venadmin','QuotationRadmin')->where('prch_quotation_approvals.manager_status', '=', 1)->get();
       //$data = QuotationApprovals::groupBy('quotation_id')->select('quotation_id', DB::raw('count(quotation_id) as total'))->get();

        

           // QuotationApprovals::select(DB::raw('count(quotation_id) as job_count'),'quotation_id')->groupBy('quotation_id')->get();
            

    		return view('rfq.quotationReceived_levelone',compact('data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function QuotationApprovalByLevelOne($id){
    		$vendor = QuotationApprovals::with('QuotationReceived.vendorsDetail')->where('rfi_id',$id)->get();
    		// return view('rfq.qa_level_one',compact('data','vendor','manager_approved')); old
            return view('rfq.qa_level_one',compact('vendor'));
    }

    public function QuotationApprovalByL1(Request $request){

        $uname = 'Mukesh';
        \Mail::to('mukesh@laxyo.org')->send(new SendMailSAAPProveQo($uname));

    		$level1_status = $request->level1_status;
    		$id = $request->ApprovalId;
    		QuotationApprovals::where('id', $id)->update(['level1_status'=> $level1_status]);
    }


    public function QuotationReceivedAtLevelTwo(){
    		$data = DB::table('prch_quotation_approvals')
            ->join('acco_vendor_mast', 'prch_quotation_approvals.vendor_id', '=', 'acco_vendor_mast.id')
            ->join('prch_quotation_receiveds', 'prch_quotation_approvals.quote_id', '=', 'prch_quotation_receiveds.quotion_sends_id')
            ->select('prch_quotation_approvals.*', 'acco_vendor_mast.*', 'prch_quotation_receiveds.*')
            ->where('prch_quotation_approvals.manager_status', '=', 1)->where('prch_quotation_approvals.level1_status', '=', 1)->get();
            
        return view('rfq.quotationReceived_leveltwo',compact('data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function QuotationApprovalByLevelTwo($id){
    		$vendor = QuotationApprovals::with('QuotationReceived.vendorsDetail')->where('rfi_id',$id)->get();

    		// return view('rfq.qa_level_two',compact('data','vendor','manager_approved')); old
            return view('rfq.qa_level_two',compact('vendor'));
    }

    public function QuotationApprovalByL2(Request $request){

        $usersmail = User::has('prchmanager')->get();
       foreach($usersmail as $mail){
         $uname = $mail->name;
        \Mail::to($mail->email)->send(new SendMailToMangQuoApp($uname));
        }
    		$level2_status = $request->level2_status;
    		$id = $request->ApprovalId;
    		QuotationApprovals::where('id', $id)->update(['level2_status'=> $level2_status]);
    }

    public function ApprovalQuotation(){
    		 $data = QuotationApprovals::with('QuotationReceived.vendorsDetail')
        				->where('manager_status',1)
        				->where('level1_status',1)
        				->where('level2_status',1)
        				->get();

        return view('rfq.approval_quotation',compact('data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function ApprovalQuotationItems($id){
        // return $id;
    	 $data = QuotationApprovals::with(['QuotationReceived.vendorsDetail','prchitemres.address'])->where('id',$id)->get();
            return view('rfq.approvalQuotation_item',compact('data'));
    }

    public function ApprovalQuotationItemSend(request $request,$id,$rfi){
        //return $rfi;
    		$tbl = $request->table;
    		$tbl1 = $request->terms;
    		$pdf = PDF::loadView('rfq.PO_mail_data', compact('tbl','tbl1'));
    		$pdf = $pdf->Output('', 'S');
            //$request->all();
    		$autoId = PO_SendToVendors::max('id');
			$nextval = $autoId+1;
  			$data = array(
  					'vendor_id'		=>	$id,
  					'approval_quotation_id' => $request->approval_quotation_id, 
  					'po_id'	=>	'#PO'.$nextval,
                    'app_rfi' =>  $rfi,
  			);
        // return $data; //avhi
  			$datas = PO_SendToVendors::create($data);
  			$vendor = Vendormain::find($id);
  			$details = array(
  				'table' => $request->table,
  				'pdf' => $pdf,
  				'vendor_data' => $vendor,
  				'po_id' => $nextval,
  			);
  			\Mail::to($vendor->email)->send(new PO_SandsToVendor($details));

  			return redirect()->route('approval_quotation')->with('success','Purchase Order and Mail sends successfully');
    }

    

    public function moreqitem(){
         
        $postore = QuotationReceived::where('po_stored',1)->orderBy('id','DESC')->paginate(10); 
        return view('rfq.moreqitem',compact('postore'));
    }

    public function msenditem($id){
        
        $postore = QuotationReceived::where('po_stored',1)->get(); 
        $send = ResponsibleSiteUser::with('users','musers','site')->where('manager_id','!=',Null)->get();
        return view('rfq.sendbymang',compact('send'));
    }

    public function itemreq($id){
        //return $id;
         // $qty = Acco_Qty_Used::with('item')->where('ref_id',$id)->get();
         $qty = podetail::with('itemname')->where('quo_id',$id)->get();
         return view('rfq.showhis',compact('qty'));
    }

    public function posites($id,$site){
       // return $id;
         $data = QuotationReceived::with(['userinfo','resuser.qtty'])->find($id);
         //dd(count($data->resuser->qtty));
         $notin = prch_itemwise_requs::where(['prch_rfi_users_id' =>$data->rfi_id,'remove_item_status'=>'0'])->where('form_wh','!=','0')->get();
         if($data->moved == 1){
         $user = ResponsibleSiteUser::with(['users','qtty'])->where('qid',$data->id)->first();
         if($user->user_status == 'deactive'){
                $user = ResponsibleSiteUser::with(['users','qtty'])->where('site_id',$site)->latest()->first();
               }
         $sitee = 'founnd';
         return view('rfq.sitestore',compact('data','user','sitee','notin'));
         }else{
           $count = count(QuotationReceived::where('site_id',$site)->get());
          if($count > 1){
              $Qi = QuotationReceived::where('site_id',$site)->where('moved','=',1)->first();
              if($Qi == null){
                $active = ResponsibleSiteUser::where('user_status','active')->pluck('user_id')->unique();
                $canuser = EmpMast::whereNotIn('user_id',$active)->where(['dept_id' =>'3','deleted_at'=> Null])->get();
                $sitee = 'not found';
                return view('rfq.sitestore',compact('data','sitee','notin','canuser'));
              }
              // need to review this code again test;
               $user = ResponsibleSiteUser::with(['users','qtty'])->where('qid',$Qi->id)->first();
               if($user->user_status == 'deactive'){
                $user = ResponsibleSiteUser::with(['users','qtty'])->where('site_id',$site)->latest()->first();
               }
                // need to review this code again test;
              $sitee = 'alreay';
              return view('rfq.sitestore',compact('data','sitee','user','notin'));
              
          }
         }
         //EmpMast::where('dept_id','3')->where('deleted_at',Null)->get();
       $active = ResponsibleSiteUser::where('user_status','active')->pluck('user_id')->unique();

         // $canuser = User::whereNotIn('id',$active)->orderBy('name')->get();
         $canuser = EmpMast::whereNotIn('user_id',$active)->where(['dept_id' =>'3','deleted_at'=> Null])->get();
         $sitee = 'not found';
         return view('rfq.sitestore',compact('data','sitee','notin','canuser'));

    }

    public function managesiteitem(Request $request){
         $data = $request->validate([
           'user' => 'required',
         ]);
         //return $request->all();

          $array = $request->item_no;
          $item_price = $request->item_price;
          $item_actual_amount = $request->item_actual_amount;
          $item_tax1_rate = $request->item_tax1_rate;
          $item_tax1_amount = $request->item_tax1_amount;
          $item_total_amount = $request->item_total_amount;
          $quantity = $request->quantity;
          $sendqty = $request->squantity;
          $ware_id = $request->ware_id;
          $count = count($array);
        
          for($i=0; $i<$count;$i++){
            if($item_price[$i] == 0 ){
                $data = podetail::select('tax_rate','price')->where('item_number',str::after($array[$i],'|'))->where(['send_from' => 0 ])->latest()->first();
                 $price = $data->price;
                 $taxerate = $data->tax_rate;
                 $send_from = 1;
            }else{
                $price = $item_price[$i]; 
                $taxerate = $item_tax1_rate[$i]; 
                $send_from = 0; 
            }
            
              $code = str::after($array[$i],'|');
              $str['item_number'] = $code;
              $str['price'] = $price;
              $str['actual_amt'] = $item_actual_amount[$i];
              $str['tax_rate'] = $taxerate;
              $str['tax_amt'] = $item_tax1_amount[$i];
              $str['t_amt'] = $item_total_amount[$i];
              $str['quantity'] = $quantity[$i];
              $str['vendor_id'] = $request->vid;
              $str['site_id'] = $request->site_id;
              $str['rfi_id'] = $request->rfi_id;
              $str['req_by'] = $request->rfi_by;
              $str['user_id'] = $request->user;
              $str['quo_id'] = $request->qid;
              $str['send_on_site'] = $sendqty[$i];
              $str['maganer_id'] = Auth::user()->id;
              $str['send_from'] = $send_from;


              podetail::create($str);
              //return $request->ware_id;
              $strid = StoreItem::where(['item_number'=>$code,'quantity'=>$quantity[$i],'warehouse_id' => $ware_id[$i]])->decrement('quantity' ,$sendqty[$i]);

               item_quantity::where(['site_id'=>$request->site_id,'item_number'=>$code,'wareh_id' => $ware_id[$i] ])->increment('quantity' ,$sendqty[$i]);

          }
          $res['user_id'] = $request->user;
          $res['site_id'] = $request->site_id;
          $res['manager_status'] = 1;
          $res['user_status'] = 'active';
          $res['manager_id'] = Auth::user()->id;
          $res['grcode'] = ($request->grcode == '') ? $request->grcode : 'from store';
          $res['qid'] = $request->qid;
          ResponsibleSiteUser::create($res);
         
     QuotationReceived::where('rfi_id',$request->rfi_id)->update(['moved'=>1]);
       return redirect()->back();
         
    }
}
