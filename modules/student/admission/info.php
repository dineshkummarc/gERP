<?php 

session_start();

include('paths.php');

include($header_footer_dir.'header.php');

mysql_select_db("gndec_erp",$conn);

require('functions.php');

CheckForLogin();

switch($_GET['mode']) {
  case "add_user":
		add_user_form();
		break;
		
	default:
		
		header("location:options.php");
		break;
	}
	
	include($header_footer_dir.'footer.php');

?>
