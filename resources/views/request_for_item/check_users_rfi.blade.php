@extends('../layouts.sbadmin2')

@section('content')
<?php
	$user_id = request()->segment('2');
?>
   
<div class="container-fluid">
  <a href="{{ '/user_request' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Select Items</h5>
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
        <h2><b>{{ "Site-:".App\sites::find($data[0]->site_id)->job_describe }}</b></h2>
        <div class="row">
            <div class="form-group col-md-6">
                <label>User Name</label>
                <input class="form-control" value="{{ $data[0]->username->name }}" readonly="">
            </div>
            <div class="form-group col-md-6">
                <label>Email</label>
                <input class="form-control" value="{{ $data[0]->username->email }}" readonly="">
            </div>
        </div>

	    <div class="row">
	      	<div class="col-md-12">
				<table class="table table-border" width="100%" id="userTable">
					<thead>
						<tr>
							{{-- <th>#</th> --}}
                            <th>Item Number</th>
							<th>Name</th>
							<th>R-Qty</th>
                            <th>IN W-house</th>
                            <th>Purchasing qty</th>
							<th>Expect Dt.</th>
                          {{--   <th>#Availability</th> --}}
                            <th></th>
						</tr>
					</thead>
					<tbody id="purchBody">
                        <form action="{{ route('filter_dis_quo',$data[0]->prch_rfi_users_id)}}" method="post">
                             @csrf
                             <input type="hidden" id= "prchid" value="{{ $data[0]->prch_rfi_users_id }}">
						@php $i=1; @endphp
						@foreach($data as $res)
                        {{-- {{dd($res->quantitystored)}} --}}
						<tr>
                        {{-- <td><input type="checkbox" name="chk[]" value="" class="form-control" readonly="" id="{{ "check".$i }}" ></td> --}}
						 <input type="hidden" name="item_no[]" value="{{ $res->item_no }}" class="form-control item_no" readonly="" id="{{ "item_".$i }}">
                         <td><span>{{ $res->item_no }}</span></td>
						 <td>{{ $res->item_name }}</td>
						 {{-- <td><input type="number" value="{{ $res->quantity }}" class="form-control" readonly=""></td> --}}
                         <td><span>{{ $res->quantity }}</span></td>
                         <td>
                         <div class="row">
                            <input type="hidden" name="whcount" value="{{ count($res->quantitystored) }}" class="form-control" readonly="" >
                          <div>@foreach($res->quantitystored as $quan)
                              @if($quan->warehouse_id == 1)
                                <span @if($quan->quantity > 0) style="color:green" @else style="color:orange" @endif>{{ "w1:".$quan->quantity }}</span>
                                <input type="hidden" name="item_v_w[]" value="{{ $quan->quantity }}" class="form-control item_no" readonly="" id="{{ "item_".$i }}">

                               @elseif($quan->warehouse_id == 2)
                                <span @if($quan->quantity > 0) style="color:green" @else style="color:orange" @endif>{{ "w2:".$quan->quantity }} </span>
                                <input type="hidden" name="item_v2_w2[]" value="{{ $quan->quantity }}" class="form-control item_no" readonly="" id="{{ "item_".$i }}">
                               @endif
                         @endforeach
                            @if(($res->quantitystored)->Isempty())
                             <span style="color:red">{{ "w1,w2 Null"}}</span>
                             <input type="hidden" name="item_v3_w3[]" value="0" class="form-control item_no" readonly="" id="{{ "item_".$i }}">
                             @endif
                         </div>
                         </div>
                     </td>
                          <td><input type="text" name="squantity[]" value="{{ $res->squantity }}"  class="form-control send" id=" {{"pass".$i }}"  min="1"></td>
						 <td>{{ $res->expected_date }}</td>
            
             @if($res->squantity > 0)
                          <td class="text-success">Approved it For Next Admin Level</td>
                          @else
                          <td> <a class="btn btn-danger" href="{{ route('remove_reqitem',$res->id) }}" >Remove</a></td>
                 @endif
						</tr>
                        @php $i++; @endphp
						 @endforeach
                       
                       
					</tbody>
				</table>
			</div>
        </div>
        <div class="row">
            <div class="col-md-12">
                   @if($res->squantity <= 0) 
                 <input class="btn btn-info" type="submit" value="Send To Admin" id="chkwar">
                 </form>
                 @elseif($res->squantity >= 0 && $res->direct_send == 1)
                  <p class="text-warning font-weight-bold text-center">Check Move Requested Items</p>
                   @elseif($res->squantity >= 0 && $res->manager_status == 1  && $res->mng_squantity_status == 1  && $res->quotation_status == 0 && $res->discard_status == 0 && $res->level2_status == 1)
                   <p class="text-success font-weight-bold text-center">Waiting to send Quotation</p>
                    @elseif($res->squantity >= 0 && $res->manager_status == 1  && $res->mng_squantity_status == 1  && $res->quotation_status == 0 && $res->discard_status == 0)
                   <p class="text-warning font-weight-bold text-center">Request is Waiting for Approval</p>
                    @elseif($res->squantity >= 0 && $res->manager_status == 1  && $res->quotation_status == 1  && $res->mng_squantity_status == 1)
                   <p class="text-success font-weight-bold text-center">Item Has been Send for Purchased</p>
                   @elseif($res->squantity >= 0 && $res->manager_status == 1  && $res->quotation_status == 0  && $res->mng_squantity_status == 1 && $res->discard_status == 2)
                   <p class="text-danger font-weight-bold text-center">Discared By Super-Admin</p>
                   @elseif($res->squantity >= 0 && $res->manager_status == 1  && $res->quotation_status == 0  && $res->mng_squantity_status == 1 && $res->discard_status == 1)
                   <p class="text-danger font-weight-normal text-center">Discared By Admin</p>
                 @else
                 <p class="text-secondary font-weight-bold text-center">Manager Approval Required</p>
                 @endif
                        

                
                  
             
            </div>
        </div>
	  </div>
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
<script type="text/javascript">
 $(document).ready(function(){
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  });
  $('.send').on('blur', function(){
     var idnos = $(this).attr('id');
     var n = Array.from(idnos);
     var item_no = $("#item_"+n[5]).val();
     var prchid = $("#prchid").val();
     $.ajax({
                url: "{{  route('getstore_info')}}",
                type: 'GET',
                 data: {'item_no':item_no,'prchid':prchid},
                success: function (data) {
                 console.log(data);
              
                }
            })
     
   
    
  })
});
  </script>


