<?php

namespace App\Http\Controllers;

use App\Department;
use App\Brand;
use App\item_category;
use App\unitofmeasurement;
use App\location;
use App\itemconsumable;
use App\AllitemRecord;
use App\vendormain;
use PDF;
use PDFs;
use DB;
use Excel;
use App\Imports\ItemsImport;
use App\Exports\ItemsExcelExportAll;
use App\Exports\ItemsExportAll;
use Illuminate\Http\Request;

class itemfilterController extends Controller
{
     public function vendorfilter(Request $request){

      return $allcag = AllitemRecord::with('category')->where('vendor_id',$request->id)->groupBy('cat_id')->select('cat_id', DB::raw('count(*) as total'))->get();



    }

    public function categoryfilter(Request $request){

     return $allcag = AllitemRecord::with('department')->where('vendor_id',$request->vendorid)->where('cat_id',$request->catid)->groupBy('dept_id')->select('dept_id', DB::raw('count(*) as total'))->get();


    }

    public function itemallfilter(Request $request){
           
           $item = AllitemRecord::with('category')->where('vendor_id',$request->vid)->where('cat_id',$request->cid)->where('dept_id',$request->did)->get();

           return view('Itemhistory.table',compact('item'));
    }

    public function datefilter(Request $request){

      $item = AllitemRecord::select("*")
                        ->whereBetween('invoice_date', [$request->fromdate, $request->todate])
                        ->get();
           return view('Itemhistory.table',compact('item'));            


    }
}
