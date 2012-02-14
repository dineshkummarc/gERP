<?php
/* This is the Main File which Contains all the forms
  This Function is used to display the various Form based on the type of form given
*/
require_once('paths.php');
require_once('functions.php');
CheckForLogin();
mysql_select_db("gndec_erp",$conn);
function form($formtype,$additional_detail) 
{
	
	if($formtype=='add_other')
	{
		echo "<p id='introduction'><table align='center'><tr><td><p>Please Fill The details below to Add New User (Admin,Teacher,TnP) To The Database.</p></td></tr></table>";
		echo "<table id='student_details' align='center'>";
		echo "<form id='add_admin' action='info.php?mode=add_other' method='post'>";
		echo "	<tr><td>User Type:</td><td><select name='usertype' id='usertype' class='required' onchange='add_other_admin_department()'>
					<option value='Admin'>Admin</option>
					<option value='Teacher'>Teacher</option>
					<option value='Clerk'>Clerk</option>
					<option value='Advisor'>Advisor</option>
					<option value='Training And Placement'>Training And Placement</option>
					</select></tr></td>";
		echo "<tr><td><div id='department_div_name'></div></td><td><div id='department_div'></div></td></tr>";
		echo "<script type='text/javascript'>add_other_admin_department()</script>";
		echo "<tr><td>Username</td><td><input type='text' name='Username' id='Username' class='required' />";
		echo "<tr><td>Password</td><td><input type='password' name='Password' id='New_Password' class='required' />";
		echo "<tr><td>Confirm Password</td><td><input type='password' name='Password_Confirm' id='Confirm_Password' class='required' />";
		echo "<tr><td>Full Name</td><td><input type='text' name='Full_Name' id='Full_Name' class='required' />";
		echo "<tr><td>Mobile</td><td><input type='text' name='Mobile' id='Mobile' class='required' />";
		echo "<tr><td>Email</td><td><input type='text' name='Email' id='Email' class='required' />";
		echo "<input type='hidden' name='Department' id='Department' value='' />";
		echo "<tr><td><input type='submit' value='Add' onclick='return add_other_checkpassword()' />";
		echo "</form></table>";
	}
		
}

?>
