@extends('layouts.sbadmin2')

@section('content')

<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2">Request For Items</h5>
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
              <th>Approved/Discard By(Admin)</th>
              <th>Approve Date</th>
              <th>Manager</th>
              <th>Item name</th>
              <th>Req. by</th>
              <th>Item</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if (!empty($requested))
            @php $sno = 1; @endphp
              @foreach ($requested as $row)
              <tr>
                <td>{{ $sno++}}</td>
                <td>{{ optional(App\User::find($row->admin_id))->name }}</td>
                <td>{{ $row->a_approve_date}}</td>
                <td>{{ App\prch_itemwise_requs::where('prch_rfi_users_id',$row->id)->first()->manager->name }}</td>
                <td>{{$row->item}}</td>
                <td>{{ App\prch_itemwise_requs::where('prch_rfi_users_id',$row->id)->first()->username->name }}</td>
                <td>{{ (substr_count($row->item,',')+1)}}</td>
                @if($row->level1_status == 1 && $row->level2_status == 1)
                <td class="text-success font-weight-bold">Approved</td>
                @elseif($row->discard_status == 2 && $row->level1_status == 1 && $row->level2_status == 0)
                <td class="text-danger font-weight-bold" >Discarded</td>
                @elseif($row->discard_status == 0 && $row->level1_status == 1 && $row->level2_status == 0)
                <td class="text-primary font-weight-bold">Pending</td>
                @else
                <td>{{ "Discraded" }}</td>
                @endif

                <td>
                  <a class="btn btn-primary" href="{{ route('edit_leveltwo_approval', $row->id) }}"><i class="fa fa-eye"></i></a>
                </td>
              </tr>
              @endforeach
            @endif
          </tbody>
        </table>
        {!! $requested->links() !!}
      </div>
    </div>
  </div>
</div>
@endsection