@extends('../layouts.sbadmin2')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Site Items Avail For Dispatch</h5>
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
              <th>From Vendor</th>
              <th>Item Count</th>
              <th>For Site</th>
             {{--  <th>Vendor's Mob No.</th> --}}
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	 @php
          	 $sno = 1;
          	 @endphp
          	 @foreach($postore as $store)
                <tr>
                  <td>{{ $sno++ }}</td>
                  <td>{{ $store->vendorsDetail->firm_name }}</td>
                  <td>{{ count(json_decode($store->items))}}</td>
                  <td>{{ $store->site->job_describe }}</td>
                 {{--  <td>sf3r</td> --}}
                  <td>
                 
                    <a class="btn btn-success" href="{{ route('po-sites',[$store->id,$store->site_id]) }}" >move Items</a> 
                    @if($store->moved == 1)
                    <a class="btn btn-info" href="{{ route('m-send-item',$store->id) }}" >Send Items</a> 
                    @endif
                  
                  </td>
                </tr>
              @endforeach
          </tbody>
        </table>
         {!! $postore->links() !!}
      </div>
    </div>
  </div>
</div>
@endsection