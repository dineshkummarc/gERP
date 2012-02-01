<?php
/* This is the Main File which Contains all the forms
  This Function is used to display the various Form based on the type of form given
*/
require_once('config.php');
require_once('functions.php');
CheckForLogin();
mysql_select_db("gndec_erp",$conn);
require_once ('input_form_class.php');
require_once('scripts.php');
function form($formtype,$additional_detail) 
{
	$table_columns = get_tables_cols();
	$form = new student_form();
	
	if($formtype=='student_details')
	{
		echo "<table id='student_details' align='center'>";
		echo "<tr><td class='heading'>Please Fill The Form Below To Find The Details of Student.</tr></td>
		<tr><td>(NOTE: Empty Fields Falls Under ANY Category)</tr></td></table>";
		echo "<table id='student_details' align='center'>";		
		echo "<tr><td><form id='search' action='info.php?mode=student_details' method='post'></tr></td>";
		$form->form_text_field('text','Student_First_Name','required','','');
		$form->form_text_field('text','Student_Middle_Name','required','','');
		$form->form_text_field('text','Student_Last_Name','required','','');
		$form->form_text_field('text','Roll_No','required','','');
		$form->form_text_field('text','Univ_Roll_No','required','','');
		$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','','');
		$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','','','');
		$form->form_dropdown_field('dropdown','','Course','student_main','Course','','','');
		$form->form_dropdown_field('dropdown','','Gender','student_main','Gender','','','');
		echo "<tr></tr><td></td><tr></tr><tr><td class='submit'><input type='submit'  Value='Search' /></tr></td>";
		echo "</form></table>";
		
	}
	
	if($formtype=='attendence_student') 
	{
			$sql_attendence_student1 = "SELECT DISTINCT Start_Date FROM student_attendance";
			$sql_details = "SELECT Batch,Course,Branch 
							FROM student_main 
							WHERE Roll_No='".$_SESSION['rollno']."'";
			$result_fetch_details = mysql_query($sql_details);
			$row_subject = mysql_fetch_assoc($result_fetch_details);
			if($_POST['Course']=='MBA' or $_POST['Course']=='MCA')
			{
				$sql_fetch_subjects = "SELECT DISTINCT Subject 
									  FROM student_attendance 
									  WHERE Course='".$row_subject['Course']."' 
									  AND Batch = '".$row_subject['Batch']."'";
			}	
		
			else
			{
				$sql_fetch_subjects = "SELECT DISTINCT Subject 
									  FROM student_attendance 
									  WHERE Course='".$row_subject['Course']."' 
									  AND Batch = '".$row_subject['Batch']."' 
									  AND Branch='".$row_subject['Branch']."'";
			}
			$result_fetch_subject = mysql_query($sql_fetch_subjects);
			$result_attendence_student1 = mysql_query($sql_attendence_student1);
			echo "<table id='student_attendence' align='center'>";
			echo "<tr><td class='heading'>Please Select The Time Period For Which You Wish To See Your Attendence</tr></td>";
			echo "<table id='student_attendence' align='center'>";
			echo "<tr><td><form action='info.php?mode=attendence_student' method='post'></tr></td>";
			$form->form_dropdown_field('dropdown','','Start_Date','student_attendance','Start_Date','','');
			$form->form_dropdown_field('dropdown','','End_Date','student_attendance','End_Date','','');
			$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
			echo "<tr><td>Subject</td><td><select name='subject'>";
			while($row=mysql_fetch_array($result_fetch_subject)) 
			{
				if($row[0]!='')
				echo "<option value='".$row[0]."'>".$row[0]."</option>";
			}
			echo "<tr><td><input type='submit' value='Submit'></tr></td>";
			echo "</form></table>"; 
	}
	
	if($formtype=='attendence_teacher_period')
		{
			$current_year = date(Y);
			echo "<table id='student_attendence' align='center'>";
			echo "<form id='teacher_attendence' action='info.php?mode=attendence_teacher' method='post'>";
			$select_group = mysql_query("SELECT DISTINCT Assigned_Group FROM teacher_allotment WHERE Assigned_Teacher='".$_SESSION['username']."'") or die(mysql_error());
			$select_subject = mysql_query("SELECT DISTINCT Subject_Name FROM teacher_allotment WHERE Assigned_Teacher='".$_SESSION['username']."'") or die(mysql_error());
			//$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
			//$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
			/*echo "<tr><td>Course</td><td><select name='Course' id='Course' onchange='branch_selector(this.value);batch_selector();semester_selector()'>";
			echo "<option value='B.Tech'>B.Tech</option>";
			echo "<option value='M.Tech'>M.Tech</option>";
			echo "<option value='MBA'>MBA</option>";
			echo "<option value='MCA'>MCA</option>";
			echo "</select></td></tr>";*/
			//echo "<tr><td>Course</td><td><input type='text' value='B.Tech' readonly='readonly' name='Course' id='Course'>";
			//echo "<tr><td>Branch</td><td><input type='text' value='".$_SESSION['department']."' readonly='readonly' name='Branch' id='Branch'>";
			/*echo "<tr><td><div id='branch_div_name'></div></td><td><div id='branch_div'></div></td></tr>";
			echo "<script type='text/javascript'>set_branch_selector()</script>";
			echo "<tr><td><div id='batch_div_name'></div></td><td><div id='batch_div'></div></td></tr>";
			echo "<script type='text/javascript'>batch_selector()</script>";*/
			echo "<tr><td>Group Name</td><td><select name='group' id='group'>";
			
			while($row=mysql_fetch_assoc($select_group)) {
				echo "<option value='".$row['Assigned_Group']."'>".$row['Assigned_Group']."</option>";
			}
			echo "</select></td></tr>";

			echo "<tr><td>Subject</td><td><select name='subject' id='subject'>";
			
			while($row=mysql_fetch_assoc($select_subject)) {
				echo "<option value='".$row['Subject_Name']."'>".$row['Subject_Name']."</option>";
			}
			echo "</select></td></tr>";
			//$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
			
			/*echo "<tr><td><div id='semester_div_name'></div></td><td><div id='semester_div'></div></td></tr>";
			echo "<script type='text/javascript'>set_semester_selector()</script>";*/
			//$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
			//$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
			$form->form_text_field('text',$form->Start_Date,'required','sdate','');
			$form->form_text_field('text',$form->End_Date,'required','edate','');
			$form->form_text_field('text',$form->Total_Lecture,'required','','');
			/*echo "<tr><td>Alert Student</td><td>If Attendence is Less than 75% ?</td></tr>";
			echo "<tr><td>Yes</td><td><input type='radio' name='Alert_Low_Attendence' value='Yes' /></td></tr>";
			echo "<tr><td>No</td><td><input type='radio' name='Alert_Low_Attendence' value='No' checked='checked' /></td></tr>";
			echo "<input type='hidden' name='Batch' id='Batch' value=''>";
			echo "<input type='hidden' name='Branch' id='Branch' value=''>";
			echo "<input type='hidden' name='Semester' id='Semester' value=''>";*/
			echo "<tr><td><input type='submit' value='Submit'></tr></td>";
			echo "</form>";
			echo "</table>";
		}
		
		
		if($formtype=='attendence_teacher_subject')
	{
		$sql_fetch_subjects = mysql_query("SELECT DISTINCT Subject_Name
										FROM student_subjects 
										WHERE Subject_Branch='".$_SESSION['department']."' 
										AND Assigned_Teacher1 = '".$_SESSION['username']."' 
										OR Assigned_Teacher2 = '".$_SESSION['username']."' 
										OR Assigned_Teacher3 = '".$_SESSION['username']."' 
										OR Assigned_Teacher4 = '".$_SESSION['username']."' 
										OR Assigned_Teacher5 = '".$_SESSION['username']."'");
		echo "<table id='student_attendence' align='center'>";
			echo "<form action='info.php?mode=attendence_teacher' method='post'>";
			echo "<tr><td>Subject</td><td><select name='Subject'>";
			while($rows = mysql_fetch_assoc($sql_fetch_subjects))
			{
				echo "<option value='".$rows['Subject_Name']."'>".$rows['Subject_Name']."</option>";
			}
			echo "</select></tr></td>";
			echo "<tr><td><input type='submit' value='Submit'></tr></td>";
			echo "</form>";
			echo "</table>";
	}
		
	
	if($formtype=='upload_marks')
	{
		$current_year = date(Y);
		echo "<table id='student_attendence' align='center'>";
		echo "<form id='upload_marks' action='info.php?mode=upload_marks' method='post'>";
		$form->form_dropdown_field('dropdown',$options=array('Sessional_Marks','Internal_Marks','External_Marks'),'Marks_Type','','','required','');
		$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
		$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
		$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
		$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
		echo "<tr><td><input type='submit' value='submit' onclick='set_semester_branch()' /></tr></td>";
		echo "</form></table>";
	}
	
	if($formtype=='student_course_record')
	{
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=sessional_marks' method='post'>";
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=upload_marks' method='post'>";
		$form->form_dropdown_field('dropdown',$options=array('Sessional_Marks','Semester_Final_Marks','Internal_Marks','External_Marks'),'Marks_Type','','','required','');
		$sql_details = "SELECT Batch,Course,Branch FROM student_main WHERE Roll_No='".$_SESSION['rollno']."'";
		$result_details = mysql_query($sql_details)or die(mysql_error());
		$row_details = mysql_fetch_assoc($result_details);
		$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
		$form->form_text_field('hidden','Course','','',$row_details['Course']);
		$form->form_text_field('hidden','Batch','','',$row_details['Batch']);
		$form->form_text_field('hidden','Branch','','',$row_details['Branch']);
		echo "<tr><td><input type='submit' value='submit' /></tr></td>";
		echo "</form></table>";
	}
	
	if($formtype=='teacher_assignment')
	{
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=teacher_assignment' id='teacher_assignment' 
			 onsubmit='return checkbranch()' method='post'>";
		$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
		$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
		$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
		$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
		echo "<tr><td><input type='submit' value='submit' /></tr></td>";
		echo "</form></table>";
		
		
	}
	
	if($formtype=='student_assignment')
	{
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=student_assignment' method='post'>";
		$form->form_dropdown_field('dropdown',array(1,2,3,4,5,6,7,8),'Semester','','','required','','');
		echo "<tr><td><input type='submit' value='submit' /></tr></td>";
		echo "</form></table>";
	}
	
	if($formtype=='change_password_user')
	{
		echo "<table id='student_details' align='center'>";
		echo "<tr><td><b>Update Your Password</b></td></tr></table>";
		echo "<table id='student_details' align='center'>";
		echo "<form id='change_password_user' action='info.php?mode=user_change_password' 
			onsubmit='return change_password_user(\"".$_SESSION['password']."\")' method='post'>";
		$form->form_text_field('password','Current_Password','required','Current_Password','');
		$form->form_text_field('password','New_Password','required','New_Password','');
		$form->form_text_field('password','Confirm_Password','required','Confirm_Password','');
		echo "<tr><td><input type='submit' value='Change' />";
	}
	
	if($formtype=='edit_login')
	{
		echo "<p id='introduction'><table align='center'><tr><td><p>Here You can Edit The Login Details For Student,Admin,Teacher And TnP.</p></td></tr></table>";
		$i = 1;
		$result = mysql_query("SELECT Username,Full_Name,User_Type,Department,Mobile,Email From users WHERE User_Type!='Student' ORDER BY User_Type ASC") or die(mysql_error());
		echo "<table align='center'>";
		echo "<tr><td><table id='test'><tr><th>Sr.No.</th><th>Fullname</th><th>Username</th><th>UserType</th><th>Edit</th><th>Delete</th></tr>";
		while($row = mysql_fetch_assoc($result)) {
			echo "<tr>
			<td>".$i."</td><td>".$row['Full_Name']."</td>";
			echo "<td>".$row['Username']."</td>";
			echo "<td>".$row['User_Type']."</td>";
			echo "<td><form action='info.php?mode=edit_login' method='post' target='_blank'>
			<input type='hidden' name='Full_Name' value='".$row['Full_Name']."' />
			<input type='hidden' name='Username' value='".$row['Username']."' />
			<input type='hidden' name='User_Type' value='".$row['User_Type']."' />
			<input type='hidden' name='Department' value='".$row['Department']."' />
			<input type='hidden' name='Mobile' value='".$row['Mobile']."' />
			<input type='hidden' name='Email' value='".$row['Email']."' />
			<input type='submit' value='Edit' /></form></td>";
			echo "<td><form action='info.php?mode=edit_login' method='post' target='_blank'>
			<input type='hidden' name='Full_Name' value='".$row['Full_Name']."' />
			<input type='hidden' name='Username' value='".$row['Username']."' />
			<input type='hidden' name='User_Type' value='".$row['User_Type']."' />
			<input type='hidden' name='Department' value='".$row['Department']."' />
			<input type='hidden' name='Mobile' value='".$row['Mobile']."' />
			<input type='hidden' name='Email' value='".$row['Email']."' />
			<input type='hidden' name='Delete_User' value='du' />
			<input type='submit' value='Delete' onclick='return confirm_delete_user(\"".$row['Full_Name']."\")'/></form></td></tr>";
			$i += 1;
		}
		echo "</td></table>";
		echo "<td><table id='student_details' align='center'>";
		echo "<tr><td></td><td><b>Enter Roll No Of Student.</b></td></tr>";
		echo "<form action='info.php?mode=edit_login' method='post'>";
		$form->form_text_field('text','Roll_No','required','','');
		echo "<tr><td><input type='submit' value='Search' /></tr></td>";
		echo "</form></table></table></td></table>";
	}
	
	if($formtype=='add_other')
	{
		echo "<p id='introduction'><table align='center'><tr><td><p>Please Fill The details below to Add New User (Admin,Teacher,TnP) To The Database.</p></td></tr></table>";
		echo "<table id='student_details' align='center'>";
		echo "<form id='add_admin' action='info.php?mode=add_other' method='post'>";
		echo "	<tr><td>User Type:</td><td><select name='usertype' id='usertype' class='required' onchange='add_other_admin_department()'>
					<option value='Admin'>Admin</option>
					<option value='Teacher'>Teacher</option>
					<option value='Clerk'>Clerk</option>
					<option value='Advisor'>Advisor</option>
					<option value='Training And Placement'>Training And Placement</option>
					</select></tr></td>";
		echo "<tr><td><div id='department_div_name'></div></td><td><div id='department_div'></div></td></tr>";
		echo "<script type='text/javascript'>add_other_admin_department()</script>";
		echo "<tr><td>Username</td><td><input type='text' name='Username' id='Username' class='required' />";
		echo "<tr><td>Password</td><td><input type='password' name='Password' id='New_Password' class='required' />";
		echo "<tr><td>Confirm Password</td><td><input type='password' name='Password_Confirm' id='Confirm_Password' class='required' />";
		echo "<tr><td>Full Name</td><td><input type='text' name='Full_Name' id='Full_Name' class='required' />";
		echo "<tr><td>Mobile</td><td><input type='text' name='Mobile' id='Mobile' class='required' />";
		echo "<tr><td>Email</td><td><input type='text' name='Email' id='Email' class='required' />";
		echo "<input type='hidden' name='Department' id='Department' value='' />";
		echo "<tr><td><input type='submit' value='Add' onclick='return add_other_checkpassword()' />";
		echo "</form></table>";
	}
	
	
	if($formtype=='internal_external_marks')
	{
		echo "<table id='student_attendence' align='center'>";
		echo "<form action='info.php?mode=internal_external_marks' onsubmit='return checkbranch()' method='post'>";
		echo "<tr><td>Type of Marks</td><td> <select name='Marks_Type'>";
		echo "<option value='Internal_Marks'>Internal Marks</option>";
		echo "<option value='External_Marks'>External Marks</option>";
		$form->form_dropdown_field('dropdown','','Course','student_main','Course','required','Course','');
		$form->form_dropdown_field('dropdown','','Batch','student_main','Batch','required','Batch','');
		$form->form_dropdown_field('dropdown','','Branch','student_main','Branch','','Branch','');
		semester_sessional("Semester");
		echo "<tr><td><input type='submit' value='submit' /></tr></td>";
		echo "</form></table>";
	}
	
	if($formtype=='assign_subjects') {
		echo "<p align='center'>Please Select Semester</p>";
			echo "<table id='student_details' align='center'>";
			echo "<form id='add_admin' action='info.php?mode=assign_subjects' method='post'>";
			if($_SESSION['department']=='MBA') {
				if(date(n)<=7){
					$start=2;
				}
				else {
					$start = 1;
				}
				$limit = 4;
			}
			elseif($_SESSION['department']=='MCA') {
				if(date(n)<=7){
					$start=2;
				}
				else {
					$start = 1;
				}
				$limit = 6;
			}
			else {
				if(date(n)<=7){
					$start=4;
				}
				else {
					$start =3;
				}
				$limit=8;
			}
			echo "<tr><td>Semester</td><td><select name='Semester'>";
			for($i=$start;$i<=$limit;$i +=2) {
				echo "<option value='".$i."'>".$i."</option>";
			}
			echo "<tr><td><input type='submit' value='Go' /></tr></td></select></td></tr></table>";
		}
	
	if($formtype=='create_groups') {
		echo "<table align='center' id='student_details'>";
		if($additional_detail=='New Group') {
			echo "<b><center><p>NOTE: If Batch is not listed, that means you have already created Group For That Batch, So try 'New Subgroup' or 'Edit Existing Grouping'</p></b></center>";
			$course = $_SESSION['department'];
			$current_year = date(Y);

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
			echo "<form id='New_Group' action='info.php?mode=create_groups' method='post' />";
			echo "<tr><td>Batch</td><td><select name='Batch'>";
			if(date(n)<=7) {
				$i = 1;
			}
			else
			{
				$i = 0;
			}
			$batch_check = $current_year-4;
			$sql_batch = mysql_query("SELECT Batch FROM student_groups WHERE Group_Created_By='".$_SESSION['username']."' AND Batch>='".$batch_check."'") or die(mysql_error());
			while($row = mysql_fetch_array($sql_batch)) {
				$batch_array[] = $row[0];
			}
			for($i;$i<=$limit;$i++) {
				$current_year_f = $current_year-$i;
				if(in_array($current_year_f,$batch_array)) {
					continue;
				}
				else {
					echo "<option value='".$current_year_f."'>".$current_year_f."</option>";
				}
			}
			echo "</select></tr></td>";
			echo "<tr><td>No. Of Groups</td><td><input type='text' name='No_Of_Groups' class='required'/></tr></td>";
			echo "<input type='hidden' name='Add_New_Group' value='Add_New_Group' />";
			echo "<tr><td><input type='submit' value='Go' /></form>";
		}
		if($additional_detail=='New Subgroup') {
			echo "<b><center><p>NOTE: If Group Name is not listed, that means you have already created Subgroup For That Group, So try 'Edit Existing Grouping'</p></center></b>";
			$current_year = date(Y);
			$current_year_f = $current_year-4;
			$sql_batch = mysql_query("SELECT DISTINCT Batch FROM student_groups WHERE Group_Created_By='".$_SESSION['username']."' AND Batch>='".$current_year_f."' ORDER BY Batch DESC") or die(mysql_error());
			echo "<form id='New_Subgroup' action='info.php?mode=create_groups' method='post' />";
			echo "<tr><td>Batch</td><td><select name='Batch' id='Batch' onchange='groupname_selector()'>";
			while($row = mysql_fetch_array($sql_batch)) {
				echo "<option value='".$row[0]."'>".$row[0]."</option>";
			}
			echo "</select></tr></td>";
			echo "<tr><td><div id='group_div_name'></div></td><td><div id='group_div'></div></td></tr>";
			echo "<script type='text/javascript'>groupname_selector()</script>";
			echo "<tr><td>No. Of Subgroups</td><td><input type='text' name='No_Of_Subgroups' class='required' /></tr></td>";
			echo "<input type='hidden' name='Add_New_Subgroup' value='Add_New_Subgroup' />";
			echo "<input type='hidden' name='Group_Name' id='Group_Name' value='' />";
			echo "<tr><td><input type='submit' value='Go' onclick='set_groupname()'/></tr></td></form>";
		}
		if($additional_detail=='Edit Existing Grouping') {
			echo "<form action='info.php?mode=create_groups' method='post'>";
			echo "<tr><td>Select Grouping</td><td><select name='Type_Of_Editing' >";
			echo "<option value='Edit_Grouping'>Edit Grouping</option>";
			echo "<option value='Edit_Subgrouping'>Edit Subgrouping</option>";
			echo "</select></td></tr>";
			echo "<input type='hidden' name='Editing_Grouping' value='Edit_Grouping' />";
			echo "<tr><td><input type='submit' value='Go' /></tr></td></form></table>";
		}
	}
	
	
	if($formtype=='editing_grouping') {
		echo "<table id='student_details' align='center' >";
		if($additional_detail=='Edit_Grouping') {
			echo "<form action='info.php?mode=create_groups' method='post'>";
			$sql_batch  = mysql_query("SELECT DISTINCT Batch FROM student_groups WHERE Group_Created_By='".$_SESSION['username']."'") or die(mysql_error());
			echo "<tr><td>Batch</td><td><select name='Batch' id='Batch'>";
			while($row = mysql_fetch_array($sql_batch)) {
				echo "<option value='".$row[0]."'>".$row[0]."</option>";
			}
			echo "</select></tr></td>";
			echo "<input type='hidden' name='Show_Groups_To_Edit' value='' />";
			echo "<tr><td><input type='submit' value='Go'/></form></table>";
		}
		if($additional_detail=='Edit_Subgrouping') {
			echo "<form action='info.php?mode=create_groups' method='post'>";
			$sql_batch  = mysql_query("SELECT DISTINCT Batch FROM student_groups WHERE Group_Created_By='".$_SESSION['username']."'") or die(mysql_error());
			echo "<tr><td>Batch</td><td><select name='Batch' id='Batch' onchange='groupname_selector_edit()' >";
			while($row = mysql_fetch_array($sql_batch)) {
				echo "<option value='".$row[0]."'>".$row[0]."</option>";
			}
			echo "</select></tr></td>";
			echo "<tr><td><div id='group_div_name'></div></td><td><div id='group_div'></div></td></tr>";
			echo "<script type='text/javascript'>groupname_selector_edit()</script>";
			echo "<input type='hidden' name='Show_Subgroups_To_Edit' value='' />";
			echo "<input type='hidden' name='Group_Name' id='Group_Name' value='' />";
			echo "<tr><td><input type='submit' value='Go' onclick='set_groupname()' /></form></table>";
		}
	}
	
	
	
	
	if($formtype=='attendence_record')
	{
		$bupper = date(Y);
		$blower = date(Y)-4;
		$period = mysql_query("SELECT DISTINCT Start_Date,End_Date FROM student_attendance WHERE Branch='".$_SESSION['department']."' 
									AND Batch>='".$blower."' 
									AND Batch<='".$bupper."'") or die(mysql_error());
		echo "<table id='student_details' align='center'>";
		echo "<form id='add_admin' action='info.php?mode=admin_check_records' method='post'>";
		echo "	<tr><td>Period:</td><td><select name='period' id='period' class='period'>";
					while($row = mysql_fetch_assoc($period)) {
						echo "<option value='".$row['Start_Date']." TO ".$row['End_Date']."'>".$row['Start_Date']." TO ".$row['End_Date']."</option>";
					}
		echo "</select></tr></td>";
		echo "	<tr><td>Batch:</td><td><select name='batch' id='batch' class='required'>";
					for($i = $bupper;$i>=$bupper-4;$i--) {
						echo "<option value='".$i."'>".$i."</option>";
					}
		echo "</select></tr></td>";
		echo "	<tr><td>Semester:</td><td><select name='semester' id='semester' class='required'>";
					for($i = 1;$i<=8;$i++) {
						echo "<option value='".$i."'>".$i."</option>";
					}
		echo "</select></tr></td>";
		echo "<tr><td><input type='submit' value='Check' />";
		echo "</form></table>";
	}
		
}

?>
