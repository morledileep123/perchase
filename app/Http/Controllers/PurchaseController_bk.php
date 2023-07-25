<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$purchases = Purchase::with('item_name.unit')->get();
    	return view('purchase.index',compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    		$validation = Validator::make($request->all(), [
            'item_number' => 'unique:purchases'
        ]);
        if ($validation->fails())
        {
            return "Item already taken";
        }
        else
        {
            $data = new purchase;
            $data->item_number = $request->input('item_number');
            $data->save();
            $purchases = Purchase::all();
            return view('purchase.display_item',compact('purchases'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
      	//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchase.index')->with('success','Item deleted successfully');
    }

    public function fetch(Request $request)
    {
    	if($request->get('query'))
      {
	      $query = $request->get('query');
	      $data = item::where('title', 'LIKE', "%{$query}%")->orWhere('item_number', 'LIKE', "%{$query}%")->get();
	      $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
	      if(count($data) != null)
	      {
		      foreach($data as $row)
		      {
		      	$output .= '<li><a id="getItemID" href="?itemId='.$row->id.'" style="pointer-events: none;" value="'.$row->id.'">'.$row->title .' | '.$row->item_number.'</a></li>';
		      }
		    }
		    else
		    {
		    	$output .= '<li><a href="JavaScript:void(0);">No Items available</a></li>';
		    }
	      $output .= '</ul>';
	      echo $output;
      }
    }

    public function updateQty(Request $request)
    {
    		$id = $request->id;
        $quantity = $request->quantity;
        purchase::where('id', $id)->update(['quantity'=>$quantity ]);
    }
}
