@extends('../layouts.sbadmin2')
@section('content')
<div class="container-fluid">
    <a href="" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
    <h5 class="main-title-w3layouts mb-2">Check Items</h5>
    <div class="card shadow mb-4">
        <div class="container mt-5">
    <div class="table-responsive">
        <form action="" method="post">
                        @csrf
        <div class="col-md-12 mb-5">
          <h4 class="text-muted">Item is Stored in W-house</h4>
              <h5 class="text-success">{{ App\Warehouse::find($data[0]->QuotationReceived->w_id)->name."-House"  }}</h5>
        <table class="table table-striped table-dark text-white table-hover">
            <thead>
                <tr>
                    <th class="text-center"><input type="checkbox"></th>
                    <th colspan="2">Item No.</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Status</th>
                    {{-- <th>Type</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
              <?php 

                  $row = json_decode($data[0]->QuotationReceived->items);
                ?>
                    <input type="hidden" name="quoapp" value="{{ $data[0]->id }}">
                @foreach($row as $data)

                <tr>
                    <td class="text-center"><input type="checkbox" name="item[]"></td>
                    <td colspan="2">
                        <input type="hidden" name="item[]" value="{{ $data->item_name }}">
                        <h6>{{ Str::after($data->item_name, '|') }}</h6>
                    </td>
                    <td>
                        <input type="hidden" name="item_namer" value="{{ $data->item_name }}">
                        <h6>{{ Str::before($data->item_name, '|') }}</h6>
                    </td>
                    <td>
                        <input type="hidden" name="quantity[]" value="{{ $data->item_quantity }}">
                        <div class="d-flex align-items-center"><span class="ml-2">{{ $data->item_quantity }}</span></div>
                    </td>
                    <td>{{ $data->item_total_amount }}<br></td>
                    <td class="font-weight-bold">Stored</td>
                   {{--  <td>Business</td> --}}
                    <td><i class="fa fa-external-link external-link"></i></td>
                </tr>
                @endforeach
            
            </tbody>
        </table>
               {{--  <button type= "submit" class= "btn btn-warning">Store In</button> --}}
    </div>
                </form>
</div>
    </div>
</div>

<style type="text/css">
  .bill-amt{
    padding: 20px;
      background: #a8a8e2;
      color: #000;
      font-weight: bold;
      border: 1px solid #000;
  }
  .table{
    /*color: #000 !important;*/
  }

</style>

<script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
@endsection
