<?php
/* This is the Main File which Contains all the functions	*
 *This is where the main processing of the program happens	*/
require_once('config.php');
require('PHPMailer/class.phpmailer.php');
mysql_select_db("gndec_erp",$conn);
require_once ('input_form_class.php');
require_once('scripts.php');
function CheckForLogin() {
	if(!isset($_SESSION['usertype'])) {
		session_destroy();
		header("location:index.php");
}	
}
/* This Function is Used To fetch the names of tables and Columns from the database*/
function get_tables_cols() {
	$query_tables = mysql_list_tables("gndec_erp");
	$table_num = mysql_num_rows($query_tables);
	for ($j = 0; $j<=$table_num-1; $j++){
		$table_names[$j] = mysql_tablename($query_tables, $j);
	}
	$num_of_tables = count($table_names);
	for($i=0; $i<=$num_of_tables-1;$i++) {
		$sql = "SELECT * FROM ".$table_names[$i]."";
		$result = mysql_query($sql);
		$num_of_fields = mysql_num_fields($result);
		for($j=0;$j<=$num_of_fields-1;$j++){
			$col_names[$table_names[$i]][$j] = mysql_field_name($result,$j);
		}
	}
	return $col_names;
}

function branch_acronym() {
	if($_SESSION['department']=='Information Technology') {
			$branch = 'IT';
		}
	if($_SESSION['department']=='Computer Science') {
		$branch = 'CSE';
	}
	if($_SESSION['department']=='Electronics & Communication Engineering') {
		$branch = 'ECE';
	}
	if($_SESSION['department']=='Electrical Engineering') {
		$branch = 'EE';
	}
	if($_SESSION['department']=='Mechanical Engineering') {
		$branch = 'ME';
	}
	if($_SESSION['department']=='Production Engineering') {
		$branch = 'PE';
	}
	if($_SESSION['department']=='Civil Engineering') {
		$branch = 'CE';
	}
	if($_SESSION['department']=='MBA') {
		$branch = 'MBA';
	}
	if($_SESSION['department']=='MCA') {
		$branch = 'MCA';
	}
	
	return $branch;
}
		

/*This Function is used to Display the Student Details*/
function student_details($query_data,$tablename) {
	$table_columns = get_tables_cols();
	$tests = count($table_columns[$tablename]);
	if($query_data[8]=='')
	{
		
		$sql = "SELECT * 
				FROM student_main 
				WHERE Student_First_Name LIKE '%".$query_data[0]."%' 
				AND Student_Middle_Name LIKE '%".$query_data[1]."%' 
				AND Student_Last_Name LIKE '%".$query_data[2]."%' 
				AND Roll_No LIKE '%".$query_data[3]."%' 
				AND Univ_Roll_No Like '%".$query_data[4]."%' 
				AND Branch LIKE '%".$query_data[5]."%' 
				AND Batch LIKE '%".$query_data[6]."%' 
				AND Course LIKE '%".$query_data[7]."%' 
				AND Gender LIKE '%".$query_data[8]."%' 
				ORDER BY student_main.Roll_No ASC";
	}
	else
	{
		$sql = "SELECT * 
				FROM student_main 
				WHERE Student_First_Name LIKE '%".$query_data[0]."%' 
				AND Student_Middle_Name LIKE '%".$query_data[1]."%' 
				AND Student_Last_Name LIKE '%".$query_data[2]."%' 
				AND Roll_No LIKE '%".$query_data[3]."%' 
				AND Univ_Roll_No Like '%".$query_data[4]."%' 
				AND Branch LIKE '%".$query_data[5]."%' 
				AND Batch LIKE '%".$query_data[6]."%' 
				AND Course LIKE '%".$query_data[7]."%' 
				AND Gender LIKE '".$query_data[8]."' 
				ORDER BY student_main.Roll_No ASC";
	}
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	if($num_rows==0){
		echo "<h1>No Result Found ):</h1>";
	}
	else {
	echo "<table id='test'><tr>";
	for ($i=0;$i<=$tests-1;){
		$table_heading = str_replace("_"," ",$table_columns[$tablename][$i]);
		echo "<th>".$table_heading."</th>";
		$i++;
		if($i==$tests)
		{
			echo "<th>Photo</th>";
			echo "<th>Details</th>";
			if($_SESSION['usertype']=='Admin')
			{
				echo "<th>Edit</th>";
			  #echo "<th>Delete</th>";
			  }
		}
	}
	echo "</tr>";
	$trcount = 2;
	while($rows = mysql_fetch_assoc($result)){
		$img = mysql_query("SELECT Image_Path FROM student_images WHERE Roll_No='".$rows['Roll_No']."'") or die(mysql_error());
		if($trcount%2==0){
			$class = "white";
		}
		else{
			$class = "alt";
		}
		echo "<tr class='".$class."'>";
	for ($j=0;$j<=$tests-1;) 
	{
		if($rows[$table_columns[$tablename][$j]]=='')
		{
			$rows[$table_columns[$tablename][$j]]='&nbsp';
		}
		
		echo "<td>".$rows[$table_columns[$tablename][$j]]."</td>";
		$j++;
		if($j==$tests){
			while($row_i = mysql_fetch_array($img)) {
				if($row_i[0]=='') {
					$img_path = 'images/student_images/pna.png';
				}
				else {
					$img_path = $row_i[0];
				}
					echo "<td><img src='".$row_i[0]."' height='70' width='70' /></td>";
				}
			echo "<td><form action='info.php?mode=view_details' method='post' target='_blank'>
				<input type='hidden' name='rollno' value=".$rows['Roll_No']." />
				<input type='submit' value='Details' /></form></td>";
			if($_SESSION['usertype']=='Admin')
			{
				
				echo "<td><form action='info.php?mode=edit_user' method='post' target='_blank'>
				<input type='hidden' name='rollno' value=".$rows['Roll_No']." />
				<input type='submit' value='Edit' /></form></td>";
			  /*echo "<td><form action='info.php?mode=delete_details' method='post'>
				<input type='hidden' name='rollno' value=".$rows['Roll_No']." />
				<input type='submit' value='Delete' onclick='return confirm_delete(\"".$rows['Roll_No']."\")' /></form></td>";*/
				}
				
			}
			
		
	}
	echo "</tr>";
	$trcount += 1;
}
	
	echo "</table>";
}
	
}
	
/*This function displays the student details in the Tabular Form*/
function view_details($rollno){
	$table_columns = get_tables_cols();
	$image_result = mysql_query("SELECT Image_Path FROM student_images WHERE Roll_No='".$rollno."'") or die(mysql_error());
	$image_path = mysql_fetch_array($image_result);
	if($image_path=='') {
		$image_path_f = 'images/student_images/pna.png';
	}
	else {
		$image_path_f = $image_path[0];
	}
	echo "<table align='center'><tr><td><img id='profile' style='border:2px solid #7a89a5' src=".$image_path_f." height='200' width='200' /></td></tr></table>";
	$sql = "SELECT * 
			FROM student_main,student_detail,student_address 
			WHERE student_main.Roll_No=student_detail.Roll_No 
			AND student_main.Roll_No=student_address.Roll_No 
			AND student_main.Roll_No=".$rollno."";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$keys = array_keys($row);
	$key_num = count($keys);
	echo "<table id='view_details' align='center' width='70%'>";
	$trcount = 2;
	for($i=0;$i<=$key_num-1;$i++){
		
		if($trcount%2!==0){
			$class = "white";
		}
		else{
			$class = "alt";
		}
		echo "<tr class='".$class."'>";
		if(str_replace("_"," ",$keys[$i])=='Photo' or str_replace("_"," ",$keys[$i])=='Passwords')
		{
			$trcount +=1;
		}
		else
		{
			
			echo "<td class='bold'>".str_replace("_"," ",$keys[$i])."</td>";
			echo "<td class='simple'>" .$row[$keys[$i]]."</td>";
			echo "</tr>";
		}
		$trcount +=1;
	}
	echo "</table>";
}


/* This function is used to show the attendence of the student */
function show_attendence($startdate,$enddate,$subject,$semester)
{
	$table_columns=get_tables_cols();
	$sql_show_attendence = "SELECT Subject, Total_Lecture, Attended_Lecture,Start_Date,End_Date 
							FROM student_attendance WHERE Subject='".$subject."' 
							AND Start_Date='".$startdate."' 
							AND End_Date='".$enddate."' 
							AND Semester='".$semester."'
							AND Roll_No='".$_SESSION['rollno']."'";
	$result_show_attendence = mysql_query($sql_show_attendence) or die(mysql_error());
	$row_num = mysql_num_rows($result_show_attendence);
	if($row_num!=0)
	{
		echo "<table id='test' align='center' width='70%'>";
		echo "<tr><th>Subject</th><th>From</th><th>To</th><th>Total Lectures</th><th>Attended By You</th></tr>";
		echo "<tr>";
		while($row=mysql_fetch_assoc($result_show_attendence)) {
			echo "<td>".$row['Subject']."</td>";
			echo "<td>".$row['Start_Date']."</td>";
			echo "<td>".$row['End_Date']."</td>";
			echo "<td>".$row['Total_Lecture']."</td>";
			echo "<td>".$row['Attended_Lecture']."</td>";
		}
		echo "</tr></table>";
	}
	else
	{
		echo "<p>No Record Found</p>";
	}
}

/*This function is used to Upload the Attendence to the Database*/
/*function attendence_teacher($course,$batch,$branch,$startdate,$enddate,$totallecture,$subject,$alert_low_attendence,$subject_code)
{
	$table_columns = get_tables_cols();
	if($course=='MBA' or $course=='MCA')
	{
		$sql_attendence_teacher = "SELECT DISTINCT Roll_No 
								  FROM student_main 
								  WHERE Course='".$course."' 
								  AND Batch='".$batch."' 
								  ORDER BY student_main.Roll_No ASC";
	}
	else
	{
		$sql_attendence_teacher = "SELECT DISTINCT Roll_No 
								  FROM student_main 
								  WHERE Course='".$course."' 
								  AND Branch='".$branch."' 
								  AND Batch='".$batch."' 
								  ORDER BY student_main.Roll_No ASC";
	}
	$result_attendence_teacher = mysql_query($sql_attendence_teacher);
	$row_num = mysql_num_rows($result_attendence_teacher);
	$i=0;
	$j=1;
	echo "<table id='student_attendence' align='center'>";
	echo "<form id='attendence_teacher' action='info.php?mode=attendence_teacher' method='post'>";
	echo "<tr><td>Notify Students Via SMS?</td>";
	echo "<tr><td>Yes<input type='radio' name='sendsms' value='Yes'/>";
	echo "No<input type='radio' name='sendsms' value='No'/></td></tr>";
	echo "<tr><td>Notify Students Via Email?</td>";
	echo "<tr><td>Yes<input type='radio' name='sendemail' value='Yes'/>";
	echo "No<input type='radio' name='sendemail' value='No'/></td></tr>";
	while($row = mysql_fetch_array($result_attendence_teacher))
	{
		echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row[0]."' /></td>";
		echo "<input readonly type='hidden' name='Start_Date' value='".$startdate."' />";
		echo "<input readonly type='hidden' name='End_Date' value='".$enddate."' />";
		echo "<td>Total Lecture</td><td><input readonly type='text' name='totallecture' value='".$totallecture."' /></td>";
		echo "<td>Lecture Attended</td><td><input type='text' class='' name='l".$i."'/></td></tr>";
		$i +=1;
		$j +=1;
	}
	echo "<input type='hidden' name='insert_attendence' value='insert_attendence' />";
	echo "</tr><td><input type='hidden' name='num_row' value='".$row_num."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Subject' value='".$subject."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Course' value='".$_POST['Course']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Batch' value='".$_POST['Batch']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Branch' value='".$_POST['Branch']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Semester' value='".$_POST['Semester']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Alert_Low_Attendence' value='".$alert_low_attendence."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Subject_Code' value='".$subject_code."'/></td></td>";
	echo "</tr><td><input type='submit' value='Upload'/>";
	echo "</form></table>";
}*/




function attendence_teacher($group,$startdate,$enddate,$totallecture,$subject)
{

		$sql_attendence_teacher = "SELECT DISTINCT Roll_No 
								  FROM student_groups 
								  WHERE Group_Name='".$group."' 
								  ORDER BY student_groups.Roll_No ASC";
	$result_attendence_teacher = mysql_query($sql_attendence_teacher) or die(mysql_error());
	$row_num = mysql_num_rows($result_attendence_teacher);
	$i=0;
	$j=1;
	echo "<table id='student_attendence' align='center'>";
	echo "<form id='attendence_teacher' action='info.php?mode=attendence_teacher' method='post'>";
	/*echo "<tr><td>Notify Students Via SMS?</td>";
	echo "<tr><td>Yes<input type='radio' name='sendsms' value='Yes'/>";
	echo "No<input type='radio' name='sendsms' value='No'/></td></tr>";
	echo "<tr><td>Notify Students Via Email?</td>";
	echo "<tr><td>Yes<input type='radio' name='sendemail' value='Yes'/>";
	echo "No<input type='radio' name='sendemail' value='No'/></td></tr>";*/
	while($row = mysql_fetch_array($result_attendence_teacher))
	{
		echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row[0]."' /></td>";
		echo "<input readonly type='hidden' name='Start_Date' value='".$startdate."' />";
		echo "<input readonly type='hidden' name='End_Date' value='".$enddate."' />";
		echo "<td>Total Lecture</td><td><input readonly type='text' name='totallecture' value='".$totallecture."' /></td>";
		echo "<td>Lecture Attended</td><td><input type='text' class='' name='l".$i."'/></td></tr>";
		$i +=1;
		$j +=1;
	}
	echo "<input type='hidden' name='insert_attendence' value='insert_attendence' />";
	echo "</tr><td><input type='hidden' name='num_row' value='".$row_num."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Subject' value='".$subject."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Course' value='".$_POST['Course']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Batch' value='".$_POST['Batch']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Branch' value='".$_POST['Branch']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Semester' value='".$_POST['Semester']."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Alert_Low_Attendence' value='".$alert_low_attendence."'/></td></td>";
	echo "</tr><td><input type='hidden' name='Subject_Code' value='".$subject_code."'/></td></td>";
	echo "</tr><td><input type='submit' value='Upload'/>";
	echo "</form></table>";
}






/*This Function is used to upload the Marks to the Database */
function upload_marks($markstype,$branch,$semester,$course,$batch)
{
	$form = new student_form();
	$table_columns = get_tables_cols();
	if($markstype=='Sessional_Marks' or $markstype=='Internal_Marks' or $markstype=='External_Marks')
	{
		
		if($course=='MBA' or $course=='MCA')
		{
			$sql_fetch_subjects = "SELECT DISTINCT Subject_Name 
								  FROM student_subjects 
								  WHERE Subject_Course='".$course."' 
								  AND Subject_Semester = '".$semester."'";
		}
		
		else
		{
			$sql_fetch_subjects = "SELECT DISTINCT Subject_Name 
								  FROM student_subjects 
								  WHERE Subject_Branch='".$branch."' 
								  AND Subject_Semester = '".$semester."'";
		}
			
		
		
		$result_fetch_subject = mysql_query($sql_fetch_subjects);
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=upload_marks' method='post'>";
	
		if($markstype=='Sessional_Marks')
		{
			$form->form_dropdown_field('dropdown',array(1,2,3),'Sessional_No','','','required','','');
		}
	
		echo "<tr><td>Subject</td><td><select name='Subject'>";
		while($rows = mysql_fetch_array($result_fetch_subject))
		{
			echo "<option value='".$rows[0]."'>".$rows[0]."</option>";
		}
		echo "</select></tr></td>";
		echo "<input type='hidden' name='Course' value='".$course."' />";
		echo "<input type='hidden' name='Batch' value='".$batch."' />";
		echo "<input type='hidden' name='Semester' value='".$semester."' />";
		echo "<input type='hidden' name='Branch' value='".$branch."' />";
		echo "<input type='hidden' name='Marks_Type' value='".$markstype."' />";
		echo "<tr><td><input type='submit' value='submit'></tr></td>";
		echo "</form></table>";
	}
	
	if($markstype=='Semester_Final_Marks')
	{
		if($course == 'MBA' or $course =='MCA')
		{
			$sql_final_marks = "SELECT DISTINCT Roll_No 
								FROM student_main 
								WHERE Course='".$course."' 
								AND Batch='".$batch."' 
								ORDER BY student_main.Roll_No ASC";
		}
		else
		{
			$sql_final_marks = "SELECT DISTINCT Roll_No 
								FROM student_main 
								WHERE Branch='".$branch."' 
								AND Course='".$course."' 
								AND Batch='".$batch."' 
								ORDER BY student_main.Roll_No ASC";
		}
		$result_final_marks = mysql_query($sql_final_marks);
		$row_num = mysql_num_rows($result_final_marks);
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='upload_marks' action='info.php?mode=upload_marks' method='post'>";
		$i = 0;
		$j = 1;
		while($row = mysql_fetch_array($result_final_marks))
		{
			echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row[0]."' /></td>";
			echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' /></td>";
			echo "<td>Max Marks</td><td><input type='text' name='Max_Marks' /></td>";
			echo "<td>Obtained Marks</td><td><input type='text' name='mo".$i."'/></td>";
			echo "<td>Backlog</td><td><input type='checkbox' name='rp".$i."' value='Yes'/></td></tr>";
			$i +=1;
			$j +=1;
		}
		echo "<input type='hidden' name='insert_sessional_final' value='insert_sessional_final' />";
		echo "</tr><td><input type='hidden' name='num_row' value='".$row_num."'/></td></td>";
		echo "</tr><td><input type='submit' value='Upload'/>";
		echo "</form></table>";
	}
	
}

/* This function is used to display the Sessional and Final Marks to student */
function student_course_record($markstype,$course,$batch,$branch,$semester)
{
	$form = new student_form();
	$table_columns=get_tables_cols();
	if($markstype=='Sessional_Marks' or $markstype=='Internal_Marks' or $markstype=='External_Marks')
	{
		if($course=='MBA' or $course=='MCA')
		{
			$sql_fetch_subjects = "SELECT DISTINCT Subject_Name 
								  FROM student_subjects 
								  WHERE Subject_Course='".$course."' 
								  AND Subject_Semester = '".$semester."'";
		} 
		else
		{
			$sql_fetch_subjects = "SELECT DISTINCT Subject_Name 
								  FROM student_subjects 
								  WHERE Subject_Course='".$course."'
								  AND Subject_Branch='".$branch."' 
								  AND Subject_Semester = '".$semester."'";
		}
		$result_fetch_subject = mysql_query($sql_fetch_subjects);
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=sessional_marks' method='post'>";
	
		echo "<tr><td>Subject</td><td><select name='Subject'>";
		while($rows = mysql_fetch_array($result_fetch_subject))
		{
			echo "<option value='".$rows[0]."'>".$rows[0]."</option>";
		}
		echo "</select></tr></td>";
		if($markstype=='Sessional_Marks')
		{
			$form->form_dropdown_field('dropdown',array(1,2,3),'Sessional_No','','','required','','');
		}
		
		echo "<input type='hidden' name='Course' value='".$course."' />";
		echo "<input type='hidden' name='Semester' value='".$semester."' />";
		echo "<input type='hidden' name='Branch' value='".$branch."' />";
		echo "<input type='hidden' name='Marks_Type' value='".$markstype."' />";
		echo "<input type='hidden' name='Insert' value='Insert' />";
		echo "<tr><td><input type='submit' value='submit'></tr></td>";
		echo "</form></table>";

			
	}
	
	if($markstype=='Semester_Final_Marks')
	{
		$sql_get_marks = "SELECT * 
						 FROM student_course_record 
						 WHERE Roll_No='".$_SESSION['rollno']."' 
						 AND Semester='".$_POST['Semester']."'";
		$sql_get_bl = "SELECT Backlog 
					  FROM student_course_record 
					  WHERE Roll_No='".$_SESSION['rollno']."' 
					  AND Semester='".$_POST['Semester']."'";
		$result_get_marks = mysql_query($sql_get_marks);
		$row_num = mysql_num_rows($result_get_marks);
		if($row_num!=0)
		{
			echo "<table id='test' align='center' width='70%'>";
			$bl = mysql_fetch_assoc(mysql_query($sql_get_bl));
			if($bl['Backlog']=='Yes')
			{
				echo "<tr><th>Roll No</th><th>Semester</th><th>Result</th></tr>";
			}
			else
			{
				echo "<tr><th>Roll No</th><th>Semester</th><th>Max Marks</th><th>Obtained Marks</th></tr>";
			}
			echo "<tr>";
			while($row=mysql_fetch_assoc($result_get_marks)) {
				if($row['Backlog']=='Yes')
				{
					echo "<td>".$row['Roll_No']."</td>";
					echo "<td>".$row['Semester']."</td>";
					echo "<td>Backlog</td>";
				}
				else
				{
					echo "<td>".$row['Roll_No']."</td>";
					echo "<td>".$row['Semester']."</td>";
					echo "<td>".$row['Max_Marks']."</td>";
					echo "<td>".$row['Obtained_Marks']."</td>";
				}
			}
			echo "</tr></table>";
		}
		else
		{
			echo "<p>No Record Found</p>";
		}
	}
}

function assignment_alert($course,$batch,$branch,$assignmentno,$teacher,$subject,$date)
{
	if($course=='MBA' or $course=='MCA')
	{
		$sql_fetch_roll ="SELECT Roll_No 
						 FROM student_main 
						 WHERE Course='".$course."' 
						 AND Batch = '".$batch."'";
		$result_fetch_roll = mysql_query($sql_fetch_roll);
		while($row = mysql_fetch_array($result_fetch_roll))
		{
			$rollarry[]=$row[0];
		}
		$num_roll = count($rollarry);
		for($r=0;$r<=$num_roll-1;$r++)
		{
			$sql_fetch_mob ="SELECT Mobile 
							FROM student_detail 
							WHERE Roll_No='".$rollarry[$r]."'";
			$result_fetch_mob = mysql_query($sql_fetch_mob);
			while($row = mysql_fetch_array($result_fetch_mob))
			{
				$mobarry[]=$row[0];
			}
		}
		$num_mob=count($mobarry);
		$msgdata = "Assignment No. " .$assignmentno."\nSubject: ".$subject." \nDue Date: ".$date." \nTeacher: ".$teacher."";
		require('config.php');
		$conn = mysql_connect($db_hostname,$db_username,$db_password);
		
		mysql_select_db("adbook",$conn);
		for($m=0;$m<=$num_mob-1;$m++)
		{
			if($mobarry[$m]=='')
			{
				continue;
			}
			else
			{
				$mob_hyphen = str_replace('-','',$mobarry[$m]);
				mysql_query("INSERT INTO send_sms 
						   (sender,receiver,msgdata) 
							VALUES ('GNDEC ERP','".$mob_hyphen."','".$msgdata."')");
			}
		}
		mysql_close($conn);
	}
	else
	{
		$sql_fetch_roll ="SELECT Roll_No 
						 FROM student_main 
						 WHERE Course='".$course."' 
						 AND Batch = '".$batch."' 
						 AND Branch='".$branch."'";
		$result_fetch_roll = mysql_query($sql_fetch_roll);
		while($row = mysql_fetch_array($result_fetch_roll))
		{
			$rollarry[]=$row[0];
		}
		$num_roll = count($rollarry);
		for($r=0;$r<=$num_roll-1;$r++)
		{
			$sql_fetch_mob ="SELECT Mobile 
							FROM student_detail 
							WHERE Roll_No='".$rollarry[$r]."'";
			$result_fetch_mob = mysql_query($sql_fetch_mob);
			while($row = mysql_fetch_array($result_fetch_mob))
			{
				$mobarry[]=$row[0];
			}
		}
		$num_mob=count($mobarry);
		$msgdata = "Assignment No. " .$assignmentno."\nSubject: ".$subject." \nDue Date: ".$date." \nTeacher: ".$teacher."";
		require('config.php');
		$conn = mysql_connect($db_hostname,$db_username,$db_password);
		
		mysql_select_db("adbook",$conn);
		for($m=0;$m<=$num_mob-1;$m++)
		{
			if($mobarry[$m]=='')
			{
				continue;
			}
			else
			{
				$mob_hyphen = str_replace('-','',$mobarry[$m]);
				mysql_query("INSERT INTO 
							send_sms 
							(sender,receiver,msgdata) 
							VALUES ('GNDEC ERP','".$mob_hyphen."','".$msgdata."')");
			}
		}
		mysql_close($conn);
	}

}

function attendence_alert($rollno,$startdate,$enddate,$total,$attended,$subject,$alert_low_attendence,$subject_code)
{
	$sql_fetch_mob ="SELECT Mobile 
					FROM student_detail 
					WHERE Roll_No='".$rollno."'";
	$mob = mysql_fetch_array(mysql_query($sql_fetch_mob));
	if($alert_low_attendence=='Yes') {
		$perct = ($attended/$total)*100;
		if($perct<75) {
			$msgdata = "Attendance For ".$subject_code."\nFrom: ".$startdate."\nTo: ".$enddate."\nYou Attended ".$attended." Lectures Out Of ".$total."\nWarning! Attendence Less Than 75%";
		}
		else {
			$msgdata = "Attendance For ".$subject_code."\nFrom: ".$startdate."\nTo: ".$enddate."\nYou Attended ".$attended." Lectures Out Of ".$total."";
		}
	}
	else{
		$msgdata = "Attendance For ".$subject_code."\nFrom: ".$startdate."\nTo: ".$enddate."\nYou Attended ".$attended." Lectures Out Of ".$total."";
	}
			
	//$msgdata = "Attendance For\nSubject: ".$subject."\nFrom: ".$startdate."\nTo: ".$enddate."\nTotal Lectures: ".$total."\nYou Attended: ".$attended."";
	require('config.php');
	$s_comm = mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db("adbook",$s_comm);
	if($mob[0]=='')
	{
		mysql_close($s_comm);
	}
	else{
		$mob_hyphen = str_replace('-','',$mob[0]);
	mysql_query("INSERT INTO send_sms 
			   (sender,receiver,msgdata) 
			   VALUES ('GNDEC ERP','".$mob_hyphen."','".$msgdata."')");
	mysql_close($s_comm);
}
}

function sessional_alert($rollno,$subject,$sessionalno,$maxmarks,$obtainedmarks)
{
	$sql_fetch_mob ="SELECT Mobile 
					FROM student_detail 
					WHERE Roll_No='".$rollno."'";
	$mob = mysql_fetch_array(mysql_query($sql_fetch_mob));
	$msgdata = "Sessional Marks For\nSubject: ".$subject."\nSessional No: ".$sessionalno."\nMax Marks: ".$maxmarks."\nYou Scored: ".$obtainedmarks."";
	require('config.php');
	$s_comm = mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db("adbook",$s_comm);
	if($mob[0]=='')
	{
		mysql_close($s_comm);
	}
	else{
		$mob_hyphen = str_replace('-','',$mob[0]);
	mysql_query("INSERT INTO send_sms 
			   (sender,receiver,msgdata) 
			   VALUES ('GNDEC ERP','".$mob_hyphen."','".$msgdata."')");
	mysql_close($s_comm);
}
}



function internal_external_marks_alert($markstype,$rollno,$subject,$maxmarks,$obtainedmarks,$detained_reappear)
{
	$sql_fetch_mob ="SELECT Mobile 
					 FROM student_detail 
					 WHERE Roll_No='".$rollno."'";
	$mob = mysql_fetch_array(mysql_query($sql_fetch_mob));
	if($markstype=='Internal_Marks')
	{
		if($detained_reappear=='Yes')
		{
			$msgdata = "Internal Marks For\nSubject: ".$subject."\nYour Result: Detained";
		}
		else
		{
			$msgdata = "Internal Marks For\nSubject: ".$subject."\nMax Marks: ".$maxmarks."\nYou Scored: ".$obtainedmarks."";
		}
	}
	if($markstype=='External_Marks')
	{
		if($detained_reappear=='Yes')
		{
			$msgdata = "External Marks For\nSubject: ".$subject."\nMax Marks: ".$maxmarks."\nYou Scored: ".$obtainedmarks."";
		}
		else
		{
			$msgdata = "External Marks For\nSubject: ".$subject."\nMax Marks: ".$maxmarks."\nYou Scored: ".$obtainedmarks."";
		}
	}
	require('config.php');
	$s_comm = mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db("adbook",$s_comm);
	if($mob[0]=='')
	{
		mysql_close($s_comm);
	}
	else{
		$mob_hyphen = str_replace('-','',$mob[0]);
		//$mob_f = str_replace(' ','',$mob_hyphen);
	mysql_query("INSERT INTO send_sms 
			   (sender,receiver,msgdata) 
			   VALUES ('GNDEC ERP','".$mob_hyphen."','".$msgdata."')");
	mysql_close($s_comm);
}
}

function add_user_form()
{
		$form = new student_form();
		echo "<p id='introduction'><table align='center'><tr><td><p>Please Fill The details below to Add New Student To The Database.</p></td></tr></table>";
		echo "<form id='add_user' action='insert_user.php' method='post' enctype='multipart/form-data'>";
		echo "<table align='center' id='student_details'>";
		echo "<tr><td>Admission Type</td><td><select name='Admission_Type' id='Admission_Type' class='required' onchange='disappear_ad_no()'>";
		echo "<option value='PTU Councelling' selected='selected'>PTU Councelling</option>";
		echo "<option value='Direct Admission'>Direct Admission</option>";
		echo "<option value='Leet'>Leet</option>";
		echo "<option value='Economically Weaker Section'>Economically Weaker Section</option>";
		echo "</select></tr></td>";
		echo "<script type='text/javascript'>admission_no()</script>";
		echo "<tr><td><div id='ad_no_name'>Admission No.</div></td><td><div id='ad_no'></div></td></tr>";
		$form->form_dropdown_field('dropdown',array('15','85'),'15_85_Quota','','','required','Course','85');
		$form->form_dropdown_field('dropdown',array('B.Tech','M.Tech','MBA','MCA'),'Course','','','required','Course','B.Tech');
		$form->form_dropdown_field('dropdown','',$form->Branch,'student_main','Branch','','Branch','Information Technology');
		echo "<tr><td>Batch</td><td><input type='text' name='Batch' class='required' id='batch'>(e.g 2007,2008,2009,2010)</td></tr>";
		$form->form_text_field('text',$form->Roll_No,'required');
		$form->form_text_field('text',$form->Univ_Roll_No,'');
		$form->form_text_field('text','Date_Of_Admission','required','DOA','');
		$form->form_text_field('text','Date_Of_Joining','','DOJ','');
		$form->form_text_field('text','CET_Rank','','','');
		$form->form_text_field('text','AIEEE_Rank','','','');
		$form->form_dropdown_field('dropdown',array('Miss','Mr.','Ms.','Mrs.','Dr.'),'Title','','','required','','Mr.');
		echo "<tr><td>Upload Image</td><td><input type='file' name='Image_Path' id='Image_Path' /></td></tr>";
		$form->form_text_field('text',$form->Student_First_Name,'required','default');
		$form->form_text_field('text',$form->Student_Middle_Name,'');
		$form->form_text_field('text',$form->Student_Last_Name,'required');
		$form->form_text_field('text',$form->Father_First_Name,'required');
		$form->form_text_field('text',$form->Father_Middle_Name,'');
		$form->form_text_field('text',$form->Father_Last_Name,'required');
		$form->form_text_field('text',$form->Father_Occupation,'');
		$form->form_text_field('text',$form->Mother_First_Name,'required');
		$form->form_text_field('text',$form->Mother_Middle_Name,'');
		$form->form_text_field('text',$form->Mother_Last_Name,'required');
		$form->form_text_field('text',$form->Mother_Occupation,'');
		$form->form_dropdown_field('dropdown','',$form->Gender,'student_main','Gender','required','','Male');
		$form->form_text_field('text',$form->DOB,'required','dob');
		$form->form_dropdown_field('dropdown',array('Hindu','Muslim','Christian','Sikh','Buddhist','Jain','Other'),'Religion','','','required','','Sikh');
		$form->form_dropdown_field('dropdown',array('Rural','Urban'),'Rural_Or_Urban','','','required','','Rural');
		$form->form_dropdown_field('dropdown',array('Sikh Minority','Open Quota'),'Student_Category','','','required','','Open Quota');
		$form->form_dropdown_field('dropdown',array('Sikh Minority','Open Quota'),'Alloted_Category','','','required','','Open Quota');
		$form->form_dropdown_field('dropdown',array('SC','BC','Border Area','Backward Area','Sports Person','Freedom Fighter','Disabled Person','Defence','Paramilitary','Terrorist Victim','General'),'Student_Sub_Category','','','required','','General');
		$form->form_dropdown_field('dropdown',array('SC','BC','Border Area','Backward Area','Sports Person','Freedom Fighter','Disabled Person','Defence','Paramilitary','Terrorist Victim','General'),'Alloted_Sub_Category','','','required','','General');
		$form->form_dropdown_field('dropdown','',$form->Blood_Group,'student_detail','Blood_Group','required','','A+ve');
		$form->form_dropdown_field('dropdown',array('Yes','No'),$form->Hostler,'','','required','','No');	
		echo "<tr><td>Height (In Centimeters)</td><td><input type='text' name='Height' /></td></tr>";
		echo "<tr><td>Weight (In Kilograms)</td><td><input type='text' name='Weight'  /></td></tr>";
		$form->form_text_field('text','Resi_Phone','required','','');
		$form->form_text_field('text',$form->Mobile,'required','','+91');
		$form->form_text_field('text',$form->Parent_Phone,'required');
		$form->form_text_field('text',$form->Email,'required email');
		$form->form_text_field('text',$form->Alt_Email,'email');
		$form->form_text_field('text','Parent_Email','');
		$form->form_text_field('text',$form->Address_Line1,'required');
		$form->form_text_field('text',$form->Address_Line2,'');
		$form->form_text_field('text',$form->_10th_Passing_Year,'required');
		$form->form_text_field('text',$form->_10th_Max_Marks,'required');
		$form->form_text_field('text',$form->_10th_Obtained_Marks,'required');
		$form->form_text_field('text',$form->_10th_School_Name,'required','');
		$form->form_dropdown_field('dropdown','',$form->_10th_Board,'school_board','Board_Name','required','','Andhra Pradesh Board of Secondary Education');
        echo "<tr><td><b>Only For Non-LEET Students</b></td></tr>";
		$form->form_text_field('text',$form->_12th_Passing_Year,'');
		$form->form_text_field('text',$form->_12th_Max_Marks,'');
		$form->form_text_field('text',$form->_12th_Obtained_Marks,'');
		$form->form_text_field('text',$form->_12th_School_Name,'','');
		$form->form_dropdown_field('dropdown','',$form->_12th_Board,'school_board','Board_Name','','','Andhra Pradesh Board of Secondary Education');
        echo "<tr><td><b>Only For LEET Students</td></b></tr>";
		$form->form_text_field('text',$form->Diploma_Passing_Year,'');
		$form->form_text_field('text',$form->Diploma_Max_Marks,'');
		$form->form_text_field('text',$form->Diploma_Obtained_Marks,'');
		$form->form_text_field('text',$form->Diploma_University,'');
		$form->form_text_field('text',$form->Diploma_College,'');
		echo "<tr><td>Kashmiri Migrant ?</td><td>Yes<input type='radio' name='Kashmiri_Migrant' value='Yes' />No<input type='radio' name='Kashmiri_Migrant' value='No' / checked='checked'></td></tr>";
		$sql_state = "SELECT State_Name FROM state ORDER BY State_Name ASC";
		$result_state = mysql_query($sql_state);
		echo "<tr><td>State/Union Territory</td><td><select name='State' id='State' onchange='changedist(this.value)'>";
		while($row_state = mysql_fetch_array($result_state)){
			echo "<option value='".$row_state[0]."'>".$row_state[0]."</option>";
		}
		echo "</select></tr></td>";
		echo "<script type='text/javascript'>setstate()</script>";
		echo "<tr><td><div id='dist_name'>District</div></td><td><div id='dist'></div></tr></td>";
		//$form->form_dropdown_field('dropdown','',$form->State,'state','State_Name','required');
		//$form->form_dropdown_field('dropdown','','District','district','District_Name','required');
		$form->form_text_field('text',$form->City,'required');
		$form->form_text_field('text',$form->Pincode,'required');
		echo "<tr><td><input type='submit' name='submit' value='submit' onclick='return confirm_add()'></td></tr>
		</table><br />";
		echo "</form>";
}



function edit_user_form($student_main,$student_detail,$student_previous_record,$student_address,$student_admission_detail,$student_images)
{
		$form = new student_form();
		echo "<form id='add_user' action='edit_user.php' onsubmit='return checkbranch()' method='post' enctype='multipart/form-data'>";
		echo "<table align='center' id='student_details'>";
		echo "<input type='hidden' name='ad_no_hidden' id='ad_no_hidden' value='".$student_admission_detail['Admission_No']."' />";
		echo "<tr><td>Admission Type</td><td><select name='Admission_Type' id='Admission_Type' class='required' onchange='disappear_ad_no_edit()'>";
		echo "<option value='".$student_admission_detail['Admission_Type']."' selected='selected'>".$student_admission_detail['Admission_Type']."</option>";
		echo "<option value='PTU Councelling'>PTU Councelling</option>";
		echo "<option value='Direct Admission'>Direct Admission</option>";
		echo "<option value='Economically Weaker Section'>Economically Weaker Section</option>";
		echo "</select></tr></td>";
		echo "<tr><td><div id='ad_no_name'>Admission No.</div></td><td><div id='ad_no'></div></td></tr>";
		if($student_admission_detail['Admission_Type']=='PTU Councelling'){
			echo "<script type='text/javascript'>admission_no_edit(\"".$student_admission_detail['Admission_No']."\")</script>";
		}
		else {
			echo "<script type='text/javascript'>disappear_ad_no_edit()</script>";
		}
		$form->form_dropdown_field('dropdown',array('15','85'),'15_85_Quota','','','required','Course',$student_admission_detail['15_85_Quota']);
		$form->form_dropdown_field('dropdown',array('B.Tech','M.Tech','MBA','MCA'),'Course','','','required','Course',$student_main['Course']);
		$form->form_dropdown_field('dropdown','',$form->Branch,'student_main','Branch','','Branch',$student_main['Branch']);
		echo "<tr><td>Batch</td><td><input type='text' name='Batch' class='required' id='batch' value='".$student_main['Batch']."'>(e.g 2007,2008,2009,2010)</td></tr>";
		$form->form_text_field('text',$form->Roll_No,'required','',$student_main['Roll_No']);
		$form->form_text_field('text',$form->Univ_Roll_No,'','',$student_main['Univ_Roll_No']);
		$form->form_text_field('text','Date_Of_Admission','required','DOA',$student_admission_detail['Date_Of_Admission']);
		$form->form_text_field('text','Date_Of_Joining','','DOJ',$student_admission_detail['Date_Of_Joining']);
		$form->form_text_field('text','CET_Rank','','',$student_admission_detail['CET_Rank']);
		$form->form_text_field('text','AIEEE_Rank','','',$student_admission_detail['AIEEE_Rank']);
		$form->form_dropdown_field('dropdown',array('Miss','Mr.','Ms.','Mrs.','Dr.'),'Title','','','required','',$student_main['Title']);
		echo "<tr><td>Upload Image</td><td><input type='file' name='Image_Path' id='Image_Path' /></td></tr>";
		echo "<input type='hidden' name='Default_Image_Path' id='Default_Image_Path' value='".$student_images['Image_Path']."' /></td></tr>";
		$form->form_text_field('text',$form->Student_First_Name,'required','default',$student_main['Student_First_Name']);
		$form->form_text_field('text',$form->Student_Middle_Name,'','',$student_main['Student_Middle_Name']);
		$form->form_text_field('text',$form->Student_Last_Name,'required','',$student_main['Student_Last_Name']);
		$form->form_text_field('text',$form->Roll_No,'required','',$student_main['Roll_No']);
		$form->form_text_field('text',$form->Univ_Roll_No,'','',$student_main['Univ_Roll_No']);
		$form->form_dropdown_field('dropdown','',$form->Gender,'student_main','Gender','','',$student_main['Gender']);
		echo "<tr><td>Batch</td><td><input type='text' name='Batch' value='".$student_main['Batch']."' class='required' id='batch'></td></tr>";
		$form->form_text_field('text',$form->Father_First_Name,'required','',$student_detail['Father_First_Name']);
		$form->form_text_field('text',$form->Father_Middle_Name,'','',$student_detail['Father_Middle_Name']);
		$form->form_text_field('text',$form->Father_Last_Name,'required','',$student_detail['Father_Last_Name']);
		$form->form_text_field('text',$form->Father_Occupation,'','',$student_detail['Father_Occupation']);
		$form->form_text_field('text',$form->Mother_First_Name,'required','',$student_detail['Mother_First_Name']);
		$form->form_text_field('text',$form->Mother_Middle_Name,'','',$student_detail['Mother_Middle_Name']);
		$form->form_text_field('text',$form->Mother_Last_Name,'required','',$student_detail['Mother_Last_Name']);
		$form->form_text_field('text',$form->Mother_Occupation,'','',$student_detail['Mother_Occupation']);
		$form->form_text_field('text',$form->DOB,'required','dob',$student_detail['DOB']);
		$form->form_dropdown_field('dropdown',array('Hindu','Muslim','Christian','Sikh','Buddhist','Jain','Other'),'Religion','','','required','',$student_detail['Religion']);
		$form->form_dropdown_field('dropdown',array('Rural','Urban'),'Rural_Or_Urban','','','required','',$student_detail['Rural_Or_Urban']);
		$form->form_dropdown_field('dropdown',array('Sikh Minority','Open Quota'),'Student_Category','','','required','',$student_detail['Student_Category']);
		$form->form_dropdown_field('dropdown',array('Sikh Minority','Open Quota'),'Alloted_Category','','','required','',$student_detail['Alloted_Category']);
		$form->form_dropdown_field('dropdown',array('SC','BC','Border Area','Backward Area','Sports Person','Freedom Fighter','Disabled Person','Defence','Paramilitary','Terrorist Victim','General'),'Student_Sub_Category','','','required','',$student_detail['Student_Sub_Category']);
		$form->form_dropdown_field('dropdown',array('SC','BC','Border Area','Backward Area','Sports Person','Freedom Fighter','Disabled Person','Defence','Paramilitary','Terrorist Victim','General'),'Alloted_Sub_Category','','','required','',$student_detail['Alloted_Sub_Category']);
		$form->form_dropdown_field('dropdown','',$form->Blood_Group,'student_detail','Blood_Group','','',$student_detail['Blood_Group']);
		$form->form_dropdown_field('dropdown',array('Yes','No'),$form->Hostler,'','','','',$student_detail['Hostler']);	
		echo "<tr><td>Height (In Centimeters)</td><td><input type='text' name='Height' value='".$student_detail['Height']."' /></td></tr>";
		echo "<tr><td>Weight (In Kilograms)</td><td><input type='text' name='Weight' value='".$student_detail['Weight']."' /></td></tr>";
		$form->form_text_field('text','Resi_Phone','required','',$student_detail['Resi_Phone']);
		$form->form_text_field('text',$form->Mobile,'required','',$student_detail['Mobile']);
		$form->form_text_field('text',$form->Parent_Phone,'required','',$student_detail['Parent_Phone']);
		$form->form_text_field('text',$form->Email,'required email','',$student_detail['Email']);
		$form->form_text_field('text',$form->Alt_Email,'','',$student_detail['Alt_Email']);
		$form->form_text_field('text','Parent_Email','','',$student_detail['Parent_Email']);
		$form->form_text_field('text',$form->Address_Line1,'required','',$student_address['Address_Line1']);
		$form->form_text_field('text',$form->Address_Line2,'required','',$student_address['Address_Line2']);
		$form->form_text_field('text',$form->_10th_Passing_Year,'required','',$student_previous_record['10th_Passing_Year']);
		$form->form_text_field('text',$form->_10th_Max_Marks,'required','',$student_previous_record['10th_Max_Marks']);
		$form->form_text_field('text',$form->_10th_Obtained_Marks,'required','',$student_previous_record['10th_Obtained_Marks']);
		$form->form_text_field('text',$form->_10th_School_Name,'','',$student_previous_record['10th_School_Name']);
		$form->form_dropdown_field('dropdown','',$form->_10th_Board,'school_board','Board_Name','required','',$student_previous_record['10th_Board']);
        echo "<tr><td><b>Only For Non-LEET Students</b></td></tr>";
		$form->form_text_field('text',$form->_12th_Passing_Year,'','',$student_previous_record['12th_Passing_Year']);
		$form->form_text_field('text',$form->_12th_Max_Marks,'','',$student_previous_record['12th_Max_Marks']);
		$form->form_text_field('text',$form->_12th_Obtained_Marks,'','',$student_previous_record['12th_Obtained_Marks']);
		$form->form_text_field('text',$form->_12th_School_Name,'','',$student_previous_record['12th_School_Name']);
		$form->form_dropdown_field('dropdown','',$form->_12th_Board,'school_board','Board_Name','','',$student_previous_record['12th_Board']);
        echo "<tr><td><b>Only For LEET Students</b></td></tr>";
		$form->form_text_field('text',$form->Diploma_Passing_Year,'','',$student_previous_record['Diploma_Passing_Year']);
		$form->form_text_field('text',$form->Diploma_Max_Marks,'','',$student_previous_record['Diploma_Max_Marks']);
		$form->form_text_field('text',$form->Diploma_Obtained_Marks,'','',$student_previous_record['Diploma_Obtained_Marks']);
		$form->form_text_field('text',$form->Diploma_University,'','',$student_previous_record['Diploma_University']);
		$form->form_text_field('text',$form->Diploma_College,'','',$student_previous_record['Diploma_College']);
		$sql_state = "SELECT State_Name FROM state";
		$result_state = mysql_query($sql_state);
		echo "<tr><td>State/Union Territory</td><td><select name='State' id='State' onchange='changedist(this.value)'>";
		echo "<option value='".$student_address['State']."' selected='selected'>".$student_address['State']."</option>";
		while($row_state = mysql_fetch_array($result_state)){
			echo "<option value='".$row_state[0]."'>".$row_state[0]."</option>";
		}
		echo "</select></tr></td>";
		echo "<script type='text/javascript'>setstate(\"".$student_address['District']."\")</script>";
		echo "<tr><td><div id='dist_name'>District</div></td><td><div id='dist'></div></tr></td>";
		
		//$form->form_dropdown_field('dropdown','',$form->State,'state','State_Name','required','',$student_address['State']);
		//$form->form_dropdown_field('dropdown','','District','district','District_Name','required','',$student_address['District']);
		$form->form_text_field('text',$form->City,'required','',$student_address['City']);
		$form->form_text_field('text',$form->Pincode,'required','',$student_address['Pincode']);
		echo "<tr><td><input type='submit' name='submit' onclick='return confirm_edit(\"".$student_main['Roll_No']."\")' value='submit'></td></tr>
		</table><br />";
		echo "</form>";
}

function get_report_form($course,$batch,$branch)
{
	echo "<p id='introduction'><table align='center'><tr><td><p>Please The Select Fields You Wish To Include In Your Report.</p></td></tr></table>";
		$table_columns = get_tables_cols();
		$student_main = count($table_columns['student_main']);
		$student_detail=count($table_columns['student_detail']);
		$student_previous_record=count($table_columns['student_previous_record']);
		$student_admission_detail=count($table_columns['student_admission_detail']);
		$student_address=count($table_columns['student_address']);
		echo "<table width='100%' id='get_report'>";
		echo "<form action='info.php?mode=get_report' method='post'>";
		echo "<tr><td><table><caption>Student Abstract Information</caption>";
		for($i=0;$i<=$student_main-1;$i++)
		{
			echo "<tr><td><input type='checkbox' name='".$table_columns['student_main'][$i]."' 
				  value='".$table_columns['student_main'][$i]."'>".str_replace('_',' ',$table_columns['student_main'][$i]).
				  "</checkbox></td></tr>";
			
		}
		echo "</table></td><td><table><caption>Student Detailed Information</caption>";
		for($j=0;$j<=$student_detail-1;$j++)
		{
			if($table_columns['student_detail'][$j]=='Roll_No' 
			   or $table_columns['student_detail'][$j]=='Department' 
			   or $table_columns['student_detail'][$j]=='Photo')
			{
				continue;
			}
			echo "<tr><td><input type='checkbox' name='".$table_columns['student_detail'][$j]."' 
				  value='".$table_columns['student_detail'][$j]."'>".str_replace('_',' ',$table_columns['student_detail'][$j]).
				  "</checkbox></tr></td>";
		}
		echo "</table></td>";
		echo"<td><table><caption>Student Address</caption>";
		for($m=0;$m<=$student_address-1;$m++)
		{
			if($table_columns['student_address'][$m]=='Roll_No')
			{
				continue;
			}
			echo "<tr><td><input type='checkbox' name='".$table_columns['student_address'][$m]."' 
				 value='".$table_columns['student_address'][$m]."'>".str_replace('_',' ',$table_columns['student_address'][$m]).
				 "</checkbox></td></tr>";
		}
		echo "</table></td>";
		
		echo"<td><table><caption>Student Previous Academic Record</caption>";
		for($m=0;$m<=$student_previous_record-1;$m++)
		{
			if($table_columns['student_previous_record'][$m]=='Roll_No')
			{
				continue;
			}
			echo "<tr><td><input type='checkbox' name='".$table_columns['student_previous_record'][$m]."' 
				 value='".$table_columns['student_previous_record'][$m]."'>".str_replace('_',' ',$table_columns['student_previous_record'][$m]).
				 "</checkbox></td></tr>";
		}
		echo "</table></td>";
		
		echo"<td><table><caption>Student Admission Detail</caption>";
		for($m=0;$m<=$student_admission_detail-1;$m++)
		{
			if($table_columns['student_admission_detail'][$m]=='Roll_No')
			{
				continue;
			}
			echo "<tr><td><input type='checkbox' name='".$table_columns['student_admission_detail'][$m]."' 
				 value='".$table_columns['student_admission_detail'][$m]."'>".str_replace('_',' ',$table_columns['student_admission_detail'][$m]).
				 "</checkbox></td></tr>";
		}
		echo "</table></td>";
		
		echo "<input type='hidden' name='get_details' value='get_details'/>";
		echo "<input type='hidden' name='Course' value='".$course."'/>";
		echo "<input type='hidden' name='Batch' value='".$batch."'/>";
		echo "<input type='hidden' name='Branch' value='".$branch."'/>";
		echo "<input type='hidden' name='student_main' value='".$student_main."'/>";
		echo "<input type='hidden' name='student_detail' value='".$student_detail."'/>";
		echo "<input type='hidden' name='student_address' value='".$student_address."'/>";
		echo "<tr><td><input type='submit' value='submit'/></tr></td>";
		echo "</form></table>";
}

function add_admin($username,$fullname,$password,$usertype,$department,$mobile,$email)
{
	echo $mobile;
	echo $email;
	echo $department;
	$pass = md5($password);
	if($usertype=='Admin') {
		$sql = "SELECT User_Type,Department FROM users WHERE User_Type='Admin' AND Department='".$department."'";
		$num = mysql_num_rows(mysql_query($sql));
		if($num>0)
		{
			echo "<p style='color:red'>Error: Admin Already Exists for ".$department.", Only 1 Admin allowed Per Department.</p>";
			form("add_other");
		}
		else {
			$sql = "INSERT INTO users 
			   (Username,Full_Name,Password,User_Type,Department,Mobile,Email) 
			   VALUES ('".$username."','".$fullname."','".$pass."','".$usertype."','".$department."','".$mobile."','".$email."')";
			mysql_query($sql)or die(mysql_error());
		echo "<p>User '".$fullname."' Successfully Added</p>";
		}
	}
	elseif($usertype!='Admin') {
	$sql = "SELECT Username, User_Type FROM users WHERE Username='".$username."' AND User_Type='".$usertype."'";
	$num = mysql_num_rows(mysql_query($sql));
		if($num>0)
		{
			echo "<p style='color:red'>Error: User With Username ".$username."  Already Exists, Please use Different Username and Try Again</p>";
			form("add_other");
		}
		else {
			$sql = "INSERT INTO users 
			   (Username,Full_Name,Password,User_Type,Department,Mobile,Email) 
			   VALUES ('".$username."','".$fullname."','".$pass."','".$usertype."','".$department."','".$mobile."','".$email."')";
			mysql_query($sql)or die(mysql_error());
		echo "<p>User '".$fullname."' Successfully Added</p>";
		}
	}
}

function change_password_student($newpassword,$usertype,$rollno)
{
	$sql = "UPDATE users 
			SET Password='".md5($newpassword)."' 
			WHERE Roll_No='".$rollno."' 
			AND User_Type='".$usertype."'";
	mysql_query($sql);
	session_destroy();
	header("location:index.php?mode=login_updated");
	
}


function change_password_admins($newpassword,$usertype,$username)
{
	$sql = "UPDATE users 
			SET Password='".md5($newpassword)."' 
			WHERE Username='".$username."' 
			AND User_Type='".$usertype."'";
	mysql_query($sql);
	session_destroy();
	header("location:index.php?mode=login_updated");
}

function edit_login_student($rollno)
{
	$form = new student_form();
	$sql = "SELECT Roll_No FROM users WHERE Roll_No='".$rollno."'";
	$result = mysql_query($sql);
	$num = mysql_num_rows($result);
	if($num==1)
	{
		$row = mysql_fetch_assoc($result);
		echo "<table id='student_details' align='center'>";
		echo "<tr><td><b>Update Student</b></td></tr></table>";
		echo "<table id='student_details' align='center'>";
		echo "<form id='add_admin' action='info.php?mode=edit_login' onsubmit='return checkpassword()' method='post'>";
		echo "<tr><td>Roll No</td><td><input readonly type='text' name='Roll_No' value='".$rollno."'/>";
		$form->form_text_field('password','New_Password','required','New_Password','');
		$form->form_text_field('password','Confirm_Password','required','Confirm_Password','');
		echo "<tr><td><input type='hidden' name='User_Type' value='Student' />";
		echo "<tr><td><input type='hidden' name='Update_Student' value='Student' />";
		echo "<tr><td><input type='submit' value='Update' />";
		echo "</form></table>";
	}
	else
	{
		echo "<p>No Record Found For ".$rollno." </p>";
	}
}


function edit_login_admins($username,$fullname,$usertype,$department,$mobile,$email)
{
	if(isset($_POST['Delete_User'])){
		mysql_query("DELETE FROM users WHERE Username='".$_POST['Username']."' AND Full_Name='".$_POST['Full_Name']."' AND User_Type='".$_POST['User_Type']."'") or die(mysql_error());
		echo "<p>User '".$_POST['Full_Name']."' Has Been Deleted From The Records</p>";
		break;
	}
	else {
		echo "<table id='student_details' align='center'>";
		echo "<tr><td><b>Update User Details</b></td></tr></table>";
		echo "<table id='student_details' align='center'>";
		echo "<form id='add_admin' action='info.php?mode=edit_login' onsubmit='return checkpassword()' method='post'>";
		echo "<tr><td>Username(Read-Only)</td><td><input readonly type='text' name='newusername' value='".$_POST['Username']."'/>";
		if($_POST['User_Type']!='Admin' && $_POST['User_Type']!='Training And Placement' ){
		echo "<tr><td>Department</td><td><select name='Department' id='Department_Ajax' />";
		echo "<option value='".$_POST['Department']."' selecter='selected'>".$_POST['Department']."</option>;
			<option value='Information Technology'>Information Technology</option>
			<option value='Computer Science'>Computer Science</option>
			<option value='Civil Engineering'>Civil Engineering</option>
			<option value='Mechanical Engineering'>Mechanical Engineering</option>
			<option value='Electrical Engineering'>Electrical Engineering</option>
			<option value='Electronics & Communication Engineering'>Electronics & Communication Engineering</option>
			<option value='Production Engineering'>Production Engineering</option>
			<option value='MBA'>MBA</option>
			<option value='MCA'>MCA</option></tr></td></select>";
		}
		echo "<tr><td>Full Name</td><td><input type='text' name='newfullname' value='".$_POST['Full_Name']."'/>";
		echo "<tr><td>Password</td><td><input type='password' name='New_Password' id='New_Password' class='required' />";
		echo "<tr><td>Confirm Password</td><td><input type='password' name='Confirm_Password' id='Confirm_Password' class='required' />";
		echo "<tr><td>Mobile</td><td><input type='text' name='Mobile' value='".$mobile."' />";
		echo "<tr><td>Email</td><td><input type='text' name='Email' value='".$email."' />";
		echo "<tr><td><input type='hidden' name='usertype' value='".$usertype."' />";
		echo "<tr><td><input type='hidden' name='username' value='".$username."' />";
		echo "<tr><td><input type='hidden' name='update_admins' value='admins' />";
		echo "<tr><td><input type='submit' value='Update' />";
		echo "</form></table>";
	}
}

function student_internal_external($markstype,$rollno,$subject,$semester)
{
	if($markstype=='Internal_Marks')
	{
		$sql_get_marks = "SELECT Subject, Internal_Max_Marks, Internal_Obtained_Marks, Semester, Detained 
						FROM student_internal_marks 
						WHERE Roll_No='".$rollno."' 
						AND Subject='".$subject."'  
						AND Semester='".$semester."'";
		$sql_get_dt = "SELECT Detained 
					  FROM student_internal_marks 
					  WHERE Roll_No='".$rollno."' 
					  AND Subject='".$subject."'  
					  AND Semester='".$semester."'";

		$result_get_marks = mysql_query($sql_get_marks);
		$row_num = mysql_num_rows($result_get_marks);
		if($row_num!=0)
		{
			echo "<table id='test' align='center' width='70%'>";
			$dt=mysql_fetch_assoc(mysql_query($sql_get_dt));
			if($dt['Detained']=='Yes')
			{
				echo "<tr><th>Subject</th><th>Semester</th><th>Result</th></tr>";
			}
			else
			{
				echo "<tr><th>Subject</th><th>Semester</th><th>Max Marks</th><th>Obtained Marks</th></tr>";
			}
			echo "<tr>";
			while($row=mysql_fetch_assoc($result_get_marks)) {
				if($row['Detained']=='Yes')
				{
					echo "<td>".$row['Subject']."</td>";
					echo "<td>".$row['Semester']."</td>";
					echo "<td>Detained</td>";
				}
				else
				{
					echo "<td>".$row['Subject']."</td>";
					echo "<td>".$row['Semester']."</td>";
					echo "<td>".$row['Internal_Max_Marks']."</td>";
					echo "<td>".$row['Internal_Obtained_Marks']."</td>";
				}
			}
			echo "</tr></table>";
			}
		else
		{
			echo "<p>No Record Found</p>";
		}
	}
	
	if($markstype=='External_Marks')
	{
		$sql_get_marks = "SELECT Subject, External_Max_Marks, External_Obtained_Marks, Semester, Reappear 
						FROM student_external_marks 
						WHERE Roll_No='".$rollno."' 
						AND Subject='".$subject."'  
						AND Semester='".$semester."'";
		$sql_get_rp = "SELECT Reappear 
					  FROM student_external_marks 
					  WHERE Roll_No='".$rollno."' 
					  AND Subject='".$subject."'  
					  AND Semester='".$semester."'";
		
		$result_get_marks = mysql_query($sql_get_marks);
		$row_num = mysql_num_rows($result_get_marks);
		if($row_num!=0)
		{
			echo "<table id='test' align='center' width='70%'>";
			$rp=mysql_fetch_assoc(mysql_query($sql_get_rp));
			if($rp['Reappear']=='Yes')
			{
				echo "<tr><th>Subject</th><th>Semester</th><th>Max Marks</th><th>Obtained Marks</th><th>Result</th></tr>";
			}
			else
			{
				echo "<tr><th>Subject</th><th>Semester</th><th>Max Marks</th><th>Obtained Marks</th></tr>";
			}
			echo "<tr>";
			while($rows=mysql_fetch_assoc($result_get_marks)) {
				if($rows['Reappear']=='Yes')
				{
					echo "<td>".$rows['Subject']."</td>";
					echo "<td>".$rows['Semester']."</td>";
					echo "<td>".$rows['External_Max_Marks']."</td>";
					echo "<td>".$rows['External_Obtained_Marks']."</td>";
					echo "<td>Reappear</td>";
				}
				else
				{
					echo "<td>".$rows['Subject']."</td>";
					echo "<td>".$rows['Semester']."</td>";
					echo "<td>".$rows['External_Max_Marks']."</td>";
					echo "<td>".$rows['External_Obtained_Marks']."</td>";
				}
			}
			echo "</tr></table>";
			}
		else
		{
			echo "<p>No Record Found</p>";
		}
	}
}

function teacher_edit_marks($markstype,$semester,$subject,$sessionalno,$course,$batch,$branch)
{
	
	$table_columns = get_tables_cols();
	if($markstype=='Edit_Sessional_Marks')
	{
			$sql = "SELECT * 
				   FROM student_sessional_record 
				   WHERE Batch='".$batch."' 
				   AND Semester='".$semester."' 
				   AND Subject='".$subject."' 
				   AND Sessional_No='".$sessionalno."' 
				   AND Teacher_Username='".$_SESSION['username']."'";
			$result = mysql_query($sql);
			$num_row = mysql_num_rows($result);
			$i=0;
			$j=1;
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='edit_user.php' method='post'>";
			while($row = mysql_fetch_assoc($result)){
				echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row['Roll_No']."' /></td>";
				echo "<td>M.M</td><td><input readonly type='text' name='Max_Marks' value='".$row['Max_Marks']."' /></td>";
				echo "<td>M.O</td><td><input type='text' name='om".$i."' value='".$row['Obtained_Marks']."' /></td>";
				if($row['Absent']=='Yes'){
					echo "<td>Absent</td><td><input type='checkbox' checked='yes' name='ab".$i."' value='Yes' /></td>";
				}
				else{
					echo "<td>Absent</td><td><input type='checkbox' name='ab".$i."' value='Yes' /></td>";
				}
				echo "<input type='hidden' name='Subject' value='".$row['Subject']."' />";
				echo "<input type='hidden' name='Subject_Code' value='".$row['Subject_Code']."' />";
				$j +=1;
				$i +=1;
			}
			echo "
						<input type='hidden' name='Semester' value=".$semester." />
						<input type='hidden' name='Batch' value=".$batch." />
						<input type='hidden' name='Sessional_No' value=".$sessionalno." />
						<input type='hidden' name='num_row' value='".$num_row."'/>
						<input type='hidden' name='update_sessional_marks' value='usm' />";
			echo "<tr><td><input type='submit' value='update' /></tr></td></form></table>";
						
		}
			
}	

function edit_marks_now($markstype,$subject,$semester,$sessionalno,$rollno,$batch)
{
	$form = new student_form();
	if($markstype=='Edit_Sessional_Marks')
	{
		$sql = "SELECT * 
				FROM student_sessional_record 
				WHERE Roll_No='".$rollno."' 
				AND Subject='".$subject."' 
				AND Semester='".$semester."' 
				AND Sessional_No='".$sessionalno."' 
				AND Teacher_Username='".$_SESSION['username']."'";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		echo "<form id='add_user' action='edit_user.php' method='post'>";
		echo "<table align='center' id='student_details'>";
		$form->form_text_field('text',$form->Roll_No,'required','',$row['Roll_No']);
		$form->form_text_field('hidden',$form->Subject,'required','',$row['Subject']);
		$form->form_text_field('hidden',$form->Subject_Code,'required','',$row['Subject_Code']);
		$form->form_text_field('text',$form->Obtained_Marks,'required','',$row['Obtained_Marks']);
		$form->form_text_field('text',$form->Max_Marks,'required','',$row['Max_Marks']);
		$form->form_text_field('hidden',$form->Sessional_No,'required','',$row['Sessional_No']);
		$form->form_text_field('hidden',$form->Semester,'required','',$row['Semester']);
		$form->form_dropdown_field('dropdown',array('Yes','No'),'Absent','','','','',$row['Absent']);
		$form->form_text_field('text',$form->Teacher_Username,'required','',$row['Teacher_Username']);
		echo "<tr><td><input type='submit' value='Update'></tr></td>";
		echo "<input type='hidden' name='update_sessional_marks' value='usm' />";
		echo "</form></table>";
		
	}
}


function send_sms_admin($smstype,$course,$batch,$branch,$semester,$sessionalno)
{
	if($course=='MBA' or $course=='MCA')
	{
		$sql = "SELECT DISTINCT Roll_No 
				FROM student_main 
				WHERE Course='".$course."' 
				AND Batch='".$batch."' 
				ORDER BY student_main.Roll_No ASC";
	}
	else
	{
		$sql = "SELECT DISTINCT Roll_No 
				FROM student_main 
				WHERE Course='".$course."' 
				AND Branch='".$branch."' 
				AND Batch='".$batch."' 
				ORDER BY student_main.Roll_No ASC";
	}
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result))
	{
		$rollarray[]=$row[0];
	}
	$sql_subject = "SELECT DISTINCT Subject, Subject_Code 
					FROM student_sessional_record 
					WHERE Roll_No='".$rollarray[0]."' 
					AND Semester='".$semester."' 
					AND Sessional_No='".$sessionalno."'";
	$result_subject=mysql_query($sql_subject) or die(mysql_error());
	while($row1 = mysql_fetch_assoc($result_subject))
	{
		$subject_array[] = $row1;
	}
	echo "<table id='test' align='center'>";
	echo "<tr>";
	echo "<th>Roll No</th>";
	$num_ra = count($rollarray);
	$num_sa = count($subject_array);
	for($i=0;$i<=$num_sa-1;$i++)
	{
		echo "<th>".$subject_array[$i]['Subject']."/".$subject_array[$i]['Subject_Code']."</th>";

	}
	echo "<th>SMS</th>";
	for($i=0;$i<=$num_sa-1;$i++)
	{
		$subject_code[] = $subject_array[$i]['Subject_Code'];
	}
	$subject_code_str=implode(',',$subject_code);
	for($j=0;$j<=$num_ra-1;$j++)
	{
		echo "<tr>";
		echo "<td>".$rollarray[$j]."</td>";
		for($i=0;$i<=$num_sa-1;$i++)
		{
			$sql_obt = "SELECT Obtained_Marks, Absent 
						FROM student_sessional_record 
						WHERE Roll_No='".$rollarray[$j]."' 
						AND Subject='".$subject_array[$i]['Subject']."' 
						AND Subject_Code='".$subject_array[$i]['Subject_Code']."' 
						AND Sessional_No='".$sessionalno."'
						AND Semester='".$semester."'";
			$result_obt = mysql_query($sql_obt) or die(mysql_error());
			while($row2=mysql_fetch_assoc($result_obt))
			{
				if($row2['Absent']=='Yes')
				{
					$row2['Obtained_Marks'] = 'Absent';
				}
				if($row2['Obtained_Marks']=='')
				{
					$row2['Obtained_Marks']='Not Available';
				}
				echo "<td>".$row2['Obtained_Marks']."</td>";
			}
		}
		echo "<td><form name='smsform' action='info.php?mode=admin_send_sms' method='post'>
		<input type='checkbox' name='if_sms' value='".$rollarray[$j]."' /></td></tr>";
	}
	
	echo "</table><table><tr><td><input type='button' name='Check_All' value='Check All' onClick='CheckAll(document.smsform.if_sms)'></td>
	<td><input type='button' name='Un_CheckAll' value='Uncheck All' onClick='UnCheckAll(document.smsform.if_sms)'></td>
	<td><input type='hidden' id='99999' name='roll_nos' value='test'></td>
	<td><input type='hidden' name='SMS_Type' value='".$smstype."'></td>
	<td><input type='hidden' name='Semester' value='".$semester."'></td>
	<td><input type='hidden' name='Subject' value='".$subject_code_str."'></td>
	<td><input type='hidden' name='Sessional_No' value='".$sessionalno."'></td>
	<td><input type='hidden' name='admin_send_sms_now' value='assn'></td>";
	echo "<td><input type='submit' value='Send SMS' onClick='add_mobile(document.smsform.if_sms,99999)' /></form></td></tr></table>";
}

function admin_send_sms_alert($smstype,$rollno,$subject,$semester,$sessionalno)
{
	$roll_nos = explode(',',$rollno);
	$subject_code = explode(',',$subject);
	if($smstype=='SMS_Sessional_Marks')
	{
		echo "<p>SMS Has Been Sent to Following Mobiles</p>";
		for($i=0;$i<=count($roll_nos)-1;$i++)
		{
			$conn = mysql_connect("localhost","harbhag","gndec   har");
			mysql_select_db("gndec_erp",$conn);
			for($j=0;$j<=count($subject_code)-1;$j++)
			{
				$sql_obt = "SELECT Subject_Code,  Obtained_Marks, Absent 
							FROM student_sessional_record 
							WHERE Roll_No='".$roll_nos[$i]."' 
							AND Subject_Code='".$subject_code[$j]."' 
							AND Sessional_No='".$sessionalno."' 
							AND Semester='".$semester."'";
				$result_obt = mysql_query($sql_obt);
				while($row = mysql_fetch_assoc($result_obt))
				{
					if($row['Absent']=='Yes')
					{
						$row['Obtained_Marks'] = 'Absent';
					}
					$msgdata[] = $row['Subject_Code']." : ".$row['Obtained_Marks'];
					$msgdata_f = implode("\n",$msgdata);
				}
				
			}
			
			$result_mobile = mysql_fetch_array(mysql_query("SELECT Mobile 
															FROM student_detail 
															WHERE Roll_No='".$roll_nos[$i]."'"));
			if($result_mobile[0]=='')
			{
				mysql_close($conn_sms);
				unset($msgdata);
				continue;
			}
			$rm_space = str_replace(' ','',$result_mobile[0]);
			$mobile_f = str_replace('-','',$rm_space);
			$msgdata_ff = "Sessional No.".$sessionalno." Marks \n".$msgdata_f."";
			require('config.php');
			$s_comm = mysql_connect($db_hostname,$db_username,$db_password);
			mysql_select_db("adbook",$s_comm);
			mysql_query("INSERT INTO send_sms 
					   (sender,receiver,msgdata) 
					   VALUES ('GNDEC ERP','".$mobile_f."','". $msgdata_ff."')") or die(mysql_error());
			mysql_close($s_comm);
			unset($msgdata);
			echo "\n<p>".$mobile_f."</p>";
		}
	}
}

function edit_semester_final_marks($course,$batch,$branch,$semester){
	$sql_roll = "SELECT DISTINCT Roll_No FROM student_main WHERE Course='".$course."' AND Batch='".$batch."' AND Branch LIKE '%".$branch."%'ORDER By student_main.Roll_No ASC";
	$result_roll = mysql_query($sql_roll) or die(mysql_error());
	echo "<table id='test' align='center'>";
	echo "<tr><th>Roll No</th><th>Semester</th><th>Max Marks</th><th>Obtained Marks</th><th>Backlog</th><th>Edit</th>";
	while($row = mysql_fetch_array($result_roll)){
		$sql_final = "SELECT Roll_No,Semester,Max_Marks,Obtained_Marks,Backlog FROM student_course_record WHERE Roll_No='".$row[0]."' AND Semester='".$semester."' ORDER By student_course_record.Roll_No ASC";
		$result_final = mysql_query($sql_final);
		while($rows = mysql_fetch_assoc($result_final)) {
			if($rows['Max_Marks']=='')
			{
				$rows['Max_Marks'] = '&nbsp';
			}
			if($rows['Obtained_Marks']==''){
				$rows['Obtained_Marks']='&nbsp';
			}
			if($rows['Backlog']==''){
				$rows['Backlog']='&nbsp';
			}
			echo "<tr><td>".$rows['Roll_No']."</td>";
			echo "<td>".$rows['Semester']."</td>";
			echo "<td>".$rows['Max_Marks']."</td>";
			echo "<td>".$rows['Obtained_Marks']."</td>";
			echo "<td>".$rows['Backlog']."</td>";
			echo "<td><form  action='info.php?mode=teacher_edit_record' method='post'>
						<input type='hidden' name='Semester' value='".$semester."' />
						<input type='hidden' name='Roll_No' value='".$rows['Roll_No']."' />
						<input type='hidden' name='Max_Marks' value='".$rows['Max_Marks']."' />
						<input type='hidden' name='Obtained_Marks' value='".$rows['Obtained_Marks']."' />
						<input type='hidden' name='Backlog' value='".$rows['Backlog']."' />
						<input type='hidden' name='Edit_Semester_Final_Marks' value='esfm' />
						<input type='submit' value='Edit' /></form></td></tr>";
		}
	}
}

function edit_internal_external($course,$batch,$branch,$semester,$markstype) {
	$form = new student_form();
	if(isset($_POST['Edit_Internal_External_Now'])) {
		if($_POST['Edit_Marks_Type']=='Edit_Internal_Marks') {
			echo "<table id='student_details' align='center'>";
			echo "<form action='edit_user.php' method='post'>";
			echo "<tr><td>Roll No</td><td><input readonly type='text' name='Roll_No' value='".$_POST['Roll_No']."' /></td></tr>";
			echo "<tr><td>Max Marks</td><td><input type='text' name='Internal_Max_Marks' value='".$_POST['Internal_Max_Marks']."' /></td></tr>";
			echo "<tr><td>Obtained Marks</td><td><input type='text' name='Internal_Obtained_Marks' value='".$_POST['Internal_Obtained_Marks']."' /></td></tr>";
			$form->form_dropdown_field('dropdown',array('Yes','No'),'Detained','','','','',$_POST['Detained']);
			echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
			echo "<input type='hidden' name='Edit_Marks_Type' value='".$markstype."' />";
			echo "<input type='hidden' name='Edit_Internal_External' value='eie' />";
			echo "<tr><td><input type='submit' value='Update' /></tr></td>";
			echo "</form></table>";
			break;
		}
		if($_POST['Edit_Marks_Type']=='Edit_External_Marks') {
			echo "<table id='student_details' align='center'>";
			echo "<form action='edit_user.php' method='post'>";
			echo "<tr><td>Roll No</td><td><input readonly type='text' name='Roll_No' value='".$_POST['Roll_No']."' /></td></tr>";
			echo "<tr><td>Max Marks</td><td><input type='text' name='External_Max_Marks' value='".$_POST['External_Max_Marks']."' /></td></tr>";
			echo "<tr><td>Obtained Marks</td><td><input type='text' name='External_Obtained_Marks' value='".$_POST['External_Obtained_Marks']."' /></td></tr>";
			$form->form_dropdown_field('dropdown',array('Yes','No'),'Reappear','','','','',$_POST['Reappear']);
			echo "<input type='hidden' name='Semester' value='".$_POST['Semester']."' />";
			echo "<input type='hidden' name='Edit_Marks_Type' value='".$markstype."' />";
			echo "<input type='hidden' name='Edit_Internal_External' value='eie' />";
			echo "<tr><td><input type='submit' value='Update' /></tr></td>";
			echo "</form></table>";
			break;
		}
	}
	if(isset($_POST['Show_Internal_External'])) {
		if($markstype=='Edit_Internal_Marks'){
			$sql_roll = "SELECT DISTINCT Roll_No FROM student_main WHERE Course='".$course."' AND Batch='".$batch."' AND Branch LIKE '%".$branch."%'ORDER By student_main.Roll_No ASC";
			$result_roll = mysql_query($sql_roll) or die(mysql_error());
			$num_row = mysql_num_rows($result_roll);
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='edit_user.php' method='post'>";
			$i=0;
			$j=1;
			while($row = mysql_fetch_array($result_roll)){
				$sql_final = "SELECT Roll_No,Semester,Internal_Max_Marks,Internal_Obtained_Marks,Detained FROM student_internal_marks WHERE Roll_No='".$row[0]."' AND Semester='".$semester."' AND Subject='".$_POST['Subject']."' ORDER By student_internal_marks.Roll_No ASC";
				$result_final = mysql_query($sql_final);
				while($rows = mysql_fetch_assoc($result_final)) {
			
				echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$rows['Roll_No']."' /></td>";
				echo "<td>M.M</td><td><input readonly type='text' name=Internal_'Max_Marks' value='".$rows['Internal_Max_Marks']."' /></td>";
				echo "<td>M.O</td><td><input type='text' name='iom".$i."' value='".$rows['Internal_Obtained_Marks']."' /></td>";
				if($rows['Detained']=='Yes'){
					echo "<td>Detained</td><td><input type='checkbox' checked='yes' name='dt".$i."' value='Yes' /></td>";
				}
				else{
					echo "<td>Detained</td><td><input type='checkbox' name='dt".$i."' value='Yes' /></td>";
				}
				echo "<input type='hidden' name='Subject' value='".$_POST['Subject']."' />";
				$j +=1;
				$i +=1;
			}
		}
		echo "
						<input type='hidden' name='Semester' value='".$semester."' />
						<input type='hidden' name='num_row' value='".$num_row."' />
						<input type='hidden' name='Edit_Marks_Type' value='".$markstype."' />
						<input type='hidden' name='Edit_Internal_External' value='esfm' />";
		echo "<tr><td><input type='submit' value='Update' /></tr></td></form></table>";
			
		}
		if($markstype=='Edit_External_Marks'){
			$sql_roll = "SELECT DISTINCT Roll_No FROM student_main WHERE Course='".$course."' AND Batch='".$batch."' AND Branch LIKE '%".$branch."%'ORDER By student_main.Roll_No ASC";
			$result_roll = mysql_query($sql_roll) or die(mysql_error());
			$num_row = mysql_num_rows($result_roll);
			echo "<table id='student_attendence' align='center'>";
			echo "<form action='edit_user.php' method='post'>";
			$i=0;
			$j=1;
			while($row = mysql_fetch_array($result_roll)){
				$sql_final = "SELECT Roll_No,Semester,External_Max_Marks,External_Obtained_Marks,Reappear FROM student_external_marks WHERE Roll_No='".$row[0]."' AND Semester='".$semester."' AND Subject='".$_POST['Subject']."' ORDER By student_external_marks.Roll_No ASC";
				$result_final = mysql_query($sql_final) or die(mysql_error());
				while($rows = mysql_fetch_assoc($result_final)) {
			
				echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$rows['Roll_No']."' /></td>";
				echo "<td>M.M</td><td><input readonly type='text' name=External_'Max_Marks' value='".$rows['External_Max_Marks']."' /></td>";
				echo "<td>M.O</td><td><input type='text' name='eom".$i."' value='".$rows['External_Obtained_Marks']."' /></td>";
				if($rows['Reappear']=='Yes'){
					echo "<td>Reappear</td><td><input type='checkbox' checked='yes' name='rp".$i."' value='Yes' /></td>";
				}
				else{
					echo "<td>Reappear</td><td><input type='checkbox' name='rp".$i."' value='Yes' /></td>";
				}
				echo "<input type='hidden' name='Subject' value='".$_POST['Subject']."' />";
				$j +=1;
				$i +=1;
			}
		}
		echo "
						<input type='hidden' name='Semester' value='".$semester."' />
						<input type='hidden' name='num_row' value='".$num_row."' />
						<input type='hidden' name='Edit_Marks_Type' value='".$markstype."' />
						<input type='hidden' name='Edit_Internal_External' value='esfm' />";
		echo "<tr><td><input type='submit' value='Update' /></tr></td></form></table>";
			
	}
}
	else {
		if($course=='MBA' or $course=='MCA'){
			$sql_fetch_subjects = "SELECT DISTINCT Subject_Name 
								  FROM student_subjects 
								  WHERE Subject_Course='".$course."' 
								  AND Subject_Semester = '".$semester."'";
		}
		
		else{
			$sql_fetch_subjects = "SELECT DISTINCT Subject_Name 
								  FROM student_subjects 
								  WHERE Subject_Branch='".$branch."' 
								  AND Subject_Semester = '".$semester."'";
		}
		$result_fetch_subject = mysql_query($sql_fetch_subjects);
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=teacher_edit_record' method='post'>";
	
		echo "<tr><td>Subject</td><td><select name='Subject'>";
		while($rows = mysql_fetch_array($result_fetch_subject))
		{
			echo "<option value='".$rows[0]."'>".$rows[0]."</option>";
		}
		echo "</select></tr></td>";
		echo "<input type='hidden' name='Show_Internal_External' value='sie'>";
		echo "<input type='hidden' name='Course' value='".$course."'>";
		echo "<input type='hidden' name='Batch' value='".$batch."'>";
		echo "<input type='hidden' name='Branch' value='".$branch."'>";
		echo "<input type='hidden' name='Semester' value='".$semester."'>";
		echo "<input type='hidden' name='Edit_Marks_Type' value='".$markstype."' />";
		echo "<tr><td><input type='submit' value='Submit'></tr></td>";
		echo "</form></table>";
		
	}
}

function send_single_sms($rollno,$msgdata){
	echo "<p>SMS Sent to following numbers</p>";
	$rollnos = explode(',',$rollno);
	foreach($rollnos as $roll){
		$sql = "SELECT Mobile FROM student_detail WHERE Roll_No='".$roll."'";
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$mobile[] = $row[0];
	}
	foreach($mobile as $mobi){
		$conn_sms =mysql_connect("localhost","harbhag","gndec   har") or die(mysql_error());
		mysql_select_db("adbook",$conn_sms);
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('".$_SESSION['fullname']."','".$mobi."','".$msgdata."')") or die(mysql_error());
		echo "<p>'".$mobi."'</p>";
		mysql_close($conn_sms);
	}
}

function notify_admin($_1,$_2,$_3,$_4,$_5,$_6){
	require('config.php');
	$conn_sms =mysql_connect($db_hostname,$db_username,$db_password) or die(mysql_error());
	mysql_select_db("gndec_erp",$conn_sms);
	if($_6=='MBA' or $_6=='MCA') {
		$result = mysql_query("SELECT Mobile FROM users WHERE User_Type='Admin' AND Department='".$_6."'") or die(mysql_error());
	}
	else {
		$result = mysql_query("SELECT Mobile FROM users WHERE User_Type='Admin' AND Department='".$_5."'") or die(mysql_error());
	}
	while($row = mysql_fetch_array($result)) {
		$receiver = $row[0];
	}
	if($_1=='Sessional Marks'){
		if($_6=='MBA' or $_6=='MCA') {
			$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nSubject = ".$_3."\nSessional No. = ".$_2."\nClass = ".$_6."(".$_4.")";
		}
		else {
		$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nSubject = ".$_3."\nSessional No. = ".$_2."\nClass = ".$_5."(".$_4.")";
		}
		mysql_select_db("adbook",$conn_sms);
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('".$_SESSION['fullname']."','".$receiver."','".$msgdata."')") or die(mysql_error());
	}
	if($_1=='Internal Marks'){
		if($_6=='MBA' or $_6=='MCA') {
			$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nSemester = ".$_4."\nSubject = ".$_3."\nSessional No. = ".$_2."\nBranch = ".$_5."";
		}
		else {
		$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nSemester = ".$_4."\nSubject = ".$_3."\nSessional No. = ".$_2."\nBranch = ".$_5."";
		}
		mysql_select_db("adbook",$conn_sms);
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('".$_SESSION['fullname']."','".$receiver."','".$msgdata."')") or die(mysql_error());
	}
	if($_1=='External Marks'){
		if($_6=='MBA' or $_6=='MCA') {
			$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nSemester = ".$_4."\nSubject = ".$_3."\nSessional No. = ".$_2."\nBranch = ".$_5."";
		}
		else {
		$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nSemester = ".$_4."\nSubject = ".$_3."\nSessional No. = ".$_2."\nBranch = ".$_5."";
		}
		mysql_select_db("adbook",$conn_sms);
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('".$_SESSION['fullname']."','".$receiver."','".$msgdata."')") or die(mysql_error());
	}
	
	if($_1=='Attendence'){
		if($_6=='MBA' or $_6=='MCA') {
			$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nFrom = ".$_2."\nTo = ".$_3."\nClass = ".$_6."(".$_5.")";
		}
		else {
		$msgdata=$_1." Uploaded By. ".$_SESSION['fullname']."\nFrom = ".$_2."\nTo = ".$_3."\nClass = ".$_5."(".$_4.")";
		}
		mysql_select_db("adbook",$conn_sms);
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('".$_SESSION['fullname']."','".$receiver."','".$msgdata."')") or die(mysql_error());
	}
}

function teacher_edit_attendence($course,$batch,$branch,$semester,$subject,$startdate,$enddate){
	$result = mysql_query("SELECT Roll_No,Total_Lecture,Attended_Lecture FROM student_attendance WHERE Course='".$course."' AND Batch='".$batch."' AND Branch LIKE '%".$branch."%' AND Semester='".$semester."' AND Subject='".$subject."' AND Start_Date='".$startdate."' AND End_Date='".$enddate."' AND  Teacher_Username='".$_SESSION['username']."' ORDER BY student_attendance.Roll_No ASC") or die(mysql_error());
	$row_num = mysql_num_rows($result);
	$i=0;
	$j=1;
	echo "<table id='student_attendence' align='center'>";
	echo "<form action='edit_user.php' method='post'>";
	while($row = mysql_fetch_assoc($result)){
		echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row['Roll_No']."' /></td>";
		echo "<td>Total Lecture</td><td><input readonly type='text' name='Total_Lecture' value='".$row['Total_Lecture']."' /></td>";
		echo "<td>Attended Lecture</td><td><input type='text' name='al".$i."' value='".$row['Attended_Lecture']."' /></td></tr>";
		$j +=1;
		$i +=1;
	}
	echo "<input type='hidden' name='num_row' value='".$row_num."'/>";
	echo "<input type='hidden' name='teacher_edit_attendence' value='tea'/>";
	echo "<input type='hidden' name='Course' value='".$course."' />";
	echo "<input type='hidden' name='Semester' value='".$semester."' />";
	echo "<input type='hidden' name='Subject' value='".$subject."' />";
	echo "<input type='hidden' name='Batch' value='".$batch."'/>";
	echo "<input type='hidden' name='Start_Date' value='".$startdate."'/>";
	echo "<input type='hidden' name='End_Date' value='".$enddate."'/>";
	echo "<input type='hidden' name='Branch' value='".$branch."'/>";
	echo "<tr><td><input type='submit' value='Update' /></td></tr></form></table>";
}

function tnp_upload_record($no,$company,$other,$dop,$package,$course,$batch) {
	$j=1;
	echo "<table id='student_attendence' align='center'>";
	echo "<form action='info.php?mode=tnp_upload_record' onsubmit='return checkbranch()' method='post'>";
	for($i=0;$i<=$no-1;$i++) {
		
		echo "<tr><td>".$j.".Roll No</td><td><input type='text' name='r".$i."' /></td>";
		if($course=='B.Tech' or $course=='M.Tech')
		{
		$sql_branch ="SELECT DISTINCT Branch FROM student_main";
		$result_branch = mysql_query($sql_branch);
		echo "<td>Branch</td><td><select name='br".$i."' id='Branch'>";
		echo "<option value='' selected='selected'></option>";
		while($branch=mysql_fetch_assoc($result_branch)){
			if($branch['Branch']=='N/A'){
				continue;
			}
			echo "<option value='".$branch['Branch']."'>".$branch['Branch']."</option>";
		}
		echo "</select></td></tr>";
	}
		$j +=1;
	}
	echo "<input type='hidden' name='NSP' value='".$no."' />";
	echo "<input type='hidden' name='Package' value='".$package."' />";
	echo "<input type='hidden' name='Course' value='".$course."' />";
	echo "<input type='hidden' name='Batch' value='".$batch."' />";
	echo "<input type='hidden' name='Company_Name' value='".$company."' />";
	echo "<input type='hidden' name='Other_Company' value='".$other."' />";
	echo "<input type='hidden' name='Date_Of_Placement' value='".$dop."' />
	<tr><td><input type='submit' value='Submit' /></tr></td></form></table>";
}

function tnp_training_record($type,$course,$batch,$branch,$startdate,$enddate) {
	$sql = "SELECT DISTINCT Roll_No FROM student_main WHERE Course='".$course."' AND Batch='".$batch."'AND Branch LIKE '%".$branch."' ORDER BY student_main.Roll_No ASC";
	$result = mysql_query($sql);
		$num_row = mysql_num_rows($result);
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='upload_marks' action='info.php?mode=tnp_training_record' method='post'>";
		$i = 0;
		$j = 1;
		while($row = mysql_fetch_array($result))
		{
			echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row[0]."' /></td>";
			echo "<td>Company Name</td><td><input type='text' name='cn".$i."' /></td>";
			echo "<td>Company Address</td><td><input type='text' name='ca".$i."'/></td>";
			echo "<td>Stipend(If any)</td><td><input type='text' name='sp".$i."'/></td></tr>";
			$i +=1;
			$j +=1;
		}
		echo "<input type='hidden' name='Course' value='".$course."' />";
		echo "<input type='hidden' name='Batch' value='".$batch."' />";
		echo "<input type='hidden' name='Branch' value='".$branch."' />";
		echo "<input type='hidden' name='Training_Type' value='".$type."' />";
		echo "<input type='hidden' name='Start_Date' value='".$startdate."' />";
		echo "<input type='hidden' name='End_Date' value='".$enddate."' />";
		echo "<input type='hidden' name='num_row' value='".$num_row."'/>";
		echo "</tr><td><input type='submit' value='Upload'/>";
		echo "</form></table>";
	}
	
function tnp_edit_record($type,$course,$batch,$branch) {
	if($type=='Placement Record') {
		$result = mysql_query("SELECT Roll_No,Company_Name,Date_Of_Placement,Package FROM student_placement WHERE Course='".$course."' AND Batch='".$batch."' AND Branch LIKE '%".$branch."%' ORDER BY student_placement.Roll_No ASC") or die(mysql_error());
		$num_row = mysql_num_rows($result);
		$j=1;
		$i=0;
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='upload_marks' action='edit_user.php' method='post'>";
		while($row=mysql_fetch_assoc($result)) {
			echo "<tr><td>".$j.". Roll No</td><td><input readonly type='text' name='r".$i."' value='".$row['Roll_No']."' /></td>";
			echo "<td>Company Name</td><td><input type='text' name='cn".$i."' value='".$row['Company_Name']."' /></td>";
			echo "<td>Date Of Placement</td><td><input type='text'  name='dop".$i."' value='".$row['Date_Of_Placement']."'/></td>";
			echo "<td>Package</td><td><input type='text' name='pk".$i."' value='".$row['Package']."'/></td></tr>";
			$i +=1;
			$j +=1;
		}
		echo "<input type='hidden' name='Course' value='".$course."' />";
		echo "<input type='hidden' name='Batch' value='".$batch."' />";
		echo "<input type='hidden' name='Branch' value='".$branch."' />";
		echo "<input type='hidden' name='num_row' value='".$num_row."' />";
		echo "<input type='hidden' name='edit_placement_record' value='epr' />";
		echo "<tr><td><input type='submit' value='Submit' /></td></tr></form></table>";
	}
	if($type=='Training Record') {
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='upload_marks' action='info.php?mode=tnp_edit_record' method='post'>";
		echo "<tr><td>Training Type</td><td><select name='Training_Type'>";
		echo "<option value='6 Weeks Industrial Training'>6 Weeks Industrial Training</option>";
		echo "<option value='6 Months Industrial Training'>6 Months Industrial Training</option>";
		echo "</select></tr></td>";
		echo "<input type='hidden' name='Course' value='".$course."' />";
		echo "<input type='hidden' name='Batch' value='".$batch."' />";
		echo "<input type='hidden' name='Branch' value='".$branch."' />";
		echo "<tr><td><input type='submit' value='Submit' /></tr></td></form></table>";
		break;
	}
}
	

function list_placed_students($type,$batch) {
	echo "<table id='test'>";
	echo "<tr><th>Sr.No.</th><th>Name</th><th>Course</th><th>Branch</th><th>Roll No</th><th>Company</th><th>Date Of Placement</th><th>Package(Lac/Annum)</th></tr>";
	$sql = "SELECT CONCAT( student_main.Student_First_Name,  ' ', student_main.Student_Middle_Name,  ' ', student_main.Student_Last_Name ) AS Name , 				student_placement.Roll_No,student_placement.Course,student_placement.Branch,student_placement.Company_Name,student_placement.Date_Of_Placement,student_placement.Package FROM student_main, student_placement WHERE student_main.Roll_No = student_placement.Roll_No AND student_placement.Batch='".$batch."' AND student_placement.Course=student_main.Course AND student_placement.Branch LIKE student_main.Branch ORDER BY student_placement.Roll_No ASC";
	$result = mysql_query($sql) or die(mysql_query());
	$j=1;
	$trcount = 2;
	while($data = mysql_fetch_assoc($result)) {
		if($trcount%2==0){
			$class = "white";
		}
		else{
			$class = "alt";
		}
		if($data['Branch']=='Electronics & Communication Engineering'){
			$data['Branch']='ECE';
		}
		echo "<tr class='".$class."'><td>".$j."</td>";
		echo "<td>".$data['Name']."</td>";
		echo "<td>".$data['Course']."</td>";
		echo "<td>".$data['Branch']."</td>";
		echo "<td>".$data['Roll_No']."</td>";
		echo "<td>".$data['Company_Name']."</td>";
		echo "<td>".$data['Date_Of_Placement']."</td>";
		echo "<td>".$data['Package']."</td></tr>";
		$j +=1;
		$trcount += 1;
	}
}

function send_email_assignment($course,$batch,$branch,$assignmentno,$teacher,$subject,$date,$attach) {
	$result = mysql_query("SELECT Email FROM student_detail, student_main WHERE student_detail.Roll_No = student_main.Roll_No AND student_main.Course =  '".$course."' AND student_main.Batch =  '".$batch."' AND student_main.Branch LIKE  '%".$branch."%'") or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$e_mail[] = $row[0];
	}
	$mail = new PHPMailer();
	$mail->SetLanguage("en", "language");
	$mail->IsSMTP();
	$mail->SMTPAuth = true; 
	$mail->From = "gndec.sms.service@gmail.com";
	$mail->FromName = "GNDEC ERP System";
	$mail->AddAttachment("".$attach."");
	/*for($i=0;$i<=count($e_mail)-1;$i++) {
		$mail->AddAddress("".$e_mail[$i]."");
		$mail->WordWrap = 50;                              
		$mail->IsHTML(true);                                
		$mail->Subject = "Assignment No.".$assignmentno." From '".$_SESSION['username']."' For '".$subject."'";
		$mail->Body    = "Date of Submission = '".$date."'    Please check attachment for more details of assignment.";
		if(!$mail->Send()){
			echo "Message could not be sent. <p>";
			echo "Mailer Error: " . $mail->ErrorInfo;
			exit;
		}
	}*/
	$mail->AddAddress("harbhag.sohal@gmail.com");
		$mail->WordWrap = 50;                              
		$mail->IsHTML(true);                                
		$mail->Subject = "Assignment No.".$assignmentno." From '".$_SESSION['username']."' For '".$subject."'";
		$mail->Body    = "Date of Submission = '".$date."'    Please check attachment for more details of assignment.";
		if(!$mail->Send()){
			echo "Message could not be sent. <p>";
			echo "Mailer Error: " . $mail->ErrorInfo;
			exit;
		}
}



function send_email_sessional($rollno,$subject,$sessionalno,$maxmarks,$obtainedmarks,$absent) {
	$result = mysql_query("SELECT Email FROM student_detail WHERE Roll_No='".$rollno."'") or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$e_mail[] = $row[0];
	}
	if($absent=='Yes'){
		$result='Absent';
	}
	else
	{
		$result = $obtainedmarks;
	}
	$mail = new PHPMailer();
	$mail->SetLanguage("en", "language");
	$mail->IsSMTP();
	$mail->SMTPAuth = true; 
	$mail->From = "gndec.sms.service@gmail.com";
	$mail->FromName = "GNDEC ERP System";
	/*for($i=0;$i<=4;$i++) {
		$mail->AddAddress("".$e_mail[$i]."");
	}*/
	$mail->AddAddress("harbhag.sohal@gmail.com");
	$mail->WordWrap = 50;                              
	$mail->IsHTML(true);                                
	$mail->Subject = "Marks for Sessional No.".$sessionalno." From '".$_SESSION['username']."' For '".$subject."'";
	$mail->Body    = "Result = '".$result."'";
	if(!$mail->Send()){
			echo "Message could not be sent. <p>";
			echo "Mailer Error: " . $mail->ErrorInfo;
			mysql_close($conn);
			exit;
		}
}

function send_email_internal_external($markstype,$rollno,$subject,$maxmarks,$obtainedmarks,$detained_reappear) {
	require('config.php');
	$conn = mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db("gndec_erp",$conn);
	$result = mysql_query("SELECT Email FROM student_detail WHERE Roll_No='".$rollno."'") or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$e_mail[] = $row[0];
	}
	if($detained_reappear=='Yes'){
		if($markstype=='Internal_Marks'){
			$result='Detained';
		}
		if($markstype=='External_Marks'){
			$result='Reappear';
		}
	}
	else
	{
		$result = $obtainedmarks;
	}
	if($markstype=='Internal_Marks') {
		$subject_email = "Internal Marks For '".$subject."'";
	}
	if($markstype=='External_Marks'){
		$subject_email = "External Marks For '".$subject."'";
	}
	$mail = new PHPMailer();
	$mail->SetLanguage("en", "language");
	$mail->IsSMTP();
	$mail->SMTPAuth = true; 
	$mail->From = "gndec.sms.service@gmail.com";
	$mail->FromName = "GNDEC ERP System";
		$mail->AddAddress("harbhag.sohal@gmail.com");
		$mail->WordWrap = 50;                              
		$mail->IsHTML(true);                                
		$mail->Subject = $subject_email;
		$mail->Body    = "Result = '".$result."' Teacher = '".$_SESSION['username']."'";
		if(!$mail->Send()){
			echo "Message could not be sent. <p>";
			echo "Mailer Error: " . $mail->ErrorInfo;
			mysql_close($conn);
			exit;
		}
		mysql_close($conn);
}

function send_email_attendence($rollno,$startdate,$enddate,$total,$attended,$subject) {
}


function assign_subjects($semester) {
	$teacher_sql = mysql_query("SELECT Username,Full_Name, CONCAT(Full_Name,' (',Department,')') AS Name_Department FROM users WHERE User_Type='Teacher' ORDER BY Full_Name ASC") or die(mysql_error());
	while($row = mysql_fetch_assoc($teacher_sql)) {
		$teacher_username[$row['Full_Name']] = $row['Username'];
		$teacher_fullname[] = $row['Full_Name'];
		$teacher_department[$row['Full_Name']] = $row['Name_Department'];
	}
	if($_SESSION['department']=='MBA') {
		$course='MBA';
	}
	elseif($_SESSION['department']=='MCA') {
		$course='MCA';
	}
	else {
		$course='B.Tech';
	}
	$subjects = mysql_query("SELECT Subject_Name, Subject_Code, CONCAT(Assigned_Teacher1,',',Assigned_Teacher2,',',Assigned_Teacher3,',',Assigned_Teacher4,',',Assigned_Teacher5) AS Assigned_Teacher, CONCAT(Subject_Name,' (',Subject_Code,')') AS Name_Code FROM student_subjects WHERE Subject_Semester='".$semester."' AND Subject_Course='".$course."' AND Subject_Branch LIKE '".$_SESSION['department']."'") or die(mysql_error());
	echo "<table id='test' align='center'>";
	echo "<form action='info.php?mode=assign_subjects' method='post'>";
	echo "<tr><th>Subject (Code)</th><th>Current Teachers</th><th>New Teachers</th></tr>";
	$i = 1;
	while($row = mysql_fetch_assoc($subjects)) {
		$teacher_array = explode(',',$row['Assigned_Teacher']);
		$num = 1;
		foreach($teacher_array as $item) {
			//if($item!='' && $item!='N/A'){
				$sql_tf = mysql_query("SELECT Distinct Full_Name FROM users WHERE Username='".$item."'") or die(mysql_error());
				$result = mysql_fetch_array($sql_tf);
				if($result[0]=='') {
					$result[0]='No Teacher Assigned';
				}
				$teacher_array_f[] = $num.". ".$result[0];
				$num +=1;
			//}
		}
		$t_fname = implode('<br />',$teacher_array_f);
		echo "<tr><td>".$row['Name_Code']."
					<input type='hidden' name='sub".$i."' value='".$row['Subject_Code']."' /><input type='hidden' name='subn".$i."' value='".$row['Subject_Name']."' /></td>";
		echo "<td>".$t_fname."";
					for($g=0;$g<=4;$g++) {
						echo "<input type='hidden' name='assit".$i.$g."' value='".$teacher_array[$g]."' />";
					}
					echo "</td>";
					
		echo "<td>";
		$num_s = 1;
		$cancel_teacher = explode('<br />',$t_fname);
		for($h=0;$h<=4;$h++) {
			$p=$h+1;
		echo "<br />".$num_s.". <select name='newt".$i.$h."'>";
		echo "<option value='DC' selected='selected'>Assign New Teacher</option>";
		if($cancel_teacher[$h]!=$p.'. No Teacher Assigned') {
			
			echo "<option value=''>Cancel Assignment</option>";
		}
		foreach($teacher_fullname as $tname) {
			echo "<option value='".$teacher_username[$tname]."'>".$teacher_department[$tname]."</option>";
		}
		echo "</select>";
		$num_s += 1;
		
		$bupper = date(Y);
		$blower = date(Y)-4;
		$select_groups = mysql_query("SELECT DISTINCT Group_Name FROM student_groups WHERE Branch='".branch_acronym()."' 
									AND Batch>='".$blower."' 
									AND Batch<='".$bupper."'") or die(mysql_error());
		
		echo "<select name='newg".$i.$h."'>";
		echo "<option value='CD`' selected='selected'>Select Group</option>";
		while($row = mysql_fetch_assoc($select_groups)) {
			echo "<option value='".$row['Group_Name']."'>".$row['Group_Name']."</option>";
		}
		echo "</select>";
	}
	echo "</tr></td>";
		$i +=1;
		unset($teacher_array_f);
	}
	echo "<input type='hidden' name='Count' value='".$i."' /></table>";
	echo "<input type='submit' value='Update Data'></form>";
}


function create_groups($action,$batch,$detail,$groupname) {
	$alphabets = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$branch = branch_acronym();
	$teacher_sql = mysql_query("SELECT Username,Full_Name FROM users WHERE User_Type='Teacher' AND Department='".$_SESSION['department']."' ORDER BY Full_Name ASC") or die(mysql_error());
	while($row = mysql_fetch_assoc($teacher_sql)) {
		$teacher_username[] = $row['Username'];
		$teacher_fullname[] = $row['Full_Name'];
	}
	if($action=='Add_New_Group') {
		echo "<table align='center' width='100%'>";
		echo "<form action='info.php?mode=create_groups' method='post' />";
		echo "<tr><td><table id='test'>";
		echo "<tr><th>Name</th><th>Academic Incharge</th></tr>";
		for($l=1;$l<=$detail;$l++) {
			echo "<tr><td>Group-".$l."";
			echo "<input type='text' readonly name='GroupName".$l."' value='".$branch.$batch.$alphabets[$l-1]."' /></td>";
			echo "<td><select name='AcademicIncharge".$l."' id='AcademicIncharge".$l."' />";
			echo "<option value='' selected='selected'>Select Academic Incharge</option>";
			for($m=1;$m<count($teacher_username);$m++) {
				echo "<option value='".$teacher_username[$m]."'>".$teacher_fullname[$m]."</option>";
			}
			echo "</select></td></tr>";
		}
		echo "</table></tr></td>";
		
		echo "<tr><td><table id='test' align='center'>";
		for($i=1;$i<=$detail;$i++) {
			echo "<th>Group-".$i."</th>";
		}
		echo "</tr><tr>";
		for($j=1;$j<=$detail;$j++) {
			$k=0;
			echo "<td>";
			if($_SESSION['department']!='MBA' && $_SESSION['department']!='MCA' && $_SESSION['department']!='M.Tech') {
			$sql = mysql_query("SELECT Roll_No, CONCAT(Student_First_Name,' ',Student_Middle_Name,' ',Student_Last_Name) AS Name FROM student_main WHERE Branch='".$_SESSION['department']."' AND Batch='".$batch."' ORDER BY Roll_No ASC") or die(mysql_error());
		}
			while($row = mysql_fetch_assoc($sql)) {
				echo "<br /><input type='checkbox' name='Group".$j.$k."' id='Group-".$j."-".$k."' value='".$row['Roll_No']."' onclick='disable_check_group(this.id,".$detail.")'/>".$row['Roll_No']."  (".$row['Name'].")";
				$k +=1;
			}
			echo "</td>";	
		}
		echo "</tr></table>";
		echo "<p><b>No. Of Un-Grouped Students<input type='text' readonly name='ungrouped' id='ungrouped' value='".$k."' size='3' /></b></p>";
		echo "<input type='hidden' name='No_Of_Groups' value='".$detail."' />";
		echo "<input type='hidden' name='No_Of_Students' value='".$k."' />";
		echo "<input type='hidden' name='Batch' value='".$batch."' />";
		echo "<input type='hidden' name='Branch' value='".$branch."' />";
		echo "<input type='hidden' name='Insert_New_Group' value='ing' />";
		echo "<tr><td><input type='submit' value='Create Grouping' onclick='return check_ungrouped(".$detail.",".$k.")' />";
	}
	
	if($action=='Add_New_Subgroup') {
		echo "<table align='center' width='100%'>";
		echo "<form action='info.php?mode=create_groups' method='post' />";
		echo "<tr><td><table id='test'>";
		echo "<tr><th>Name</th><th>Advisor</th></tr>";
		for($l=1;$l<=$detail;$l++) {
			echo "<tr><td>Subgroup-".$l."";
			echo "<input type='text' readonly name='SubgroupName".$l."' value='".$groupname.$l."' /></td>";
			echo "<td><select name='SubgroupAdvisor".$l."' id='SubgroupAdvisor".$l."' />";
			echo "<option value='' selected='selected'>Select Advisor</option>";
			for($m=1;$m<count($teacher_username);$m++) {
				echo "<option value='".$teacher_username[$m]."'>".$teacher_fullname[$m]."</option>";
			}
			echo "</select></td></tr>";
		}
		echo "</table></tr></td>";
		
		echo "<tr><td><table id='test' align='center'>";
		for($i=1;$i<=$detail;$i++) {
			echo "<th>Subgroup-".$i."</th>";
		}
		echo "</tr><tr>";
		for($j=1;$j<=$detail;$j++) {
			$k=0;
			echo "<td>";
			$sql = mysql_query("SELECT Roll_No FROM student_groups WHERE Group_Name='".$groupname."' ORDER BY Roll_No ASC") or die(mysql_error());
			while($row = mysql_fetch_array($sql)) {
				$sql_name = mysql_query("SELECT CONCAT(Student_First_Name,' ',Student_Middle_Name,' ',Student_Last_Name) AS Name FROM student_main WHERE Roll_No='".$row[0]."'") or die(mysql_error());
				$name_stu = mysql_fetch_array($sql_name);
				echo "<br /><input type='checkbox' name='Subgroup".$j.$k."' id='Subgroup-".$j."-".$k."' value='".$row[0]."' onclick='disable_check_group(this.id,".$detail.")'/>".$row[0]."  (".$name_stu[0].")";
				$k +=1;
			}
			echo "</td>";	
		}
		echo "</tr></table>";
		echo "<p><b>No. Of Un-Grouped Students<input type='text' readonly name='ungrouped' id='ungrouped' value='".$k."' size='3' /></b></p>";
		echo "<input type='hidden' name='No_Of_Subgroups' value='".$detail."' />";
		echo "<input type='hidden' name='No_Of_Students' value='".$k."' />";
		echo "<input type='hidden' name='Group_Name' value='".$groupname."' />";
		echo "<input type='hidden' name='Insert_New_Subgroup' value='ing' />";
		echo "<tr><td><input type='submit' value='Create Subgroup' onclick='return check_unsubgrouped(".$detail.",".$k.")' />";
	}
}
	
function edit_grouping($batch,$groupname,$type) {
	$teacher_sql = mysql_query("SELECT Username,Full_Name FROM users WHERE User_Type='Teacher' AND Department='".$_SESSION['department']."' ORDER BY Full_Name ASC") or die(mysql_error());
	while($row = mysql_fetch_assoc($teacher_sql)) {
		$teacher_username[] = $row['Username'];
		$teacher_fullname[] = $row['Full_Name'];
	}
	if($type=='Edit_Subgrouping') {
		$subgroup_sql = mysql_query("SELECT DISTINCT Subgroup_Name FROM student_groups WHERE Batch='".$batch."' AND Group_Name='".$groupname."' AND Subgroup_Name!=''") or die(mysql_error());
		$num_of_subgroups = mysql_num_rows($subgroup_sql);
		if($num_of_subgroups==0) {
			echo "<p>Subgroups Does not exists for ".$groupname ;
			break;
		}
		else {
		for($i=1;$i<=$num_of_subgroups;$i++) {
			$subgroup_rollno = mysql_query("SELECT Roll_No FROM student_groups WHERE Group_Name='".$groupname."' AND Subgroup_Name='".$groupname.$i."'") or die(mysql_error());
			while($row = mysql_fetch_array($subgroup_rollno)) {
				$subgroup_rollno_array[$groupname.$i][] = $row[0];
			}
		}
		
		echo "<table align='center' width='100%'>";
		echo "<form action='info.php?mode=create_groups' method='post' />";
		echo "<tr><td><table id='test'>";
		echo "<tr><th>Name</th><th>Advisor</th></tr>";
		for($l=1;$l<=$num_of_subgroups;$l++) {
			echo "<tr><td>Subgroup-".$l."";
			$subgroup_advisor = mysql_query("SELECT DISTINCT Subgroup_Advisor FROM student_groups WHERE Batch='".$batch."' AND Group_Name='".$groupname."' AND Subgroup_Name='".$groupname.$l."'") or die(mysql_error());
			$uname_advisor = mysql_fetch_array($subgroup_advisor);
			$advisor_fname = mysql_query("SELECT DISTINCT Full_Name FROM users WHERE Username='".$uname_advisor[0]."'") or die(mysql_error());
			$fname_advisor = mysql_fetch_array($advisor_fname);
			echo "<input type='text' readonly name='SubgroupName".$l."' value='".$groupname.$l."' /></td>";
			echo "<td><select name='SubgroupAdvisor".$l."' />";
			echo "<option value='".$uname_advisor[0]."' selected='selected'>".$fname_advisor[0]."</option>";
			for($m=1;$m<count($teacher_username);$m++) {
				echo "<option value='".$teacher_username[$m]."'>".$teacher_fullname[$m]."</option>";
			}
			echo "</select></td></tr>";
		}
		echo "</table></tr></td>";
		
		echo "<tr><td><table id='test' align='center'>";
		for($i=1;$i<=$num_of_subgroups;$i++) {
			echo "<th>Subgroup-".$i."</th>";
		}
		echo "</tr><tr>";
		for($j=1;$j<=$num_of_subgroups;$j++) {
			$k=0;
			echo "<td>";
			$sql = mysql_query("SELECT Roll_No FROM student_groups WHERE Group_Name='".$groupname."' ORDER BY Roll_No ASC") or die(mysql_error());
			while($row = mysql_fetch_array($sql)) {
				$sql_name = mysql_query("SELECT CONCAT(Student_First_Name,' ',Student_Middle_Name,' ',Student_Last_Name) AS Name FROM student_main WHERE Roll_No='".$row[0]."'") or die(mysql_error());
				$name_stu = mysql_fetch_array($sql_name);
				if(in_array($row[0],$subgroup_rollno_array[$groupname.$j])) {
					echo "<br /><input type='checkbox' name='Subgroup".$j.$k."' id='Subgroup-".$j."-".$k."' value='".$row[0]."' checked='checked' onclick='disable_check_group(this.id,".$num_of_subgroups.")'/>".$row[0]."  (".$name_stu[0].")";
				}
				else {
					echo "<br /><input type='checkbox' name='Subgroup".$j.$k."' id='Subgroup-".$j."-".$k."' value='".$row[0]."' disabled='disabled' onclick='disable_check_group(this.id,".$num_of_subgroups.")'/>".$row[0]."  (".$name_stu[0].")";
				}
				
				$k +=1;
			}
			echo "</td>";	
		}
		echo "</tr></table>";
		echo "<p><b>No. Of Un-Grouped Students<input type='text' readonly name='ungrouped' id='ungrouped' value='0' size='3' /><b></p>";
		echo "<input type='hidden' name='No_Of_Subgroups' value='".$num_of_subgroups."' />";
		echo "<input type='hidden' name='No_Of_Students' value='".$k."' />";
		echo "<input type='hidden' name='Group_Name' value='".$groupname."' />";
		echo "<input type='hidden' name='Edit_Subgroup_Now' value='ing' />";
		echo "<tr><td><input type='submit' value='Update Subgrouping' onclick='return check_edit_unsubgrouped(".$num_of_subgroups.",".$k.")'/>";
		
		}
	}
	if($type=='Edit_Grouping') {
		$alphabets = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$branch = branch_acronym();
		$group_sql = mysql_query("SELECT DISTINCT Group_Name FROM student_groups WHERE Batch='".$batch."'") or die(mysql_error());
		$num_of_groups = mysql_num_rows($group_sql);
		for($i=1;$i<=$num_of_groups;$i++) {
			$group_rollno = mysql_query("SELECT Roll_No FROM student_groups WHERE Group_Name='".$branch.$batch.$alphabets[$i-1]."'") or die(mysql_error());
			while($row = mysql_fetch_array($group_rollno)) {
				$group_rollno_array[$branch.$batch.$alphabets[$i-1]][] = $row[0];
			}
		}
		print_r($group_rollno_array);
	}
	
}


function attendence_record($start_date,$end_date,$batch,$semester) {
	$data = mysql_query("SELECT * FROM student_attendance WHERE Start_Date='".$start_date."' AND End_Date='".$end_date."' AND Batch='".$batch."' AND Semester='".$semester."' AND Branch='".$_SESSION['department']."'") or die(mysql_error());

	echo "<table id='mytable'>";
	echo "<tr>";
	echo "<th>Roll No</th><th>Subject</th><th>Total Lecture</th><th>Attended Lecture</th><th>Teacher</th><th>Uploaded On</th>";
	while($row = mysql_fetch_assoc($data)) {
		echo "<tr>";
		echo "<td>".$row['Roll_No']."</td>";
		echo "<td>".$row['Subject']."</td>";
		echo "<td>".$row['Total_Lecture']."</td>";
		echo "<td>".$row['Attended_Lecture']."</td>";
		echo "<td>".ucfirst(str_replace("_"," ",$row['Teacher_Username']))."</td>";
		echo "<td>".$row['Uploaded_On']."</td>";
		echo "</tr>";
	}
	/*echo "<tr>";
	echo "<th>Teacher</th>";
	while($row = mysql_fetch_assoc($teacher)) {
		$teacher_assi = ucfirst(str_replace("_"," ",$row['Assigned_Teacher1']));
		if($row['Assigned_Teacher2']!='' && $row['Assigned_Teacher2']!='N/A') {
			$teacher_assi = $teacher_assi.",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher2']));
		}
		if($row['Assigned_Teacher3']!='' && $row['Assigned_Teacher3']!='N/A') {
			$teacher_assi = $teacher_assi.",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher3']));
		}
		if($row['Assigned_Teacher4']!='' && $row['Assigned_Teacher4']!='N/A') {
			$teacher_assi = $teacher_assi.",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher4']));
		}
		if($row['Assigned_Teacher5']!='' && $row['Assigned_Teacher5']!='N/A') {
			$teacher_assi = $teacher_assi.",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher5']));
		}
		echo "<th>".ucfirst(str_replace("_"," ",$row['Assigned_Teacher1'])).",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher2'])).",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher3'])).",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher4'])).",".ucfirst(str_replace("_"," ",$row['Assigned_Teacher5']))."</th>";
		echo "<th>".$teacher_assi."</th>";
	}
	echo "</tr>";*/
	
}

?>
