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
              <th>Item No.</th>
              <th>Item Name</th>
              <th>quantity send</th>
              <th>received on site</th>
            </tr>
          </thead>
          <tbody>
          	@php
          	 $sno = 1;
          	 @endphp
          	 @foreach($qty as $qty)
                <tr>
                  <td>{{ $sno++ }}</td>
                  <td>{{ $qty->item_number }}</td>
                  <td>{{ $qty->itemname->title }}</td>
                  <td>{{ $qty->send_on_site }}</td>
                  <td>{{ $qty->created_at->format('d/m/Y') }}</td>
                 {{--  <td>sf3r</td> --}}
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection