<?php
require_once('config.php');
session_start();
$course = $_GET['course'];
$batch = $_GET['batch'];
$other = $_GET['other'];
$current_year = date(Y);
if($other=='create_groups') {
	$conn = mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db("gndec_erp",$conn);
	$groupname = mysql_query("SELECT DISTINCT Group_Name FROM student_groups WHERE Batch='".$batch."' AND Group_Created_By='".$_SESSION['username']."' AND
	Subgroup_Name=''") or die(mysql_error());
	echo "<select name='Group_Name_Ajax' id='Group_Name_Ajax'>";
	while($row = mysql_fetch_array($groupname)) {
		echo "<option value='".$row[0]."'>".$row[0]."</option>";
	}
	echo "</select>";
}


elseif($other=='editing') {
	$conn = mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db("gndec_erp",$conn);
	$groupname = mysql_query("SELECT DISTINCT Group_Name FROM student_groups WHERE Batch='".$batch."' AND Group_Created_By='".$_SESSION['username']."'") or die(mysql_error());
	echo "<select name='Group_Name_Ajax' id='Group_Name_Ajax'>";
	while($row = mysql_fetch_array($groupname)) {
		echo "<option value='".$row[0]."'>".$row[0]."</option>";
	}
	echo "</select>";
}

else {
	

if($course=='MBA'or $course=='M.Tech') {
	$limit = 2;
}
elseif($course=='MCA' ) {
	$limit = 3;
}
else 
{
	$limit = 4;
}

echo "<select name='Batch_Ajax' id='Batch_Ajax' onchange='semester_selector()'>";
if(date(n)<=7) {
	$i = 1;
}
else
{
				$i = 0;
			}
			for($i;$i<=$limit;$i++) {
				$current_year_f = $current_year-$i;
				echo "<option value='".$current_year_f."'>".$current_year_f."</option>";
			}
			echo "</select>";
			
	}
?>
