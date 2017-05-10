@extends('layouts.default')

	@section('content')
	
	@if($request_type == 'quote')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('quotes', 'Quotes', array(), array('class' => 'to_all')) }} &raquo; Copy</h1>
	@elseif($request_type == 'invoice')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('invoices', 'Invoices', array(), array('class' => 'to_all')) }} &raquo; Copy</h1>
	@endif
<?php 

	use IntegrityInvoice\Utilities\AppHelper as AppHelper;
	
	// Get preferences
	// We have passed in $preferences from the controller
	// We have passed in $invoice from controller
	// We have passed in $company from controller
	// We have passed in $client from the controller

	$lineitems = explode("|", $invoice->items);

	$line_item_total = count($lineitems);
	$items = array();
	
	for($i=0; $i < $line_item_total; $i++){
		
	 //${'item_' . $i}[] = explode(',',$lineitems[$i]);
	 $items[] = explode(',',$lineitems[$i]);
	 // remove empty value
	 ($items[$i][0] == ' ') ? array_shift($items[$i]) : "";
	  (count($items[$i]) == 1) ? array_pop($items) : "";
	
		
	}
	//echo $item_0[0][0];
	//echo $items[1];
	
	// replacement for comma
	 $find ="/__/"; 
 	 $replace =","; 
	 
	 
	 // Currency
	 $cur = $invoice->cur;
	 
	 $template_id = $preferences->invoice_template;
	 $l_id = $invoice->tenant_invoice_id;
	 

if($limit_reached == FALSE): ?>
	
<div id="invoicecontainer">
	
	<div class="pdf_logo_up_create">
     <?php   
	     function addhttp($url) {
		    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		        $url = "http://" . $url;
			    }
			    return $url;
		 }
     ?>
	 
	<?php  $tenantID = Session::get('tenantID'); $ext = '.png'; $logo_file =  public_path(). '/te_da/'.$tenantID . '/'.$tenantID.$ext; ?>
			 
			@if (file_exists($logo_file))					 
				 <img src="{{ Config::get('app.app_main_domain').'/te_da/'.$tenantID.'/'.$tenantID.'.png' }}" alt="" />
			@endif	
	</div><!-- END -->
	 
 <?php  
 
 	 $tax_discount_options = '';
 	 $business_model;
 	 $item_type = 'product';
	 
     if($invoice->business_model == 0) { $item_type = 'product'; $business_model = 0; }
     if($invoice->business_model == 1) { $item_type = 'service'; $business_model = 1; }
	 
	  $enable_discount = true;
	  $enable_tax = true;
	  
	  if($invoice->enable_discount == 0) { $enable_discount = false; }else{ $enable_discount = true;}
      if($invoice->enable_tax == 0) { $enable_tax = false; }else{ $enable_tax = true;}
	  
	  // Default with discount and no tax
	  $colspan1 = $invoice->business_model == 0 ? 2 : 3;
	  $colspan2 = $invoice->business_model == 0 ? 4 : 5;
	  $colspan3 = $invoice->business_model == 0 ? 6 : 6;
	  
	  // Only Discount enabled
	  if($enable_discount == true && $enable_tax == false)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 2 : 3;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 3 : 1;
		  $colspan3 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 6 : 4;
		  
		  $tax_discount_options = 'discount';
	  }
	  
	  // Only Tax enabled
	  if($enable_discount  == false && $enable_tax  == true)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 2 : 3;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 3 : 1;
		  $colspan3 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 6 : 4;
		  
		  $tax_discount_options = 'tax';
	  }
	  
	   // Discount and tax enabled
	  if($enable_discount  == true && $enable_tax  == true)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 2 : 2;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 4 : 3;
		  $colspan3 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 6 : 6;
		  
		  $tax_discount_options = 'both';
	  }
	  
	  // Discount and tax disabled
	  if($enable_discount  == false && $enable_tax  == false)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 3 : 2;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 0;
		  $colspan3 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 6 : 0;
		  
		  $tax_discount_options = 'none';
	  }

 ?>
 
<div class="invoice_setings">
	 {{Form::open(array('url' => 'InvoicesController@update_settings', 'action' => 'PUT')) }}
	 <p>
	 {{ Form::label('discount_option', 'Enable Discount') }}
	 {{ Form::checkbox('discount_option', 'value', $enable_discount, array('class' => 'discount_enabler')) }}
	 </p>
	 
	 @if($preferences->tax_perc1 > 0 || $preferences->tax_perc2 > 0)
	 <p>
	 {{ Form::label('tax_option', 'Enable Tax') }}
	 {{ Form::checkbox('tax_option', 'value', $enable_tax, array('class' => 'tax_enabler')) }}
	 </p>
	 @endif
	 {{ Form::close() }}
</div>

<input type="hidden" name="" id="param" tax_discount_options="{{ $tax_discount_options }}" provision_type="{{ $item_type }}" business_model="{{ $business_model }}" enable_discount="{{ $invoice->enable_discount }}" bill_option="{{ $invoice->bill_option }}" enable_tax="{{ $invoice->enable_tax }}" />
<input type="hidden" name="request_type" id="request_type" value="<?php echo $request_type ?>" />
<input type="hidden" name="date_format" class="date_format" value="<?php echo $preferences->date_format; ?>" />
<input type="hidden" name="bus_model" id="bus_model" value="<?php echo $invoice->business_model; ?>" />
 
	<div id="identity" class="company_info">		
        <div class="cw" id="address"><p><strong class="cname"><?php echo $company->company_name; ?></strong><br /><?php echo $company->add_1; ?><?php
		if($company->add_2 == ""){
			echo "";
		}else if($company->add_2 == $company->state){
			echo "";
		}else{ echo ", ".$company->add_2 . ", ";} ?> <?php echo $company->state; ?><?php echo "  ".$company->postal_code; ?> 
		<?php echo ". Tel. ". $company->phone; ?></p></div>
      
	</div> <!-- END identity -->
	
	<div id="invoice_subj">		  
	 <input type="text" class="mytext" value="{{ $invoice->subject }}" id="inv_subject" placeholder="Subject or Title (optional)" />
    </div><!-- END Invoice Subject -->
	 
	<div class="invoiceTop">
		
		<div class="invoiceTopLeft">
		
	 	<div id="customer" class="invoice_customer">
	 		
	         <div id="selectcompany">
	        	 
	            <form name="" id="invoiceClientsList">
	           <!--  <label><strong>Bill To: </strong></label> -->
	            <select name="client_number" id="client_number" class="client_option">
	            <option value="" selected="selected">Select Client</option>
	             <optgroup label="------------------">	            
	            		<option id="createnewclient" value="newclient">Enter New Client</option>
	             </optgroup>
	             
	             <optgroup label="------------------">	            
	            	 
	             </optgroup>
	             <optgroup label="Client list">
	            <?php foreach($clients as $client): ?>
	                <option cl_id="<?php echo $client->id; ?>" cl_add1="<?php echo $client->add_1; ?>" cl_add2="<?php echo $client->add_2; ?>" cl_state="<?php echo $client->state; ?>" cl_pcode="<?php echo $client->postal; ?>" value="<?php echo $client->company; ?>"><?php echo $client->company; ?></option>
	            <?php endforeach; ?>
	             </optgroup>
	           </select>  
	           
	           <?php $dateformat ="d/m/Y";
					// British dateformat
					if($preferences->date_format == "dd/mm/yyyy"){ $dateformat ="d/m/Y"; }
					
					// America dateformat
					if($preferences->date_format == "mm/dd/yyyy"){ $dateformat = "m/d/Y"; }	?>	
	            <input type="hidden" class="pref_dateformat" value="{{ $dateformat }}" >
	            
	           </form> 
	           <input type="hidden" class="newclientoninvoice_popup_open">
	            
	        </div> <!--selectcompany-->
	      
		</div> <!-- END customer -->
	 
		   <div class="mycur">
			  
				<select id="the_currency" class="invoice_currency" <?php echo $preferences->currency_code != null || $preferences->currency_code != "" ? "": "";  ?>>
			  		<?php if($preferences->currency_code == null || $preferences->currency_code == ""): ?>
				    <option value="" selected="selected">Select Currency</option>
				    <?php endif; ?>
				    <?php echo IntegrityInvoice\Utilities\AppHelper::getUserCurrencyListOptionsForInvoice($currency_list, $invoice->currency_code); ?>				
				      
				</select>
				
			</div><!-- END My Cur-->
		 
		
		 @if($request_type == 'invoice')              
			<div class="prorata_duedate">
				
				<!-- <label><strong>Prorated Due date: </strong></label> -->
			
				<select id="prorata_due_date" class="prorata_due_date" name="prorata_due_date">
				    <option value="0" selected="selected">- Payment Terms -</option>
				    <option value="7">7 days after Issue date</option>
				    <option value="15">15 days after Issue date</option>
				    <option value="21">21 days after Issue date</option>
				    <option value="30">30 days after Issue date</option>
				</select>
			
			</div><!-- END prorata_duedate -->
		  
			<div class="p-order">
				<input type="text" placeholder=" P.O Number (Optional)" id="p-order" name="po_number" title="P.O Number" value="" />
			</div>
		  @endif
       </div> <!-- END invoiceTopLeft --> 
       
        
        <div class="invoiceTopRight" id="invnum">
        	<table id="meta">
               <tr>
                    <td class="meta-head">@if($request_type == 'quote') 
                    						{{ 'Quote ID' }}
                    						@elseif($request_type == 'invoice')
                    						{{ 'Invoice ID' }}
                    						@endif
                    					</td>
                    <td><div class="">
                    	<input type="text" class="cw inv_num inv_id_align" id="" value="<?php 
                     
					if($request_type == 'invoice')
					{
						if($tenant_last_invoice_id){
							$last_id = (int)$tenant_last_invoice_id;
						}else{
							// first invoice
							$last_id = 0;
						}
						
						if($tenant_last_used_invoice_id){
							$last_used_id = (int)$tenant_last_used_invoice_id;
						}else{
							// first invoice
							$last_used_id = 0;
						}
						
						
						$output_new_id = IntegrityInvoice\Utilities\AppHelper::newInvoiceID($last_id);
					}
					else if($request_type == 'quote')
					{
						if($tenant_last_quote_id){
							$last_id = (int)$tenant_last_quote_id;
						}else{
							// first invoice
							$last_id = 0;
						}
						
						if($tenant_last_used_quote_id){
							$last_used_id = (int)$tenant_last_used_quote_id;
						}else{
							// first invoice
							$last_used_id = 0;
						}
						
						$output_new_id = IntegrityInvoice\Utilities\AppHelper::newQuoteID($last_id);
					}
					  echo $output_new_id; ?>" placeholder="{{ $output_new_id }}">
					  <small class="the_last_id">Last used ID: {{ $last_used_id }}</small>
					  </div></td>
                </tr>
                <tr>
                    <td class="meta-head">Issue Date</td>
                    <td><input class="cw issuedate" value="<?php echo $inv_date = AppHelper::date_to_text(substr($invoice->created_at, 0, 10),$preferences->date_format); ?>" id="issuedate" name="" type="text" /></td>
                </tr> 
                @if($request_type == 'quote')                  						
                <tr>
                    <td class="meta-head">Amount</td>
                    <td><div><span class="cur_symbol"></span><span class="due"><?php echo $invoice->balance_due; ?></span></div></td>
                </tr>
                @elseif($request_type == 'invoice')                  						
                <tr>
                    <td class="meta-head">Amount Due</td>
                    <td><div><span class="cur_symbol"></span><span class="due"><?php echo $invoice->balance_due; ?></span></div></td>
                </tr>
                @endif
                
                @if($request_type == 'invoice')                  						
                <tr>
                    <td class="meta-head">Due Date</td>
                    <td><input class="cw duedate" value="<?php echo $inv_date = AppHelper::date_to_text(substr($invoice->due_date, 0, 10), $preferences->date_format); ?>" id="duedate" name="" type="text" /></td>
                </tr>
                @endif
                
            </table>           
		</div> <!-- END invnum -->
 
 </div><!-- End Invoice Top -->


<?php // $this->load->view('invoices/currency'); ?>
<input type="hidden" name="pref_tax1" id="pref_tax1" value="<?php echo $preferences->tax_perc1; ?>" tax_1name="<?php echo $preferences->tax_1name; ?>" />
<input type="hidden" name="pref_tax2" id="pref_tax2" value="<?php echo $preferences->tax_perc2; ?>" tax_2name="<?php echo $preferences->tax_2name; ?>" />
<input type="hidden" id="discount_or_tax_updated" value="<?php echo Session::has('discount_or_tax_updated') ? Session::get('discount_or_tax_updated') : 0; ?>" >
		<table id="items">
		
		  <tr>
		      <th width="2%" class="alignLeft">#</th>
		      @if($invoice->business_model == 0)
		      <th class="th_item" width="30%" class="alignLeft">Product</th>
		      @elseif($invoice->business_model == 1)
		      <th class="th_item" width="30%" class="alignLeft">Service</th>
		      @endif
		      @if($invoice->business_model == 1 && $invoice->bill_option == 0)
		      <th width="8%">Rate</th>
		      @else
		      <th width="8%">Cost</th>
		      @endif
		      @if($invoice->business_model == 0)
		      <th width="7%">Qty</th>
		      @elseif($invoice->business_model == 1 && $invoice->bill_option == 0)
		      <th width="7%">Hour(s)</th>
		      @endif
		      
		      @if($enable_discount)
		      <th class="th_discount" width="8%">Disc. (%)</th>
		      @endif
		      
		      @if($enable_tax)
		      <th class="th_tax" width="10%">Tax</th>
		      @endif	      
		      <th width="15%">Line Total</th>
		  </tr>
		  
		  <!-- START ITEM ROW -->
		  
		  <?php $countrow = 0; foreach($items as $item): ?>
           	<?php $countrow ++; ?>
           	<tr class="item-row {{ $countrow == 1 ? 'firstrow' : '' }}">
		      <td class="item-name"><div class="delete-wpr">
		      	<a class="delete fa fa-trash-o" href="javascript:;" title="Remove row">&nbsp;</a></div></td>
		      <td class="description"><textarea class="cw desc"><?php echo preg_replace($find, $replace, $item[1]); ?></textarea></td>
		      <td><textarea class="cost cw"><?php echo $item[2]; ?></textarea></td>
		      
		      @if($invoice->business_model == 0)
		        <td><textarea class="qty cw"><?php echo $item[3] ?></textarea></td>
		      @elseif($invoice->business_model == 1 && $invoice->bill_option == 0)
		    	<td><textarea class="qty cw"><?php echo $item[3] ?></textarea></td>
		      @endif
		      
		      
		      @if($enable_discount)
		       <td class="disc_holder">
		       	<input class="disc" value="<?php 		       	
		       	if($item[6] == 1){
		       		$percs = round(($item[7] * 100) / ($item[3] * $item[2]));
		       	}else if($item[6] == 2){
		       		$percs = $item[7];
		       	}else{
		       		$percs = "";
		       	}
		       	echo $percs; ?>" name="" />
		               	
		       </td>
		        @endif
		        
		       @if($enable_tax)
		      <td class="tx_holder"><select class="itemtax cw">
		      	<option <?php echo $item[4] == 0 ? 'selected' : ""; ?> value="0"> - </option>
		      	<option class="ttax1" <?php echo $item[4] == 1 ? 'selected' : ""; ?> value="1">Tax 1</option>
		      	<option class="ttax2" <?php echo $item[4] == 2 ? 'selected' : ""; ?> value="2">Tax 2</option>
		      </select></td>
		      @endif
		      
			  <td><span class="cur_symbol"></span><span class="price"><?php echo $item[8]; ?></span><span class="hide-line-tax">0.00</span><span class="hide-line-discount">0.00</span></td>
		  </tr>
		  
         <?php endforeach; ?>
         
		      
		  <!-- END ITEM ROWS -->
          
		  <tr id="hiderow">
		    <td class="alignLeft" width="3%" colspan="1"><a id="addrow" class="gen_btn" href="javascript:;" title="Add a row"><i class="fa fa-plus"></i></a></td>
            <td colspan="{{ $colspan3 }}">&nbsp;</td>
		  </tr>
		  
		  <tr>
		      <td colspan="{{ $colspan1 }}" class="blank "><h5 class="invoice_note_title">Additional Notes</h5><div class="fornote"><textarea style="height:120px; width:98%;" class="cwl notetext"><?php echo $invoice->note; ?></textarea></div></td>
		      <td colspan="{{ $colspan2 }}" class="total-line"><strong>Sub Total</strong></td>
		      <td class="total-value"><div><span class="cur_symbol"></span><span class="c_subtotal" id="subtotal"><?php echo $invoice->subtotal; ?></span></div></td>
		  </tr>
		  
		   @if($enable_discount == true)
           <tr id="mydiscount">
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="vat-line"><strong>Discount</strong></td>
		      <td class="vat-value"><div><span class="cur_symbol"></span><span id="discount" class="c_discount"><?php echo $invoice->discount_val; ?></span></div></td>
		  </tr>
		  @endif
		  
		  @if($enable_tax == true)
          <tr id="mytax">
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="vat-line"><strong>Tax</strong></td>
		      <td class="vat-value"><div><span class="cur_symbol"></span><span id="vat" class="c_vat"><?php echo $invoice->tax_val; ?></span></div></td>
		  </tr>
		  @endif		  
		 
		  <tr>
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="total-line balance">NET Total </td>
		      <td class="total-value balance"><div><span class="cur_symbol"></span><span class="due"><?php echo $invoice->balance_due; ?></span></div></td>
		  </tr>
          <tr>
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="ttoleft"><div><a class="gen_btn" id="cancelInvoice" href="<?php echo URL::to('invoices'); ?>">Cancel</a></div></td>
		      <td class=""><div><button class="gen_btn" id="editInvoice">Create</button></div></td>
		  </tr>
		  
		  <tr>		  	
		  	<td colspan="{{ $colspan1 }}">
		  		<p class="bankinfo">				 
				 <label>Show payment details on PDF</label> <input type="checkbox" value="<?php echo $invoice->bankinfo; ?>" <?php if($invoice->bankinfo != 0){ echo "checked=\"checked\""; } ?> name="bankinfo" id="bankinfo" class="">
				</p>
		  	</td>   
		  </tr>
          
		</table>
		 
	 
        <input type="hidden" value="<?php echo htmlentities(Session::get('tenantID')); ?>" name="tenantID" id="tenantID" />
        <input type="hidden" value="<?php echo htmlentities(Session::get('user_id')); ?>" name="user_id" id="user_id" />
       	
		<div id="terms">
		  <h5><?php echo $preferences->footnote1 != "" ? $preferences->footnote1 : ""; ?></h5>
		  <textarea class="cw"><?php echo $preferences->footnote2 != "" ? $preferences->footnote2 : ""; ?></textarea>
		</div>
	</div>
</div>

</div><!-- Inner Wrap-->
 
         
<?php else: ?>
	<h3>You have reached your monthly limit. Please consider upgrading if you wish to create more invoices. <a href="{{ URL::to('subscription') }}" class="to_all"> UPGRADE NOW</a></h3>
	
<?php endif; ?>

<div id="newclientoninvoice_popup" class="page_popup well">
		<h2>Clients &raquo; New</h2> <br />
		
		 <form>
		
				<label>Company / Business name <span class="mand">*</span></label>
		        <input type="text" name="company" class="txt the_company_name" id="company_" value="{{ Input::old('company')}}" autocomplete="off" />
		        
		         <label>Email <span class="mand">*</span></label>
		            <input type="text" name="email" class="txt contact_email" id="email_" value="{{ Input::old('email')}}" autocomplete="off" />
		      
		         <label>First name<span class="mand">*</span> </label>
		            <input type="text" name="firstname" class="txt" id="firstname" value="{{ Input::old('firstname')}}" autocomplete="off" />
		        <label>Last name or Surname</label>
		            <input type="text" name="lastname" class="txt" id="lastname" value="{{ Input::old('lastname')}}" autocomplete="off" />
		          
		         <p class="show_hide_field"><a class="ordinary_link" href="">Show more fields</a></p>
		        <div class="hide_field">
		        <label>Telephone </label>
		            <input type="text" name="phone" class="txt" id="phone" value="{{ Input::old('phone')}}" autocomplete="off" />
		        <label>Address Line 1 <span> </span></label>
		            <input type="text" name="add_1" class="txt" id="add_1" value="{{ Input::old('add_1')}}" autocomplete="off" />
		        
		        <label>County or State </label>
		            <input type="text" name="state" class="txt" id="state" value="{{ Input::old('state')}}" autocomplete="off" />
		            
		         <label>Postcode  or Zip code</label>
		            <input type="text" name="postal_code" class="txt" id="postal_code" value="{{ Input::old('postal_code')}}" autocomplete="off" />
		            
		        <label>Country </label>
	            <select name="country" id="country" class="sel">
				    <option value="<?php ?>" selected="selected">- select country -</option>
				    <?php foreach($countries as $country): ?>
				        <option value="<?php echo $country->name; ?>"><?php echo $country->name; ?></option>
				    <?php endforeach; ?>
				</select>				
		         
		        </div><!-- END hide_field -->   
		        <p class="hide_hide_field"><a class="ordinary_link" href="">Hide more fields</a></p> 
		        <br />	         
		        <button class="newclientoninvoice_popup_close btn btn-default cancelBtn">Cancel</button> <input type="submit" id="addnewclient" class="gen_btn" name="add_item" value="Save client" /> 
		 
   		 </form>
    
     </div> <!-- END newclient_popup -->

	@stop
 
	@section('footer')
	 
		<script src="{{ URL::asset('assets/js/number_formatter.js') }}"></script>		 
		<script src="{{ URL::asset('assets/js/block.js') }}"></script>
		<script src="{{ URL::asset('assets/js/jquery.datetimepicker.js') }}"></script>	
		<script src="{{ URL::asset('assets/js/jquery.autocomplete.js') }}"></script>
		<script src="{{ URL::asset('assets/js/copy_invoice.js') }}"></script>
		
		<script>
			$(document).ready(function(){
				
				if($('#appmenu').length > 0){
	  				// Check if ULR Contain invoice
	  				if(window.location.href.indexOf("invoice") > -1){
	  					
	  					$('.create_all_menu').addClass('selected_group'); 		 
				  		$('.menu_create_invoice').addClass('selected');		  		
				  		$('.create_all_menu ul').css({'display': 'block'});
				  		
	  				}else if(window.location.href.indexOf("quote") > -1){
	  				 	
	  				 	$('.create_all_menu').addClass('selected_group');  
				   		$('.menu_create_quote').addClass('selected');
				   		$('.create_all_menu ul').css({'display': 'block'});
				   		
	  				}
	  			 				   
			     }


                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                    $('#prorata_due_date, #the_currency, .item_sel').select2({
                        width: 'element'
                    });


                    $('#client_number').select2({ width: 'element' }).on("select2-close", function(){
                        if($(this).val() == "newclient"){
                            $(".newclientoninvoice_popup_open").trigger("click");
                        }
                    });

                    $('.itemtax').select2({	width: 'element' }).on("select2-close", function(){
                        $(".itemtax").trigger('mouseup');
                    });
                }
			 
				
				$('#issuedate').datetimepicker({			 
					lang:'en',
					timepicker:false,
					format: $('.pref_dateformat').val(),
					formatDate:'Y/m/d',
					closeOnDateSelect:true					  
				}); 
				
				
				$('#duedate').datetimepicker({			 
					lang:'en',
					timepicker:false,
					format: $('.pref_dateformat').val(),
					formatDate:'Y/m/d',
					closeOnDateSelect:true					  
				});
				
				$.fn.popup.defaults.pagecontainer = '.page-panel';
				
				 $('.show_hide_field').click(function() {
				 	$('.show_hide_field').hide();				 
				  	$('.hide_field').fadeIn(400, function(){				  		
				  		$('.hide_hide_field').show();
				  	});
				  	
				  	return false;				   
				 });
				 
				 
				 $('.hide_hide_field').click(function() {				 
				  	$('.hide_field').fadeOut(400, function(){
				  		$('.show_hide_field').show();
				  		$('.hide_hide_field').hide();
				  	});
				  					  	
				  	return false;				   
				 });
				 
				 
				$('#client_number').on('change', function(){					
				   if($(this).val() == "newclient"){				   	 
				   		$(".newclientoninvoice_popup_open").trigger("click");				   		
				   }				 			 
				});  
				
			 
	 
	   			// Initialize the plugin
	  			$('#newclientoninvoice_popup').popup({
	  				opacity: 0.8,
	  				vertical: 'top',
	  				transition: 'all 0.3s',			    
				    outline: true, // optional
	    			focusdelay: 300, // optional,	    			 	    			
	    			onclose: function() {  				 	
	    				//$( "#client_number").unbind("change");				    
					 }
				});
				
				
				// New Client AJAX request
				$('#addnewclient').click(function(){					
				 
				  $company = $('#company_').val();
				  $email = $('#email_').val();
				  $firstname = $('#firstname').val();
				  $lastname = $('#lastname').val();
				  $phone = $('#phone').val();
				  $add1 = $('#add_1').val();
				  $state = $('#state').val();
				  $postcode = $('#postal_code').val();
				  $country = $('#country').val();
		 
			 	   
			 	   var $data = "company="+$company+"&email="+$email+"&firstname="+$firstname+"&lastname="+$lastname+"&phone="+$phone+"&state="+$state+"&add_1="+$add1+"&postal_code="+$postcode+"&country="+$country;
			 	   
			 	   var jqxhr = $.ajax({ url: "../../clients/store",
									 type: "POST",	
									 data: $data
					}).success(function() {
				 
					    alert("New client was saved successfully.");						
							
					})
					.error(function() { alert("error"); })
					.complete(function() {
						
						$('#invoiceClientsList').load("../../invoices/clients_select_list");
					    $(".newclientoninvoice_popup_close").trigger("click");
					    $('#client_number').select2({
		 					width: 'element'
		 				});
		
				     });
			 	 
			 		return false;
			 	});	
				
				
				
	   		       // Item Auto complete
	   		      var items_ajax_url;
	   		      if($('#bus_model').val() == 0){
	   		      	items_ajax_url = "../../products/json_list";
	   		      }else if($('#bus_model').val() == 1){
	   		      	items_ajax_url = "../../services/json_list";
	   		      }
			 	   
			 	  var all_products = [];
			 	 
			 	  $.getJSON( items_ajax_url, function(data){			 	 	
			 	     	$.each( data, function(index, value) {			 	 	 	
			 	 	 		var obj = {};
    							obj.value = value.item_name;
    							obj.data = value.unit_price;
    							obj.id = value.id;
    							obj.tax_type = value.tax_type;
							all_products.push(obj);
					 	});
				   });
			 	 
				 
			       // AJAX For Auto complete
			 	 	$('.desc').autocomplete({
						// serviceUrl: items_ajax_url,
						minChars:1,
						delimiter: /(,|;)\s*/, // regex or character
						maxHeight:180,								 
						lookup: all_products,
					    onSelect: function (suggestion) {
					        //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
					      
					        var selected_row = $(this).parent().parent();
					       	 
								 var itm_desc = suggestion.value;
								 var itm_unit = suggestion.data;
								 var itm_qty = 1;
								 var itm_tax = suggestion.tax_type; 
						    			
								 selected_row.find(".desc").val(itm_desc);
								 selected_row.find(".cost").val(itm_unit);
								 selected_row.find(".qty").val(itm_qty);
								 selected_row.find(".itemtax option[value='"+itm_tax+"']").attr("selected", "selected").val(itm_tax);
								 selected_row.find(".itemtax").trigger("change");
								
								 // update other areas			
								 $(".cost").trigger('keyup');
					 			 $(".qty").trigger('keyup');
					 			 $(".cost").trigger('blur');
					 			 $(".qty").trigger('blur');
					 			 $(".disc").trigger('blur');
					 			 $(".disc").trigger('mouseup');
					 			 $(".itemtax").trigger('mouseup');
					 			 $(".itemdisc").trigger('mouseup');
						 
					    }	// End onSelect	
					    	 
					});
				
			});
				
	    </script>
	 
	@stop
