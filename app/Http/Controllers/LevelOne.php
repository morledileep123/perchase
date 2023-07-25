<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\unitofmeasurement;
use App\Member;
use App\RfiUsers;
use App\RfiManager;
use App\RfiDiscardReason;
use App\prch_itemwise_requs;
use \App\Mail\SendMailToSadmin;
use DB;

class LevelOne extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ManagerApproval()
    {        
         $requested = DB::table('prch_req_itemwise')
                 ->select("manager_id","prch_rfi_users_id as id","m_approve_date","discard_status","level1_status","level2_status",DB::raw("string_agg(item_name,',') as item"))
                 ->where('manager_status',1)->where('remove_item_status','=',0)
                 ->groupBy('manager_id','prch_rfi_users_id','m_approve_date',"level1_status","level2_status","discard_status")
                 ->orderBy('m_approve_date','DESC')
                 ->paginate(10); 
                 //return (count($requested));
        $mngreq = DB::table('prch_req_itemwise')
                 ->select("user_id","prch_rfi_users_id as id","created_at",DB::raw("string_agg(item_name,',') as item"))
                 ->where('requested_role','Manager')->where('remove_item_status','=',0)
                 ->groupBy('user_id','prch_rfi_users_id','created_at')
                 ->get();
    		return view('level_one.manager_approval', compact('requested','mngreq'));
	  }

	  public function LevelOneApproval($id){

          

              $mang['adminstatusfq'] = '1';
	  		$data = RfiUsers::with('discardReason')->where('id',$id)->get();
            prch_itemwise_requs::where('prch_rfi_users_id',$id)->update($mang);
            $quo = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0','form_wh' => '0'])->get();
            $notin = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0'])->where('form_wh','!=','0')->get();
            $count = count($notin);
	  		 $role = $data[0]->requested_role;
            //$unit = unitofmeasurement::get();
	  		if($role == 'Manager'){
	  			$status = 0;
	  		}else{
	  			$status = 1;
	  		}
	  		//$requested = RfiUsers::where('id',$id)->where('requested_role',$role)->where('manager_status',$status)->get();
            $requested = prch_itemwise_requs::where('prch_rfi_users_id',$id)->where('requested_role',$role)->where('manager_status',$status)->get();
	  		return view('level_one.edit_levelone_approval',compact('requested','quo','notin','count'));
	  }

      public function discardreason(Request $request, $id){
          $data = $request->validate([
                'discardReason' => 'required'
            ]);
          $reason = array(
                'rfi_id'   =>  $id,
                'discard_reason'  =>  $request->discardReason,
                'level1_discard'  =>  1,
            );
          RfiDiscardReason::create($reason);
          RfiUsers::where('id',$id)->update(['discard_status'=> 1]);
           prch_itemwise_requs::where(['prch_rfi_users_id'=>$id])->update(['discard_status'=> 1,'admin_id'=> Auth::user()->id,'a_approve_date'=> date('Y-m-d')]);

          return redirect()->route('manager_approval')->with('success','Your status for Discard has been updated');


         

      }

	  public function UpdateLevelOneApproval(Request $request, $id){
         //return $id;
	  		$request->validate([
            'status' => 'required'
        ]);
        $status = $request->status;
        if($status == 2){
            $request->validate([
                'discardReason' => 'required'
            ]);
            $reason = array(
                'rfi_id'   =>  $id,
                'level1_discard'  =>  $request->discardReason,
            );
            RfiDiscardReason::create($reason);
        }
        RfiUsers::where('id',$id)->update(['level1_status'=> $status]);
        prch_itemwise_requs::where(['prch_rfi_users_id'=>$id])->update(['level1_status'=> $status,'admin_id'=> Auth::user()->id,'a_approve_date'=> date('Y-m-d')]);
        // $usersmail = User::has('prchmanager')->get();
       // foreach($usersmail as $mail){
         $uname = 'Mukesh';
        \Mail::to('mukesh@laxyo.org')->send(new SendMailToSadmin($uname));
        // }
        return redirect()->route('manager_approval')->with('success','Your status has been updated-&&-Mailed To Super-Admin');
	  }
}
