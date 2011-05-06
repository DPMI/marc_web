function updateIndex() {
	ci=0;
	vlan=0;
	ethtype=0;
	ethsrc=0;
	ethdst=0;
	ipprot=0;
	ipsrc=0;
	ipdst=0;
	srcport=0;
	dstport=0;
	if(document.myForm.ci_cb.checked==1){ ci=512;}
	if(document.myForm.vlan_cb.checked==1) { vlan=256;}
	if(document.myForm.etht_cb.checked==1) { ethtype=128;}
	if(document.myForm.eths_cb.checked==1) { ethsrc=64; }
	if(document.myForm.ethd_cb.checked==1) { ethdst=32; }
	if(document.myForm.ipp_cb.checked==1) { ipprot=16; }
	if(document.myForm.ips_cb.checked==1) { ipsrc=8; }
	if(document.myForm.ipd_cb.checked==1) { ipdst=4; }
	if(document.myForm.sprt_cb.checked==1) { srcport=2; }
	if(document.myForm.dprt_cb.checked==1) { dstport=1; }
	document.myForm.ind.value=ci+vlan+ethtype+ethsrc+ethdst+ipprot+ipsrc+ipdst+srcport+dstport;
	return;
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

function filter_clear(elem){
    elem.className = "";
    elem.title = "";
}

function filter_submit(){
    fields = {
	'VLAN_TCI': [trim, parse_hex, validate_numeric],
	'VLAN_TCI_MASK': [trim, parse_hex],
	'ETH_TYPE_MASK': [trim, parse_hex],
	'ETH_SRC': [trim, strip_mac, upper],
	'ETH_DST': [trim, strip_mac, upper],
	'SRC_PORT': [trim, parse_hex],
	'SRC_PORT_MASK': [trim, parse_hex],
	'DST_PORT': [trim, parse_hex],
	'DST_PORT_MASK': [trim, parse_hex],
	'CAPLEN': [trim, validate_numeric]
    };
    var ret = true;

    for ( id in fields ){
	elem = document.getElementById(id);
	if ( elem == null ){
	    continue;
	}

	try {
	    pipes = fields[id];
	    value = elem.value;
	    for ( func in pipes ){
		value = pipes[func](value);
	    }
	    elem.value = value;
	} catch ( e ){
	    elem.className = "invalid";
	    elem.title = e;
	    ret = false;
	}
    }
    
    console.log("validated");
    return ret;
}

function filter_cancel(){
    /* this is required because the form would otherwise be validated, and if
     * it contains errors it wouldn't be able to cancel. Which would make the
     * point of the cancel button vague. Also, onsubmit must be used to ensure
     * the form is validated all the time, like when pressing enter. */
    document.myForm.onsubmit = function(){};
    document.myForm.submit();
}
