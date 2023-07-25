@extends('../layouts.sbadmin2')

@section('content')
<!-- Begin Page Content -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<style type="text/css">
	#itemList > ul{
    position: absolute !important;
    margin-top: -15px;
    width: 96%;
    background: #eeeff5;
    margin-left: 10px;
	}
	#itemList > ul > li{
    border-bottom: 1px solid #d4d2e4;
    padding: 5px 10px;
	}
	.blink_me {
	  animation: blinker 2s linear infinite;
	}
	@keyframes blinker {
	  50% {
	    opacity: 0;
	  }
	}
</style>
<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
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
        <form autocomplete="off">
        <div class="row">
        	<div class="col-md-3"></div>
        	<div class="col-md-6">
	        	<div class="form-group">
	        		<input type="text" name="search" id="searchItem" class="form-control" placeholder="Search Items...">
	        		<div id="itemList"></div>
	        	</div>
	        	@csrf
	        </div>
	        <div class="col-md-3">
	        	@if(!empty($temp))
	        	<a href="{{ route('cartRestore') }}"><div class="blink_me float-right" style="color: blue">Restore cart items</div></a>
						@endif
	        </div>
	      </div>
	    	</form>
	    	<div class="row">
	    		<div class="col-md-12">
	    			<div class="float-right p-2">
	    				<a class="btn btn-warning" href="{{ route('holdStatus') }}"><i class="fa fa-hourglass-half" aria-hidden="true"></i>  Hold </a>
	      		</div>
	      	</div>
	    	</div>
	      <div class="row">
	      	<div class="col-md-12">
							<table class="table table-border" width="100%" id="userTable">
								<thead>
									<tr>
										<th>#Item</th>
										<th>Name</th>
										<th>Quantity</th>
										<th>Action</th>
									</tr>
								</thead>
  							<tbody id="purchBody">
									@include('purchase.display_item')
								</tbody>
							</table>
					</div>
        </div>
        <div class="row">
	    		<div class="col-md-5"></div>
	    		<div class="col-md-2">
	    			<div class="p-2">
		      		<a class="btn btn-success" href="{{ route('invoice') }}"><i class="fa fa-print" aria-hidden="true"></i> Generate</a>
	      		</div>
	      	</div>
	      	<div class="col-md-5"></div>
	    	</div>
	  </div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- search and store data in session -->
<script>
$(document).ready(function(){
 	$('#searchItem').keyup(function(){ 
    var query = $(this).val();
    if(query != '')
    {
      var _token = $('input[name="_token"]').val();
      $.ajax({
        url:"{{ route('fetch') }}",
        method:"POST",
        data:{query:query, _token:_token},
        success:function(data){
         	$('#itemList').fadeIn();  
          $('#itemList').html(data);
        }
      });
    }
    else
    {
    	$('#itemList').fadeOut();
    }
  });
	
	$(document).on('click', 'li', function(){ 
		$('#searchItem').val($(this).text()); 
    $('#itemList').fadeOut(); 
    var value = $('#searchItem').val();
    var res = value.split("|");
    var final = res[1];
    if(final != '')
    {
      var _token = $('input[name="_token"]').val();
      $.ajax({
        url:"{{ route('purchase.store') }}",
        method:"POST",
        data:{item_number:final, _token:_token},
        success:function(data){
        	$('#searchItem').val('');
			    $('#purchBody').empty().html(data);
			    $('.load-cls').hide();
        }
      });
    }
  });  
});
</script>
<!-- end -->
<!-- update quantity from input field -->
<script>
$('.load-cls').hide();
function myFunction(id) {
	var qty = $('#chngQty'+id).val();
	var _token = $('input[name="_token"]').val();
	$.ajax({
    url:"{{ route('updateQty') }}",
    method:"POST",
    data:{quantity:qty, id:id, _token:_token},
    success:function(data){
    	setTimeout(function(){
    		$('#loading'+id).show();
    	}, 1000);
    	setTimeout(function(){
		  	$('#loading'+id).hide();
		  }, 3000);
    }
  });
}
</script>
<!-- end -->
<!-- Hold Button -->
{{-- <script type="text/javascript">
function holdStatus() {
	var btn = document.getElementById('btn');
	btn.disabled = true;
	var ses = "";
	alert(ses);
}
</script> --}}
<!-- end -->
@endsection