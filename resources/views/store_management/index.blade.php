@extends('../layouts.sbadmin2')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Approved Quotation</h5>
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>S.No</th>
              <th>P.O. ID</th>
              <th>Vendor's Firm Name</th>
              <th>Vendor's Name</th>
              <th>Vendor's Mob No.</th>
              <th>Action</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
              @if (!empty($data))
                @foreach($data as $row)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $row->po_id }}</td>
                  <td>{{ $row->firm_name }}</td>
                  <td>{{ $row->name }}</td>
                  <td>{{ $row->mobile }}</td>
                  <td>
                    @if($row->po_stored == 1)
                     <b class="btn btn-success btn-sm">Stored</b>
                    @else
                    <a class="btn btn-warning btn-sm" href="{{ route("view_accepted_po",$row->approval_quotation_id) }}">In Items</a>
                    @endif
                  </td>
                  <td> @if($row->po_stored == 1)
                     <a class="btn btn-info btn-sm" href="{{ route("view-stored",$row->approval_quotation_id) }}">Stored History</a>
                    @else
                     <b class="btn btn-success btn-sm">Pending</b>
                     @endif
                    
                  </td>
                </tr>
                @endforeach
              @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection