@extends('../layouts.sbadmin2')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Received Quotation</h5>
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

				<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item" style="width: 50%">
				    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><b>RFI Send Quotations</b></a>
				  </li>
				  <li class="nav-item" style="width: 50%">
				    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><b>Quotations Received From Vendors</b></a>
				  </li>
				</ul>

				<div class="tab-content" id="myTabContent">
				  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		          <thead>
		            <tr>
		              <th>S.No</th>
		              <th>Quotation Id</th>
		              <th>User Name</th>
		              <th>Send Date</th>
		              <th>Action</th>
		            </tr>
		          </thead>
		          <tbody>
		          		<?php //dd($vendor); ?>
		          		@if (!empty($rfq))
			              @foreach ($rfq as $row)
			              <tr>
			                <td>{{ ++$i }}</td>
			                <td>{{ $row->quotion_id }}</td>
			                @php $user = App\prch_itemwise_requs::where('prch_rfi_users_id',$row->quotion_sent_id)->first(); @endphp
			                <td>{{ $user->username->name }}</td>
			                <td>{{ Date('Y-m-d',strtotime($row->created_at)) }}</td>
			                <td>
			                  <a class="btn btn-success" href="{{ route('rfq.show',$row->id) }}" title="Sent Quotation"> <i class="fa fa-mail-forward"></i></a>
			                </td>
			              </tr>
			              @endforeach
			            @endif
		          </tbody>
		        </table>
		        {!! $rfq->links() !!}						
				  </div>


				  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		          <thead>
		            <tr>
		              <th>S.No</th>
		              <th>Quotation Id</th>
		              <th>Vendor Name</th>
		              <th>Quotation Rec. Date</th>
		              {{-- <th>Vendor's Count</th>
		              <th>Items Counts</th> --}}
		              <!-- <th>Manager</th>
		              <th>Level 1</th>
		              <th>Level 2</th> -->
		              <th>Quotation status by vendor</th>
		              <th>Action</th>
		            </tr>
		          </thead>
		          <tbody>
		          		
		          			<?php 
		          				if (!empty($data)){
				              	$n = 1;
				              	$arr = array();
				              	foreach ($data as $rows){
			              ?>
			              <tr>
			                <td>{{ $n++ }}</td>
			                <td>{{ $rows->quotation_id }}</td>
			                @php
			                $stats = App\QuotationApprovals::with('rfi_status','vendordettl')->where(['rfi_id'=>$rows->rfi_id,'level2_status'=>1])->first();
			               // dd($stats);
			                 
			                @endphp
			                 @if(is_null($stats))
			                <td>{{ $rows->vendordettl->firm_name }}</td>
			                @else
			                <td>{{ $stats->vendordettl->firm_name }}</td>
			                @endif
			                <td>{{ date('Y-m-d',strtotime($rows->created_at)) }}</td>
			               {{--  @php
			                $stats = App\QuotationApprovals::with('rfi_status')->select('quotation_id','rfi_id')->groupBy('quotation_id','rfi_id')->first();
			                dd($stats);
			                 
			                @endphp --}}
			                @if($rows->rfi_status->po_accept_status == 0)
			                <td class="text-primary">{{'Pendig Approval'}}</td>
			                @elseif($rows->rfi_status->po_accept_status == '1')
			                <td class="text-success">{{'Accepted'}}</td>
			                @else
			                 <td class="text-danger">{{'Declined'}}</td>
			                @endif
			                <td>
			                  <a class="btn btn-success" href="{{ route('receivedQuotation',$rows->rfi_id) }}" title="Received Quotation"><i class="fa fa-mail-reply"></i> </a>
			                </td>
			              </tr>
			              <?php } } ?>
		          </tbody>
		        </table>
		        {!! $rfq->links() !!}
				  </div>
				</div>
			</div>
    </div>
  </div>
</div>
@endsection