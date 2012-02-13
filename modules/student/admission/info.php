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
  case "add_user":
		add_user_form();
		break;
		
	default:
		
		header("location:options.php");
		break;
	}
	
	include_once($header_footer_dir.'footer.php');

?>
