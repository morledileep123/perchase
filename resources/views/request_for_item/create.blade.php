@extends('../layouts.sbadmin2')
@section('content')
<div class="container-fluid">
    <!-- <a href="{{ '/request_for_item' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a> -->
    <h5 class="main-title-w3layouts mb-2">Create RFI</h5>
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
            
						
            <form action="{{ route('request_for_item.store') }}" method="post">
                @csrf
                 <div class="row">
                  <div class="col-md-10">
                    <label>For Site</label>
                  <select name="site_id" id="site_id" data-srno="1" class="form-control input-sm" required />
                               <option value="">Select</option>
                              @foreach($site as $stee)
                              <option value="{{$stee->id}}">{{$stee->job_describe}}</option>
                              @endforeach
                        </select>
                        </div>
                      </div><br>
								<table id="invoice-item-table" class="table table-bordered">
			            <tr>
			              <!-- <th>S.No</th> -->
			              <th>Item Name</th>
			              <th>Item No.</th>
                    <th>Expected date</th>
                    <th>Quantity</th>
			             {{--  <th>Indent</th> --}}
                    <th>Description</th>
			              <th></th>
			            </tr>
			            <tr>
			              <!-- <td><span id="sr_no">1</span></td> -->
			              <td>
			              	<input type="text" name="item_name[]" id="item_name1" class="form-control input-sm" autocomplete="off" required/>
                      <div id="itemList1" /></div>
			              	<input type="hidden" name="user_id[]" id="user_id1" class="form-control input-sm" value="{{ Auth::user()->id }}" />
			              </td>
			              <td colspan="3">
                      <div class="row">
                        <div class="col-md-4">
    			              	<input type="text" name="item_no[]" id="item_no1" data-srno="1" class="form-control input-sm item_no" readonly/>
                        </div>
                        <div class="col-md-5">
                          <input type="date" name="expected_date[]" id="expected_date1" data-srno="1" class="form-control input-sm"  min="{{date("Y-m-d")}}" required/>
                        </div>

                        <div class="col-md-3">
                          
                          <input type="number" name="quantity[]" id="quantity1" data-srno="1" class="form-control input-sm unit" required/>
                        </div>
                      </div>
			              </td>
			              <td>
                      <div class="row">
                      
                        <div class="col-md-12">
                      <textarea name="description[]" id="description1" data-srno="1" class="form-control input-sm number_only description" readonly /></textarea>
                        </div>
                      </div>
			              </td>
			              <td></td>
			            </tr>
			          </table>
               
			          <div align="right">
			            <button type="button" name="add_row" id="add_row" class="btn btn-success btn-xs">+</button>
			          </div>
                <button type="submit" name="submit" class="btn btn-primary error-w3l-btn px-4" onclick="return confirm('Are you sure you want to submit this from?');">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection

<style type="text/css">
  .items-dropdown{
    height: 250px !important;
    overflow-x: hidden !important;
    background: #dadada !important;
    width: 100% !important;
  }
  .items-dropdown > li{
    padding: 5px !important;
    border-bottom: 1px solid #8c4949 !important;
}
</style>

<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
$(document).ready(function(){
	
  var final_total_amt = $('#final_total_amt').text();
  var count = 1;

  $('#item_name'+count).keyup(function(){ 
    var query = $(this).val();
    if(query != '')
    {
      var _token = $('input[name="_token"]').val();
      $.ajax({
        url:"{{ route('fetch') }}",
        method:"POST",
        data:{query:query, _token:_token},
        success:function(data){
        console.log(data);
          $('#itemList'+count).fadeIn();  
          $('#itemList'+count).html(data);

        }
      });
    }
    else
    {
      $('#itemList'+count).fadeOut();
    }
  });
  
  $(document).on('click', 'li', function(){ 
    $('#item_name'+count).val($(this).text());
    var str = $(this).text();
      console.log(str);
    var sp = str.split("|");
    $('#item_no'+count).val(sp[1]);
    $('#description'+count).val(sp[2]);
    $('#itemList'+count).fadeOut(); 
  });

  $(document).on('click', '#add_row', function(){
    count++;
    //alert("wrq");
    $('#total_item').val(count);
    var html_code = '';
    html_code += '<tr id="row_id_'+count+'">';
    //html_code += '<td><span id="sr_no">'+count+'</span></td>';
    
    html_code += '<td><input type="text" name="item_name[]" id="item_name'+count+'" class="form-control input-sm" autocomplete="off" required/><div id="itemList'+count+'"></div><input type="hidden" name="user_id[]" value="{{ Auth::user()->id }}" id="user_id'+count+'" class="form-control input-sm" /></td>';
    html_code += '<td colspan="3"><div class="row"><div class="col-md-4"><input type="text" name="item_no[]" id="item_no'+count+'" data-srno="'+count+'" class="form-control input-sm number_only item_no" readonly/></div><div class="col-md-5"><input type="date" name="expected_date[]" id="expected_date'+count+'" data-srno="1" class="form-control input-sm expected_date" min="{{date("Y-m-d")}}" required/></div><div class="col-md-3"><input type="number" name="quantity[]" id="quantity'+count+'" data-srno="1" class="form-control input-sm unit" required/></div></td>';
    html_code += '<td><div class="row"><div class="col-md-12"><textarea name="description[]" id="description'+count+'" data-srno="'+count+'" class="form-control input-sm number_only description" readonly></textarea></div></div></td>';
    html_code += '<td><button type="button" name="remove_row" id="'+count+'" class="btn btn-danger btn-xs remove_row">X</button></td>';
    html_code += '</tr>';
    $('#invoice-item-table').append(html_code);

    $('#item_name'+count).keyup(function(){ 
      var query = $(this).val();
      if(query != '')
      {
        var _token = $('input[name="_token"]').val();
        $.ajax({
          url:"{{ route('fetch') }}",
          method:"POST",
          data:{query:query, _token:_token},
          success:function(data){
            $('#itemList'+count).fadeIn();  
            $('#itemList'+count).html(data);
          }
        });
      }
      else
      {
        $('#itemList'+count).fadeOut();
      }
    });
    
    $(document).on('click', 'li', function(){ 
      $('#item_name'+count).val($(this).text()); 
      $('#itemList'+count).fadeOut(); 
    });

  });
  
  $(document).on('click', '.remove_row', function(){
    var row_id = $(this).attr("id");
    var total_item_amount = $('#order_item_final_amount'+row_id).val();
    var final_amount = $('#final_total_amt').text();
    var result_amount = parseFloat(final_amount) - parseFloat(total_item_amount);
    $('#final_total_amt').text(result_amount);
    $('#row_id_'+row_id).remove();
    count--;
    $('#total_item').val(count);
  });

  /*$(document).on('blur', '.order_item_price', function(){
    cal_final_total(count);
  });

  $(document).on('blur', '.order_item_tax1_rate', function(){
    cal_final_total(count);
  });

  $(document).on('blur', '.order_item_tax2_rate', function(){
    cal_final_total(count);
  });

  $(document).on('blur', '.order_item_tax3_rate', function(){
    cal_final_total(count);
  });*/
  
});
</script>