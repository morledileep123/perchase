<table class="table table-bordered tbl-hide-cls table-item" id="dataTables" width="100%" cellspacing="0">
          <thead>
            <tr>
              <!-- <th>S.No</th> -->
              <th>Laxyo Item Number</th>
              <th>Title</th>
              <th>Rate</th>
              <th>HSN Code</th>
              <th>Category</th>
              <th>Part no.</th>
              <th>Vendor</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="tbl-tbody">
            @php $i=0;
            @endphp
            @foreach($item as $items)
              <tr>
               <!--  <td>{{ $i++ }}</td>    -->              
                <td>{{ $items->item_number }}</td>
                <td>{{ $items->item_name }}</td>
                <td>{{ "₹ ".$items->rate }}</td>
                <td>{{ $items->hsn_code }}</td>
                <td>{{ $items->category->name }}</td>
                <td>{{ $items->part_no }}</td>
                <td>{{ $items->vendor->firm_name }}</td>   <?php //$items->vendor->firm_name ?>     	       
                <td>{{ $items->invoice_date }}</td>
                <td>
                  
                    <a  href="{{ route('all-details-item',$items->id) }}" class="btn btn-warning btn-sm" title="Show"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    <a  href="{{ route('allitem.edit',$items->id) }}" class="btn btn-success btn-sm" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <form action="{{ route('allitem.destroy',$items->id) }}" method="POST">
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