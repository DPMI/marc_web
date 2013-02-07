var header_size = 14+16+36;

function update_index() {
	var mask = 0;
	$('.row input:checked').each(function(){
		mask += 1 << $(this).data('index');
	});
	$('#index').val(mask);
}

function trim(value){
    return value.replace(/^\s+|\s+$/g, '');
}

function parse_hex(value){
    if ( value.substr(0,2) == "0x" ){
		return parseInt(value, 16);
    }
    return value;
}

function strip_mac(value){
    return value.replace(/[:-]/g, '');
}

function upper(value){
    return value.toUpperCase();
}

function validate_numeric(value){
    if ( value == "" ){ return value; }
    var tmp = parseInt(value);
    if ( isNaN(tmp) || value != tmp ) throw "Not a number";
    return value;
}

function validate_ethernet(value){
	if ( value.match(/^[0-9a-fA-F]+$/) == null ){
		throw "Not valid ethernet address";
	}
	return value;
}

function filter_clear(elem){
	elem.className = "";
	elem.title = "";
}

function filter_validate(){
    fields = {
		'VLAN_TCI': [trim, parse_hex, validate_numeric],
		'VLAN_TCI_MASK': [trim, parse_hex, validate_numeric],
		'ETH_TYPE_MASK': [trim, parse_hex, validate_numeric],
		'ETH_SRC': [trim, strip_mac, upper, validate_ethernet],
		'ETH_DST': [trim, strip_mac, upper, validate_ethernet],
		'SRC_PORT': [trim, parse_hex, validate_numeric],
		'SRC_PORT_MASK': [trim, parse_hex, validate_numeric],
		'DST_PORT': [trim, parse_hex, validate_numeric],
		'DST_PORT_MASK': [trim, parse_hex, validate_numeric],
		'CAPLEN': [trim, validate_numeric]
    };
    var ret = true;

    for ( id in fields ){
		elem = document.getElementById(id);
		if ( elem == null ){
			continue;
		}

		/* ignore disabled fields */
		if ( $(elem).attr('disabled') ){
			continue;
		}

		value = elem.value;
		try {
			pipes = fields[id];
			for ( func in pipes ){
				value = pipes[func](value);
			}
			elem.value = value;
		} catch ( e ){
			console.log(value + ' failed to validate: ' + e);
			elem.className = "invalid";
			elem.title = e;
			ret = false;
		}
    }

	return ret;
}

function caplen_validate(){
	if ( ma_mtu == -1 ) return; /* mtu info not available */

	var $this = $('#caplen');
	var color = '';
	if ( $this.val() > (ma_mtu - header_size) ){
		color = '#f00';
	}
	$('#caplen').css('color', color);
}

function filter_submit(){
	if ( !filter_validate() ){
		return false;
	}

	/* #42: re-enable all fields to make sure they are transferred */
	$('.row input:text').attr('disabled', false);
	$('.row select').attr('disabled', false);

    return true;
}

function filter_cancel(){
    /* this is required because the form would otherwise be validated, and if
     * it contains errors it wouldn't be able to cancel. Which would make the
     * point of the cancel button vague. Also, onsubmit must be used to ensure
     * the form is validated all the time, like when pressing enter. */
    document.myForm.onsubmit = function(){};
    document.myForm.submit();
}

function filter_init(){
	$('#ind').attr('readonly', true);

	/* remove row index from view, final mask is calculated anyway */
	$('.row input:checkbox').each(function(){
		var $td = $(this).parent();
		var $children = $td.children();
		$td.text('').append($children);
		$td.width(25);
	});

	$('.row input:checkbox').each(function(){
		var $row = $(this).parent().parent();
		var en = !$(this).attr('checked');
		$row.find('input:text').attr('disabled', en);
		$row.find('select').attr('disabled', en);
	});
	$('.row input:checkbox').click(function(){
		update_index();
		var $row = $(this).parent().parent();
		var en = !$(this).attr('checked');
		$row.find('input:text').attr('disabled', en);
		$row.find('select').attr('disabled', en);
	});

	/* enable help for input fields */
	f = function($row){
		/* show new help */
		var text = $row.data('description');
		if ( text == undefined ) text = 'No description available';
		$('#description').html(text);

		/* update addresstable */
		var addresstable = $.inArray('address', $row.attr('class').split(' ')) >= 0;
		if ( addresstable ){
			if ( ethernet_addr.length > 0 ){
				var existing = ethernet_addr.map(function(x){
					return '<li>' + x.addr + ' ' + x.mampid + ' ' + x.id + '</li>';
				});
				$('#description').find('.address').html('<ul>'+existing.join('')+'</ul>');
			} else {
				$('#description').find('.address').html('<p>No addresses is currently used</p>');
			}
		}

		if ( $row.find('#caplen').length > 0 && ma_mtu >= 0 ){
			$('#description').find('.mtu').html('<b>MTU: ' + ma_mtu + '</b> (max caplen is ' + (ma_mtu - header_size) + ')');
		}
	}
	$('.row input:text, .row select').focus(function(){
		f($(this).parent().parent());
	});
	$('.row .label').click(function(){
		f($(this).parent());
	});

	/* show default help when clicking somewhere else */
	var default_description = $('#description').html();
	$(document).click(function(){
		$('#description').html(default_description);
	});

	/* prevent default description over certain elements */
	$('.row input:text, .row select, .row .label, #help').click(function(e){
		e.stopPropagation();
	});

	/* warn if caplen is too large */
	$('#caplen').change(caplen_validate);
	caplen_validate(); /* always run during init */
}

$(document).ready(function(){
	var $confirm = $("#dialog-confirm");

	$('a.delete').click(function(){
		var $a = $(this);
		var id = $a.parent().parent().find('.id').html();
		var mampid = $('.mampid').html();
		$confirm.attr('title', 'Confirm delete');
		$confirm.find('.text').html('Are you sure you want to delete filter '+id+' from '+mampid+'?');
		$confirm.dialog({
			resizable: false,
			height:140,
			buttons: {
				"Delete": function() {
					$(this).dialog('close');
					window.location = $a.attr('href')+'?confirm=delete';
				},
				"Cancel": function() {
					$(this).dialog('close');
				}
			}
		});

		return false;
	});
});
