@extends('../layouts.sbadmin2')

@section('content')
<div class="container-fluid">
    <a href="{{ '/item' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
    <h5 class="main-title-w3layouts mb-2">Show Item</h5>
    <div class="card shadow mb-4">
              <div class="row">
                <div class="col-md-3 boldth">
                    <p>Item Number</p>
                    <p>Title</p>
                    <p>HSN Code</p>
                    <p>Category</p>
                    <p>Department</p>
                    <p>Unit</p>
                    <p>Description</p>
                </div>
                <div class="col-md-9">
                    <p>{{ $item->item_number }}</p>
                    <p>{{ $item->title }}</p>
                    <p>{{ ($item->hsn_code) ? $item->hsn_code : 'N/A' }}</p>
                    <p>{{ $item->category->name }}</p>
                    <p>{{ $item->department_name->name }}</p>
                    <p>{{ $item->unit->name }}</p>
                    <p>{{ $item->description }}</p>
                </div>
              </div>
        </div>
    </div>
</div>
@endsection