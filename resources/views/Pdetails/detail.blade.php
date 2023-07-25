<table class="table table-bordered tbl-hide-cls table-item" id="dataTables" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Item</th>
              <th>Item No.</th>
              <th>Site</th>
              <th>Vendor</th>
              <th>Quantity</th>
              <th>Price(unit)</th>
              <th>Tax rate</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody id="tbl-tbody">
          	@foreach($details as $detail)
              <tr>
                <td>{{ $detail->itemname->title }}</td>                     
                <td>{{ $detail->itemname->item_number }}</td>                     
                <td>{{ $detail->site->job_describe }}</td>                     
                <td>{{ $detail->vendor->firm_name }}</td>                                       
                <td>{{ $detail->quantity }}</td>                     
                <td>{{ $detail->price }}</td>                     
                <td>{{ $detail->tax_rate }}</td>                     
                <td>
                   
                  <form action="" method="POST">
                    <a class="btn btn-success btn-sm" href="" title="Show"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    @csrf
                    @method('DELETE')
                  
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </form>
                    
                </td>
              </tr>
              @endforeach
        
          </tbody>
        </table>
          
 <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    $.noConflict();
    var table = $('#dataTables').DataTable();
});
</script>