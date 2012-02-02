<?php
require_once('functions.php');
require_once('config.php');
session_start();
CheckForLogin();

include_once('header_footer/header.php');
if($_SESSION['usertype']=='Student'){
	include_once('user_options/student_content.php');
}

if($_SESSION['usertype']=='Teacher'){
	include_once('user_options/teacher_content.php');
}

if($_SESSION['usertype']=='Training And Placement'){
	include_once('user_options/tnp_content.php');
}

if($_SESSION['usertype']=='Admin'){
	include_once('user_options/admin_content.php');
}
include_once('header_footer/footer.php');
?>
