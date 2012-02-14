<?php
/* This is the Main File which Contains all the functions	*
 *This is where the main processing of the program happens	*/

require('paths.php');

mysql_select_db("gndec_erp",$conn);

require($main_dir.'input_form_class.php');

require($includes_dir.'includes.php');

function CheckForLogin() {
	if(!isset($_SESSION['usertype'])) {
		session_destroy();
		header("location:index.php");
	}	
}

/* This Function is Used To fetch the names of tables and Columns from the database*/
function get_tables_cols() {
	$query_tables = mysql_list_tables("gndec_erp");
	$table_num = mysql_num_rows($query_tables);
	for ($j = 0; $j<=$table_num-1; $j++){
		$table_names[$j] = mysql_tablename($query_tables, $j);
	}
	$num_of_tables = count($table_names);
	for($i=0; $i<=$num_of_tables-1;$i++) {
		$sql = "SELECT * FROM ".$table_names[$i]."";
		$result = mysql_query($sql);
		$num_of_fields = mysql_num_fields($result);
		for($j=0;$j<=$num_of_fields-1;$j++){
			$col_names[$table_names[$i]][$j] = mysql_field_name($result,$j);
		}
	}
	return $col_names;
}

function add_admin($username,$fullname,$password,$usertype,$department,$mobile,$email)
{
	echo $mobile;
	echo $email;
	echo $department;
	$pass = md5($password);
	if($usertype=='Admin') {
		$sql = "SELECT User_Type,Department FROM users WHERE User_Type='Admin' AND Department='".$department."'";
		$num = mysql_num_rows(mysql_query($sql));
		if($num>0)
		{
			echo "<p style='color:red'>Error: Admin Already Exists for ".$department.", Only 1 Admin allowed Per Department.</p>";
			form("add_other");
		}
		else {
			$sql = "INSERT INTO users 
			   (Username,Full_Name,Password,User_Type,Department,Mobile,Email) 
			   VALUES ('".$username."','".$fullname."','".$pass."','".$usertype."','".$department."','".$mobile."','".$email."')";
			mysql_query($sql)or die(mysql_error());
		echo "<p>User '".$fullname."' Successfully Added</p>";
		}
	}
	elseif($usertype!='Admin') {
	$sql = "SELECT Username, User_Type FROM users WHERE Username='".$username."' AND User_Type='".$usertype."'";
	$num = mysql_num_rows(mysql_query($sql));
		if($num>0)
		{
			echo "<p style='color:red'>Error: User With Username ".$username."  Already Exists, Please use Different Username and Try Again</p>";
			form("add_other");
		}
		else {
			$sql = "INSERT INTO users 
			   (Username,Full_Name,Password,User_Type,Department,Mobile,Email) 
			   VALUES ('".$username."','".$fullname."','".$pass."','".$usertype."','".$department."','".$mobile."','".$email."')";
			mysql_query($sql)or die(mysql_error());
		echo "<p>User '".$fullname."' Successfully Added</p>";
		}
	}
}

?>
