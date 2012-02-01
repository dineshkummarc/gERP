<?
$conn = mysql_connect("localhost","root","GndeC");
mysql_select_db("gndec_erp",$conn);
if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
			{
				$sql = "SELECT Ass_Path
										FROM student_assignments 
										WHERE Course='".$_POST['Course']."' 
										AND Subject = '".$_POST['Subject']."'
										AND Semester = '".$_POST['Semester']."' 
										AND Batch='".$_POST['Batch']."'";
			}	
		
			else
			{
				$sql = "SELECT Ass_Path
										FROM student_assignments 
										WHERE Course='".$_POST['Course']."' 
										AND Subject = '".$_POST['Subject']."'
										AND Semester = '".$_POST['Semester']."' 
										AND Batch='".$_POST['Batch']."' 
										AND Branch='".$_POST['Branch']."'";
			}
$result = mysql_query($sql);
$ass_path = mysql_fetch_array($result);
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename='.$ass_path[0]);

readfile($ass_path[0]);

?>
