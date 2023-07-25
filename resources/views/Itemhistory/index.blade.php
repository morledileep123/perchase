<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Dashboard</title>
  <!-- Custom fonts for this template-->
  <link href="/themes/sb-admin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <!-- Custom styles for this template-->
  <link href="/themes/sb-admin2/css/sb-admin-2.min.css" rel="stylesheet">

 


</head>
<body id="page-top">
<!-- Begin Page Content -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<div class="container-fluid">
  <a href="{{ URL::previous() }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
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
            <div class="form-group col-md-4">
                <select class="form-control" name="vendor" id="vendor">
                  <option value="">Filter By Vendor</option>
                  @foreach($vendor as $vendors)
                  <option value="{{$vendors->id}}">{{ $vendors->firm_name }}</option>
                   @endforeach
                    <option value="trt">yrt</option>
                  
                </select>    
            </div>
            <div class="form-group col-md-4">
                <select class="form-control" name="cat_id" id="cat_id">
                  <option value="">Select Vendor First</option>
                </select>    
            </div>
            <div class="form-group col-md-4">
                <select class="form-control" name="department_id" id="department_id">
                  <option value="">Select Vendor First</option>
                </select>    
            </div>
          </div>
           <div class="row">
            <div class="form-group col-md-6">

                <input type="date" class="form-control" placeholder="from date" id="from_date"> 
            </div>
            <div class="form-group col-md-6">
                 <input type="date" class="form-control" placeholder="end date" id="todate">   
            </div>
          </div>
        </form>
        <br>
        <div id="item-table">
        @include('Itemhistory.table')
        </div>
    </div>
  </div>
</div>


<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
  $("#addForm").on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: '{{ route("filter") }}',
      data: $('#addForm').serialize(),
      success: function(data) {
        //$('#item-table').empty().html(data);
        //$('.table-item').DataTable();
      },
    });
  });
});

$(document).ready(function(){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  });
        $('#vendor').on("change",function(){
          var vendor_id = $(this).val();
          //alert(vendor_id);
           $.ajax({
                 type: "GET",
                 url: "{{ route('vendor-filter') }}?id="+vendor_id,
                 success: function(res){
                  if(res){
                    //console.log(res);
                       $("#cat_id").empty();
                       $("#cat_id").append('<option value="">select Category</option>');
                    $.each(res,function(index, rece){
                     // console.log(rece);
                        $("#cat_id").append('<option value='+rece.category['id']+'>'+rece.category['name']+'</option>')
                    });
                    
                  }
                }
                      });

        });

        $('#cat_id').on('change',function(){
               var category_id = $(this).val();
               var vendorid = $('#vendor').val();
              // alert(vendorid);
               $.ajax({
                url: "{{  route('category-filter')}}",
                type: 'GET',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'catid':category_id,'vendorid':vendorid},
                success: function (data) {
                  //console.log(data);
                  $("#department_id").empty();
                  $("#department_id").append('<option value="">Select Department</option>');
                  $.each(data,function(index, recev){
                  $("#department_id").append('<option value='+recev.department['id']+'>'+recev.department['name']+'</option>');
              });
                 
                }
            })

        })

        $("#department_id").on('change',function(){
             var vid = $('#vendor').val();
             var cid = $('#cat_id').val();
             var did = $('#department_id').val();

              $.ajax({
                url: "{{  route('item-all-filter')}}",
                type: 'GET',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'vid':vid,'cid':cid,'did':did},
                success: function (data) {
                 // console.log(data);
               if(data){
                       $('#item-table').empty().html(data);
                }
                 
                }
            })

             

        })

        $("#todate").on('change',function(){
            var from_date = $("#from_date").val();

            $.ajax({
                url: "{{  route('date-filter')}}",
                type: 'GET',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'fromdate':from_date,'todate':$(this).val()},
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