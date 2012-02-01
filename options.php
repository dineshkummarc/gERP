<?php
require_once('functions.php');
require_once('config.php');
session_start();
CheckForLogin();

include_once('header_footer/header.html');
if($_SESSION['usertype']=='Student'){
	include_once('student_content.html');
}

if($_SESSION['usertype']=='Teacher'){
	include_once('teacher_content.html');
}

if($_SESSION['usertype']=='Training And Placement'){
	include_once('tnp_content.html');
}

if($_SESSION['usertype']=='Admin'){
	include_once('admin_content.html');
}
include_once('header_footer/footer.php');
?>
