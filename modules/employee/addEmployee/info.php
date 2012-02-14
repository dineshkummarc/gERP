<?php 

session_start();

require('paths.php');

include_once($header_footer_dir.'header.php');

require_once($includes_dir.'config.inc');

require_once('forms.php');

mysql_select_db("gndec_erp",$conn);

require_once('functions.php');

CheckForLogin();

switch($_GET['mode']) {

  case "add_other":
		
		if(isset($_POST['Username']) 
		         && isset($_POST['Full_Name'])  
		         && isset($_POST['Password']) 
		         && $_POST['usertype'])
		{
			add_admin($_POST['Username'],
					  $_POST['Full_Name'],
					  $_POST['Password'],
					  $_POST['usertype'],$_POST['Department'],$_POST['Mobile'],$_POST['Email']);
			break;
		}
		else
		{
			form("add_other");
		}
		break;
		
	default:
		
		header("location:options.php");
		break;
	}
	
	include_once($header_footer_dir.'footer.php');

?>
