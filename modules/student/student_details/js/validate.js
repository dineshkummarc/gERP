/* This File Contains Various Validation functions and AJAX Functions
 * The Contents of This file are available under GPL License.
 * Author: Harbhag Singh Sohal
 * Blog : http://harbhag.wordpress.com
 */





function checkbranch(){
	var course=document.getElementById('Course')
	var branch=document.getElementById('Branch')
	if(course.value=='MBA'){
		if(branch.value != ''){
			alert("Please Leave Branch Field Blank for "+course.value);
			return false;
		}
	}
	
	if(course.value=='MCA'){
		if(branch.value != ''){
			alert("Please Leave Branch Field Blank for "+course.value);
			return false;
		}
	}
	
	if(course.value=='B.Tech'){
		if(branch.value=='' || branch.value=='N/A'){
			alert("Please Select Branch");
			return false;
		}
	}
	
}

function confirm_add(){
	var answer = confirm("You are going add new user, do you want to continue ?");
	
	if(answer){
		return true;
	}
	else{
		return false;
	}
}

function confirm_edit(rollno){
	var answer = confirm("You are going to update the entry for Roll No "+ rollno+", do you want to continue ?");
	
	if(answer){
		return true;
	}
	else{
		return false;
	}
}

function confirm_delete(rollno){
	var answer = confirm("You are going to delete the entry for Roll No "+ rollno+", do you want to continue ?");
	
	if(answer){
		return true;
	}
	else{
		return false;
	}
}

function confirm_delete_user(name){
	var answer = confirm("You are going to delete the User  "+ name+", do you want to continue ?");
	
	if(answer){
		return true;
	}
	else{
		return false;
	}
}

function checkpassword(){
	var password=document.getElementById('New_Password')
	var password_confirm=document.getElementById('Confirm_Password')
	
	if(password.value==''){
		alert("Please Enter Password");
		return false;
	}
	
	if(password.value!=''){
		
		if(password.value!=password_confirm.value){
			alert("Both Passwords Does not Match");
			return false;
		}
	}
}



function add_other_checkpassword(){
	var username = document.getElementById('Username')
	var password=document.getElementById('New_Password')
	var password_confirm=document.getElementById('Confirm_Password')
	var fullname = document.getElementById('Full_Name')
	
	if(username.value=='') {
		alert("Please Enter Username");
		return false;
	}
	
	if(password.value==''){
		alert("Please Enter Password");
		return false;
	}
	
	if(password.value!=''){
		
		if(password.value!=password_confirm.value){
			alert("Both Passwords Does not Match");
			return false;
		}
	}
	
	if(fullname.value=='') {
		alert("Please Enter Fullname");
		return false;
	}
	else {
		var depa = document.getElementById('Department_Ajax').value;
		document.getElementById('Department').value=depa;
	}
}


function change_password_user(current){
	var post=document.getElementById('Current_Password')
	var password=document.getElementById('New_Password')
	var password_confirm=document.getElementById('Confirm_Password')
	if(post.value!=current){
		alert("Wrong Current Password");
		return false;
	}
	
	if(password.value==''){
		alert("Please Enter Password");
		return false;
	}
	
	if(password.value!=''){
		
		if(password.value!=password_confirm.value){
			alert("New Password and Confirm Password Does Not Match");
			return false;
		}
	}
}

function CheckAll(chk){
	var mobile = new Array();
	for (i = 0; i < chk.length; i++){
		chk[i].checked = true ;
	}
}

function UnCheckAll(chk){
	for (i = 0; i < chk.length; i++)
	chk[i].checked = false ;
}

function add_mobile(chk,objid){
	var mobile = new Array();
	for (i = 0; i < chk.length; i++){
		if(chk[i].checked==true){
			mobile.push(chk[i].value);
		}
	}
	mobile_str = mobile.join(",")
	var x = document.getElementById(objid);
	x.value = mobile_str;
}



function disappear_name(id) {
	document.getElementById(id).innerHTML="";
}

function show_name(id,name) {
	document.getElementById(id).innerHTML=name;
}


function changedist(str)
{
if (str=="")
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
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
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

function checkcompany(){
	var company = document.getElementById("Company_Name");
	var other = document.getElementById("Other_Company");
	if(company.value!=''){
		if(other.value!=''){
			alert("Either Select company from Dropdown or Enter name manually. Do not fill both the fields");
			return false;
		}
	}
}

function patience(){
	alert("Sending email to all the students can take upto 10 Mins. so please be patient");
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

function set_branch_selector() {
	show_name('branch_div_name','Branch');
		var xmlhttp;
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
				document.getElementById('branch_div').innerHTML=xmlhttp.responseText;
			}


		}		
		xmlhttp.open("GET","branch_selector.php",true);
		xmlhttp.send();
}

























