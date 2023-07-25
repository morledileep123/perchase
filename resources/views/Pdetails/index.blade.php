@extends('../layouts.sbadmin2')
@section('content')
<!-- Begin Page Content -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<div class="container-fluid">
  <a href="{{ route('home') }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Purchase Details Info</h5>
  <div class="card shadow mt-3">
    <div class="card-body">
      <div class="table-responsive">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <form id="addForm" action="{{route('pucharse-details')}}" method="get">
          @csrf
	        <div class="row">
	          <div class="form-group col-md-3">
	          	<lable>Vendor</lable>

	              <select class="form-control" name="vendor" id="vendr">
	                <option value=" ">Select</option>
                    @foreach($vendors as $vendor)
                    <option value="{{ $vendor->vendor->id }}">{{ $vendor->vendor->firm_name }}</option> 
                    @endforeach
                   </select>    
	          </div>
	          <div class="form-group col-md-3">
	          	<lable>Site</lable>
	              <select class="form-control" name="site" id="sites">
	                <option value="">Select</option>
                    @foreach($sites as $site)
                    <option value="{{ $site->site->id }}">{{ $site->site->job_describe }}</option> 
                    @endforeach
	              </select>    
	          </div>
	          <div class="form-group col-md-3">
	          	<lable>Item</lable>
	              <select class="form-control" name="item" id="itm">
	                <option value="">Select</option>
                    @foreach($items as $item)
                    <option value="{{ $item->itemname->item_number }}">{{ $item->itemname->title }}</option> 
                    @endforeach
	              </select>    
	          </div>
              <div class="form-group col-md-2">
                <label></label><br>
                <input type="submit" name="submit" class="btn btn-primary">    
              </div>
	          <div class="form-group col-md-1">
	            <a class="float-right" href="{{ route('export_pdf') }}" title="PDF Download"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size: 22px"></i></a>
	            <a class="float-right" href="{{ route('excel_export') }}" title="Excel Download"><i class="fa fa-file-excel-o" aria-hidden="true" style="font-size: 22px; margin-right: 12px"></i></a>	           
	          </div>
	        </div>
        </form>
        <br>
       	<div id="item-table">
       		@include('Pdetails.detail')
       	</div>
		</div>
  </div>
</div>

<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function(){
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
     });
      $('#vendr').on('change',function(){
           var vendrid = $(this).val();
           $.ajax({
                 type: "GET",
                 url: "{{ route('prchvendor') }}?id="+vendrid,
                 success: function(res){
                  if(res){
                       $("#sites").empty();
                       $("#sites").append('<option value="">select site</option>');
                       $.each(res,function(index, rece){
                        $("#sites").append('<option value='+rece.site_id+'>'+rece.site['job_describe']+'</option>')
                    });
                        //$('#item-table').empty().html(res);
                    
                  }
                }
                      });

      })

      $("#sites").on('change', function(){
         var siteid = $(this).val();
         var vendrid = $("#vendr").val();
         $.ajax({
                type: 'GET',
                data: {'vendrid':vendrid,'siteid':siteid},
                url: "{{ route('prchsite')}}",
                success: function (datas) {
                 if(datas){
                       $("#itm").empty();
                       $("#itm").append('<option value="">select item</option>');
                       $.each(datas,function(i, v){
                        $("#itm").append('<option value='+v.item_number+'>'+v.itemname['title']+'</option>')
                       });
                      // $('#item-table').empty().html(datas);
                     
                }
                 
                }
            })
      });

	});
</script>
@endsection