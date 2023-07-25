@extends('../layouts.sbadmin2')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Site ITem Send By</h5>
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
              <th>By Manager</th>
              <th>On Site</th>
              <th>Site Receiver</th>
              <th>On Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	@php
          	 $sno = 1;
          	 @endphp
          	 @foreach($send as $send)
                <tr>
                  <td>{{ $sno++ }}</td>
                  <td>{{ $send->musers->name }}</td>
                  <td>{{ $send->site->job_describe }}</td>
                  <td>{{ $send->users->name }}</td>
                  <td>{{ $send->created_at->format('d/m/Y') }}</td>
                 {{--  <td>sf3r</td> --}}
                  <td>
                    <a class="btn btn-info" href="{{ route('item-req',$send->qid) }}" >Details Item</a> 
                  
                  </td>
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection