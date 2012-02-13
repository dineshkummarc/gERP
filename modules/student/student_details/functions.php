<?php
/* This is the Main File which Contains all the functions	*
 *This is where the main processing of the program happens	*/

require('paths.php');

require($main_dir.'PHPMailer/class.phpmailer.php');

mysql_select_db("gndec_erp",$conn);

require($main_dir.'input_form_class.php');

require('includes.php');

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
					$img_path = $media_url.'images/student_images/pna.png';
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
  require('paths.php');
	$table_columns = get_tables_cols();
	$image_result = mysql_query("SELECT Image_Path FROM student_images WHERE Roll_No='".$rollno."'") or die(mysql_error());
	$image_path = mysql_fetch_array($image_result);
	if($image_path=='') {
		$image_path_f = $media_url.'images/student_images/pna.png';
	}
	else {
		$image_path_f = $media_url.'images/student_images/'.$image_path[0];
	}
	echo "<table align='center'><tr><td><img id='profile' style='border:2px solid #7a89a5' src='".$image_path_f."' height='200' width='200' /></td></tr></table>";
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
		#echo "<script type='text/javascript'>setstate()</script>";
		echo "<tr><td><div id='dist_name'>District</div></td><td><div id='dist'></div></tr></td>";
		
		//$form->form_dropdown_field('dropdown','',$form->State,'state','State_Name','required','',$student_address['State']);
		//$form->form_dropdown_field('dropdown','','District','district','District_Name','required','',$student_address['District']);
		$form->form_text_field('text',$form->City,'required','',$student_address['City']);
		$form->form_text_field('text',$form->Pincode,'required','',$student_address['Pincode']);
		echo "<tr><td><input type='submit' name='submit' onclick='return confirm_edit(\"".$student_main['Roll_No']."\")' value='submit'></td></tr>
		</table><br />";
		echo "</form>";
}

?>
