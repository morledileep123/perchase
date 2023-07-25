@extends('../layouts.sbadmin2')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <a href="{{ '/home' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
  <h5 class="main-title-w3layouts mb-2"><b>Purchase Item Not In Stock</b></h5>
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
              <th>User Name</th>
              <th>Total Item</th>
              <th>Date</th>
              <th>status</th>
              <th>View Details</th>
            </tr>
          </thead>
          <tbody>
            @php 
              $i = 0;
            @endphp
            @foreach($prch_item as $dis)

              <tr>
                <td>{{ ++$i }}</td>
                <td>{{ App\User::find($dis->user_id)->name}}</td>
                <td>{{ (substr_count($dis->item,',')+1) }}</td>
                <td>{{ date('d-m-Y', strtotime($dis->created_at)) }}</td>
                @php 
                 // $quotation = App\prch_itemwise_requs::where('prch_rfi_users_id',$dis->id)->first();
                $all = App\prch_itemwise_requs::select("level1_status","level2_status","manager_status","discard_status","prch_rfi_users_id","mng_squantity_status","quotation_status",DB::raw("count(level1_status) as item"))->where(['prch_rfi_users_id'=>$dis->id])->groupBy('level1_status','level2_status','manager_status','prch_rfi_users_id',"discard_status","mng_squantity_status","quotation_status")->first();
              //dd($all);
                 @endphp
                @if($all->level2_status == 1 && $all->level1_status == 1 && $all->discard_status == 0 && $all->quotation_status == 0)
                <td class="text-success font-weight-bold">Approved to Request Quotation</td>
                 @elseif($all->level1_status == 1 && $all->level2_status == 1 && $all->quotation_status == 1)
                <td class="text-info font-weight-normal">Item Send For Quotation</td>
                @elseif($all->level1_status == 0 && $all->level2_status == 0 && $all->discard_status == 1)
                <td class="text-danger font-weight-normal">Discarded by Admin</td>
                 @elseif($all->manager_status == 1 && $all->level2_status == 0 && $all->level1_status == 0 && $all->discard_status == 0)
                 <td class="text-primary font-weight-normal">Waiting for Admin Approval</td>
                  @elseif($all->level2_status == 0 && $all->level1_status == 0 && $all->discard_status == 0 && $all->mng_squantity_status == 0)
                  <td class="text-danger font-weight-bold">Send Quantity Required </td>
                   @elseif($all->level2_status == 0 && $all->level1_status == 0 && $all->discard_status == 0 && $all->mng_squantity_status == 1)
                  <td class="text-info font-weight-bold">Manager Approval Required</td>
                 @elseif($all->level1_status == 1 && $all->level2_status == 0 && $all->discard_status == 2)
                 <td class="text-danger font-weight-bold">Discarded by Super-admin</td>
                 @elseif($all->level1_status == 1 && $all->level2_status == 0 && $all->discard_status == 0)
                 <td class="text-primary font-weight-bold">Waiting For Super-admin Approval</td>
                 @else
                 <td>visite</td>
                @endif
                <td>
                    <a class="btn btn-primary disbtn" href="{{ route('showdisbleForquo',$dis->id ) }}"><i class="fa fa-eye"></i></a>
                </td>
              </tr>
              @endforeach
          </tbody>
        </table>
         {!! $prch_item->links() !!}
      </div>
    </div>
  </div>
</div>
@endsection