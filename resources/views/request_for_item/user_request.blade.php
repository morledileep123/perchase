@extends('../layouts.sbadmin2')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Users RFI Listing</h5>
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <?php //dd($MailStatus[0]->id); die; ?>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        	<thead>
            <tr>
              <th>S.No</th>
              <th>User Name</th>
              <th>Item Name</th>
              <th>Total Item</th>
              <th>Req. Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	@if (!empty($request_for_items))
            @php $i = 0; @endphp
            	@foreach($request_for_items as $row)
              {{-- {{ dd($row) }} --}}
            	<tr id="bgclr{{$row->id}}">
                <td>{{ ++$i }}</td>
                <td>{{ App\User::find($row->user_id)->name}}</td>
                <td>{{ $row->item }}</td>
                <td>{{ (substr_count($row->item,',')+1) }}</td>
                <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
               @php 
                 $all = App\prch_itemwise_requs::select("manager_status","level2_status","discard_status","prch_rfi_users_id","quotation_status","mng_squantity_status",DB::raw("count(manager_status) as item"))->where(['prch_rfi_users_id'=>$row->id])->groupBy('manager_status','level2_status','prch_rfi_users_id',"discard_status","quotation_status","mng_squantity_status")->first();
               // dd($all);
                 @endphp
                 @if($all->manager_status == 0 && $all->mng_squantity_status == 1)
                <td class="text-primary font-weight-bold">Pending</td>
                 @elseif($all->manager_status == 0 && $all->mng_squantity_status == 0)
                <td class="text-success font-weight-bold">Processed New Request</td>
                @elseif($all->manager_status == 1 && $all->discard_status == 1)
                <td class="text-danger font-weight-normal">Discared by Admin</td>
                @elseif($all->manager_status == 1 && $all->discard_status == 2)
                <td class="text-danger font-weight-bold">Discared by S-Admin</td>
                @elseif($all->manager_status == 1 && $all->quotation_status == 1 && $all->discard_status == 0)
                <td class="text-success font-weight-bold" >Quotation Received</td>
                 @elseif($all->manager_status == 1 && $all->level2_status == 1 && $all->discard_status == 0)
                <td class="text-info font-weight-bold" >Waiting To Send Quotation</td>
                @else
                <td class="text-success font-weight-bold" >waiting For Approval</td>
                @endif
                <td><a href="{{ route('check_rfi', $row->id) }}"><i class="fa fa-eye"></i></a></td>
              </tr>
              	<?php
	              	foreach($MailStatus as $key){
	              		if($key->quotion_sent_id == $row->id){
	              ?>
            		<style>
              		#bgclr{{$row->id}}{
              			background-color: #dcdab2;
              			opacity: 0.4;
              		}
              		.disbtn{{$row->id}}{
              			pointer-events:none;
              		}
								</style>
								<?php
								    }
								  }
							  ?>
              @endforeach
            @endif
          </tbody>
        </table>
        {{-- {{ $request_for_items->links() }} --}}
        {!! $request_for_items->links() !!}
      </div>
    </div>
  </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
