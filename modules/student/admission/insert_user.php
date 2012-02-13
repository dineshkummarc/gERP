<?php

require_once('paths.php');

include_once($header_footer_dir.'header.php');

require_once('functions.php');

session_start();

CheckForLogin();

include_once($includes_dir.'config.inc');

include_once($main_dir.'lib/input_form_class.php');

$image_folder = $media_dir."images/student_images/";

$image_file = $image_folder.$_POST['Roll_No']."_".$_POST['Batch'].basename($_FILES["Image_Path"]["name"]);

$image_file_f = $_POST['Roll_No']."_".$_POST['Batch'].basename($_FILES["Image_Path"]["name"]);;

$form = new student_form();

move_uploaded_file($_FILES["Image_Path"]["tmp_name"] ,$image_file );

mysql_select_db("gndec_erp", $conn) or die(mysql_error());

$post=$_POST;

$sql1="INSERT INTO 
	  student_main 
	  VALUES ('".$_POST['Title']."','".$post[$form->Student_First_Name]."',
			  '".$post[$form->Student_Middle_Name]."',
			  '".$post[$form->Student_Last_Name]."',
			  '".$post[$form->Roll_No]."', 
			  '".$post[$form->Univ_Roll_No]."', 
			  '".$post[$form->Gender]."',
			  '".$post[$form->Batch]."',
			  '".$post[$form->Course]."',
			  '".$post[$form->Branch]."')";
mysql_query($sql1) or die(mysql_error());

$sql2="INSERT INTO student_address 
	  VALUES ('".$post[$form->Roll_No]."',
			  '".$post[$form->Address_Line_1]."',
			  '".$post[$form->Address_Line_2]."',
			  '".$post[$form->City]."', 
			  '".$post['District']."', 
			  '".$post['State']."', 
			  '".$post[$form->Pincode]."')";
mysql_query($sql2) or die(mysql_error());

$sql3="INSERT INTO student_detail 
	  VALUES ('".$post[$form->Roll_No]."',
			  '".$post[$form->Father_First_Name]."',
			  '".$post[$form->Father_Middle_Name]."',
			  '".$post[$form->Father_Last_Name]."',
			  '".$post[$form->Father_Occupation]."',
			  '".$post[$form->Mother_First_Name]."',
			  '".$post[$form->Mother_Middle_Name]."',
			  '".$post[$form->Mother_Last_Name]."',
			  '".$post[$form->Mother_Occupation]."',
			  '".$post[$form->DOB]."',
			  '".$post[$form->Branch]."',
			  '".$post['Religion']."',
			  '".$post['Rural_Or_Urban']."',
			  '".$post['Student_Category']."',
			  '".$post['Alloted_Category']."',
			  '".$post['Student_Sub_Category']."',
			  '".$post['Alloted_Sub_Category']."',
			  '".$post[$form->Blood_Group]."',
			  '".$post[$form->Hostler]."',
			  '".$post[$form->Height]."',
			  '".$post[$form->Weight]."',
			  '".$post['Resi_Phone']."',
			  '".$post[$form->Mobile]."',
			  '".$post[$form->Parent_Moblie]."',
			  '".$post[$form->Email]."',
			  '".$post[$form->Alt_Email]."',
			  '".$post['Parent_Email']."')";
mysql_query($sql3) or die(mysql_error());

$sql4="INSERT 
	   INTO student_previous_record 
	   VALUES ('".$post[$form->Roll_No]."',
			   '".$post[$form->_10th_Passing_Year]."',
			   '".$post[$form->_10th_Max_Marks]."',
			   '".$post[$form->_10th_Obtained_Marks]."',
			   '".$post[$form->_10th_School_Name]."',
			   '".$post[$form->_10th_Board]."',
			   '".$post[$form->_12th_Passing_Year]."',
			   '".$post[$form->_12th_Max_Marks]."',
			   '".$post[$form->_12th_Obtained_Marks]."',
			   '".$post[$form->_12th_School_Name]."',
			   '".$post[$form->_12th_Board]."',
			   '".$post[$form->Diploma_Passing_Year]."',
			   '".$post[$form->Diploma_Max_Marks]."',
			   '".$post[$form->Diploma_Obtained_Marks]."',
			   '".$post[$form->Diploma_University]."',
			   '".$post[$form->Diploma_College]."')";
mysql_query($sql4) or die(mysql_error());


$sql5="INSERT INTO student_admission_detail 
	  VALUES ('".$post[$form->Roll_No]."',
				'".$post['Admission_Type']."',
				'".$post['Admission_No']."',
				'".$post['15_85_Quota']."',
			  '".$post['Date_Of_Admission']."',
			  '".$post['Date_Of_Joining']."',
			  '".$post['CET_Rank']."', 
			  '".$post['AIEEE_Rank']."')";
mysql_query($sql5) or die(mysql_error());


$sql6 = "INSERT INTO student_images (Roll_NO,Image_Path) VALUES ('".$post['Roll_No']."','".$image_file_f."')";
mysql_query($sql6) or die(mysql_error());


$password = md5($post[$form->Roll_No]);

$sql6="INSERT 
	  INTO users 
	  (Roll_No,Password,User_Type) 
	  VALUES ('".$post[$form->Roll_No]."','".$password."','Student')";
mysql_query($sql6) or die(mysql_error());

echo "<p>Student Successfully Added</p>";
mysql_close($conn); 

?>
