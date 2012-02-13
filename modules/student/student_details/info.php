<?php 

session_start();

include_once('../../../includes/paths.php');

include_once($header_footer_dir.'header.php');

require_once($includes_dir.'config.inc');

require_once('forms.php');

mysql_select_db("gndec_erp",$conn);

require_once('functions.php');

CheckForLogin();

switch($_GET['mode']) {
#Run this case if the user asked for get the student details. This will GET the mode parameter from the  url.		
	case "student_details":
		if(isset($_POST['Student_First_Name']) 
		or isset($_POST['Student_Middle_Name']) 
		or isset($_POST['Student_Last_Name']) 
		or isset($_POST['Roll_No']) 
		or isset($_POST['Univ_Roll_No']) 
		or isset($_POST['Branch']) 
		or isset($_POST['Batch']) 
		or isset($_POST['Course']) 
		or isset($_POST['Gender'])) {
			
			$query_data=array($_POST['Student_First_Name'],
			$_POST['Student_Middle_Name'],
			$_POST['Student_Last_Name'],
			$_POST['Roll_No'],
			$_POST['Univ_Roll_No'],
			$_POST['Branch'],
			$_POST['Batch'],
			$_POST['Course'],
			$_POST['Gender']);
			student_details($query_data,"student_main");
		}
		
		else {
			form("student_details");
		}
		
		break;
	
	case "view_details":
		if(isset($_POST['rollno'])) {
			view_details($_POST['rollno']);
		}
		else {
			view_details($_SESSION['rollno']);
		}
		break;
	
	case "edit_user":
		$table_columns = get_tables_cols();
		$sql1 = "SELECT * FROM student_main WHERE Roll_No='".$_POST['rollno']."'";
		$sql2 = "SELECT * FROM student_detail WHERE Roll_No='".$_POST['rollno']."'";
		$sql3 = "SELECT * FROM student_previous_record WHERE Roll_No='".$_POST['rollno']."'";
		$sql4 = "SELECT * FROM student_address WHERE Roll_No='".$_POST['rollno']."'";
		$sql5 = "SELECT * FROM student_admission_detail WHERE Roll_No='".$_POST['rollno']."'";
		$sql6 = "SELECT * FROM student_images WHERE Roll_No='".$_POST['rollno']."'";
		$result1 = mysql_query($sql1);
		$result2 = mysql_query($sql2);
		$result3 = mysql_query($sql3);
		$result4 = mysql_query($sql4);
		$result5 = mysql_query($sql5);
		$result6 = mysql_query($sql6);
		$student_main = mysql_fetch_assoc($result1);
		$student_detail = mysql_fetch_assoc($result2);
		$student_previous_record = mysql_fetch_assoc($result3);
		$student_address = mysql_fetch_assoc($result4);
		$student_admission_detail = mysql_fetch_assoc($result5);
		$student_images = mysql_fetch_assoc($result6);
		edit_user_form($student_main,
					   $student_detail,
					   $student_previous_record,
					   $student_address,$student_admission_detail,$student_images);
		break;
		
	default:
		
		header("location:options.php");
		break;
	}
	
	include_once($header_footer_dir.'footer.php');

?>
