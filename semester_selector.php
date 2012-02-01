<?php
$batch = $_GET['batch'];
$course = $_GET['course'];
$current_year = date(Y);
$year = $current_year - $batch;
$upper = array(1,2,3,4,5,6);
$lower = array(7,8,9,10,11,12);
$month = date(n);
if($course=='MBA') {
	if($year==1) {
	if(in_array($month,$lower)) {
		$semester = 1;
	}
	if(in_array($month,$upper)) {
		$semester = 2;
	}
}

if($year==2) {
	if(in_array($month,$lower)) {
		$semester = 3;
	}
	if(in_array($month,$upper)) {
		$semester = 4;
	}
}
}

else
{
	

if($year==1) {
	if(in_array($month,$lower)) {
		$semester = 1;
	}
	if(in_array($month,$upper)) {
		$semester = 2;
	}
}

if($year==2) {
	if(in_array($month,$lower)) {
		$semester = 3;
	}
	if(in_array($month,$upper)) {
		$semester = 4;
	}
}

if($year==3) {
	if(in_array($month,$lower)) {
		$semester = 5;
	}
	if(in_array($month,$upper)) {
		$semester = 6;
	}
}

if($year==4) {
	if(in_array($month,$lower)) {
		$semester = 7;
	}
	if(in_array($month,$upper)) {
		$semester = 8;
	}
}
}
echo "<select name='Semester_Ajax' id='Semester_Ajax'>";
echo "<option value='".$semester."'>".$semester."</option>";
echo "</select>";

?>
