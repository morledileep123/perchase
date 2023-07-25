@extends('../layouts.sbadmin2')

@section('content')
<?php
	$user_id = request()->segment('2');
?>
   
<div class="container-fluid">
  <a href="{{ '/user_request' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Fill Quantity To Send</h5>
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
        @error('user')
              <div class="alert alert-danger">{{'User is required'}}</div>
          @enderror
        <h2><b>{{ $data->site->job_describe }}</b></h2>
            <form action="{{ route("manage-site-item") }}" method="post">
                             @csrf
         <div class="row mb-5">
             <div class="col-md-3">
             </div>
            <div class="col-md-6">
            @if($data->moved == 0 && $sitee == 'not found')
            <label><b>Resposible Person</b></label>
                {{-- @php 
                  $user = App\User::orderBy('name')->get();
                @endphp --}}
             <select name="user" class="form-control" required/>
                <option value="">select Engineer</option>
                @foreach($canuser as $usr)
                <option value="{{ $usr->user_id }}">{{ $usr->emp_name}}</option>
                @endforeach
             </select>
             @elseif($sitee == 'alreay')
             <label><b>{{ $user->users->name }} Is Already Responsible for site You Can Move Items </b></label><br>
             <lable>Quantity are Sending from {{ App\Warehouse::find($data->w_id)->name }} W-House </lable>
              <input type="hidden" name="user" value="{{ $user->user_id }}" class="form-control">
             @else
             <label><b>{{ $user->users->name }} Is Responsible For Item On Site</b></label><br>
             <lable>Quantity to site received By {{ App\Warehouse::find($data->w_id)->name }} W-House </lable>
             @endif
         </div>
         <div class="col-md-3">
             </div>
         </div>

	    <div class="row">
	      	<div class="col-md-12">
				<table class="table table-border" width="100%" id="userTable">
					<thead>
						<tr>
							<th>Item No.</th>
                            <th>Item Name</th>
                            <th></th>
                            <th>InStore qty</th>
                             @if($data->moved == 0)
							<th>Send Quantity</th>
                            @endif
                          {{--   <th>#Availability</th> --}}
                            <th></th>
						</tr>
					</thead>
					<tbody id="purchBody">
                        
                           <input type="hidden" name="vid" value="{{ $data->vender_id }}" class="form-control">
                           <input type="hidden" name="site_id" value="{{ $data->site_id }}" class="form-control">
                            <input type="hidden" name="rfi_id" value="{{ $data->rfi_id }}" class="form-control">
                            <input type="hidden" name="rfi_by" value="{{ $data->userinfo->user_id }}" class="form-control">
                            <input type="hidden" name="grcode" value="{{ $data->grcode }}" class="form-control">
                            <input type="hidden" name="qid" value="{{ $data->id }}" class="
                            form-control">
                            @php $i=1;@endphp
                      @if($data->not_prch != 0)
						@php $i=1;
                        $row = json_decode($data->items);

                         @endphp
                        
						@foreach($row as $res)
						<tr>
						 <td><input type="text" name="item_no[]" value="{{ Str::after($res->item_name, '|') }}" class="form-control item_no" readonly="" id=""></td>
                          <td><input type="text" name="item_name[]" value="{{ Str::before($res->item_name, '|') }}" class="form-control item_no" readonly="" id=""></td>
						 <td></td>
                         <input type="hidden" name="ware_id[]" value="{{ $data->w_id }}" class="form-control">
						 <td><input type="number" name="quantity[]" value="{{ $res->item_quantity }}" id="{{ "count".$i }}" class="form-control" readonly=""></td>
                         @if($sitee != 'founnd')
                          <td><input type="number" name="squantity[]" value="" class="form-control send" id="{{"sending".$i }}" min="1" required></td>
                          @else
                         
                          @endif
						 <input type="hidden" name="item_price[]" value="{{ $res->item_price }}" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_actual_amount[]" value="{{ $res->item_actual_amount }}" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_tax1_rate[]" value="{{ $res->item_tax1_rate }}" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_tax1_amount[]" value="{{ $res->item_tax1_amount }}" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_total_amount[]" value="{{ $res->item_total_amount }}" class="form-control sqty" id="" min="1">
                          {{-- <td> <a class="btn btn-danger" href="" >Remove</a></td> --}}
           
						</tr>
                        @php $i++; @endphp
						 @endforeach
                         @endif

                         @php $j = $i; @endphp 
                         @foreach($notin as $in)
                       <tr>
                         <td><input type="text" name="item_no[]" value="{{ $in->item_no }}" class="form-control item_no" readonly="" id=""></td>
                          <td><input type="text" name="item_name[]" value="{{ $in->item_name }}" class="form-control item_no" readonly="" id=""></td>
                         <td></td>
                         <input type="hidden" name="ware_id[]" value="{{ $in->form_wh }}" class="form-control">
                         <td><input type="text" name="quantity[]" id="{{ "count".$j }}" value="{{ App\Model\store_inventory\StoreItem::where(['item_number' =>$in->item_no,'warehouse_id' => $in->form_wh])->first()->quantity }}" id="" class="form-control" readonly /></td>
                         @if($sitee != 'founnd')
                          <td><input type="number" name="squantity[]" value="" class="form-control send" id="{{"sending".$j }}" min="1" required></td>
                          @else
                         
                          @endif
                          <input type="hidden" name="item_price[]" value="0" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_actual_amount[]" value="0" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_tax1_rate[]" value="0" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_tax1_amount[]" value="0" class="form-control sqty" id="" min="1">
                         <input type="hidden" name="item_total_amount[]" value="0" class="form-control sqty" id="" min="1">
                      </tr>
                      @php $j++; @endphp
                         @endforeach
					</tbody>
				</table>
			</div>
        </div>
        <div class="row">
            <div class="col-md-12">
                  @if($data->moved == 0)
                  <button class="btn btn-success">Move Item To Site</button>
                  @else
                  <P style="color:green">This Item Has Move to Respected Site</P>
                  @endif
              
                        </form>
                  
            </div>
        </div>
	  </div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
  $('.send').on('keyup', function(){
     var idnos = $(this).attr('id');
     var n = Array.from(idnos);
     var prev = parseInt($("#count"+n[7]).val());
     var rec = parseInt($("#sending"+n[7]).val());
     if(prev >= rec){
        return true;
     }else{
        alert("can not send quantity more than in w-house");
        $("#sending"+n[7]).val(0)
     }
   
    
  })
});
  </script>

@endsection

<style type="text/css">
	.avail-item-msg{
		color: #ad3636;
    	margin-left: 10px;
    	font-size: 12px;
    	font-weight: bold;
	}
</style>


