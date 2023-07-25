@extends('../layouts.sbadmin2')

@section('content')
<div class="container-fluid">
    <a href="{{ URL::previous() }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
    <h5 class="main-title-w3layouts mb-2">Item Details</h5>
    <div class="card shadow mb-4">
        <div class="card-body">
        		<form action="" method="">
								<div class="row">
                    <div class="form-group col-md-6">
                        <label>Title</label>
                        <input type="text" class="form-control"  value="{{ $item->item_name }}" name="register_number" readonly="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Item no.</label>
                        <input type="text" class="form-control"  value="{{ $item->item_number }}" name="firm_name" readonly="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Rate</label>
                        <input type="text" class="form-control"  value="{{ '₹ '.$item->rate }}" name="name" readonly="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Hns No.</label>
                        <input type="email" class="form-control"  value="{{ $item->hsn_code }}" name="email" readonly="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Category</label>
                        <input type="text" class="form-control"  value="{{ $item->category->name }}" name="mobile" readonly="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Sub-category</label>
                        <input type="text" class="form-control"  value="{{ $item->subcat->name }}" name="alt_number" readonly="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>UOM</label>
                            <input type="text" class="form-control"  value="{{ $item->unit->name }}" name="gst_number" readonly="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Department</label>
                            <input type="text" class="form-control"  value="{{ $item->department->name }}" name="gst_number" readonly="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Laxyo Part no.</label>
                            <input type="text" class="form-control"  value="{{ $item->item_number }}" name="gst_number" readonly="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Vender Part no.</label>
                            <input type="text" class="form-control"  value="{{ $item->part_no }}" name="gst_number" readonly="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Vendor</label>
                            <input type="text" class="form-control"  value="{{ $item->vendor->firm_name }}" name="gst_number" readonly="">
                    </div>
                    <div class="form-group col-md-6">
                        <label>date of Purchase</label>
                            <input type="text" class="form-control"  value="{{ $item->invoice_date }}" name="gst_number" readonly="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Vendor location : </label>
                        <input type="text" class="form-control"  value="{{ $item->vendor->city }}" name="gst_number" readonly="">
                    </div>
                </div>
						</form>
        </div>
    </div>
</div>
@endsection