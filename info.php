<?php 
session_start();
include_once('header_footer/header.html');
require_once('config.php');
require_once('forms.php');
mysql_select_db("gndec_erp",$conn);
require_once('functions.php');
require_once('scripts.php');
CheckForLogin();
switch($_GET['mode']) {
	
	case "view_details":
		if(isset($_POST['rollno']))
		{
			view_details($_POST['rollno']);
		}
		else
		{
			
			view_details($_SESSION['rollno']);
		}
		break;
	
	case "attendence_student":
		if(isset($_POST['Start_Date']) 
			&& isset($_POST['End_Date']) 
			&& isset($_POST['subject'])) 
		{
			show_attendence($_POST['Start_Date'],
							$_POST['End_Date'],
							$_POST['subject'],$_POST['Semester']);
		}
		else
		{
			form("attendence_student");
		}
		break;
	
	case "sessional_marks":
		
		if(isset($_POST['Insert']))
		{
			if($_POST['Marks_Type']=='Internal_Marks' 
			  or $_POST['Marks_Type']=='External_Marks')
			{
				student_internal_external($_POST['Marks_Type'],$_SESSION['rollno'],
																	$_POST['Subject'],$_POST['Semester']);
				break;
			}
			else
			{
				
				$sql_get_marks ="SELECT Subject, Max_Marks, Obtained_Marks, Sessional_No, Absent 
								FROM student_sessional_record 
								WHERE Roll_No='".$_SESSION['rollno']."' 
								AND Subject='".$_POST['Subject']."' 
								AND Sessional_No='".$_POST['Sessional_No']."' 
								AND Semester='".$_POST['Semester']."'";
				$sql_get_ab ="SELECT Absent 
							FROM student_sessional_record 
							WHERE Roll_No='".$_SESSION['rollno']."' 
							AND Subject='".$_POST['Subject']."' 
							AND Sessional_No='".$_POST['Sessional_No']."' 
							AND Semester='".$_POST['Semester']."'";
				
				$result_get_marks = mysql_query($sql_get_marks);
				$row_num = mysql_num_rows($result_get_marks);
				if($row_num!=0)
				{
					echo "<table id='test' align='center' width='70%'>";
					$ab = mysql_fetch_assoc(mysql_query($sql_get_ab));
					if($ab['Absent']=='Yes')
					{
						echo "<tr><th>Subject</th><th>Sessional No</th><th>Result</th></tr>";
					}
					else
					{
						echo "<tr><th>Subject</th><th>Sessional No</th><th>Max Marks</th><th>Obtained Marks</th></tr>";
					}
					echo "<tr>";
					while($row=mysql_fetch_assoc($result_get_marks)) {
						if($row['Absent']=='Yes')
						{
							echo "<td>".$row['Subject']."</td>";
							echo "<td>".$row['Sessional_No']."</td>";
							echo "<td>Absent</td>";
						}
						else
						{
							echo "<td>".$row['Subject']."</td>";
							echo "<td>".$row['Sessional_No']."</td>";
							echo "<td>".$row['Max_Marks']."</td>";
							echo "<td>".$row['Obtained_Marks']."</td>";
						}
					}
					echo "</tr></table>";
					break;
				}
				else
				{
					echo "<p>No Record Found</p>";
					break;
				}
			}
		}
			
		if(isset($_POST['Marks_Type']))
		{
			student_course_record($_POST['Marks_Type'],
								  $_POST['Course'],
								  $_POST['Batch'],
								  $_POST['Branch'],
								  $_POST['Semester']);
		}
		
		else 
		{
			form("student_course_record");
		}
		
		break;
		
	case "teacher_assignment":
		
		if(isset($_POST['Insert']))
		{
			$assdir = "assignments/";
			$assfile = $assdir.$_POST['Assignment_No']."_".$_POST['Course']."_".$_POST['Batch']."_".$_POST['Branch']."_".$_POST['Semester']."_".$_POST['Subject']."_".$_SESSION['username']."_".basename($_FILES['assignment']['name']);
			move_uploaded_file($_FILES['assignment']['tmp_name'], $assfile);
			if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
			{
				if($_POST['Branch']=='')
				{
					$_POST['Branch']="N/A";
				}
				$sql_insert_assignment = "INSERT INTO student_assignments  
										VALUES('".$_POST['Assignment_No']."','".$_POST['Batch']."','".$_POST['Course']."','".$_POST['Branch']."','".$_POST['Semester']."','".$_POST['Subject']."','".$_POST['Assignment_Details']."','".$_POST['Date_Of_Submission']."','".$_POST['Teacher_Name']."','".$_SESSION['username']."','".$assfile."')";
				mysql_query($sql_insert_assignment)or die(mysql_error());
				echo "<p>Record Updated<p>";
				send_email($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Assignment_No'],$_POST['Teacher_Name'],$_POST['Subject'],$_POST['Date_Of_Submission']);
				if($_POST['sendsms']=='Yes')
				{
					assignment_alert($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Assignment_No'],$_POST['Teacher_Name'],$_POST['Subject'],$_POST['Date_Of_Submission']);
				}
				break;
			}	
		
			else
			{
				
				$sql_insert_assignment = "INSERT INTO 
										student_assignments  
										VALUES('".$_POST['Assignment_No']."','".$_POST['Batch']."','".$_POST['Course']."','".$_POST['Branch']."','".$_POST['Semester']."',
										'".$_POST['Subject']."','".$_POST['Assignment_Details']."','".$_POST['Date_Of_Submission']."','".$_POST['Teacher_Name']."','".$_SESSION['username']."','".$assfile."')";
				mysql_query($sql_insert_assignment);
				echo "<p>Record Updated</p>";
				if($_POST['sendsms']=='Yes')
				{
					assignment_alert($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Assignment_No'],$_POST['Teacher_Name'],$_POST['Subject'],$_POST['Date_Of_Submission']);
				}
				send_email_assignment($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Assignment_No'],$_POST['Teacher_Name'],$_POST['Subject'],$_POST['Date_Of_Submission'],$assfile);
				break;
			}
		}
		
		if(isset($_POST['Subject']))
		{
			$sql_assi = mysql_query("SELECT * FROM student_assignments WHERE Batch='".$_POST['Batch']."' AND Course='".$_POST['Course']."' AND Branch='".$_POST['Branch']."' AND Semester='".$_POST['Semester']."' AND Subject='".$_POST['Subject']."'") or die(mysql_error());
			$num_assi = mysql_num_rows($sql_assi);
			$limit = $num_assi+1;
			if($num_assi>=3) {
				echo "<p style='color:red'>Error: Operation Not Permitteed Since 3 Assignments of '".$_POST['Subject']."' For this class have already been uploaded. Please Select Other Class or Contact the Administrator</p>";
				break;
			}
			else {
			echo "<table id='student_attendence' align='center'>";
			echo "<form id='assignment_details' action='info.php?mode=teacher_assignment' method='post' enctype='multipart/form-data'>";
			echo "<tr><td>Notify Students Via SMS?</td>";
			echo "<tr><td>Yes<input type='radio' name='sendsms' value='Yes'/>";
			echo "No<input type='radio' name='sendsms' value='No'/ checked='checked'></td></tr>";
			echo "<tr><td>Notify Students Via Email?</td>";
			echo "<tr><td>Yes<input type='radio' name='sendemail' value='Yes'/>";
			echo "No<input type='radio' name='sendemai' value='No'/ checked='checked'></td></tr>";
			echo "<tr><td>Assignment No.</td><td><select name='Assignment_No'>";
			for($i=$limit;$i<=3;$i++)
			{
				echo "<option value='".$i."'>".$i."</option>";
			}
		
			echo "</select>";
			echo "<tr><td>Assignment Details</td><td><textarea rows='20' cols='80' class='required' name='Assignment_Details'>Here you specify any additional remarks for the assignment or you can Specify the accepted format for assignment like Hand-Written or Printed.</textarea></td></tr>";
			echo "<tr><td>Upload Assignment</td><td><input type='file' name='assignment' id='assignment' /></td></tr>";
			echo "<tr><td>Date Of Submission</td><td><input type='text' class='required' id='assi_date' name='Date_Of_Submission' /></td></tr>";
			echo "<tr><td>Teacher Name</td><td><input type='text' name='Teacher_Name' value='".$_SESSION['fullname']."' /></td></tr>";
			echo "<input type='hidden' name='Course' value='".$_POST['Course']."' />";
			echo "<input type='hidden' name='Subject' value='".$_POST['Subject']."' />";
			echo "<input type='hidden' name='Insert' value='Insert' />";
			echo "<input type='hidden' name='Batch' value='".$_POST['Batch']."' />";
			echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
			echo "<input type='hidden' name='Branch' value='".$_POST['Branch']."' />";
			echo "<tr><td><input type='submit' value='submit' onclick='patience()'></tr></td>";
			echo "</form></table>";
			break;
		}
	}
			
		if(isset($_POST['Course']) 
				&& isset($_POST['Batch']) 
				&& isset($_POST['Branch']) 
				&& isset($_POST['Semester']))
		{
			if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
			{
				$sql_fetch_subjects = "SELECT DISTINCT Subject_Name,Subject_Id 
									FROM student_subjects 
									WHERE Subject_Course='".$_POST['Course']."' 
									AND Subject_Semester = '".$_POST['Semester']."'";
				
			}	
		
			else
			{
				$sql_fetch_subjects ="SELECT DISTINCT Subject_Name 
									FROM student_subjects 
									WHERE Subject_Course='".$_POST['Course']."' 
									AND Subject_Branch='".$_POST['Branch']."' 
									AND Subject_Semester = '".$_POST['Semester']."'";
				
			}
			$result_fetch_subject = mysql_query($sql_fetch_subjects);
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='info.php?mode=teacher_assignment' method='post'>";
			echo "<tr><td>Subject</td><td><select name='Subject'>";
			while($rows = mysql_fetch_array($result_fetch_subject))
			{
				echo "<option value='".$rows[0]."'>".$rows[0]."</option>";
				$subarray[] = $rows[0];
			}
			echo "</select></tr></td>";
			echo "<input type='hidden' name='Course' value='".$_POST['Course']."' />";
			echo "<input type='hidden' name='Batch' value='".$_POST['Batch']."' />";
			echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
			echo "<input type='hidden' name='Branch' value='".$_POST['Branch']."' />";
			echo "<input type='hidden' name='rollarry' value='".$rollarry."' />";
			echo "<tr><td><input type='submit' value='submit'></tr></td>";
			echo "</form></table>";
		}
		
		else
		{
			form("teacher_assignment");
		}
		break;
	
		
	case "student_details":
		if(isset($_POST['Student_First_Name']) 
				or isset($_POST['Student_Middle_Name']) 
				or isset($_POST['Student_Last_Name']) 
				or isset($_POST['Roll_No']) 
				or isset($_POST['Univ_Roll_No']) 
				or isset($_POST['Branch']) 
				or isset($_POST['Batch']) 
				or isset($_POST['Course']) 
				or isset($_POST['Gender'])) {
			$query_data=array($_POST['Student_First_Name'],
							  $_POST['Student_Middle_Name'],
							  $_POST['Student_Last_Name'],
							  $_POST['Roll_No'],
							  $_POST['Univ_Roll_No'],
							  $_POST['Branch'],
							  $_POST['Batch'],
							  $_POST['Course'],
							  $_POST['Gender']);
			student_details($query_data,"student_main");
		}
		else 
		{
			
			form("student_details");
		}
		break;
		
	case "upload_marks":
		
		if(isset($_POST['insert_internal_external']))
		{
			if($_POST['Marks_Type']=='Internal_Marks')
			{
				$sql_internal_check = mysql_query("SELECT * FROM student_internal_marks WHERE Semester='".$_POST['Semester']."' AND Subject='".$_POST['Subject']."'") or die(mysql_error());
				$num_internal_record = mysql_num_rows($sql_internal_check);
				if($num_internal_record>0) {
					echo "<p style='color:red'>Error: Same Record Already Exists In the Database, So Nothing Uploaded To Database. Try 'Edit Record' Instead</p>";
					break;
				}
				else {
				for($i=0;$i<=$_POST['num_row']-1;$i++)
				{
					require('config.php');
					$conn = mysql_connect($db_hostname,$db_username,$db_password);
					mysql_select_db("gndec_erp",$conn);
					$dtarray[] = $_POST["dt".$i];
					$rollarry[] = $_POST["r".$i];
					$moarray[] = $_POST["mo".$i];
					if($dtarray[$i]=='Yes')
					{
						mysql_query("INSERT INTO student_internal_marks (Roll_No, Semester,Subject,Internal_Max_Marks,Detained,Teacher_Username) VALUES ('".$rollarry[$i]."','".$_POST['Semester']."','".$_POST['Subject']."','".$_POST['Max_Marks']."','".$dtarray[$i]."','".$_SESSION['username']."')");
						
						
						if($_POST['sendsms']=='Yes')
						{
							internal_external_marks_alert($_POST['Marks_Type'],
														  $rollarry[$i],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[$i],'Yes');
						}
						/*send_email_internal_external($_POST['Marks_Type'],
														  $rollarry[$i],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[$i],'Yes');*/
					}
					else
					{
						mysql_query("INSERT INTO student_internal_marks 
									(Roll_No, 
									Semester,
									Subject,
									Internal_Max_Marks,
									Internal_Obtained_Marks,
									Teacher_Username) 
									VALUES ('".$rollarry[$i]."',
									'".$_POST['Semester']."',
									'".$_POST['Subject']."',
									'".$_POST['Max_Marks']."',
									'".$moarray[$i]."',
									'".$_SESSION['username']."')");
						if($_POST['sendsms']=='Yes')
						{
							internal_external_marks_alert($_POST['Marks_Type'],
														  $rollarry[$i],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[$i],'No');
						}
						/*send_email_internal_external($_POST['Marks_Type'],
														  $rollarry[$i],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[$i],'No');*/
					}
				}
				/*send_email_internal_external($_POST['Marks_Type'],
														  $rollarry[0],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[0],'No');*/
				echo "<p>Record Updated </p>";
				break;
			}
			}
			
			
			if($_POST['Marks_Type']=='External_Marks')
			{
				$sql_external_check = mysql_query("SELECT * FROM student_external_marks WHERE Semester='".$_POST['Semester']."' AND Subject='".$_POST['Subject']."'") or die(mysql_error());
				$num_external_record = mysql_num_rows($sql_external_check);
				if($num_external_record>0) {
					echo "<p style='color:red'>Error: Same Record Already Exists In the Database, So Nothing Uploaded To Database. Try 'Edit Record' Instead</p>";
					break;
				}
				else {
				for($i=0;$i<=$_POST['num_row']-1;$i++)
				{
					require('config.php');
					$conn = mysql_connect($db_hostname,$db_username,$db_password);
					mysql_select_db("gndec_erp",$conn);
					$rparray[] = $_POST["rp".$i];
					$rollarry[] = $_POST["r".$i];
					$moarray[] = $_POST["mo".$i];
					if($moarray[$i]<24 or $rparray[$i]=='Yes')
					{
						mysql_query("INSERT INTO student_external_marks 
									(Roll_No, 
									Semester,
									Subject,
									External_Max_Marks,
									External_Obtained_Marks,
									Reappear,
									Teacher_Username) 
									VALUES ('".$rollarry[$i]."',
									'".$_POST['Semester']."',
									'".$_POST['Subject']."',
									'".$_POST['Max_Marks']."',
									'".$moarray[$i]."',
									'".$rparray[$i]."',
									'".$_SESSION['username']."')");
						if($_POST['sendsms']=='Yes')
						{
							internal_external_marks_alert($_POST['Marks_Type'],
														  $rollarry[$i],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[$i],'Yes');
						}
					}
					else
					{
						mysql_query("INSERT INTO student_external_marks 
									(Roll_No, 
									Semester,
									Subject,
									External_Max_Marks,
									External_Obtained_Marks,
									Teacher_Username) 
									VALUES ('".$rollarry[$i]."',
									'".$_POST['Semester']."',
									'".$_POST['Subject']."',
									'".$_POST['Max_Marks']."',
									'".$moarray[$i]."',
									'".$_SESSION['username']."')");
						if($_POST['sendsms']=='Yes')
						{
							internal_external_marks_alert($_POST['Marks_Type'],
														  $rollarry[$i],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[$i],'No');
						}
					}
				}
				/*send_email_internal_external($_POST['Marks_Type'],
														  $rollarry[0],
														  $_POST['Subject'],
														  $_POST['Max_Marks'],
														  $moarray[0],'Yes');*/
				echo "<p>Record Updated </p>";
				break;
			}
			}
		}
		
		if(isset($_POST['insert_sessional_final']))
		{
			for($i=0;$i<=$_POST['num_row']-1;$i++)
			{
				$rparray[] = $_POST["rp".$i];
				$rollarry[] = $_POST["r".$i];
				$moarray[] = $_POST["mo".$i];
				if($moarray[$i]=='')
				{
					mysql_query("INSERT INTO student_course_record 
								(Roll_No, 
								Semester,
								Backlog,
								Teacher_Username) 
								VALUES ('".$rollarry[$i]."',
								'".$_POST['Semester']."',
								'".$rparray[$i]."',
								'".$_SESSION['username']."')");
				}
				else
				{
					mysql_query("INSERT INTO student_course_record 
								(Roll_No, 
								Semester,
								Max_Marks,
								Obtained_Marks,
								Teacher_Username) 
								VALUES ('".$rollarry[$i]."',
								'".$_POST['Semester']."',
								'".$_POST['Max_Marks']."',
								'".$moarray[$i]."',
								'".$_SESSION['username']."')");
				}
			}
			
			echo "<p>Record Updated<p>";
			break;
		}
		if(isset($_POST['insert_sessional_marks']))
		{
			$check_sessional_record = mysql_query("SELECT * FROM student_sessional_record WHERE Batch='".$_POST['Batch']."' AND Subject='".$_POST['Subject']."' AND Subject_Code='".$_POST['Subject_Code']."' AND Sessional_No='".$_POST['Sessional_No']."' AND Semester='".$_POST['Semester']."'") or die(mysql_error());
			$num_sessional_record = mysql_num_rows($check_sessional_record);
			if($num_sessional_record>0) {
				echo "<p style='color:red'>Error: Same Record Already Exists In the Database, So Nothing Uploaded To Database. Try 'Edit Record' Instead</p>";
				break;
			}
			else {
			for($i=0;$i<=$_POST['num_row']-1;$i++)
			{
				$rollarry[] = $_POST["r".$i];
				$moarray[] = $_POST["mo".$i];
				$abarray[] = $_POST["ab".$i];
				if($abarray[$i]=='Yes')
				{
					mysql_query("INSERT INTO student_sessional_record 
								(Roll_No,
								Batch,
								Subject,
								Subject_Code,
								Max_Marks,
								Sessional_No,
								Semester,
								Absent,
								Teacher_Username) 
								VALUES ('".$rollarry[$i]."',
								'".$_POST['Batch']."',
								'".$_POST['Subject']."',
								'".$_POST['Subject_Code']."',
								'".$_POST['maxmarks']."',
								'".$_POST['Sessional_No']."',
								'".$_POST['Semester']."',
								'".$abarray[$i]."',
								'".$_SESSION['username']."')") or die(mysql_error());
								//send_email_sessional($rollarry[$i],$_POST['Subject'],$_POST['Sessional_No'],$_POST['maxmarks'],$moarray[$i],'Yes');
				}
				else
				{
					mysql_query("INSERT INTO student_sessional_record 
								(Roll_No,
								Batch,
								Subject,
								Subject_Code,
								Obtained_Marks,
								Max_Marks,Sessional_No,
								Semester,
								Teacher_Username) 
								VALUES ('".$rollarry[$i]."',
								'".$_POST['Batch']."',
								'".$_POST['Subject']."',
								'".$_POST['Subject_Code']."',
								'".$moarray[$i]."',
								'".$_POST['maxmarks']."',
								'".$_POST['Sessional_No']."',
								'".$_POST['Semester']."',
								'".$_SESSION['username']."')");
								//send_email_sessional($rollarry[$i],$_POST['Subject'],$_POST['Sessional_No'],$_POST['maxmarks'],$moarray[$i],'No');
				}
				
			}
			echo "<p>Record Updated<p>";
			notify_admin('Sessional Marks',$_POST['Sessional_No'],$_POST['Subject'],$_POST['Batch'],$_POST['Branch'],$_POST['Course']);
			break;
		}
		}
		if(isset($_POST['Subject']))
		{
			
			if($_POST['Marks_Type']=='Sessional_Marks')
			{
			
				if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
				{
					$sql_get_code = "SELECT Subject_Code 
									FROM student_subjects 
									WHERE Subject_Name='".$_POST['Subject']."' 
									AND Subject_Course='".$_POST['Course']."' 
									AND Subject_Semester='".$_POST['Semester']."'";
					$sql_insert_marks = "SELECT DISTINCT Roll_No 
										FROM student_main 
										WHERE Course='".$_POST['Course']."' 
										AND Batch='".$_POST['Batch']."' 
										ORDER BY student_main.Roll_No ASC";
				}
				else
				{
					require('config.php');
					$conn = mysql_connect($db_hostname,$db_username,$db_password);
					mysql_select_db("gndec_erp",$conn);
					$sql_get_code = "SELECT Subject_Code 
									FROM student_subjects 
									WHERE Subject_Name='".$_POST['Subject']."' 
									AND Subject_Course='".$_POST['Course']."' 
									AND Subject_Semester='".$_POST['Semester']."' 
									AND Subject_Branch='".$_POST['Branch']."'";
					$sql_insert_marks = "SELECT DISTINCT Roll_No 
										FROM student_main 
										WHERE Course='".$_POST['Course']."' 
										AND Batch='".$_POST['Batch']."' 
										AND Branch='".$_POST['Branch']."' 
										ORDER BY student_main.Roll_No ASC";
				}
				$result_get_code = mysql_query($sql_get_code);
				$subject_code = mysql_fetch_array($result_get_code);
				$result_insert_marks = mysql_query($sql_insert_marks);
				$row_num = mysql_num_rows($result_insert_marks);
				$i=0;
				$j=1;
				echo "<table id='student_attendence' align='center'>";
				echo "<form id='upload_marks' action='info.php?mode=upload_marks' method='post'>";
				echo "<tr><td>Notify Students Via Email?</td>";
				echo "<tr><td>Yes<input type='radio' name='sendemail' value='Yes'/>";
				echo "No<input type='radio' name='sendemail' value='No'/ checked='checked'></td></tr>";
				while($row = mysql_fetch_array($result_insert_marks))
				{
					echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row[0]."' /></td>";
					echo "<input type='hidden' name='Subject' value='".$_POST['Subject']."' />";
					echo "<input type='hidden' name='Subject_Code' value='".$subject_code[0]."' />";
					echo "<input type='hidden' name='Sessional_No' value='".$_POST['Sessional_No']."' /></td>";
					echo "<td>M.M</td><td><input type='text' name='maxmarks' value='24'/></td>";
					echo "<td>M.O</td><td><input type='text' name='mo".$i."'/></td>";
					echo "<td>Absent</td><td><input type='checkbox' name='ab".$i."' value='Yes'/></td></tr>";
					$i +=1;
					$j +=1;
				}
				echo "<input type='hidden' name='insert_sessional_marks' value='insert' />";
				echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
				echo "<input type='hidden' name='Subject' value='".$_POST['Subject']."' />";
				echo "<input type='hidden' name='num_row' value='".$row_num."'/>";
				echo "<input type='hidden' name='Batch' value='".$_POST['Batch']."'/>";
				echo "<input type='hidden' name='Course' value='".$_POST['Course']."'/>";
				echo "<input type='hidden' name='Branch' value='".$_POST['Branch']."'/>";
				echo "<tr><td><input type='submit' value='Upload'/>";
				echo "</form></table>";
				break;
			}
			
			if($_POST['Marks_Type']=='Internal_Marks' or $_POST['Marks_Type']=='External_Marks')
			{
				if($_POST['Course'] == 'MBA' or $_POST['Course'] =='MCA')
				{
					$sql_final_marks = "SELECT DISTINCT Roll_No 
										FROM student_main 
										WHERE Course='".$_POST['Course']."' 
										AND Batch='".$_POST['Batch']."' 
										ORDER BY student_main.Roll_No ASC";
				}
				else
				{
					$sql_final_marks = "SELECT DISTINCT Roll_No 
										FROM student_main 
										WHERE Branch='".$_POST['Branch']."' 
										AND Course='".$_POST['Course']."' 
										AND Batch='".$_POST['Batch']."' 
										ORDER BY student_main.Roll_No ASC";
				}
				$result_final_marks = mysql_query($sql_final_marks);
				$row_num = mysql_num_rows($result_final_marks);
				echo "<table id='student_attendence' align='center'>";
				echo "<form action='info.php?mode=upload_marks' method='post'>";
				echo "<tr><td>Notify Students Via SMS?</td>";
				echo "<tr><td>Yes<input type='radio' name='sendsms' value='Yes' />";
				echo "No<input type='radio' name='sendsms' value='No'/ checked='checked'></td></tr>";
				echo "<tr><td>Notify Students Via Email?</td>";
				echo "<tr><td>Yes<input type='radio' name='sendemail' value='Yes'/>";
				echo "No<input type='radio' name='sendemail' value='No'/ checked='checked'></td></tr>";
				$i = 0;
				$j = 1;
				while($row = mysql_fetch_array($result_final_marks))
				{
					if($_POST['Marks_Type']=='Internal_Marks')
					{
						$max='40';
					}
					if($_POST['Marks_Type']=='External_Marks')
					{
						$max='60';
					}
					echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row[0]."' /></td>";
					echo "<input  type='hidden' name='Semester' value='".$_POST['Semester']."' />";
					echo "<input  type='hidden' name='Subject' value='".$_POST['Subject']."' />";
					echo "<td>M.M</td><td><input type='text' name='Max_Marks' value='".$max."' /></td>";
					echo "<td>M.O</td><td><input type='text' name='mo".$i."'/></td>";
					if($_POST['Marks_Type']=='Internal_Marks')
					{
						echo "<td>Detained</td><td><input type='checkbox' name='dt".$i."' value='Yes'/></td></tr>";
					}
					if($_POST['Marks_Type']=='External_Marks')
					{
						echo "<td>Re-appear</td><td><input type='checkbox' name='rp".$i."' value='Yes'/></td></tr>";
					}
				
					$i +=1;
					$j +=1;
				}
				echo "</tr><td><input type='hidden' name='num_row' value='".$row_num."'/></td></td>";
				echo "</tr><td><input type='hidden' name='Marks_Type' value='".$_POST['Marks_Type']."'/></td></td>";
				echo "</tr><td><input type='hidden' name='insert_internal_external' value='insert'/></td></td>";
				echo "</tr><td><input type='submit' value='Upload'/>";
				echo "</form></table>";
				break;
			}
		}
	
		
		
		if(isset($_POST['Marks_Type']) && isset($_POST['Semester']) && isset($_POST['Course']) && isset($_POST['Batch']))
		{
			upload_marks($_POST['Marks_Type'],$_POST['Branch'],$_POST['Semester'],$_POST['Course'],$_POST['Batch']);
		}
		
		else 
		{
			form("upload_marks");
		}
		
		
		break;
		
	case "attendence_teacher":
		if(isset($_POST['insert_attendence']))
		{
			
			$check_record = mysql_query("SELECT * FROM student_attendance WHERE End_Date>'".$_POST['Start_Date']."' AND Course='".$_POST['Course']."' AND Semester='".$_POST['Semester']."' AND Batch='".$_POST['Batch']."' AND Branch LIKE '%".$_POST['Branch']."%' AND Subject='".$_POST['Subject']."'") or die(mysql_error());
			$num_record = mysql_num_rows($check_record);
			if($num_record>0) {
				echo "<p style='color:red'>Error: A Record For This Time Period Already Exists In the Database, So Nothing Uploaded To Database. Try 'Edit Record' Instead</p>";
				break;
			}
			else {
			
			for($i=0;$i<=$_POST['num_row']-1;$i++)
			{
				if($_POST['Branch']=='')
				{
					$_POST['Branch']="N/A";
				}
				$rollarry[] = $_POST["r".$i];
				$attlec[] = $_POST["l".$i];
				require('config.php');
				$conn = mysql_connect($db_hostname,$db_username,$db_password);
				mysql_select_db("gndec_erp",$conn);
				mysql_query("INSERT INTO student_attendance 
							VALUES('".$rollarry[$i]."',
							'".$_POST['Start_Date']."',
							'".$_POST['End_Date']."',
							'".$_POST['Course']."',
							'".$_POST['Semester']."',
							'".$_POST['Batch']."',
							'".$_POST['Branch']."',
							'".$_POST['Subject']."',
							'".$_POST['totallecture']."',
							'".$attlec[$i]."',
							'".$_SESSION['username']."',NOW())");
				/*if($_POST['sendsms']=='No') {
				if($_POST['Alert_Low_Attendence']=='Yes') {
					$check = ($attlec[$i]/$_POST['totallecture'])*100;
					if($check < 75) {
						attendence_alert($rollarry[$i],
									 $_POST['Start_Date'],
									 $_POST['End_Date'],
									 $_POST['totallecture'],
									 $attlec[$i],
									 $_POST['Subject'],$_POST['Alert_Low_Attendence'],$_POST['Subject_Code']);
					}
				}
			}
				/*if($_POST['sendsms']=='Yes')
				{
					attendence_alert($rollarry[$i],
									 $_POST['Start_Date'],
									 $_POST['End_Date'],
									 $_POST['totallecture'],
									 $attlec[$i],
									 $_POST['Subject'],$_POST['Alert_Low_Attendence'],$_POST['Subject_Code']);
				}*/
			}
			echo "<p>Record Updated<p>";
			//notify_admin('Attendence',$_POST['Start_Date'],$_POST['End_Date'],$_POST['Batch'],$_POST['Branch'],$_POST['Course']);
			break;
		}
		}
		if(isset($_POST['Subject']))
		{
			$result_subject_code = mysql_query("SELECT Subject_Code FROM student_subjects WHERE Subject_Name='".$_POST['Subject']."' AND Subject_Branch LIKE '%".$_POST['Branch']."%'");
			while($row = mysql_fetch_array($result_subject_code)){
				$subject_code = $row[0];
			}
			/*attendence_teacher($_POST['Course'],
							   $_POST['Batch'],
							   $_POST['Branch'],
							   $_POST['Start_Date'],
							   $_POST['End_Date'],
							   $_POST['Total_Lecture'],
							   $_POST['Subject'],$_POST['Alert_Low_Attendence'],$subject_code);*/
			
			/*attendence_teacher($_POST['group'],
							   $_POST['subject']
							   $_POST['Start_Date'],
							   $_POST['End_Date'],
							   $_POST['Total_Lecture']);*/
			break;
		}
		if(isset($_POST['group']) 
				 or isset($_POST['subject']) 
				 or isset($_POST['Start_Date']) 
				 or isset($_POST['End_Date']) 
				 or isset($_POST['Total_Lecture'])) 
		{
			$start_date = strtotime($_POST['Start_Date']);
			$end_date = strtotime($_POST['End_Date']);
			if($end_date <= $start_date) {
				echo "<p style='color:red;font-weight:bold'>Error: End Date must be higher than the Start date</p>";
				form("attendence_teacher_period");
				exit();
			}
			/*if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
			{
				$sql_fetch_subjects = "SELECT DISTINCT Subject_Name
										FROM student_subjects 
										WHERE Subject_Course='".$_POST['Course']."' 
										AND Subject_Semester = '".$_POST['Semester']."'";
			}	
		
			else
			{
				$sql_fetch_subjects = "SELECT DISTINCT Subject_Name
										FROM student_subjects 
										WHERE Subject_Course='".$_POST['Course']."' 
										AND Subject_Branch='".$_POST['Branch']."' 
										AND Subject_Semester = '".$_POST['Semester']."'";
			}
			$result_fetch_subjects = mysql_query($sql_fetch_subjects);
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='info.php?mode=attendence_teacher' method='post'>";
			echo "<tr><td>Subject</td><td><select name='Subject'>";
			while($rows = mysql_fetch_array($result_fetch_subjects))
			{
				echo "<option value='".$rows[0]."'>".$rows[0]."</option>";
			}
			echo "</select></tr></td>";
			echo "<tr><td><input type='submit' value='submit'></tr></td>";
			echo "<input type='hidden' name='Course' value='".$_POST['Course']."' />";
			echo "<input type='hidden' name='Batch' value='".$_POST['Batch']."' />";
			echo "<input type='hidden' name='Branch' value='".$_POST['Branch']."' />";
			echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
			echo "<input type='hidden' name='Start_Date' value='".$_POST['Start_Date']."' />";
			echo "<input type='hidden' name='End_Date' value='".$_POST['End_Date']."' />";
			echo "<input type='hidden' name='Total_Lecture' value='".$_POST['Total_Lecture']."' />";
			echo "<input type='hidden' name='Alert_Low_Attendence' value='".$_POST['Alert_Low_Attendence']."' />";
			echo "</form></table>";*/
			
			attendence_teacher($_POST['group'],
							   $_POST['Start_Date'],
							   $_POST['End_Date'],
							   $_POST['Total_Lecture'],$_POST['subject']);
			break;
			
		}
		else
		{
			form("attendence_teacher_period");
		}
			
		break;
	
	case "student_assignment":
		if(isset($_POST['Semester']))
		{
			$sql_details = "SELECT Batch,Course,Branch 
							FROM student_main
							WHERE Roll_No='".$_SESSION['rollno']."'";
			$result_details = mysql_query($sql_details) or die(mysql_error());
			$row_details = mysql_fetch_assoc($result_details);
			if($row_details['Course']=='MBA' or $row_details['Course']=='MCA')
			{
				$sql_fetch_assignment = "SELECT Assignment_No,Subject, Assignment_Details, Date_Of_Submission, Teacher_Name,Ass_Path
										FROM student_assignments 
										WHERE Course='".$row_details['Course']."' 
										AND Semester = '".$_POST['Semester']."' 
										AND Batch='".$row_details['Batch']."'";
			}	
		
			else
			{
				$sql_fetch_assignment = "SELECT Assignment_No,Subject, Assignment_Details, Date_Of_Submission, Teacher_Name,Ass_Path 
										FROM student_assignments 
										WHERE Course='".$row_details['Course']."' 
										AND Semester = '".$_POST['Semester']."' 
										AND Batch='".$row_details['Batch']."' 
										AND Branch='".$row_details['Branch']."'";
			}
			
			$result_fetch_assignment = mysql_query($sql_fetch_assignment) or die(mysql_error());
			$row_num = mysql_num_rows($result_fetch_assignment);
			if($row_num!=0)
			{
				echo "<table id='test' align='center' width='70%'>";
				echo "<tr><th>Assignment No</th><th>Subject</th><th>Assignment Details</th><th>Date Of Submission</th><th>Teacher Name</th><th>File</th></tr>";
				echo "<tr>";
				while($row=mysql_fetch_assoc($result_fetch_assignment)) {
	
					echo "<td>".$row['Assignment_No']."</td>";
					echo "<td>".$row['Subject']."</td>";
					echo "<td>".$row['Assignment_Details']."</td>";
					echo "<td>".$row['Date_Of_Submission']."</td>";
					echo "<td>".$row['Teacher_Name']."</td>";
					echo "<td><form action='download.php' method='post'>
					<input type='hidden' name='Assignment_No' value='".$row['Assignment_No']."' />
					<input type='hidden' name='Course' value='".$row_details['Course']."' />
					<input type='hidden' name='Batch' value='".$row_details['Batch']."' />
					<input type='hidden' name='Branch' value='".$row_details['Branch']."' />
					<input type='hidden' name='Semester' value='".$_POST['Semester']."' />
					<input type='hidden' name='Subject' value='".$row['Subject']."' />
					<input type='submit' value='download' /></td></tr>";
					
				}
				echo "</tr></table>";
				break;
			}
			else
			{
				echo "<p>No Record Found</p>";
				break;
			}
		}
		else
		{
			form("student_assignment");
		}
		break;
		
	case "get_report":
		$table_columns= get_tables_cols();
		if(isset($_POST['get_details']))
		{
			echo "<table id='test'>";
			for($i=0;$i<=count($table_columns['student_main'])-1;$i++)
			{
				if($_POST[$table_columns['student_main'][$i]]!='')
				{
					if($_POST[$table_columns['student_main'][$i]]==$_POST['Course'] 
					   or $_POST[$table_columns['student_main'][$i]]==$_POST['Batch'] 
					   or $_POST[$table_columns['student_main'][$i]]==$_POST['Branch'])
					{
						continue;
					}
					else
					{
						echo "<th>".str_replace('_',' ',$_POST[$table_columns['student_main'][$i]])."</th>";
						$sqlarray[]= "student_main".".".$_POST[$table_columns['student_main'][$i]];
					}
					
				}
				else
				{
					continue;
				}
			}
			for($i=0;$i<=count($table_columns['student_detail'])-1;$i++)
			{
				
				if($_POST[$table_columns['student_detail'][$i]]!='' 
				   && $_POST[$table_columns['student_detail'][$i]]!='Roll_No')
				{
					
					echo "<th>".str_replace('_',' ',$_POST[$table_columns['student_detail'][$i]])."</th>";
					$sqlarray[]= "student_detail".".".$_POST[$table_columns['student_detail'][$i]];
				
				}
				else
				{
					continue;
				}
			}
			for($i=0;$i<=count($table_columns['student_previous_record'])-1;$i++)
			{
				
				if($_POST[$table_columns['student_previous_record'][$i]]!='' 
				   && $_POST[$table_columns['student_previous_record'][$i]]!='Roll_No')
				{
					echo "<th>".str_replace('_',' ',$_POST[$table_columns['student_previous_record'][$i]])."</th>";
					$sqlarray[]= "student_previous_record".".".$_POST[$table_columns['student_previous_record'][$i]];
					
				}
				else
				{
					continue;
				}
			}
			
			
			
			for($i=0;$i<=count($table_columns['student_address'])-1;$i++)
			{
				
				if($_POST[$table_columns['student_address'][$i]]!='' 
				   && $_POST[$table_columns['student_address'][$i]]!='Roll_No')
				{
					echo "<th>".str_replace('_',' ',$_POST[$table_columns['student_address'][$i]])."</th>";
					$sqlarray[]= "student_address".".".$_POST[$table_columns['student_address'][$i]];
					
				}
				else
				{
					continue;
				}
			}
			
			
			
			for($i=0;$i<=count($table_columns['student_previous_record'])-1;$i++)
			{
				
				if($_POST[$table_columns['student_previous_record'][$i]]!='' 
				   && $_POST[$table_columns['student_previous_record'][$i]]!='Roll_No')
				{
					echo "<th>".str_replace('_',' ',$_POST[$table_columns['student_previous_record'][$i]])."</th>";
					$sqlarray[]= "student_previous_record".".".$_POST[$table_columns['student_previous_record'][$i]];
					
				}
				else
				{
					continue;
				}
			}
			
			
			for($i=0;$i<=count($table_columns['student_admission_detail'])-1;$i++)
			{
				
				if($_POST[$table_columns['student_admission_detail'][$i]]!='' 
				   && $_POST[$table_columns['student_admission_detail'][$i]]!='Roll_No')
				{
					echo "<th>".str_replace('_',' ',$_POST[$table_columns['student_admission_detail'][$i]])."</th>";
					$sqlarray[]= "student_admission_detail".".".$_POST[$table_columns['student_admission_detail'][$i]];
					
				}
				else
				{
					continue;
				}
			}
			
			
			
			
			$sqlselect = implode(',',$sqlarray);
			if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
			{
				$sql = "SELECT ".$sqlselect."
						FROM student_main, student_detail, student_address,student_previous_record,student_admission_detail
						WHERE student_main.Roll_No = student_detail.Roll_No
						AND student_main.Roll_No = student_address.Roll_No
						AND student_main.Roll_No = student_previous_record.Roll_No
						AND student_main.Roll_No = student_admission_detail.Roll_No
						AND student_main.Course =  '".$_POST['Course']."'
						AND student_main.Batch =  '".$_POST['Batch']."' ORDER BY student_main.Roll_No ASC";
			}
			else
			{
				$sql = "SELECT ".$sqlselect."
						FROM student_main, student_detail, student_address,student_previous_record,student_admission_detail
						WHERE student_main.Roll_No = student_detail.Roll_No
						AND student_main.Roll_No = student_address.Roll_No
						AND student_main.Roll_No = student_previous_record.Roll_No
						AND student_main.Roll_No = student_admission_detail.Roll_No
						AND student_main.Course =  '".$_POST['Course']."'
						AND student_main.Batch =  '".$_POST['Batch']."'
						AND student_main.Branch =  '".$_POST['Branch']."' ORDER BY student_main.Roll_No ASC";
			}
			$result = mysql_query($sql) or die(mysql_error());
			while($row=mysql_fetch_array($result))
			{
				echo "<tr>";
				for($j=0;$j<=count($sqlarray)-1;$j++)
				{
					if($row[$j]=='')
					{
						$row[$j]='&nbsp';
					}
					echo "<td>".$row[$j]."</td>";
				}
				echo "</tr>";
				
			}
			echo "</table>";
			break;
		}
		
		if(isset($_POST['Course']) 
				 && isset($_POST['Batch']))
		{
			get_report_form($_POST['Course'],
							$_POST['Batch'],
							$_POST['Branch']);
			break;
		}
		else
		{
			$form = new student_form();
			echo "<p id='introduction'><table align='center'><tr><td><p>Please Select Class For Which You Wish To Generate Report.</p></td></tr></table>";
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='info.php?mode=get_report' onsubmit='return checkbranch()' method='post'>";
			$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
			$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
			$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
			echo "</select>";
			echo "<tr><td><input type='submit' value='submit' /></tr></td>";
			echo "</form></table>";
			break;
		}
		
		break;
	
	case "add_user":
		add_user_form();
		break;
		
	case "edit_user":
		$table_columns = get_tables_cols();
		$sql1 = "SELECT * FROM student_main WHERE Roll_No='".$_POST['rollno']."'";
		$sql2 = "SELECT * FROM student_detail WHERE Roll_No='".$_POST['rollno']."'";
		$sql3 = "SELECT * FROM student_previous_record WHERE Roll_No='".$_POST['rollno']."'";
		$sql4 = "SELECT * FROM student_address WHERE Roll_No='".$_POST['rollno']."'";
		$sql5 = "SELECT * FROM student_admission_detail WHERE Roll_No='".$_POST['rollno']."'";
		$sql6 = "SELECT * FROM student_images WHERE Roll_No='".$_POST['rollno']."'";
		$result1 = mysql_query($sql1);
		$result2 = mysql_query($sql2);
		$result3 = mysql_query($sql3);
		$result4 = mysql_query($sql4);
		$result5 = mysql_query($sql5);
		$result6 = mysql_query($sql6);
		$student_main = mysql_fetch_assoc($result1);
		$student_detail = mysql_fetch_assoc($result2);
		$student_previous_record = mysql_fetch_assoc($result3);
		$student_address = mysql_fetch_assoc($result4);
		$student_admission_detail = mysql_fetch_assoc($result5);
		$student_images = mysql_fetch_assoc($result6);
		edit_user_form($student_main,
					   $student_detail,
					   $student_previous_record,
					   $student_address,$student_admission_detail,$student_images);
		break;
	
	case "delete_details":
	      $sql_delete = "DELETE FROM student_main WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete1 = "DELETE FROM student_detail WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete2 = "DELETE FROM student_previous_record WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete3 = "DELETE FROM student_course_record WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete4 = "DELETE FROM student_sessional_record WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete5 = "DELETE FROM student_internal_marks WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete6 = "DELETE FROM student_external_marks WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete7 = "DELETE FROM student_training WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete9 = "DELETE FROM student_address WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete11 = "DELETE FROM student_attendance WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete12 = "DELETE FROM student_extra_activities WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete13 = "DELETE FROM users WHERE Roll_No='".$_POST['rollno']."'";
	      $sql_delete14 = "DELETE FROM student_admission_detail WHERE Roll_No='".$_POST['rollno']."'";
	      mysql_query($sql_delete) or die(mysql_error());
	      mysql_query($sql_delete1) or die(mysql_error());
	      mysql_query($sql_delete2) or die(mysql_error());
	      mysql_query($sql_delete3) or die(mysql_error());
	      mysql_query($sql_delete4) or die(mysql_error());
	      mysql_query($sql_delete5) or die(mysql_error());
	      mysql_query($sql_delete6) or die(mysql_error());
	      mysql_query($sql_delete7) or die(mysql_error());
	      //mysql_query($sql_delete8) or die(mysql_error());
	      mysql_query($sql_delete9) or die(mysql_error());
	      //mysql_query($sql_delete10) or die(mysql_error());
	      mysql_query($sql_delete11) or die(mysql_error());
	      mysql_query($sql_delete12) or die(mysql_error());
	      mysql_query($sql_delete13) or die(mysql_error());
	      mysql_query($sql_delete14) or die(mysql_error());
	      echo "<p>Record Successfully Deleted</p>";
		break;
	
	case "add_other":
		
		if(isset($_POST['Username']) 
		         && isset($_POST['Full_Name'])  
		         && isset($_POST['Password']) 
		         && $_POST['usertype'])
		{
			add_admin($_POST['Username'],
					  $_POST['Full_Name'],
					  $_POST['Password'],
					  $_POST['usertype'],$_POST['Department'],$_POST['Mobile'],$_POST['Email']);
			break;
		}
		else
		{
			form("add_other");
		}
		break;
		
	case "user_change_password" :
		if(isset($_POST['New_Password']) && $_SESSION['usertype']=='Student')
		{
			
			change_password_student($_POST['New_Password'],
									$_SESSION['usertype'],
									$_SESSION['rollno']);
			break;
		}
		
		if(isset($_POST['New_Password']))
		{
			change_password_admins($_POST['New_Password'],
								   $_SESSION['usertype'],
								   $_SESSION['username']);
		}
		else
		{
			form("change_password_user");
			break;
		}
		break;
		
		
	case "edit_login" :
		if(isset($_POST['Update_Student']) && $_POST['User_Type']=='Student')
		{
			$sql = "UPDATE users 
					SET Password='".md5($_POST['New_Password'])."' 
					WHERE Roll_No='".$_POST['Roll_No']."' 
					AND User_Type='".$_POST['User_Type']."'";
			mysql_query($sql) or die(mysql_error());
			echo "<p>Student Successfully Updated</p>";
			break;
		}
		
		if(isset($_POST['update_admins']) && $_POST['User_Type']!='Student')
		{
			$pass = md5($_POST['New_Password']);
			if($_POST['usertype']=='Admin') {
				$sql = "UPDATE users 
					SET Password='".$pass."',
					Full_Name='".$_POST['newfullname']."',
					Mobile = '".$_POST['Mobile']."',
					Email = '".$_POST['Email']."'
					WHERE Username='".$_POST['username']."' 
					AND User_Type='".$_POST['usertype']."'";
			}
			else {
			$sql = "UPDATE users 
					SET Password='".$pass."',
					Full_Name='".$_POST['newfullname']."', 
					Mobile = '".$_POST['Mobile']."',
					Department = '".$_POST['Department']."',
					Email = '".$_POST['Email']."' 
					WHERE Username='".$_POST['username']."' 
					AND User_Type='".$_POST['usertype']."'";
				}
			mysql_query($sql) or die(mysql_error());
			echo "<p>Record Successfully Updated</p>";
			break;
		}
		
		
		if($_POST['Roll_No']!='')
		{
			edit_login_student($_POST['Roll_No']);
			break;
		}
		
		if($_POST['Username']!='' or $_POST['Fullname']!='')
		{
			edit_login_admins($_POST['Username'],
							  $_POST['Fullname'],
							  $_POST['User_Type'],$_POST['Department'],$_POST['Mobile'],$_POST['Email']);
			break;
		}
		else
		{
			form("edit_login");
			break;
		}
		break;
		
	case "teacher_edit_record":
		$form = new student_form();
		
		if(isset($_POST['Edit_Internal_External_Now'])) {
			edit_internal_external($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Semester'],$_POST['Edit_Marks_Type']);
		}
		
		if(isset($_POST['Show_Internal_External'])) {
			edit_internal_external($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Semester'],$_POST['Edit_Marks_Type']);
			break;
		}
		
		if(isset($_POST['Edit_Semester_Final_Marks']) && isset($_POST['Backlog'])) {
			echo "<table id='student_details' align='center'>";
			echo "<form action='edit_user.php' method='post'>";
			echo "<tr><td>Roll No</td><td><input readonly type='text' name='Roll_No' value='".$_POST['Roll_No']."' /></td></tr>";
			echo "<tr><td>Max Marks</td><td><input type='text' name='Max_Marks' value='".$_POST['Max_Marks']."' /></td></tr>";
			echo "<tr><td>Obtained Marks</td><td><input type='text' name='Obtained_Marks' value='".$_POST['Obtained_Marks']."' /></td></tr>";
			$form->form_dropdown_field('dropdown',array('Yes','No'),'Backlog','','','','',$_POST['Backlog']);
			echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
			echo "<input type='hidden' name='Edit_Semester_Final_Marks' value='esmf' />";
			echo "<tr><td><input type='submit' value='Update' /></tr></td>";
			echo "</form></table>";
			break;
		}
		
		if(isset($_POST['teacher_edit_attendence']))
		{
			echo "<table align='center' id='student_details'>";
			echo "<form id='add_user' action='edit_user.php' method='post'>";
			$form->form_text_field('text','Roll_No','required','',$_POST['Roll_No']);
			$form->form_text_field('text','Subject','required','',$_POST['Subject']);
			$form->form_text_field('text','Total_Lecture','required','',$_POST['Total_Lecture']);
			$form->form_text_field('text','Attended_Lecture','required','',$_POST['Attended_Lecture']);
			$form->form_text_field('hidden','Course','','',$_POST['Course']);
			$form->form_text_field('hidden','Semester','','',$_POST['Semester']);
			$form->form_text_field('hidden','Batch','','',$_POST['Batch']);
			$form->form_text_field('hidden','Branch','','',$_POST['Branch']);
			$form->form_text_field('hidden','Start_Date','','',$_POST['Start_Date']);
			$form->form_text_field('hidden','End_Date','','',$_POST['End_Date']);
			$form->form_text_field('hidden','teacher_edit_attendence','','',$_POST['teacher_edit_attendence']);
			echo "<tr><td><input type='submit' value='Update' ></tr></td></form></table>";
			break;
		}
		if(isset($_POST['Start_Date']) && isset($_POST['End_Date']) && isset($_POST['Subject'])) {
			teacher_edit_attendence($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Semester'],$_POST['Subject'],$_POST['Start_Date'],$_POST['End_Date']);
			break;
		}
		if(isset($_POST['Start_Date']) && isset($_POST['End_Date']))
		{
			$result = mysql_query("SELECT Distinct Subject FROM student_attendance WHERE Course='".$_POST['Course']."' AND Batch='".$_POST['Batch']."' AND Semester='".$_POST['Semester']."' AND Teacher_Username='".$_SESSION['username']."'") or die(mysql_error());
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='info.php?mode=teacher_edit_record' method='post'>";
			echo "<tr><td>Subject</td><td><select name='Subject'>";
			while($subject = mysql_fetch_array($result)){
				echo "<option value='".$subject[0]."'>".$subject[0]."</option>";
			}
			echo "</select></td></tr>";
			echo "<input type='hidden' name='Course' value='".$_POST['Course']."' />
							<input type='hidden' name='Semester' value='".$_POST['Semester']."' />
							<input type='hidden' name='Batch' value='".$_POST['Batch']."' />
							<input type='hidden' name='Start_Date' value='".$_POST['Start_Date']."' />
							<input type='hidden' name='End_Date' value='".$_POST['End_Date']."' />";
			echo "<tr><td><input type='submit' value='Submit' /></tr></td></form></table>";
			break;
		}
		if(isset($_POST['edit_marks_now']))
		{
			edit_marks_now($_POST['markstype'],$_POST['subject'],$_POST['semester'],$_POST['sessionalno'],$_POST['rollno'],$_POST['batch']);
			break;
		}
	
		if(isset($_POST['Edit_Marks_Type']) && isset($_POST['Subject']) && isset($_POST['Sessional_No']))
		{
			teacher_edit_marks($_POST['Edit_Marks_Type'],$_POST['Semester'],$_POST['Subject'],$_POST['Sessional_No'],$_POST['Course'],$_POST['Batch'],$_POST['Branch']);
			break;
		}
		
		if(isset($_POST['Edit_Marks_Type']) && isset($_POST['Course']) && isset($_POST['Batch']) && isset($_POST['Semester']))
    {
			if($_POST['Edit_Marks_Type']=='Edit_Sessional_Marks')
      {
				if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
				{
					$sql_fetch_subjects ="SELECT DISTINCT Subject_Name 
															FROM student_subjects 
															WHERE Subject_Course='".$_POST['Course']."' 
															AND Subject_Semester = '".$_POST['Semester']."'";
			  }
		
			  else
			  {
				  $sql_fetch_subjects="SELECT DISTINCT Subject_Name 
															FROM student_subjects 
															WHERE Subject_Branch='".$_POST['Branch']."' 
															AND Subject_Semester = '".$_POST['Semester']."'";
			  }
			
		
			  $result_fetch_subject = mysql_query($sql_fetch_subjects);
			  echo "<table id='student_attendence' align='center'>";
			  echo "<form action='info.php?mode=teacher_edit_record' method='post'>";
			  $form->form_dropdown_field('dropdown',array(1,2,3),'Sessional_No','','','required','','');
			  echo "</select></tr></td>";
			  echo "<tr><td>Subject</td><td><select name='Subject'>";
			  while($rows = mysql_fetch_array($result_fetch_subject))
			  {
					echo "<option value='".$rows[0]."'>".$rows[0]."</option>";
			  }
			  echo "</select></tr></td>";
			  echo "<input type='hidden' name='Course' value='".$_POST['Course']."' />";
			  echo "<input type='hidden' name='Batch' value='".$_POST['Batch']."' />";
			  echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
			  echo "<input type='hidden' name='Branch' value='".$_POST['Branch']."' />";
			  echo "<input type='hidden' name='Edit_Marks_Type' value='".$_POST['Edit_Marks_Type']."' />";
			  echo "<tr><td><input type='submit' value='submit'></tr></td>";
			  echo "</form></table>";
			  break;
      }
      if($_POST['Edit_Marks_Type']=='Edit_Internal_Marks' or $_POST['Edit_Marks_Type']=='Edit_External_Marks')
      {
				echo "hello";
				edit_internal_external($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Semester'],$_POST['Edit_Marks_Type']);
				break;
      }
      if($_POST['Edit_Marks_Type']=='Edit_Semester_Final_Marks')
      {
				edit_semester_final_marks($_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Semester']);
				break;
			}
    }
		
		if(isset($_POST['Edit_Marks_Type']))
		{
			
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='info.php?mode=teacher_edit_record' onsubmit='return checkbranch()' method='post'>";
			$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
			$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
			$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
			$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
			echo "<input type='hidden' name='Edit_Marks_Type' value='".$_POST['Edit_Marks_Type']."' />";
			echo "<tr><td><input type='submit' value='submit' /></tr></td>";
			echo "</form></table>";
			break;
		}
		if(isset($_POST['Record_Type']))
		{
			if($_POST['Record_Type']=='Edit_Marks_Record')
			{
				echo"<p>Please Select Marks Type</p>";
				echo "<table id='student_details' align='center'>";
				echo "<form name='teacher_edit_record' action='info.php?mode=teacher_edit_record' method='post'>";
				$form->form_dropdown_field('dropdown',array('Edit_Sessional_Marks','Edit_Internal_Marks','Edit_External_Marks'),'Edit_Marks_Type','','','required','','');
				echo "<tr><td><input type='submit' value='Submit'></tr></td>";
				echo "</form></table>";
				break;
			}
			if($_POST['Record_Type']=='Edit_Attendence_Record')
			{
				echo "<table id='student_attendence' align='center'>";
				echo "<form action='info.php?mode=teacher_edit_record' onsubmit='return checkbranch()' method='post'>";
				$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
				$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
				$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
				$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
				echo "<tr><td>Start Date</td><td><select name='Start_Date'>";
				$result = mysql_query("SELECT Distinct Start_Date FROM student_attendance WHERE Teacher_Username='".$_SESSION['username']."'");
				while($start_date = mysql_fetch_array($result))
				{
					echo "<option value='".$start_date[0]."'>".$start_date[0]."</option>";
				}
				echo "</tr></td></select>";
				echo "<tr><td>Start Date</td><td><select name='End_Date'>";
				$result = mysql_query("SELECT Distinct End_Date FROM student_attendance WHERE Teacher_Username='".$_SESSION['username']."'");
				while($end_date = mysql_fetch_array($result))
				{
					echo "<option value='".$end_date[0]."'>".$end_date[0]."</option>";
				}
				echo "</tr></td></select>";
				echo "<tr><td><input type='submit' value='submit'></td></tr></form></table>";
				break;
			}
			if($_POST['Record_Type']=='Edit_Assignment_Record')
			{
				echo "<table id='student_attendence' align='center'>";
				echo "<form action='info.php?mode=teacher_edit_record' onsubmit='return checkbranch()' method='post'>";
				$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
				$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
				$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
				break;
			}
		}
		else
		{
			echo"<p>Please Select Record to edit</p>";
			echo "<table id='student_details' align='center'>";
			echo "<form name='teacher_edit_record' action='info.php?mode=teacher_edit_record' method='post'>";
			$form->form_dropdown_field('dropdown',array('Edit_Marks_Record','Edit_Attendence_Record'),'Record_Type','','','required','','');
			echo "<tr><td><input type='submit' value='Submit'></tr></td>";
			echo "</form></table>";
			break;
		}
		
	case "admin_send_sms":
		
		if(isset($_POST['send_single_sms'])){
			send_single_sms($_POST['Roll_No'],$_POST['msgdata']);
			break;
		}
	
		if(isset($_POST['admin_send_sms_now']))
		{
			admin_send_sms_alert($_POST['SMS_Type'],
								 $_POST['roll_nos'],
								 $_POST['Subject'],
								 $_POST['Semester'],
								 $_POST['Sessional_No']);
			break;
		}
		
		if(isset($_POST['SMS_Type']) 
				 && isset($_POST['Course']) 
				 && isset($_POST['Batch']) 
				 && isset($_POST['Semester']) 
				 && isset($_POST['Sessional_No']))
		{
			send_sms_admin($_POST['SMS_Type'],
						   $_POST['Course'],
						   $_POST['Batch'],
						   $_POST['Branch'],
						   $_POST['Semester'],
						   $_POST['Sessional_No']);
			break;
		}
		
		if(isset($_POST['SMS_Marks_Type']))
		{
			if($_POST['SMS_Marks_Type']=='SMS_Sessional_Marks')
			{
				$form = new student_form();
				echo "<p>Please Select The Class to send SMS</p>";
				echo "<table id='student_attendence' align='center'>";
				echo "<form action='info.php?mode=admin_send_sms' onsubmit='return checkbranch()' method='post'>";
				$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
				$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
				$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
				$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
				$form->form_dropdown_field('dropdown',array(1,2,3),'Sessional_No','','','required','','');
				echo "<input type='hidden' name='SMS_Type' value='".$_POST['SMS_Marks_Type']."' />";
				echo "<tr><td><input type='submit' value='submit' /></tr></td>";
				echo "</form></table>";
				break;
			}
			else
			{
				echo "<p>Under Development</p>";
				break;
			}
		}
		
		if(isset($_POST['SMS_Type']))
		{
			if($_POST['SMS_Type']=='notify_students')
			{
				echo"<p>Please Select Marks Type</p>";
				echo "<table id='student_details' align='center'>";
				echo "<form name='admin_send_sms' action='info.php?mode=admin_send_sms' method='post'>";
				echo "<tr><td><select name='SMS_Marks_Type'>
					<option value='SMS_Sessional_Marks'>Sessional Marks</option>
					</select></tr></td>";
				echo "<tr><td><input type='submit' value='Submit'></tr></td>";
				echo "</form></table>";
				break;
			}
			if($_POST['SMS_Type']=='single_sms')
			{
				echo "<p id='introduction'><table align='center'><tr><td><p>Please Enter Roll No.</p></td></tr></table>";
				echo "<table id='attendence_teacher' align='center'>";
				echo "<form name='admin_send_sms' action='info.php?mode=admin_send_sms' method='post'>";
				echo "<tr><td>To:</td><td><input type='text' name='Roll_No'></tr></td>";
				echo "<tr><td>Message:</td><td><textarea name='msgdata' rows='10' cols='20'></textarea></tr></td>";
				echo "<input type='hidden' name='send_single_sms' value='sss' />";
				echo "<tr><td><input type='submit' value='Submit'></tr></td>";
				echo "</form></table>";
				break;
				
			}
		}
		else
		{
			echo "<p id='introduction'><table align='center'><tr><td><p>Please Select Type Of SMS to Send.</p></td></tr></table>";
			echo "<table id='student_details' align='center'>";
			echo "<form name='admin_send_sms' action='info.php?mode=admin_send_sms' method='post'>";
			echo "<tr><td><select name='SMS_Type'>
				<option value='single_sms'>Send SMS To Individuals</option>
				<option value='notify_students'>Notify Students about Marks</option>
				</select></tr></td>";
			echo "<tr><td><input type='submit' value='Submit'></tr></td>";
			echo "</form></table>";
			break;
		}
		
	case "tnp_upload_record":
		$form = new student_form();
		if(isset($_POST['r0'])) {
			for($i=0;$i<=$_POST['NSP']-1;$i++) {
				$roll = $_POST["r".$i];
				$branch=$_POST["br".$i];
				if($_POST['Other_Company']!=''){
					$company = $_POST['Other_Company'];
					mysql_query("INSERT INTO recruiters (Company_Name) VALUES ('".$_POST['Other_Company']."')");
				}
				else {
					$company = $_POST['Company_Name'];
				}
				mysql_query("INSERT INTO student_placement (Roll_No,Course,Batch,Branch,Company_Name,Date_Of_Placement,Package) VALUES ('".$roll."','".$_POST['Course']."','".$_POST['Batch']."','".$branch."','".$company."','".$_POST['Date_Of_Placement']."','".$_POST['Package']."')") or die(mysql_error());
			}
			echo "<p>Placemenet Record Successfully Updated</p>";
			break;
		}
			
		if(isset($_POST['Company_Name']) or isset($_POST['Other_Company'])) {
			tnp_upload_record($_POST['NSP'],$_POST['Company_Name'],$_POST['Other_Company'],$_POST['Date_Of_Placement'],$_POST['Package'],$_POST['Course'],$_POST['Batch']);
			break;
		}
		else
		{
		$sql = "SELECT Company_Name FROM recruiters ORDER BY recruiters.Company_Name ASC";
		$result = mysql_query($sql);
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='placement_record' action='info.php?mode=tnp_upload_record' onsubmit='return checkcompany()' method='post'>";
		echo "<tr><td>No. Of Students Placed</td><td><input type='text' name='NSP' class='required' />";
		echo "<tr><td>Company Name</td><td><select name='Company_Name' id='Company_Name'>";
		echo "<option value='' selected='selected'></option>";
		while($company = mysql_fetch_array($result)){
			echo "<option value='".$company[0]."'>".$company[0]."</option>";
		}
		echo "</select></tr></td>";
		echo "<tr><td>Company Not listed ? Enter Name Manually</td><td><input type='text' name='Other_Company' id='Other_Company' /></tr>";
		echo "<tr><td>Date Of Placement</td><td><input type='text' name='Date_Of_Placement' id='dop' class='required' /></tr></td>";
		echo "<tr><td>Package(Lac/annum)</td><td><input type='text' name='Package' /></tr></td>";
		$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
		$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
		echo "<tr><td><input type='submit' value='Submit' /></tr></td></form></table>";
		break;
	}
	
	case "tnp_training_record":
		$form = new student_form();
		if(isset($_POST['r0']) && isset($_POST['cn0'])) {
			for($i=0;$i<=$_POST['num_row']-1;$i++) {
				$roll = $_POST["r".$i];
				$company_name = $_POST["cn".$i];
				$company_address = $_POST["ca".$i];
				$stipend = $_POST["sp".$i];
				mysql_query("INSERT INTO student_training (Roll_No,Course,Batch,Branch,Training_Type,Start_Date,End_Date,Company_Name,Company_Address,Stipend) VALUES ('".$roll."','".$_POST['Course']."','".$_POST['Batch']."','".$_POST['Branch']."','".$_POST['Training_Type']."','".$_POST['Start_Date']."','".$_POST['End_Date']."','".$company_name."','".$company_address."','".$stipend."')") or die(mysql_error());
			}
			echo "<p>Training Record Updated</p>";
			break;
		}
		if(isset($_POST['Type_Of_Training']) && isset($_POST['Course']) && isset($_POST['Batch']) && isset($_POST['Start_Date']) && isset($_POST['End_Date'])) {
			tnp_training_record($_POST['Type_Of_Training'],$_POST['Course'],$_POST['Batch'],$_POST['Branch'],$_POST['Start_Date'],$_POST['End_Date']);
			break;
		}
		else{
			
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='placement_record' action='info.php?mode=tnp_training_record' onsubmit='return checkbranch()' method='post'>";
		$form->form_dropdown_field('dropdown',array('6 Weeks Industrial Training','6 Months Industrial Training'),'Type_Of_Training','','','required','','');
		$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
		$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
		$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
		echo "<tr><td>Duration:</td><td>From<input type='text' class='required' name='Start_Date' id='sdate' />To<input type='text' class='required' name='End_Date' id='edate' /></td>";
		echo "<tr><td><input type='submit' value='submit' /></tr></td></form></table>";
		break;
	}
	
	case "tnp_edit_record":
		$form = new student_form();
		if(isset($_POST['Training_Type'])) {
			$sql = "SELECT Roll_No, Start_Date,End_Date,Company_Name,Company_Address,Stipend FROM student_training WHERE Course='".$_POST['Course']."' AND Batch='".$_POST['Batch']."' AND Branch LIKE '%".$_POST['Branch']."%' AND Training_Type='".$_POST['Training_Type']."'";
			$result = mysql_query($sql) or die(mysql_error());
			$num_row = mysql_num_rows($result);
			$j=1;
			$i=0;
			echo "<table id='student_attendence' align='center'>";
			echo "<form id='upload_marks' action='edit_user.php' method='post'>";
			while($row=mysql_fetch_assoc($result)) {
				echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row['Roll_No']."' /></td>";
				echo "<td>Start Date</td><td><input type='text' name='sd".$i."' value='".$row['Start_Date']."' /></td>";
				echo "<td>End Date</td><td><input type='text' name='ed".$i."' value='".$row['End_Date']."' /></td>";
				echo "<td>Company Name</td><td><input type='text' name='cn".$i."' value='".$row['Company_Name']."' /></td>";
				echo "<td>Company Address</td><td><input type='text' name='ca".$i."' value='".$row['Company_Address']."' /></td>";
				echo "<td>Stipend</td><td><input type='text' name='st".$i."' value='".$row['Stipend']."' /></td>";
				$i +=1;
				$j +=1;
			}
			echo "<input type='hidden' name='Course' value='".$_POST['Course']."' />";
			echo "<input type='hidden' name='Batch' value='".$_POST['Batch']."' />";
			echo "<input type='hidden' name='Branch' value='".$_POST['Branch']."' />";
			echo "<input type='hidden' name='Training_Type' value='".$_POST['Training_Type']."' />";
			echo "<input type='hidden' name='edit_training_record' value='etr' />";
			echo "<input type='hidden' name='num_row' value='".$num_row."' />";
			echo "<tr><td><input type='submit' value='Submit' /></tr></td></form></table>";
			break;
		}
		if(isset($_POST['Record_Type'])) {
			tnp_edit_record($_POST['Record_Type'],$_POST['Course'],$_POST['Batch'],$_POST['Branch']);
			break;
		}
		else
		{
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='placement_record' action='info.php?mode=tnp_edit_record' onsubmit='return checkbranch()' method='post'>";
		$form->form_dropdown_field('dropdown',array('Training Record','Placement Record'),'Record_Type','','','required','','');
		$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
		$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
		$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
		echo "<tr><td><input type='submit' value='submit' /></tr></td></form></table>";
		break;
	}
	
	
	case "tnp_statistics":
		$form = new student_form();
		if(isset($_POST['Get_Record']) && $_POST['Record_Type']=='List Of Placed Students'){
			list_placed_students($_POST['Record_Type'],$_POST['Batch']);
			break;
		}
		if(isset($_POST['Record_Type'])) {
			if($_POST['Record_Type']=='List Of Placed Students') {
				echo "<table id='student_attendence' align='center'>";
				echo "<form id='placement_record' action='info.php?mode=tnp_statistics' method='post'>";
				$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
				echo "<input type='hidden' name='Record_Type' value='".$_POST['Record_Type']."' />";
				echo "<input type='hidden' name='Get_Record' value='glops' />";
				echo "<tr><td><input type='submit' value='submit' /></tr></td></form></table>";
				break;
			}
		}
		else{
			
			echo "<table id='student_attendence' align='center'>";
			echo "<form id='placement_record' action='info.php?mode=tnp_statistics' method='post'>";
			$form->form_dropdown_field('dropdown',array('List Of Placed Students'),'Record_Type','','','required','','');
			echo "<tr><td><input type='submit' value='submit' /></tr></td></form></table>";
			break;
		}
	
	
	case "assign_subjects":
		if(isset($_POST['Count'])) {
			for($j=1;$j<=$_POST['Count']-1;$j++) {
				for($a=0;$a<=4;$a++) {
					$b = $a+1;
					if($_POST["newt".$j.$a]=='DC') {
						continue;
					}
					else
					{
						mysql_query("UPDATE student_subjects SET Assigned_Teacher".$b."='".$_POST["newt".$j.$a]."',Modified_By='".$_SESSION['username']."',Last_Updated='".date('Y-m-d H:i:s')."' WHERE Subject_Code='".$_POST["sub".$j]."'") or die(mysql_error());
						$chkexisting = mysql_query("SELECT * FROM teacher_allotment WHERE Subject_Name='".$_POST["subn".$j]."' AND Subject_Code='".$_POST["sub".$j]."' AND Changed='No'") or die(mysql_error());
						if(mysql_num_rows($chkexisting)!=0) {
							mysql_query("UPDATE teacher_allotment SET Assigned_Teacher='".$_POST["newt".$j.$a]."' , Assigned_Group='".$_POST["newg".$j.$a]."', Changed='Yes' WHERE Subject_Code='".$_POST["sub".$j]."' AND Subject_Name='".$_POST["subn".$j]."' AND Changed='No'") or die(mysql_error());
						}
						else {
							mysql_query("INSERT INTO teacher_allotment (Subject_Name,Subject_Code,Assigned_Teacher,Assigned_Group,Changed) VALUES ('".$_POST["subn".$j]."','".$_POST["sub".$j]."','".$_POST["newt".$j.$a]."','".$_POST["newg".$j.$a]."','Yes')") or die(mysql_error());
						}
					}
				}
			}
			mysql_query("UPDATE teacher_allotment SET Changed='No'");
			echo "<p>Record Updated</p>";
			break;
		}
		if(isset($_POST['Semester'])) {
			assign_subjects($_POST['Semester']);
			break;
		}
		else {
			form("assign_subjects");
			break;
		}
			
	
	case "create_groups":
		
		
		if(isset($_POST['Edit_Subgroup_Now'])) {
			echo "<p>Subgrouping is updated as follows.</p>";
			echo "<table align='center'><td>";
			for($i=1;$i<=$_POST['No_Of_Subgroups'];$i++) {
				echo "<td><table id='test' align='center'>";
				echo "<tr><th>".$_POST["SubgroupName".$i]."</th></tr>";
				for($j=0;$j<=$_POST['No_Of_Students'];$j++) {
					$rollno = $_POST["Subgroup".$i.$j];
					$subgroupname = $_POST["SubgroupName".$i];
					$subgroupadvisor = $_POST["SubgroupAdvisor".$i];
					if($rollno=='') {
						continue;
					}
					else {
						mysql_query("UPDATE student_groups SET
												Subgroup_Name='".$subgroupname."',
												Subgroup_Advisor = '".$subgroupadvisor."',
												Subgroup_Modified_By = '".$_SESSION['username']."',
												Subgroup_Last_Updated = '".date('Y-m-d H:i:s')."'
												WHERE Roll_No='".$rollno."'
												AND Group_Name='".$_POST['Group_Name']."'") or die(mysql_error());
						echo "<tr><td>".$rollno."</td></tr>";
					}
				}
				echo "</table></td>";
			}
			echo "</tr></table>";
			echo "<p>Grouping Done Successfully</p>";
			break;
		}
		
		if(isset($_POST['Show_Groups_To_Edit'])) {
			edit_grouping($_POST['Batch'],'','Edit_Grouping');
			break;
		}
		
		if(isset($_POST['Show_Subgroups_To_Edit'])) {
			edit_grouping($_POST['Batch'],$_POST['Group_Name'],'Edit_Subgrouping');
			break;
		}
		
		if(isset($_POST['Editing_Grouping'])) {
			form('editing_grouping',$_POST['Type_Of_Editing']);
			break;
		}
		
		if(isset($_POST['Insert_New_Subgroup'])) {
			
			echo "<p>Following Subgrouping is done. Please check if everything is OK. If not, then Select 'Edit Existing Grouping' to edit.</p>";
			echo "<table align='center'><tr>";
			for($i=1;$i<=$_POST['No_Of_Subgroups'];$i++) {
				echo "<td><table id='test' align='center'>";
				echo "<tr><th>".$_POST["SubgroupName".$i]."</th></tr>";
				for($j=0;$j<=$_POST['No_Of_Students'];$j++) {
					$rollno = $_POST["Subgroup".$i.$j];
					$subgroupname = $_POST["SubgroupName".$i];
					$subgroupadvisor = $_POST["SubgroupAdvisor".$i];
					if($rollno=='') {
						continue;
					}
					else {
						mysql_query("UPDATE student_groups SET
												Subgroup_Name='".$subgroupname."',
												Subgroup_Advisor = '".$subgroupadvisor."',
												Subgroup_Created_By = '".$_SESSION['username']."',
												Subgroup_Created_On = '".date('Y-m-d H:i:s')."'
												WHERE Roll_No='".$rollno."'
												AND Group_Name='".$_POST['Group_Name']."'") or die(mysql_error());
						echo "<tr><td>".$rollno."</td></tr>";
					}
				}
				echo "</table></td>";
			}
			echo "</tr><table>";
			echo "<p>Grouping Done Successfully</p>";
			break;
		}
			
			
		
		if(isset($_POST['Add_New_Subgroup'])) {
			create_groups($_POST['Add_New_Subgroup'],$_POST['Batch'],$_POST['No_Of_Subgroups'],$_POST['Group_Name']);
			break;
		}
		
		
		if(isset($_POST['Insert_New_Group'])) {
			if($_POST['Branch']=='MBA' or $_POST['Branch']=='MCA') {
				$branch = 'N/A';
				$course = $_POST['Branch'];
			}
			else {
				$branch = $_POST['Branch'];
				$course = 'B.Tech';
			}
			echo "<p>Following Grouping is done. Please check if everything is OK. If not, then Select 'Edit Existing Grouping' to edit.</p>";
			echo "<table align='center' ><tr>";
			for($i=1;$i<=$_POST['No_Of_Groups'];$i++) {
				echo "<td><table style='width:10%' id='test'>";
				echo "<tr><th>".$_POST["GroupName".$i]."</th></tr>";
				for($j=0;$j<=$_POST['No_Of_Students'];$j++) {
					$rollno = $_POST["Group".$i.$j];
					$groupname = $_POST["GroupName".$i];
					$academicincharge = $_POST["AcademicIncharge".$i];
					if($rollno=='') {
						continue;
					}
					else {
						mysql_query("INSERT INTO student_groups (Roll_No,Batch,Course,Branch,Group_Name,Academic_Incharge,Group_Created_By,Group_Created_On) VALUES ('".$rollno."','".$_POST['Batch']."','".$course."','".$branch."','".$groupname."','".$academicincharge."','".$_SESSION['username']."','".date('Y-m-d H:i:s')."')") or die(mysql_error());
						echo "<tr><td>".$rollno."</td></tr>";
					}
				}
				echo "</table></td>";
			}
			echo "</tr><table>";
			echo "<p>Grouping Done Successfully</p>";
			break;
		}
		if(isset($_POST['Add_New_Group'])) {
			create_groups($_POST['Add_New_Group'],$_POST['Batch'],$_POST['No_Of_Groups']);
			break;
		}
		if(isset($_POST['Operation'])) {
			form("create_groups",$_POST['Operation']);
			break;
		}
		else {
			echo "<table id='student_details' align='center'>";
			echo "<form action='info.php?mode=create_groups' method='post'>";
			echo "<tr><td>Select Action</td><td><select name='Operation'>";
			echo "<option value='New Group'>New Group</option>";
			echo "<option value='New Subgroup'>New Subgroup</option>";
			echo "<option value='Edit Existing Grouping'>Edit Existing Grouping</option>";
			echo "</select></tr></td>";
			echo "<tr><td><input type='submit' value='Go' /></tr></td>";
			echo "</form></table>";
			break;
		}
		
		
	case "admin_check_records":
		
		if(isset($_POST['period'])) {
			$se = explode(" TO ",$_POST['period']);
			$start_date = $se[0];
			$end_date = $se[1];
			attendence_record($start_date,$end_date,$_POST['batch'],$_POST['semester']);
			break;
		}
	
		if(isset($_POST['record_type'])) {
			form("attendence_record");
			break;
		}
		
		
		echo "<table id='student_details' align='center'>";
		echo "<form action='info.php?mode=admin_check_records' method='post'>";
		echo "<tr><td>Select Record</td><td><select name='record_type'>";
		echo "<option value='Attendence'>Attendence</option>";
		echo "<option value='Sessional'>Sessional</option>";
		echo "<option value='Internal Marks'>Internal Marks</option>";
		echo "</select></tr></td>";
		echo "<tr><td><input type='submit' value='Go' /></tr></td>";
		echo "</form></table>";
		break;
		  
	
	default:
		header("location:options.php");
		break;
	}
	
	include_once('header_footer/footer.php');

?>
