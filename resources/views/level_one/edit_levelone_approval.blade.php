@extends('../layouts.sbadmin2')

@section('content')
<div class="container-fluid">
    <a href="{{ '/request_for_item' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
    <h5 class="main-title-w3layouts mb-2">Update User RFI</h5>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Warning!</strong> Please check your input code<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
						<?php //print_r($requested[0]->requested_data); die; ?>
            <form action="{{ route('update_levelone_approval',$requested[0]->prch_rfi_users_id) }}" method="post">
                @csrf
                @method('PUT')
                <p><b>Update Status</b></p>
                @if($requested[0]->discard_status != 1)
                <div class="row" id="row">
                    <div class="form-group col-md-4">
                      <label class="container green">Approve
											  <input type="radio" name="status" value="1" @if($requested[0]->level1_status == 1) checked @endif >
											  <span class="checkmark"></span>
											</label>
                    </div>
                    @if($requested[0]->level1_status == 0)
                    <div class="form-group col-md-4">
                      <label class="container yellow">Pending
											  <input type="radio" name="status" value="0" @if($requested[0]->level1_status == 0) checked @endif >
											  <span class="checkmark"></span>
											</label>
                    </div>
                    <div class="form-group col-md-4">
                      <label class="container red">Discard
											  <input type="radio" name="status" value="2" @if($requested[0]->level1_status == 2) checked @endif data-toggle="modal" data-target="#myModal" id="dismissResponce">
											  <span class="checkmark"></span>
											</label>
                    </div>
                    @endif
                </div>
                @else
                <div class="row" id="row">
                  <div class="form-group col-md-4">
                      <label class="container red">Discard
                        <input type="radio" name="status" value="2" @if($requested[0]->discard_status == 1) checked @endif data-toggle="modal" data-target="" id="dismissResponce">
                        <span class="checkmark"></span>
                      </label>
                    </div>
                </div>
                @endif
                <h2><b>{{ "Sites-:".App\sites::find($quo[0]->site_id)->job_describe }}</b></h2>
                <h4>Listed item is not in w-house Pls Approve to Purchase</h4>
                <table id="invoice-item-table" class="table table-bordered">
			            <tr>
			              <th>S.No</th>
			              <th>Item Name</th>
			              <th>Quantity</th>
			              <th>Description</th>
			              <th></th>
			            </tr>
			            <?php
                        $m=1; 
                    foreach ($quo as $res) { ?>
                  
			            <tr>
			              <td>
			              	<span id="sr_no"><?= $m++ ?></span>
			              </td>
			              <td>
			              	<input type="text" name="item_name[]" id="item_name" class="form-control input-sm" value="{{ $res->item_name }}" readonly />
			              </td>
			              <td>
                      <div class="row">
                        {{-- @if($res->requested_role == 'Users') --}}
                        <div class="col-md-8">
			              	    <input type="number" name="quantity[]" id="quantity" data-srno="" class="form-control input-sm quantity" value="{{ $res->squantity }}" readonly />
                        </div>
                        {{-- @elseif($res->requested_role == 'Manager')
                        <div class="col-md-8">
                          <input type="number" name="quantity[]" id="quantity" data-srno="" class="form-control input-sm quantity" value="{{ $res->quantity }}" readonly />
                        </div>
                         @endif --}}
                      </div>
			              </td>
			              <td>
			              	<textarea name="description[]" id="description" data-srno="" class="form-control input-sm number_only description" readonly >{{ $res->description }}</textarea>
			              </td>
			              <td></td>
			            </tr>
			           <?php } ?>
			          </table>
			          <input type="hidden" name="user_id" value="" />
			          <input type="hidden" name="req_user_table_id" value="" />
                 @if($requested[0]->discard_status == 1)
                <button type="button" name="" class="btn btn-danger error-w3l-btn px-4">Discarded</button>
                @elseif($requested[0]->discard_status == 2)
                <button type="" name="" class="btn btn-warning error-w3l-btn px-4">Discarded By SAdmin</button>
                @else
                <button type="submit" name="submit" class="btn btn-primary error-w3l-btn px-4">Submit</button>
                @endif
            </form>
            @if(!is_null($notin))
            <h4>Listed item is in W-house This Will send After purchase </h4>
                 <table id="invoice-item-table" class="table table-bordered">
                  <tr>
                    <th>S.No</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th></th>
                  </tr>
                  <?php
                        $m=1; 
                    foreach ($notin as $res) { ?>
                  
                  <tr>
                    <td>
                      <span id="sr_no"><?= $m++ ?></span>
                    </td>
                    <td>
                      <input type="text" name="item_name[]" id="item_name" class="form-control input-sm" value="{{ $res->item_name }}" readonly />
                    </td>
                    <td>
                      <div class="row">
                        {{-- @if($res->requested_role == 'Users') --}}
                        <div class="col-md-8">
                          <input type="number" name="quantity[]" id="quantity" data-srno="" class="form-control input-sm quantity" value="{{ $res->squantity }}" readonly />
                        </div>
                        {{-- @elseif($res->requested_role == 'Manager')
                        <div class="col-md-8">
                          <input type="number" name="quantity[]" id="quantity" data-srno="" class="form-control input-sm quantity" value="{{ $res->quantity }}" readonly />
                        </div>
                         @endif --}}
                      </div>
                    </td>
                    <td>
                      <textarea name="description[]" id="description" data-srno="" class="form-control input-sm number_only description" readonly >{{ $res->description }}</textarea>
                    </td>
                    <td></td>
                  </tr>
                 <?php } ?>
                </table>
                @else
                Purchase Inventory
                @endif
                <!-- Modal -->
                  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <form action="{{ route('discard-reason',$requested[0]->prch_rfi_users_id) }}" method="post">
                        @csrf
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Discard Reason</h4>
                         {{--  <button type="button" class="close modalCloss" data-dismiss="modal">&times;</button> --}}
                        </div>
                        <div class="modal-body">
                          
                          <textarea name="discardReason" id="discardReason" class="form-control input-sm number_only discardReason" placeholder="Enter Reason.. Why you discard ?"></textarea>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-danger" >submit</button>
                        </div>
                      </div>
                    </form>
                    </div>
                  </div>
                <!-- Modal -->
               
        </div>
    </div>
</div>
<style type="text/css">
	/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

#row {
	margin-right: 0;
  margin-left: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container.green  input:checked ~ .checkmark {
  background-color: green;
}

.container.red  input:checked ~ .checkmark {
  background-color: red;
}

.container.yellow  input:checked ~ .checkmark {
  background-color: #ffa80a;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
@endsection
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
{{--  --}}