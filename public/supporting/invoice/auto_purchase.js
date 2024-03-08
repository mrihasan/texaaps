//adds extra table rows
var i=$('table tr').length;
function addNewRow() {
	html = '<tr>';
	html += '<td style="text-align:center"><input class="case" type="checkbox"/></td>';
    html += '<td><input type="text" data-type="title" custom="doup" name="itemName[]" id="itemName_'+i+'" class="form-control autocomplete_txt" autocomplete="off" required ></td>';
    html += '<td class = "d-none"><input type="number" data-type="productId" name="productId[]" id="productId_'+i+'" class="form-control autocomplete_txt " autocomplete="off"></td>';
    html += '<td><input type="number" name="quantity[]" id="quantity_'+i+'" step="any" class="form-control changesNo" autocomplete="off"  onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" style="text-align:right;"></td>';
    html += '<td><input type="text" name="unit_name[]" id="unit_name_'+i+'" class="form-control " autocomplete="off"  ondrop="return false;" onpaste="return false;" style="text-align:center;" readonly></td>';
    html += '<td><input type="number" name="stock[]" id="stock_'+i+'" class="form-control in_stock"  disabled="disabled"></td>';
    html += '<td><input type="number" step="any" name="unitBuyPrice[]" id="unitBuyPrice_'+i+'" class="form-control changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" style="text-align:right;"></td>';
    html += '<td><input type="number" step="any" name="mrpTotal[]" id="mrpTotal_'+i+'" class="form-control changesNo mrpTotal" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" style="text-align:right;" readonly></td>';

    $('table').append(html);
    i++;
}

// Function to set autofocus on the first input field of the last row
function setAutofocus() {
    var newRow = $('table tr:last');
    var firstInput = newRow.find('.autocomplete_txt:first');
    if (firstInput.length > 0) {
        firstInput.focus();
    }
}

// Hotkey event listener
document.addEventListener("keydown", function (event) {
    // Check if the key pressed is 'A' and the Ctrl key is also pressed
    if ((event.key === 'a' || event.key === 'A') && event.altKey) {
        addNewRow();
        setAutofocus();
    }
});

// Add more button click event listener
$(".addmore").on('click', function () {
    addNewRow();
    setAutofocus();
});

// DOMNodeInserted event listener to handle dynamic additions
$('table').on('DOMNodeInserted', function () {
    setAutofocus();
});


setTimeout(function() {
    // Set autofocus on a specific input field by ID
    document.getElementById('itemName_1').focus();
}, 300);


//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//deletes the selected table rows
$(".delete").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal();
});

//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
	type = $(this).data('type');
	
	if(type =='title' )autoTypeNo=1;
	
	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : './auto_product',
				dataType: "json",
				method: 'post',
				data: {
				   name_startsWith: request.term,
				   type: type
				},
				beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
				 success: function( data ) {
					 // console.log(data);
					 response( $.map( data, function( item ) {
					 	var code = item.split("|");
						return {
							label: code[autoTypeNo],
							value: code[autoTypeNo],
							data : item
						}
					}));
				}
			});
		},
		autoFocus: true,	      	
		minLength: 0,
		select: function( event, ui ) {
			var names = ui.item.data.split("|");						
			id_arr = $(this).attr('id');
	  		id = id_arr.split("_");
            // $name = $value->product_id . '|' . $value->fulltitle . '|' . $value->unitsell_price . '|' . $value->unitbuy_price . '|' . $value->utitle;
			$('#itemName_'+id[1]).val(names[1]);
            $('#productId_'+id[1]).val(names[0]);
            $('#quantity_'+id[1]).val(0);
			$('#unit_name_'+id[1]).val(names[4]);
			$('#unitBuyPrice_'+id[1]).val(names[3]);
			$('#mrpTotal_'+id[1]).val(0*names[3]);
			calculateTotal();
		}		      	
	});
});

$(document).on('change keyup blur','.changesNo',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
    // console.log(id);
	quantity = $('#quantity_'+id[1]).val();
    unitBuyPrice = $('#unitBuyPrice_'+id[1]).val();

    console.log(unitBuyPrice)
    // discountUnit = $('#discountUnit_'+id[1]).val();
    // discountedMrp = $('#discountedMrp_'+id[1]).val();
    if( quantity!='' && unitBuyPrice!='') $('#mrpTotal_'+id[1]).val( (parseFloat(unitBuyPrice)*parseFloat(quantity)).toFixed(2));
    // if( quantity!='' && discountUnit !='') $('#discountTotal_'+id[1]).val((parseFloat(discountUnit)*parseFloat(quantity)).toFixed(2));
    // if( quantity!='' && discountedMrp !='') $('#discountedTotalMrp_'+id[1]).val((parseFloat(discountedMrp)*parseFloat(quantity)).toFixed(2));
	calculateTotal();
});

$(document).on('change keyup blur','#tax',function(){
	calculateTotal();
});

$(document).on('change keyup blur','#discount',function(){
	calculateTotal();
});

//total price calculation
function calculateTotal(){
    subTotal = 0 ; total = 0;
    $('.mrpTotal').each(function(){
        if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
    });
    // console.log( subTotal );
    $('#subTotal').val( subTotal.toFixed(2) );
    tax = $('#tax').val();
    discount = $('#discount').val();

    // discountAmount = $('#discountAmount').val();

    if(tax != '' && typeof(tax) != "undefined" && discount != '' && typeof(discount) != "undefined" ){
        taxAmount = subTotal * ( parseFloat(tax) /100 );
        discountAmount = subTotal * ( parseFloat(discount) /100 );
        // discountAmount =  parseFloat(discount) ;
        $('#taxAmount').val(taxAmount.toFixed(2));
        $('#discountAmount').val(discountAmount.toFixed(2));
        // $('#discountAmount').val(discountAmount.toFixed(2));
        total = subTotal +taxAmount - discountAmount;
    }

    else if(tax != '' && typeof(tax) != "undefined" ){
        taxAmount = subTotal * ( parseFloat(tax) /100 );
        $('#taxAmount').val(taxAmount.toFixed(2));
        total = subTotal + taxAmount;
    }
    else if(discount != '' && typeof(discount) != "undefined" ){
        discountAmount = subTotal * ( parseFloat(discount) /100 );
        $('#discountAmount').val(discountAmount.toFixed(2));
        total = subTotal - discountAmount;
    }

    else{
        $('#taxAmount').val(0);
        $('#discountAmount').val(0);
        total = subTotal;
    }
    $('#totalAftertax').val( Math.round(total) );
    calculateInvoiceTotal();
}

$(document).on('change keyup blur','#lessAmount',function(){
	calculateInvoiceTotal();
    // calculateLessAmount();

});

//due amount calculation
function calculateInvoiceTotal(){
    lessAmount = $('#lessAmount').val();
    // amountPaid = $('#amountPaid').val();
    total = $('#totalAftertax').val();
    // if(amountPaid != '' && typeof(amountPaid) != "undefined" ){
        invoiceTotal = parseFloat(total) - parseFloat( lessAmount );
        $('.invoiceTotal').val( parseFloat(invoiceTotal).toFixed(2));
    // }else{
    //     total1 = parseFloat(total).toFixed(2);
    //     $('.invoiceTotal').val( total1 );
    // // }
}

//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    // console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}


$(document).on("focus", ".datepicker", function() {
    $(this).filter(":not(.hasDatepicker)").datepicker(
        {
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "auto",
            // todayBtn: true,
        }
	)
})


$('#submit').click(function () {
//Payment method is required if paid amount is not equal to zero
// function methodRequired() {
    var returnValue = true;
    var paidAmount = document.getElementById("paidAmount").value;
    var transactionMethod = document.getElementById("transactionMethod").value;
    if (paidAmount != 0 && transactionMethod =='') {
        document.getElementById("reqMethod").innerHTML = "* Method is required.";
        returnValue = false;
    }
    return returnValue;

// Check Duplicate for input product name
    var valid = true;
    $.each($('input[custom="doup"]'), function (index1, item1) {
        $.each($('input[custom="doup"]').not(this), function (index2, item2) {
            if ($(item1).val() == $(item2).val())
            {
                $(item1).css("border-color", "red");
                valid = false;
                // alert("Please Check Duplicate entry");
            }
        });
    });
    return valid;

});







