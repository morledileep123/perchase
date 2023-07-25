<div id="All" class="tabcontent1"> 
 <table class="table table-bordered tbl-hide-cls table-item" id="dataTables" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>S.No</th>
      <th>Item Number</th>
      <th>Title</th>
      <th>Category</th>
      <th>W-House</th>
      <th>Qty</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody id="tbl-tbody">
    @if(!empty($items))
      @foreach ($items as $row)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $row->item_number }}</td>
        <td>{{ $row->itemdetails->title }}</td>
        <td>{{ ($row->itemdetails->category->name) }}</td>
        <td>{{ ($row->store_warehouse->name) }}</td>
        <td>{{ ($row->quantity ) }}</td>
        <td>
          <a class="btn btn-success" href="#" title="Show" data-toggle="modal" data-target="#view_items{{$row->id}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
          @if($role_name == 'store_admin')
            <a class="btn btn-primary" href="#" title="Edit" data-toggle="modal" data-target="#update_qty{{$row->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
          @endif
        </td>
      </tr>

      <!-- Modal -->
        <div class="modal fade" id="view_items{{$row->id}}" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"><span style="font-weight: bold; color: red">{{ $row->item_number }}Here show details form vendore and other</span><span style="font-weight: bold; color: #000"> - {{ $row->title }} {{ (!empty($row->items_qty->store_warehouse->name)) ? '('.$row->items_qty->store_warehouse->name.')' : "" }}</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="" method="">
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label>HSN Code</label>
                          <input type="text" class="form-control" value="{{ $row->hsn_code }}" readonly="">
                      </div>
                      <div class="form-group col-md-6">
                          <label>Department</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->department_name->name }} --}}" readonly="">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label>Category</label> <?php //$row->category->name ?>
                          <input type="text" class="form-control" value="xyz" readonly="">
                      </div>
                      <div class="form-group col-md-6">
                          <label>Subcategory</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->brand_name->name }}" --}} readonly="">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <label>Unit</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->unit->name }} --}}" readonly="">
                      </div>
                      <!-- <div class="form-group col-md-6">
                          <label>Available Quantity</label>
                          <input type="text" class="form-control" value='{{ (!empty($row->items_qty->quantity)) ? $row->items_qty->quantity : "0" }}' readonly="">
                      </div> -->
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                        <label>Available Quantity</label>
                        <div class="col-md-12">
                          <div class="col-md-6 float-left" style="font-weight: bold">Warehouse</div>
                          <div class="col-md-6 float-right" style="font-weight: bold">Quantity</div>
                          @foreach($warehouse as $wh)
                              <div class="col-md-6 float-left">{{ $wh->name }}</div>
                              <div class="col-md-6 float-right"></div>
                            
                          @endforeach
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <label>Descriptions</label>
                          <textarea readonly="" class="form-control" rows="2">{{ $row->description }}</textarea>
                      </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!-- Modal -->

      <!-- Modal -->
        <div class="modal fade" id="update_qty{{$row->id}}" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"><span style="font-weight: bold; color: red">{{ $row->item_number }}</span><span style="font-weight: bold; color: #000"> - {{ $row->title }}</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="" method="" >
                  @csrf   
                  <div class="row">
                      <div class="form-group col-md-12">
                        <label>Select Warehouse</label><br>
                        @foreach($warehouse as $wh)
                          <div class="row mt-3">
                            <div class="col-md-12">
                              <div class="col-md-6 float-left">
                                <input class="hidden" type="checkbox" name="warehouse_id[]"  value="{{ $wh->id }}" checked="">{{ $wh->name }}
                              </div>
                              <div class="col-md-6 float-right">
                                <input type="text" class="form-control" name="quantity[]"  placeholder="{{ $wh->name }} warehouse qty">
                              </div>
                            </div>
                          </div>
                        @endforeach
                        <div class="alertMsg1" style="color:red"></div>
                        <input type="hidden" class="form-control" name="item_id" value="{{ $row->id }}">
                          <input type="hidden" class="form-control" name="item_number" value="{{ $row->item_number }}">
                      </div>
                  </div>
                  <button class="btn btn-primary float-right">Update</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!-- Modal -->

      @endforeach
    @endif
  </tbody>
</table>
{{-- {{ $items->links() }} --}}
</div>

<div id="Indore" class="tabcontent1" style="display : none"> 
 <table class="table table-bordered tbl-hide-cls table-item" id="dataTables1" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>S.No</th>
      <th>Item Numbere</th>
      <th>Title</th>
      <th>Category</th>
      <th>W-House</th>
      <th>Qty</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody id="tbl-tbody2">
    @if(!empty($wh1))
      @foreach ($wh1 as $row)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $row->item_number }}</td>
        <td>{{ $row->itemdetails->title }}</td>
        <td>{{ ($row->itemdetails->category->name) }}</td>
        <td>{{ ($row->store_warehouse->name) }}</td>
        <td>{{ ($row->quantity ) }}</td>
        <td>
          <a class="btn btn-success" href="#" title="Show" data-toggle="modal" data-target="#view_items{{$row->id}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
          @if($role_name == 'store_admin')
            <a class="btn btn-primary" href="#" title="Edit" data-toggle="modal" data-target="#update_qty{{$row->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
          @endif
        </td>
      </tr>

      <!-- Modal -->
        <div class="modal fade" id="view_items{{$row->id}}" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"><span style="font-weight: bold; color: red">{{ $row->item_number }}Here show details form vendore and other</span><span style="font-weight: bold; color: #000"> - {{ $row->title }} {{ (!empty($row->items_qty->store_warehouse->name)) ? '('.$row->items_qty->store_warehouse->name.')' : "" }}</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="" method="">
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label>HSN Code</label>
                          <input type="text" class="form-control" value="{{ $row->hsn_code }}" readonly="">
                      </div>
                      <div class="form-group col-md-6">
                          <label>Department</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->department_name->name }} --}}" readonly="">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label>Category</label> <?php //$row->category->name ?>
                          <input type="text" class="form-control" value="xyz" readonly="">
                      </div>
                      <div class="form-group col-md-6">
                          <label>Subcategory</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->brand_name->name }}" --}} readonly="">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <label>Unit</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->unit->name }} --}}" readonly="">
                      </div>
                      <!-- <div class="form-group col-md-6">
                          <label>Available Quantity</label>
                          <input type="text" class="form-control" value='{{ (!empty($row->items_qty->quantity)) ? $row->items_qty->quantity : "0" }}' readonly="">
                      </div> -->
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                        <label>Available Quantity</label>
                        <div class="col-md-12">
                          <div class="col-md-6 float-left" style="font-weight: bold">Warehouse</div>
                          <div class="col-md-6 float-right" style="font-weight: bold">Quantity</div>
                          @foreach($warehouse as $wh)
                              <div class="col-md-6 float-left">{{ $wh->name }}</div>
                              <div class="col-md-6 float-right"></div>
                            
                          @endforeach
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <label>Descriptions</label>
                          <textarea readonly="" class="form-control" rows="2">{{ $row->description }}</textarea>
                      </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!-- Modal -->

      <!-- Modal -->
        <div class="modal fade" id="update_qty{{$row->id}}" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"><span style="font-weight: bold; color: red">{{ $row->item_number }}</span><span style="font-weight: bold; color: #000"> - {{ $row->title }}</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="" method="" >
                  @csrf   
                  <div class="row">
                      <div class="form-group col-md-12">
                        <label>Select Warehouse</label><br>
                        @foreach($warehouse as $wh)
                          <div class="row mt-3">
                            <div class="col-md-12">
                              <div class="col-md-6 float-left">
                                <input class="hidden" type="checkbox" name="warehouse_id[]"  value="{{ $wh->id }}" checked="">{{ $wh->name }}
                              </div>
                              <div class="col-md-6 float-right">
                                <input type="text" class="form-control" name="quantity[]"  placeholder="{{ $wh->name }} warehouse qty">
                              </div>
                            </div>
                          </div>
                        @endforeach
                        <div class="alertMsg1" style="color:red"></div>
                        <input type="hidden" class="form-control" name="item_id" value="{{ $row->id }}">
                          <input type="hidden" class="form-control" name="item_number" value="{{ $row->item_number }}">
                      </div>
                  </div>
                  <button class="btn btn-primary float-right">Update</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!-- Modal -->

      @endforeach
    @endif
  </tbody>
</table>
{{-- {{ $items->links() }} --}}
</div>

<div id="Ratlam" class="tabcontent1" style="display : none"> 
 <table class="table table-bordered tbl-hide-cls table-item" id="dataTables2" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>S.No</th>
      <th>Item NumberRatlam</th>
      <th>Title</th>
      <th>Category</th>
      <th>W-House</th>
      <th>Qty</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody id="tbl-tbody3">
    @if(!empty($wh2))
      @foreach ($wh2 as $row)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $row->item_number }}</td>
        <td>{{ $row->itemdetails->title }}</td>
        <td>{{ ($row->itemdetails->category->name) }}</td>
        <td>{{ ($row->store_warehouse->name) }}</td>
        <td>{{ ($row->quantity ) }}</td>
        <td>
          <a class="btn btn-success" href="#" title="Show" data-toggle="modal" data-target="#view_items{{$row->id}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
          @if($role_name == 'store_admin')
            <a class="btn btn-primary" href="#" title="Edit" data-toggle="modal" data-target="#update_qty{{$row->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
          @endif
        </td>
      </tr>

      <!-- Modal -->
        <div class="modal fade" id="view_items{{$row->id}}" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"><span style="font-weight: bold; color: red">{{ $row->item_number }}Here show details form vendore and other</span><span style="font-weight: bold; color: #000"> - {{ $row->title }} {{ (!empty($row->items_qty->store_warehouse->name)) ? '('.$row->items_qty->store_warehouse->name.')' : "" }}</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="" method="">
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label>HSN Code</label>
                          <input type="text" class="form-control" value="{{ $row->hsn_code }}" readonly="">
                      </div>
                      <div class="form-group col-md-6">
                          <label>Department</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->department_name->name }} --}}" readonly="">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label>Category</label> <?php //$row->category->name ?>
                          <input type="text" class="form-control" value="xyz" readonly="">
                      </div>
                      <div class="form-group col-md-6">
                          <label>Subcategory</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->brand_name->name }}" --}} readonly="">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <label>Unit</label>
                          <input type="text" class="form-control" value="{{-- {{ $row->unit->name }} --}}" readonly="">
                      </div>
                      <!-- <div class="form-group col-md-6">
                          <label>Available Quantity</label>
                          <input type="text" class="form-control" value='{{ (!empty($row->items_qty->quantity)) ? $row->items_qty->quantity : "0" }}' readonly="">
                      </div> -->
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                        <label>Available Quantity</label>
                        <div class="col-md-12">
                          <div class="col-md-6 float-left" style="font-weight: bold">Warehouse</div>
                          <div class="col-md-6 float-right" style="font-weight: bold">Quantity</div>
                          @foreach($warehouse as $wh)
                              <div class="col-md-6 float-left">{{ $wh->name }}</div>
                              <div class="col-md-6 float-right"></div>
                            
                          @endforeach
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <label>Descriptions</label>
                          <textarea readonly="" class="form-control" rows="2">{{ $row->description }}</textarea>
                      </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!-- Modal -->

      <!-- Modal -->
        <div class="modal fade" id="update_qty{{$row->id}}" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"><span style="font-weight: bold; color: red">{{ $row->item_number }}</span><span style="font-weight: bold; color: #000"> - {{ $row->title }}</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="" method="" >
                  @csrf   
                  <div class="row">
                      <div class="form-group col-md-12">
                        <label>Select Warehouse</label><br>
                        @foreach($warehouse as $wh)
                          <div class="row mt-3">
                            <div class="col-md-12">
                              <div class="col-md-6 float-left">
                                <input class="hidden" type="checkbox" name="warehouse_id[]"  value="{{ $wh->id }}" checked="">{{ $wh->name }}
                              </div>
                              <div class="col-md-6 float-right">
                                <input type="text" class="form-control" name="quantity[]"  placeholder="{{ $wh->name }} warehouse qty">
                              </div>
                            </div>
                          </div>
                        @endforeach
                        <div class="alertMsg1" style="color:red"></div>
                        <input type="hidden" class="form-control" name="item_id" value="{{ $row->id }}">
                          <input type="hidden" class="form-control" name="item_number" value="{{ $row->item_number }}">
                      </div>
                  </div>
                  <button class="btn btn-primary float-right">Update</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!-- Modal -->

      @endforeach
    @endif
  </tbody>
</table>
{{-- {{ $items->links() }} --}}
</div>
{{-- @include('store_item.table1') --}}



<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    $.noConflict();
    var table = $('#dataTables').DataTable();
    var Indore = $('#dataTables1').DataTable();
    var Ratlam = $('#dataTables2').DataTable();
    @foreach($warehouse as $wh)
      var table = $('#dataTables{{ $wh->id }}').DataTable();
    
    @endforeach
      var table13 = $('#dataTables13').DataTable();
      var tablerat = $('#dataTablesrat').DataTable();
});
</script>