@extends('../layouts.sbadmin2')
@section('content')
<!-- Begin Page Content -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<div class="container-fluid">
  <a href="{{ route('item.create') }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Item Listing</h5>
  <div class="card shadow mt-3">
    <div class="card-body">
      <div class="table-responsive">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <form id="addForm">
          @csrf
	        <div class="row">
	          <div class="form-group col-md-5">
	              <select class="form-control" name="category" id="category">
	                <option selected="" disabled="" value="0">Filter By Category</option>
	                @foreach($category as $cat)
	                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
	                @endforeach
	              </select>    
	          </div>
	          <div class="form-group col-md-5">
	              <select class="form-control" name="department" id="department">
	                <option selected="" disabled="" value="0">Filter By Department</option>
	                @foreach($department as $dept)
	                  <option value="{{ $dept->id }}">{{ $dept->name }}</option>
	                @endforeach
	              </select>    
	          </div>
	          <div class="form-group col-md-2">
	            <a class="float-right" href="{{ route('export_pdf') }}" title="PDF Download"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size: 22px"></i></a>
	            <a class="float-right" href="{{ route('excel_export') }}" title="Excel Download"><i class="fa fa-file-excel-o" aria-hidden="true" style="font-size: 22px; margin-right: 12px"></i></a>	           
	          </div>
	        </div>
        </form>
        <br>
       	<div id="item-table">
       		@include('item.table')
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
      $('#category').on('change',function(){
           var catid = $(this).val();
          $.ajax({
                 type: "GET",
                 url: "{{ route('item-cat') }}?id="+catid,
                 success: function(res){
                  if(res){
                    //console.log(res);
                       $("#department").empty();
                       $("#department").append('<option value="">select department</option>');
                       $.each(res,function(index, rece){
                        $("#department").append('<option value='+rece.department_name['id']+'>'+rece.department_name['name']+'</option>')
                    });
                    
                  }
                }
                      });

      })

      $("#department").on('change', function(){
         var dep = $(this).val();
         var cat = $("#category").val();
         $.ajax({
                url: "{{  route('items-filter')}}",
                type: 'GET',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'dep':dep,'cat':cat},
                success: function (data) {
                 console.log(data);
               if(data){
                       $('#item-table').empty().html(data);
                }
                 
                }
            })
      });

	});
</script>
@endsection