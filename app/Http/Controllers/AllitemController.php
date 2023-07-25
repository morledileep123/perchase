<?php

namespace App\Http\Controllers;

use App\Department;
use App\Brand;
use App\item_category;
use App\unitofmeasurement;
use App\location;
use App\itemconsumable;
use App\AllitemRecord;
use App\prch_invoice;
use App\allitemtestnew;
use App\Vendormain;
use PDF;
use PDFs;
use DB;
use Excel;
use App\Imports\ItemsImport;
use App\Exports\ItemsExcelExportAll;
use App\Exports\ItemsExportAll;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Http\Request;

class AllitemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $item = AllitemRecord::all();
        $vendor = Vendormain::all();
        $catgry = item_category::all();
        $depart = Department::all();
        return view('Itemhistory.index',compact('item','vendor','catgry','depart'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = unitofmeasurement::get();
        $category = item_category::get();
        //$location = location::get();
        $brand = Brand::get();
        $department = Department::get();
        $itemconsumable = itemconsumable::get();
        $vendors = Vendormain::get();
        return view('Itemhistory.create',compact('units','category','brand', 'department','itemconsumable','vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'item_name' => 'required',
            'cat_id' => 'required',
            'dept_id' => 'required',
            'unit_id' => 'required',
            'vendor_id' => 'required',
            //'sub_cat_id' => 'required',
            'hsn_code' => 'required|max:9|min:4|unique:prch_items',
            'item_type' => 'required',
            'invoice_no'=> 'required',
        ]);
        $ids = DB::select(DB::raw("SELECT nextval('prch_items_id_seq')"));
        $id = $ids[0]->nextval+1;
        //$cat = str_pad($request->cat_id, 2, 'x', STR_PAD_LEFT);
        //dd($cat);
        //$unit = str_pad($request->unit_id, 2, '0', STR_PAD_LEFT);
        //$item = str_pad($id, 4, '0', STR_PAD_LEFT);
        //$barcode = $cat.$unit.$item;
        //$data['item_number'] = $barcode;
        $data['part_no'] = $request->part_no;
        $data['rate'] = $request->rate;
        $data['gst_perc'] = $request->gst_perc;
        $data['invoice_date'] = $request->invoice_date;
        $data['description'] = $request->description;
        //return $data;
       
        $categories = item_category::where('id', $request->cat_id)->first();
        $cat_id = $request->cat_id;
        $cat_skey = $categories->short_key;

        $lastitem = AllitemRecord::create($data);
        $l_id = $lastitem->id;
        $insertdata = DB::select('call itemidentity(?,?,?)',array($l_id,$cat_id,$cat_skey));
        // if($lastitem){
        //     $asn = 'xyz112233';
        // }

        return redirect()->route('allitem.index')->with('success','Item Added successfully');
    }

    public function alldetailsitem($id){
       
         $item = AllitemRecord::with('category','vendor','department')->find($id);
         //dd($item->category->name);
         return view('Itemhistory.details',compact('item'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = AllitemRecord::with('category','vendor','department')->find($id);
        $category = item_category::get();
        $brand = Brand::get();
        $units = unitofmeasurement::get();
        $department = Department::get();
        $itemcon = itemconsumable::get();
        return view('Itemhistory.edit',compact('item','category','brand','units','department','itemcon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'item_name' => 'required',
            'cat_id' => 'required',
            'dept_id' => 'required',
            'unit_id' => 'required',
            //'vendor_id' => 'required',
            'sub_cat_id' => 'required',
            'hsn_code' => 'required|max:9|min:4|unique:prch_items',
            'item_type' => 'required',
            'invoice_date' => 'required',
            'rate' => 'required',
        ]);
        AllitemRecord::where('id',$id)->update($data);
        return redirect('allitem');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dservice = AllitemRecord::where('id', $id)->delete();
        return redirect()->back()->with('message','Bg Commission Is Successfully Removed From List');
    }

     public function excelItemsall()
     {
        //1937
        //1951
        $datas = Excel::toCollection(new ItemsImport,request()->file('excel_data'));
        
        $errors= array();
        $error_name = '';
        $workid = '';

        foreach($datas as $sales){
            //dd(count($sales));
          foreach($sales as $items){
              
          //return $items;
                $status = true;
                    if($status){
                        if($items['category_name'] != ''){
                            // return $items['titles'];
                          $category_id = item_category::where('name', $items['category_name'])->first();
                          if($category_id){
                             $catid = $category_id->id;
                             $skey = $category_id->short_key;
                             $status = true;

                          }else{

                              $error_name = "Category is not found in database";
                              $status = 0;
                              $catid = '';
                          }
                    }else{

                              $error_name = "Category is Empty";
                              $status = 0;
                              $catid = '';
                    }
                    
                 }

                 if($status){
                        if($items['department'] != ''){
                          $department = Department::where('name', $items['department'])->first();
                          //dd($department);
                          if($department){
                             $dept = $department->id;
                             $status = true;

                          }else{

                              $error_name = "Department is not found in database";
                              $status = 0;
                              $dept = '';
                          }
                    }else{
                              $error_name = "Department is Empty";
                              $status = 0;
                              $dept = '';

                    }
                    
                 }

                  if($status){
                        if($items['units'] != ''){
                          $unit = unitofmeasurement::where('name', $items['units'])->first();
                          if($unit){
                             $unitid = $unit->id;
                             $status = true;

                          }else{

                              $error_name = "Unit of measurement is not found in database";
                              $status = 0;
                              $unitid = '';
                          }
                    }else{

                              $error_name = "Unit of measurement is Empty";
                              $status = 0;
                              $unitid = '';
                    }
                    
                 }

 

                 if($status){
                        if($items['vendor_name'] != ''){
                        $vendorname = $items['vendor_name'];
                         $vendor = Vendormain::where('firm_name', $vendorname)->first();
                           
                          if($vendor){
                             $vendorid = $vendor->id;
                             $status = true;

                          }else{
                              
                              $vendor['firm_name'] = trim($items['vendor_name']);
                              $vendor['mobile'] = $items['contact'];
                              $vendor['gst_number'] = $items['gst_no'];
                              $vendor['city'] = $items['location'];
                              $vendor['email'] = strtolower($items['email_id']);
                             $vendor = Vendormain::create($vendor);
                             $vendorid = $vendor->id;
                             $status = true;
                          }
                    }else{

                              $error_name = "Vendor Name is Empty";
                              $status = 0;
                              $vendorid = '';
                    }
                    
                 }
              

                 if($status){
                        if($items['item_type'] != ''){
                          $itemtype = itemconsumable::where('cat_name', $items['item_type'])->first();
                          if($itemtype){
                             $itemtypeid = $itemtype->id;
                             $status = true;

                          }else{

                              $error_name = "Item Type is not found in database";
                              $status = 0;
                              $itemtypeid = '';
                          }
                    }else{

                              $error_name = "Item Type Field is Empty";
                              $status = 0;
                              $itemtypeid = '';
                    }
                    
                 }
                
                 
                 if($status){
                        if($items['titles'] != ''){
                         $item_name = AllitemRecord::where('item_name',$items['titles'])->where('cat_id',$catid)->where('dept_id',$dept)->where('unit_id',$unitid)->where('item_type',$itemtypeid)->where('invoice_no',$items['invoice_no'])->first();
                   
                          if($item_name){
                             $itemidd = $item_name->id;
                             $item_number=$item_name->item_number;
                             $status = true;
                             $error_name = "This Item exist in db";

                          }else{

                             //$item_number = itemidentity($catid,$skey);
                               //dd($item_number);
                              // itemidd

                         
                
                    }
                      } 
                      else{

                              $error_name = "Item  Field is Empty";
                              $status = 0;
                              $itemidd = '';
                  }
                  }

                // return $item_number.$status;
                 if($status){ 
                         $array = array(
                                        //'item_number'  =>  $item_number,
                                        'item_name'  => $items['titles'],
                                        'hsn_code'  => (!empty($items['hsn_code']))  ?  $items['hsn_code'] :  '0000',
                                        'part_no'  => $items['part_number'],
                                        'rate'  => $items['rate'],
                                        'gst_perc'  => (!empty($items['gst']))  ?  $items['gst'] :  '00',
                                        'invoice_no'  => $items['invoice_no'],
                                        'invoice_date'  => date("Y-m-d", strtotime($items['invoice_date'])),
                                        'vendor_id'  => $vendorid,
                                        //'location'  => $items['location'],
                                        'cat_id'  => $catid,
                                        // 'sub_cat_id'  => $subcatid,
                                        'dept_id'  => $dept,
                                        'unit_id'  => $unitid,
                                        'item_type'  => $itemtypeid,
                                        'description' => $items['description'],
                                        
                               );
                          
                        
                       $insrt = AllitemRecord::create($array);
                       /*Procedure in database*/
                       $insertdata = DB::select('call itemidentity(?,?,?)',array($insrt->id,$catid,$skey));

                    }else{ 
                        
                        
            
             $errors[] = array(
                            'item_name'  => $items['titles'],
                            'hsn_code'  => $items['hsn_code'],
                            'cat_id'  => $items['category_name'],
                            //'sub_cat_id'  => $items['subcategory_name'],
                            'department'  => $items['department'],
                            'unit_id'  => $items['units'],
                            'item_type'  =>$items['item_type'],
                            'description' => $items['description'],
                            'vendor_name' => $items['vendor_name'],
                            'part_number' => $items['part_number'],
                            'rate'  => $items['rate'],
                            'gst'  => $items['gst'],
                            'invoice_no'  => $items['invoice_no'],
                            'invoice_date'  => $items['invoice_date'],
                            'contact' => $items['contact'],
                            'gst_no' => $items['gst_no'],
                            'location' => $items['location'],
                            'email_id' => $items['email_id'],
                            'error_filed' => $error_name,

             );
          }


              }
              
      }
          // return $array;

       if(count($errors) !=0){
            return Excel::download(new ItemsExportAll($errors), 'Item_error.xlsx');

        }else{
          
           return redirect()->route('allitem.index');
        }
  }
}




