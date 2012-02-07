<?
include_once('../../../includes/paths.inc');
include_once($includes_folder.'includes.inc');
include_once($header_footer_folder.'header.php');
include_once('functions.php');
session_start();
CheckForLogin();
mysql_select_db("gndec_erp",$conn);
$post = $_POST;
if($_FILES["Image_Path"]["tmp_name"]!='') {
$image_folder = $media_path.'images/';
$image_file = $image_folder.$_POST['Roll_No']."_".$_POST['Batch'].
basename($_FILES["Image_Path"]["name"]);
$form = new student_form();
move_uploaded_file($_FILES["Image_Path"]["tmp_name"] ,$image_file );
}
else {
	$image_file = $_POST['Default_Image_Path'];
}


if(isset($_POST['edit_training_record'])){
	for($i=0;$i<=$_POST['num_row']-1;$i++) {
		$roll = $_POST["r".$i];
		$company_name=$_POST["cn".$i];
		$company_address=$_POST["ca".$i];
		$sd = $_POST["sd".$i];
		$ed = $_POST["ed".$i];
		$stipend=$_POST["st".$i];
		mysql_query("UPDATE student_training SET Start_Date='".$sd."',
		End_Date='".$ed."', Company_Name='".$company_name."',
		Company_Address='".$company_address."',Stipend='".$stipend."' 
		WHERE Roll_No='".$roll."' AND Course='".$_POST['Course']."' 
		AND Batch='".$_POST['Batch']."' AND Branch LIKE '%".$_POST['Branch']."%' 
		AND Training_Type='".$_POST['Training_Type']."'") or die(mysql_error());
	}
	echo "<p>Training Record Succesfully Updated</p>";
	break;
}
if(isset($_POST['edit_placement_record'])){
	for($i=0;$i<=$_POST['num_row']-1;$i++) {
		$roll = $_POST["r".$i];
		$company=$_POST["cn".$i];
		$dop = $_POST["dop".$i];
		$package=$_POST["pk".$i];
		mysql_query("UPDATE student_placement SET Company_Name='".$company."',
		Date_Of_Placement='".$dop."',Package='".$package."' WHERE 
		Roll_No='".$roll."' AND Course='".$_POST['Course']."' AND 
		Batch='".$_POST['Batch']."' AND Branch LIKE '%".$_POST['Branch']."%'") 
		or die(mysql_error());
	}
	echo "<p>Placement Record Updated Successfully</p>";
	break;
}
if(isset($_POST['Edit_Internal_External'])) {
	if($_POST['Edit_Marks_Type']=='Edit_Internal_Marks') {
		for($i=0;$i<=$_POST['num_row'];$i++){
			$roll = $_POST["r".$i];
			$iom = $_POST["iom".$i];
			$dt = $_POST["dt".$i];
		$sql = "Update student_internal_marks
		SET Internal_Obtained_Marks = '".$iom."',Detained='".$dt."'
		WHERE Roll_No = '".$roll."' 
		AND Semester = '".$_POST['Semester']."'
		AND Subject = '".$_POST['Subject']."'
		AND Teacher_username ='".$_SESSION['username']."'";
		mysql_query($sql) or die(mysql_error());
	}
		echo "<p>Internal Marks Updated Successfully</p>";
		break;
	}
	if($_POST['Edit_Marks_Type']=='Edit_External_Marks') {

		for($i=0;$i<=$_POST['num_row'];$i++){
			$roll = $_POST["r".$i];
			$eom = $_POST["eom".$i];
			$rp = $_POST["rp".$i];
		$sql = "Update student_external_marks
		SET External_Obtained_Marks = '".$eom."',Reappear='".$rp."'
		WHERE Roll_No = '".$roll."' 
		AND Semester = '".$_POST['Semester']."'
		AND Subject = '".$_POST['Subject']."'
		AND Teacher_username ='".$_SESSION['username']."'";
		mysql_query($sql) or die(mysql_error());
	}
		echo "<p>External Marks Updated Successfully</p>";
		break;
	}
}

if(isset($_POST['Edit_Semester_Final_Marks'])) {
	$sql="Update student_course_record
	SET Obtained_Marks='".$_POST['Obtained_Marks']."',
	Backlog='".$_POST['Backlog']."'
	WHERE Roll_No='".$_POST['Roll_No']."'
	AND Semester ='".$_POST['Semester']."'";
	mysql_query($sql) or die(mysql_error());
	echo "<p>Semester Final Marks Updated Successfully for 
	Roll No '".$_POST['Roll_No']."'</p>";
	break;
}

if(isset($_POST['teacher_edit_attendence']))
		{
			for($i=0;$i<=$_POST['num_row']-1;$i++){
				{
					$roll = $_POST["r".$i];
					$al = $_POST["al".$i];
					$sql = "UPDATE student_attendance 
					SET Attended_Lecture='".$al."' 
					WHERE Roll_No='".$roll."' 
					AND Subject='".$post['Subject']."' 
					AND Course='".$post['Course']."' 
					AND Semester='".$post['Semester']."'
					AND Batch='".$post['Batch']."' 
					AND Branch Like '%".$post['Branch']."%' 
					AND Start_Date = '".$post['Start_Date']."'
					AND End_Date = '".$post['End_Date']."'
					AND Teacher_Username='".$_SESSION['username']."'";
					mysql_query($sql) or die(mysql_error());
					}
		}
		echo "<p>Attendence Successfully Updated</p>";
		break;
	}
if(isset($post['update_sessional_marks']))
{
	for($i=0;$i<=$_POST['num_row']-1;$i++){
		$roll = $_POST["r".$i];
		$om = $_POST["om".$i];
		$ab = $_POST["ab".$i];
	$sql = "UPDATE student_sessional_record 
			SET Obtained_Marks='".$om."',
			Absent = '".$ab."'
			WHERE Roll_No='".$roll."' 
			AND Subject='".$post['Subject']."' 
			AND Batch='".$post['Batch']."' 
			AND Subject_Code='".$post['Subject_Code']."' 
			AND Sessional_No='".$post['Sessional_No']."' 
			AND Semester='".$post['Semester']."' 
			AND Teacher_Username='".$_SESSION['username']."'";
	mysql_query($sql) or die(mysql_error());
}
	echo "<p>Sessional Marks Successfully Updated</p>";
	break;
}
else
{
	$sql1 = "UPDATE student_main 
			SET Title = '".$_POST['Title']."',
			Student_First_Name='".$post['Student_First_Name']."', 
			Student_Middle_Name='".$post['Student_Middle_Name']."',
			Student_Last_Name='".$post['Student_Last_Name']."', 
			Roll_No='".$post['Roll_No']."',
			Univ_Roll_No='".$post['Univ_Roll_No']."',
			Gender='".$post['Gender']."',
			Batch='".$post['Batch']."', 
			Course='".$post['Course']."', 
			Branch='".$post['Branch']."' 
			WHERE Roll_No='".$post['Roll_No']."'";
			mysql_query($sql1) or die(mysql_error());
	$sql2 = "UPDATE student_detail 
			SET Roll_No='".$post['Roll_No']."', 
			Father_First_Name='".$post['Father_First_Name']."',
			Father_Middle_Name='".$post['Father_Middle_Name']."', 
			Father_Last_Name='".$post['Father_Last_Name']."',
			Father_Occupation='".$post['Father_Occupation']."',
			Mother_First_Name='".$post['Mother_First_Name']."',
			Mother_Middle_Name='".$post['Mother_Middle_Name']."',
			Mother_Last_Name='".$post['Mother_Last_Name']."',
			Mother_Occupation='".$post['Mother_Occupation']."', 
			DOB='".$post['DOB']."', 
			Department='".$post['Branch']."', 
			Religion='".$post['Religion']."',
			Rural_Or_Urban='".$post['Rural_Or_Urban']."',
			Student_Category='".$post['Student_Category']."',
			Alloted_Category='".$post['Alloted_Category']."', 
			Student_Sub_Category='".$post['Student_Sub_Category']."',
			Alloted_Sub_Category='".$post['Alloted_Sub_Category']."',
			Blood_Group='".$post['Blood_Group']."', 
			Hostler='".$post['Hostler']."',
			Height='".$post['Height']."', 
			Weight='".$post['Weight']."', 
			Resi_Phone='".$post['Resi_Phone']."',
			Mobile='".$post['Mobile']."', 
			Parent_Phone='".$post['Parent_Phone']."',
			Email='".$post['Email']."', 
			Alt_Email='".$post['Alt_Email']."',
			Parent_Email='".$post['Parent_Email']."' 
			WHERE Roll_No='".$post['Roll_No']."'";
			mysql_query($sql2) or die(mysql_error());
	$sql3 = "UPDATE student_address 
			SET Roll_No='".$post['Roll_No']."', 
			Address_Line1='".$post['Address_Line1']."', 
			Address_Line2='".$post['Address_Line2']."', 
			City='".$post['City']."', 
			District='".$post['District']."',
			State='".$post['State']."', 
			Pincode='".$post['Pincode']."' 
			WHERE Roll_No='".$post['Roll_No']."'";
			mysql_query($sql3) or die(mysql_error());
	$sql4 = "UPDATE student_previous_record 
			SET Roll_No='".$post['Roll_No']."', 
			10th_Passing_Year='".$post['10th_Passing_Year']."', 
			10th_Max_Marks='".$post['10th_Max_Marks']."',
			10th_Obtained_Marks='".$post['10th_Obtained_Marks']."',
			10th_School_Name='".$post['10th_School_Name']."', 
			10th_Board='".$post['10th_Board']."', 
			12th_Passing_Year='".$post['12th_Passing_Year']."',
			12th_Max_Marks='".$post['12th_Max_Marks']."', 
			12th_Obtained_Marks='".$post['12th_Obtained_Marks']."',
			12th_School_Name='".$post['12th_School_Name']."', 
			12th_Board='".$post['12th_Board']."',
			Diploma_Passing_Year='".$post['Diploma_Passing_Year']."', 
			Diploma_Max_Marks='".$post['Diploma_Max_Marks']."', 
			Diploma_Obtained_Marks='".$post['Diploma_Obtained_Marks']."',
			Diploma_University='".$post['Diploma_University']."', 
			Diploma_College='".$post['Diploma_College']."' 
			WHERE Roll_No='".$post['Roll_No']."'";
			mysql_query($sql4) or die(mysql_error());
			
			if($post['Admission_Type']!='PTU Councelling') {
				$post['Admission_No']='N/A';
			}
			$sql5 = "UPDATE student_admission_detail 
			SET Roll_No='".$post['Roll_No']."',
			Admission_Type='".$post['Admission_Type']."',
			Admission_No='".$post['Admission_No']."', 
			15_85_Quota = '".$post['15_85_Quota']."',
			Date_Of_Admission='".$post['Date_Of_Admission']."', 
			Date_Of_Joining='".$post['Date_Of_Joining']."', 
			CET_Rank='".$post['CET_Rank']."', 
			AIEEE_Rank='".$post['AIEEE_Rank']."'
			WHERE Roll_No='".$post['Roll_No']."'";
			mysql_query($sql5) or die(mysql_error());
			
			$sql6 = "UPDATE student_images SET
			Image_Path = '".$image_file."'
			WHERE Roll_No = '".$post['Roll_No']."'";
			mysql_query($sql6) or die(mysql_error());
	echo "<p>Record Updated For Roll No ".$post['Roll_No']."</p>";
}
?>
