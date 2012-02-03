/* This File Contains Various Validation functions and AJAX Functions
 * The Contents of This file are available under GPL License.
 * Author: Harbhag Singh Sohal
 * Blog : http://harbhag.wordpress.com
 */

/*The following function takes roll no as argument and shows confirmation box
 * before submitting the form
 */
function confirm_edit(rollno){
	var answer = confirm("You are going to update the entry for Roll No "+ rollno+", do you want to continue ?");
	
	if(answer){
		return true;
	}
	else{
		return false;
	}
}

/*This function is used to disappear the name of the <div>
 */
function disappear_name(id) {
	document.getElementById(id).innerHTML="";
}

/*This function is used to show the name of the <div> when that <div> is select-
 * -ed
 */
function show_name(id,name) {
	document.getElementById(id).innerHTML=name;
}

/*This function is used to change the district according to the selected state
 */
function changedist(str) {
	if(str=="") {
		document.getElementById("dist").innerHTML="";
		return;
  }
  if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
  }
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById('dist').innerHTML=xmlhttp.responseText;
    }
	}

	xmlhttp.open("GET","dist.php?q="+str,true);
	xmlhttp.send();
}

function setstate(distr)
{
	var str=document.getElementById('State');
	
	if (str.value=="")
  {
  document.getElementById("dist").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.open("GET","setaccesstoken.php",true);
xmlhttp.send();

}
		
function admission_no()
{
	var xmlhttp;
	var str=document.getElementById('Admission_Type');
	if (str.value!="PTU Councelling")
  {
  document.getElementById("ad_no").innerHTML="";
  return;
  }
  
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
  
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById('ad_no').innerHTML=xmlhttp.responseText;
    }


}
xmlhttp.open("GET","add_no.php",true);
xmlhttp.send();
	
}



function admission_no_edit(ad_no)
{
	var xmlhttp;
	var str=document.getElementById('Admission_Type');
	if (str.value!="PTU Councelling")
  {
  document.getElementById("ad_no").innerHTML="";
  return;
  }
  
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
  
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById('ad_no').innerHTML=xmlhttp.responseText;
    }


}
xmlhttp.open("GET","add_no.php?q="+ad_no,true);
xmlhttp.send();
	
}




function disappear_ad_no() {
	var str = document.getElementById('Admission_Type');
	if(str.value!="PTU Councelling"){
		disappear_name("ad_no_name");
		document.getElementById("ad_no").innerHTML="";
	}
	if(str.value=="PTU Councelling"){
		show_name("ad_no_name","Admission No.");
		admission_no();
	}

}


function disappear_ad_no_edit() {
	var str = document.getElementById('Admission_Type');
	if(str.value!="PTU Councelling"){
		document.getElementById("ad_no_name").innerHTML="";
		document.getElementById("ad_no").innerHTML="";
	}
	if(str.value=="PTU Councelling"){
		var no = document.getElementById("ad_no_hidden");
		show_name("ad_no_name","Admission No.");
		admission_no_edit(no.value);
	}

}
