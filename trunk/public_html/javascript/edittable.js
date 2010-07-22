var SelectState = false;
var SelectedElement = null;
var TextInput = null;
var TextInputOldValue="";
var EditTableURL="";

function EditTableCell( e ,url){

	if ( SelectState == false ){
		EditTableURL=url;
		SelectedElement = e;
		var CellText = e.innerHTML;
		e.innerHTML = "";

		var objInput = document.createElement("INPUT");
		objInput.type = 'text';
		objInput.value = CellText;
		objInput.id = "txt" + e.id;
		TextInputOldValue=CellText;
		objInput.onkeypress = KeypressEditTextBox;
		objInput.onchange = FinishEditTextBox;
		objInput.onblur = FinishEditTextBox;

		TextInput = objInput;
		e.appendChild(objInput);

		SelectState = true;

	}else{


	}
}


function FinishEditTextBox(e){
	if (!SelectState) return;
	SelectedElement.innerHTML = TextInput.value;

	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}


	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp && window.createRequest) {
		try {
			xmlhttp = window.createRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp) return ;


	httppage=EditTableURL+"&value="+TextInput.value;
	xmlhttp.open("GET", httppage,true);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			$result=xmlhttp.responseText;
		}
	}
	xmlhttp.send(null);

	SelectState = false;


}

function KeypressEditTextBox( e ){


	if (navigator.appName == "Microsoft Internet Explorer"){

		e = window.event;
		key = e.keyCode;
	}

	else if (navigator.appName == "Netscape"){

		key = e.which;
	}

/*	if ( key == 27 ){
		TextInput.value=TextInputOldValue;
		SelectState = false;

	}
	*/
	if ( key == 13 ){
		FinishEditTextBox(e);
	}
}

