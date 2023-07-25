@extends('../layouts.sbadmin2')
@section('content')
<?php
	$user_id = request()->segment('2');
?>
<div class="container-fluid">
  <a href="{{ '/user_request' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Quotation Item Details</h5>
  <div class="card shadow mt-3">
    <div class="card-body">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('alert'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif
         @if(!($items)->isempty())
        <h2><b>{{ "Sites-:".App\sites::find($items[0]->site_id)->job_describe }}</b></h2>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Requested  By</label>
                <input class="form-control" value="{{ $items[0]->username->name }}" readonly="">
            </div>
        </div>
    
	    <div class="row">
	      	<div class="col-md-12">
                <h4>Listed Item is not in w-house Send Approve</h4>
				<table class="table table-border" width="100%" id="userTable">
					<thead>
						<tr>
							<th>#Item Number</th>
							<th>#Name</th>
                           {{--  <th>#In W-house</th> --}}
							<th>#Purchasing Qty</th>
						</tr>
					</thead>
					<tbody id="purchBody">
						@php 
						$i=0;
						@endphp
						@foreach($items as $item)
						<tr>
						<td><input type="text" class="form-control" value="{{ $item->item_no }}" readonly="">
						<input type="hidden" class="form-control itemno" name="itemid[]" value="{{ $item->item_id }}" readonly=""></td>
						<td>{{ $item->item_name }}</td>
                       {{--  <td>ery</td> --}}
						<td id="{{ "itemqt".$i++ }}"><span class="avail-item-msg" id=""><input type="text" value="{{ $item->squantity }}" class="form-control quantity" name="qty[]" readonly=""></span></td>
						</tr>
						@endforeach
						<input type="hidden" name="item_num[]" value="" class="item">
						<input type="hidden" name="req_qty[]" value="" class="itemqty">
						<input type="hidden" value="{{ $item->prch_rfi_users_id }}"  id="impid">
                        <input type="hidden" value="{{ $item->site_id }}"  name="site_id">
					</tbody>
				</table>
			</div>
        </div>

       
	    	  <div class="row">
            <div class="col-md-12">
       {{-- <h4>Listed Item is in w-house will send after purchase</h4> --}}
                <div class="p-2 text-center">
                	<?php 
                	if($item->manager_status == 0 ){  ?>
                	 <button class="btn btn-success" id="approveit">Approve</button>
                	 <?php } else if($item->manager_status == 1 && $item->level1_status == 1 && $item->level2_status == 1 ){ ?>

                   <?php if(!empty($MailStatus)) { ?>
                    <button class="btn btn-primary" disabled >Qutation Send</button>
                  <?php }else { ?>
                   <a class="btn btn-primary" href="{{ route('applyforquotation',$item->prch_rfi_users_id) }}">Send Qutation Request</a>
                  <?php } ?>
                  
                    <?php }else{ ?>
                    @if($item->discard_status == 1)
                     <button class="btn btn-Danger" id="">Discard By Admin</button>
                     @elseif($item->discard_status == 2)
                     <button class="btn btn-warning" id="">Discard By SAdmin</button>
                     @else
                     <button class="btn btn-success" id="">Send For Approval To Admin</button>
                     @endif
                     <?php } ?>
                  
               
                <input type="hidden" value="{{-- {{ $id }} --}}"  id="impid">
                    {{-- <button class="btn btn-danger"> Send For Approval</button>
                   --}}
                </div>
                 </div>
      </div>
      @else
      <p>Item is avaiable in w-house you can move it to Respected site</p>
      @endif
  </div>
          <div class="card-body">
                @if(!($notin)->isempty())
                <div class="row">
                 <div class="col-md-12">
                <table class="table table-border" width="100%" id="userTable">
                    <thead>
                        <tr>
                            <th>#Item Number</th>
                            <th>#Name</th>
                           {{--  <th>#In W-house</th> --}}
                            <th>#Purchasing Qty</th>
                        </tr>
                    </thead>
                    <tbody id="purchBody">
                        @php 
                        $i=0;
                        @endphp
                        @foreach($notin as $itme2)
                        <tr>
                        <td><input type="text" class="form-control" value="{{ $itme2->item_no }}" readonly="">
                        <input type="hidden" class="form-control itemno" name="itemid[]" value="{{ $itme2->item_id }}" readonly=""></td>
                        <td>{{ $itme2->item_name }}</td>
                        {{-- <td>ery</td> --}}
                        <td id="{{ "itemqt".$i++ }}"><span class="avail-item-msg" id=""><input type="text" value="{{ $itme2->squantity }}" class="form-control quantity" name="qty[]" readonly=""></span></td>
                        </tr>
                        @endforeach
                        <input type="hidden" name="item_num[]" value="" class="item">
                        <input type="hidden" name="req_qty[]" value="" class="itemqty">
                        <input type="hidden" value="{{ $itme2->prch_rfi_users_id }}"  id="impid">
                        <input type="hidden" value="{{ $itme2->site_id }}"  name="site_id">
                    </tbody>
                </table>
            </div>
            @if(($items)->isempty())
             <h4><b>{{ "Sites-:".App\sites::find($notin[0]->site_id)->job_describe }}</b></h4>&nbsp&nbsp&nbsp&nbsp
        
                <label>Requested  By-{{ $notin[0]->username->name }}</label>
               
                   @if($itme2->direct_send == 0)
                   <a class="btn btn-info" href="{{ route('direct-to-site',$itme2->prch_rfi_users_id) }}">Direct Send</a>
                   @endif
            @endif
        </div>
        @else
        <h4>Purchase Inventory</h4>
        @endif
            
	</div>
</div>
@endsection

<style type="text/css">
	.avail-item-msg{
		color: #ad3636;
    	margin-left: 10px;
    	font-size: 12px;
    	font-weight: bold;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
$('document').ready(function(){
    $('#approveit').on('click',function(){
          var pid = $('#impid').val();
          // alert(pid);
          $.ajax({
                 type: "GET",
                 url: "{{ route('managr-apv') }}",
                 data: {'pid':pid},
                 success: function(res){
                   console.log(res);
                 }

             });
          location.reload();


        });
});
</script>
