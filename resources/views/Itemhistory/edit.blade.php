@extends('../layouts.sbadmin2')

@section('content')
<div class="container-fluid">
    <a href="{{ URL::previous() }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
    <h5 class="main-title-w3layouts mb-2"><b>Item Update Form</b></h5>
    <div class="card shadow mb-4">
        <div class="card-body">
          <form action="{{ route('allitem.update',$item->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" class="form-control"  value="{{ $item->item_name }}" name="item_name" />
                </div>
                <div class="form-group col-md-6">
                    <label>Item Type</label>
                    <select name="item_type" class="form-control">
                        @foreach($itemcon as $consum)
                        <option value="{{ $consum->id }}" {{ $item->item_type == $consum->id ? 'selected' : '' }}>{{ $consum->cat_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Rate</label>
                    <input type="text" class="form-control"  value="{{ $item->rate }}" name="rate" />
                </div>
                <div class="form-group col-md-6">
                    <label>Hns No.</label>
                    <input type="number" class="form-control"  value="{{ $item->hsn_code }}" name="hsn_code" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Category</label>
                    <select name="cat_id" class="form-control">
                        @foreach($category as $cate)
                        <option value="{{ $cate->id }}" {{ $item->cat_id == $cate->id ? 'selected' : '' }}>{{ $cate->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Sub-category</label>
                    <select name="sub_cat_id" class="form-control">
                        @foreach($brand as $bend)
                        <option value="{{ $bend->id }}" {{ $item->sub_cat_id == $bend->id ? 'selected' : '' }}>{{ $bend->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>UOM</label>
                    <select name="unit_id" class="form-control">
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Department</label>
                    <select name="dept_id" class="form-control">
                        @foreach($department as $depart)
                        <option value="{{ $depart->id }}" {{ $item->dept_id == $depart->id ? 'selected' : '' }}>{{ $depart->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Laxyo Part no.</label>
                    <input type="text" class="form-control"  value="{{ $item->item_number }}" name="gst_number" readonly="" />
                </div>
                <div class="form-group col-md-6">
                    <label>Vender Part no.</label>
                    <input type="text" class="form-control"  value="{{ $item->part_no }}" name="gst_number" readonly="" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Vendor</label>
                    <input type="text" class="form-control"  value="{{ $item->vendor->firm_name }}" name="vendor_id" readonly="" />
                </div>
                <div class="form-group col-md-6">
                    <label>date of Purchase</label>
                    <input type="date" class="form-control"  value="{{ $item->invoice_date }}" name="invoice_date" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Vendor location : </label>
                    <input type="text" class="form-control"  value="{{ $item->vendor->city }}" name="gst_number" readonly="" />
                </div>
            </div>
            <button type="submit" class="btn btn-warning" >Update</button>
        </form>
    </div>
</div>
</div>
@endsection