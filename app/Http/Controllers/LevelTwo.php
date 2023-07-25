<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RfiUsers;
use App\User;
use App\RfiDiscardReason;
use App\unitofmeasurement;
use App\prch_itemwise_requs;
use \App\Mail\SendManagermail;
use Auth;
Use DB;

class LevelTwo extends Controller
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

    public function LevelTwoApproval()
    {      
           $requested = DB::table('prch_req_itemwise')
                 ->select("admin_id","prch_rfi_users_id as id","a_approve_date","discard_status","level1_status","level2_status",DB::raw("string_agg(item_name,',') as item"))
                 ->where('level1_status',1)->where('remove_item_status','=',0)
                 ->orderBy('a_approve_date','DESC')
                 ->groupBy('admin_id','prch_rfi_users_id','a_approve_date','discard_status','level1_status','level2_status')
                 ->orderBy('a_approve_date','DESC')
                 ->paginate(10); 
    		return view('level_two.items_approval', compact('requested'));
    }

    public function EditLevelTwoApproval($id)
    {     
          // return "ewt";
       $mang['sadminstatusfq'] = '1';
	  		$data = RfiUsers::with('discardReason')->where('id',$id)->get();
            prch_itemwise_requs::where('prch_rfi_users_id',$id)->update($mang);
            $quo = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0','form_wh' => '0'])->get();
             $notin = prch_itemwise_requs::where(['prch_rfi_users_id'=>$id,'remove_item_status'=>'0'])->where('form_wh','!=','0')->get();
	  		$role = $data[0]->requested_role;
            //$unit = unitofmeasurement::get();
	  		if($role == 'Manager'){
	  			$status = 0;
	  		}else{
	  			$status = 1;
	  		}
	  		//$requested = RfiUsers::where('id',$id)->where('requested_role',$role)->where('manager_status',$status)->get();
            $requested = prch_itemwise_requs::where('prch_rfi_users_id',$id)->where('manager_status',$status)->get();
            //dd($requested);
	  		return view('level_two.edit_leveltwo_approval',compact('requested','quo','notin'));
	}

    public function discardreasonadmin(Request $request,$id){

        $data = $request->validate([
                'discardReason' => 'required'
            ]);
          $reason = array(
                'rfi_id'   =>  $id,
                'discard_reason'  =>  $request->discardReason,
                'level2_discard'  =>  1,
            );
          // return $reason;
          RfiDiscardReason::create($reason);
          RfiUsers::where('id',$id)->update(['discard_status'=> 2]);
           prch_itemwise_requs::where(['prch_rfi_users_id'=>$id])->update(['discard_status'=> 2,'admin_id'=> Auth::user()->id,'a_approve_date'=> date('Y-m-d')]);
            return redirect()->route('items_approval')->with('success','Your status Discrad updated successfully');
    }

	public function UpdateLevelTwoApproval(Request $request, $id) 
	{
        $usersmail = User::has('prchmanager')->get();
       foreach($usersmail as $mail){
         $uname = $mail->name;
        \Mail::to($mail->email)->send(new SendManagermail($uname));
        }


	  	$request->validate([
            'status' => 'required'
        ]);
        $status = $request->status;
       // dd($status); Avhishek
        if($status == 2){
            $request->validate([
                'discardReason' => 'required'
            ]);
            $reason = array(
                'rfi_id'   =>  $id,
                'level2_discard'  =>  $request->discardReason,
            );
            RfiDiscardReason::create($reason);
        }
        //RfiUsers::where('id',$id)->update(['level2_status'=> $status]);
        prch_itemwise_requs::where(['prch_rfi_users_id'=>$id])->update(['level2_status'=> $status]);
        return redirect()->route('items_approval')->with('success','Your status has been updated and mail send to Manger');
	}
}
