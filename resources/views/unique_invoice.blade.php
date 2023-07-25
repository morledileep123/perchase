@section('content')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link rel="stylesheet" type="text/css" href="{{ asset('css/invoice.css') }}">
<div id="invoice">
    <div class="toolbar hidden-print">
        <div class="text-right">
        		<a href="{{ "/item_purchase_history" }}" class="btn btn-info float-left"> Back </a>
            <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="http://www.laxyo.com">
                            <img src="http://www.laxyo.com/images/laxyo-logo.png" data-holder-rendered="true" />
                        </a>
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            <a target="_blank" href="http://www.laxyo.com">
                            Laxyo Energy
                            </a>
                        </h2>
                        <div>455 Foggy Heights, AZ 85004, US</div>
                        <div>(123) 456-789</div>
                        <div>company@example.com</div>
                        <div>INVOICE: {{ $invoice[0]['invoice_no'] }}</div>
                        <div>Date of Invoice: {{ date("d-m-Y", time()) }}</div>
                    </div>
                </div>
            </header>
            <main>
                <table border="0" cellspacing="0" cellpadding="0" id="example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-left">Item Name</th>
                            <th class="text-right">Item Number</th>
                            <th class="text-right">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                    	{{-- @if (!empty($purchases)) --}}
                    		<?php $itms = json_decode($invoice[0]['items'], true); ?>
	                    	@foreach($itms as $id => $obj)
	                        <tr>
	                            <td class="no">{{ $loop->iteration }}</td>
	                            <td class="text-left">{{ $obj['name'] }}</td>
	                            <td class="unit">{{ $obj['item_number'] }}</td>
	                            <td class="total">{{ $obj['quantity'] }} qty</td>
	                        </tr>
	                      @endforeach
											{{-- @endif --}}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="1">TOTAL</td>
                            <td id="total_qnt"></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="thanks">Thank you!</div>
                <div class="notices">
                    <div>NOTICE:</div>
                    <div class="notice">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
                </div>
            </main>
            <footer>
                Invoice was created on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>

<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
	 $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
  });
</script>
<script type="text/javascript">
	function sum_table_col(selector, index) {
    var total = 0;
    $(selector).each(function(){
        var value = $(this).text().match(/[\d\.]/g);
        if (!value) { return; }
        value = parseFloat(value.join(''));
        if (index) { 
            if ($(this).index() == index) {
                total += value
            }
        } else {
            total += value;
        }
    });
    document.getElementById("total_qnt").innerHTML = total + ' qty';
    //return total;
	}
	$('#example .total_qnt').html(sum_table_col('#example td',3).toFixed(0));
</script>