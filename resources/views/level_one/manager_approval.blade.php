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

        <button class="tablink" onclick="openPage('Home', this, 'red')">Approval from Purchase Manager</button>
				<button class="tablink" onclick="openPage('News', this, 'green')" id="defaultOpen">Request from Purchase Manager</button>

				<div id="Home" class="tabcontent">
	        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
	          <thead>
	            <tr>
	              <th>S.No</th>
	              <th>Approved by Mng</th>
	              <th>User</th>
	              <th>Items</th>
	              <th>Approved date</th>
	              <th>status</th>
	              <th>Action</th>
	            </tr>
	          </thead>
	          <tbody>
	            @if (!empty($requested))
	            @php $i = 1 @endphp
	              @foreach ($requested as $row)
	              <tr>
	                <td>{{ $i++ }}</td>
	                <td>{{ optional(App\User::find($row->manager_id))->name }}</td>
	                <td>{{ App\prch_itemwise_requs::where('prch_rfi_users_id',$row->id)->first()->username->name  }}</td>
	                <td>{{ $row->item }}</td>
	                <td>
	                	<center>
											<span style="">
									{{ $row->m_approve_date }}
											</span>
										</center>
	                </td>
	                @if($row->level1_status == 1 && $row->level2_status == 1)
	                <td class="text-success font-weight-bold">Approved</td>
	                @elseif($row->level1_status == 1 && $row->level2_status == 0 && $row->discard_status == 0)
	                <td class="text-primary font-weight-bold">Send For Super-Admin Approval</td>
	                @elseif($row->level1_status == 1 && $row->level2_status == 0 && $row->discard_status == 2)
	                <td class="text-danger font-weight-bold">Discared by S-Admin</td>
	                @elseif($row->discard_status == 1 && $row->level1_status == 0 && $row->level2_status == 0)
	                <td class="text-danger font-weight-bold">Discared By You OR Other Admin</td>
	                @else
	                <td class="text-primary font-weight-bold">Pending</td>
	                @endif

	                <td>
	                  <a class="btn btn-primary" href="{{ route('edit_levelone_approval', $row->id) }}"><i class="fa fa-eye"></i></a>
	                </td>
	              </tr>
	              @endforeach
	            @endif
	          </tbody>
	        </table>
	        {!! $requested->links() !!}
	      </div>
	      <div id="News" class="tabcontent">
				  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
	          <thead>
	            <tr>
	              <th>S.No</th>
	              <th>Mng Name</th>
	              <th>Item  count</th>
	              <th>Date</th>
	              <th>Action</th>
	            </tr>
	          </thead>
	          <tbody>
	            @if (!empty($mngreq))
	              @foreach ($mngreq as $row)
	              <tr>
	                <td>1</td>
	                <td>{{ App\User::find($row->user_id)->name }}</td>
	                <td>{{ (substr_count($row->item,',')+1)}}</td>
	                <td>
	                	<center>
											<span style="">
										{{ Date('Y-m-d',strtotime($row->created_at))}}
											</span>
										</center>
	                </td>
	                <td>
	                  <a class="btn btn-primary" href="{{ route('edit_levelone_approval', $row->id) }}"><i class="fa fa-eye"></i></a>
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
</div>
@endsection
<style>
* {box-sizing: border-box}

/* Set height of body and the document to 100% */
body, html {
  height: 100%;
  margin: 0;
  font-family: Arial;
}

/* Style tab links */
.tablink {
  background-color: #555;
  color: white;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  font-size: 17px;
  width: 50%;
}

.tablink:hover {
  background-color: #777;
}

/* Style the tab content (and add height:100% for full page content) */
.tabcontent {
  color: black;
  display: none;
  padding: 100px 20px;
  height: 100%;
}
</style>
<script>
function openPage(pageName,elmnt,color) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }
  document.getElementById(pageName).style.display = "block";
  elmnt.style.backgroundColor = color;
}
document.getElementById("defaultOpen").click();
</script>