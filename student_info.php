<?php 
session_start();
require_once('config.php');
require_once('functions.php');
CheckForLogin();
?>
<html><head><title>Student Input</title></head>
<body>
	<form action="student_info_show.php" method="post" >
	<select name="mydropdown">
	<option value="Harbhag">Harbhag Singh Sohal</option>
	<option value="Jagdeep">Jagdeep Singh Malhi</option>
	<option value="Vikas">Vikas Mahajan</option>
	<input type="submit" value="Search Student" />
	</form>
</body>
</html>
