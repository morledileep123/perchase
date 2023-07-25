@extends('../layouts.sbadmin2')

@section('content')
<?php
    $s = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 10);
?>  
<div class="container-fluid">
    <a href="{{ '/allitem' }}" class="main-title-w3layouts mb-2 float-right"><i class="fa fa-arrow-left"></i>  Back</a>
    <h5 class="main-title-w3layouts mb-2">Add Item</h5>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Warning!</strong> Please check your input code<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                    <div class="row">
                        <div class="col-md-8" style="border-right: 1px solid">
            <form action="{{ route('allitem.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- <div class="form-group col-md-6">
                        <label>Item Number</label>
                        <input type="text" class="form-control" value="{{ $s }}" name="item_number" readonly="">
                    </div> --}}
                    <div class="form-group col-md-8">
                        <label>Item Name<span style="color: red">*</span></label>
                        <input type="text" class="form-control" placeholder="Add Item" name="item_name" value={{ old('item_name') }} >
                    </div>

                    <div class="form-group col-md-4">
                        <label>Add HSN Code<span style="color: red">*</span></label>
                        <input type="text" class="form-control" placeholder="Add HSN Code" name="hsn_code" value={{ old('hsn_code') }} >
                 @error('hsn_code')
                    <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Select Category<span style="color: red">*</span></label>
                        <select name="cat_id" id="category" class="form-control">
                            <option disabled="" selected="">Select Category</option>
                            @foreach ($category as $categorys)
                                <option value="{{ $categorys->id }}" {{ old('cat_id') == $categorys->id ? 'selected' : '' }}>{{ $categorys->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                   <!--  <div class="form-group col-md-6">
                        <label>Select Subcategory<span style="color: red">*</span></label>
                        <select name="sub_cat_id" class="form-control">
                            <option disabled="" selected="">Select Subcategory</option>
                            @foreach ($brand as $brands)
                                <option value="{{ $brands->id }}" {{ old('sub_cat_id') == $brands->id ? 'selected' : '' }}>{{ $brands->name }}</option>
                            @endforeach
                        </select>
                    </div> -->
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Select Department<span style="color: red">*</span></label>
                        <select name="dept_id" class="form-control">
                            <option disabled="" selected="">Select Department</option>
                            @foreach ($department as $departments)
                                {{-- <option value="{{ $departments->id }}">{{ $departments->name }}</option> --}}
                                 <option value="{{ $departments->id }}" {{ old('dept_id') == $departments->id ? 'selected' : '' }}>{{ $departments->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Select Unit<span style="color: red">*</span></label>
                        <select name="unit_id" class="form-control">
                            <option disabled="" selected="">Select Units</option>
                            @foreach ($units as $unit)
                                {{-- <option value="{{ $unit->id }}">{{ $unit->name }}</option> --}}
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>

                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                         <label>Select Item Type<span style="color: red">*</span></label>
                        <select name="item_type" class="form-control">
                            <option disabled="" selected="">Select Type</option>
                            @foreach ($itemconsumable as $itmable)
                               {{--  <option value="{{ $itmable->id }}">{{ $itmable->cat_name }}</option> --}}
                                 <option value="{{ $itmable->id }}" {{ old('item_type') == $itmable->id ? 'selected' : '' }}>{{ $itmable->cat_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                       <label>Part No.</label>
                        <input type="text" class="form-control" placeholder="Add Part no." name="part_no" value={{ old('part_no') }} >
                        
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                         <label>Rate</label>
                        <input type="text" class="form-control" placeholder="Add Rate" name="rate" value={{ old('rate') }} >
                    </div>
                    <div class="form-group col-md-6">
                       <label>GST %</label>
                        <input type="text" class="form-control" placeholder="Add GST in %" name="gst_perc" value={{ old('gst_perc') }} >
                        
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                         <label>Invoice Number</label>
                        <input type="text" class="form-control" placeholder="Add Invoice Number" name="invoice_no"  id="prch_invoice">
                    </div>
                    <div class="form-group col-md-6">
                       <label>Invoice Date</label>
                        <input type="date" class="form-control" placeholder="Add Invoice Date" name="invoice_date" value={{ old('invoice_date') }} >
                        
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                         <label>Vendor Name<span style="color: red">*</span></label>
                        
                        <select name="vendor_id" class="form-control">
                            <option disabled="" selected="">Select Vendor</option>
                            @foreach ($vendors as $vendor)
                                 <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->firm_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                       <label>No. of Item (Invoice)</label>
                        <input type="text" class="form-control" id="no_of_item" placeholder="Add Location" name="location" value={{ old('location') }} >
                        
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Add Description">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>
            <div class="col-md-4" style="border-left: 1px solid">
                <div class="container">   
                    <form action="{{ route('excel_import_all') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <h2 class="text-center mt-3">Excel Import</h2><br><br>
                        <input type="file" name="excel_data" id="imgupload" style="display:none">
                        <img src="https://icons.iconarchive.com/icons/dakirby309/simply-styled/256/Microsoft-Excel-2013-icon.png" id="OpenImgUpload"><br>
                        <span class="text-muted">
                            <a class="float-right" href="{{ route('avhi') }}" title="Excel Download">Click Here to download sheet format</a>
                        </span>
                        <br><br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });
</script>
<script>
    $(document).ready(function(){
        $('#no_of_item').hide();
    $('#prch_invoice').on('blur',function(){
        $('#no_of_item').toggle();

    })
});
</script>
@endsection