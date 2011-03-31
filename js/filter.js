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

function filter_submit(){
    id = [
	'VLAN_TCI','VLAN_TCI_MASK',
	'ETH_TYPE_MASK',
	'SRC_PORT', 'SRC_PORT_MASK',
	'DST_PORT', 'DST_PORT_MASK',
    ];

    for (i in id){
	elem = document.getElementById(id[i]);
	if ( elem == null ){
	    continue;
	}

	value = elem.value.replace(/^\s+|\s+$/g, ''); /* trim */
	if ( value.substr(0,2) == "0x" ){
	    value = parseInt(value, 16);
	}
	elem.value = value;
    }

    return true;
}
