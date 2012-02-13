<?php
/* This is the Main File which Contains all the functions	*
 *This is where the main processing of the program happens	*/

include_once('../../../includes/paths.php');

require($main_dir.'PHPMailer/class.phpmailer.php');

mysql_select_db("gndec_erp",$conn);

require_once ($main_dir.'input_form_class.php');

require_once('includes.php');

function CheckForLogin() {
	if(!isset($_SESSION['usertype'])) {
		session_destroy();
		header("location:index.php");
	}	
}

/* This Function is Used To fetch the names of tables and Columns from the
 * database*/
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


function add_user_form()
{
		$form = new student_form();
		echo "<p id='introduction'><table align='center'><tr><td><p>Please Fill The
    details below to Add New Student To The Database.</p></td></tr></table>";

    echo "<form id='add_user' action='insert_user.php' method='post'
    enctype='multipart/form-data'>";

    echo "<table align='center' id='student_details'>";

    echo "<tr><td>Admission Type</td><td><select name='Admission_Type'
    id='Admission_Type' class='required' onchange='disappear_ad_no()'>";

    echo "<option value='PTU Councelling' selected='selected'>PTU Councelling
    </option>";

    echo "<option value='Direct Admission'>Direct Admission</option>";

    echo "<option value='Leet'>Leet</option>";

    echo "<option value='Economically Weaker Section'>Economically Weaker
    Section</option>";

    echo "</select></tr></td>";

    echo "<script type='text/javascript'>admission_no()</script>";

    echo "<tr><td><div id='ad_no_name'>Admission No.</div></td><td><div
    id='ad_no'></div></td></tr>";

    $form->form_dropdown_field('dropdown',array('15','85'),'15_85_Quota','','',
    'required','Course','85');

    $form->form_dropdown_field('dropdown',array('B.Tech','M.Tech','MBA','MCA'),
    'Course','','','required','Course','B.Tech');

    $form->form_dropdown_field('dropdown','',$form->Branch,'student_main',
    'Branch','','Branch','Information Technology');

    echo "<tr><td>Batch</td><td><input type='text' name='Batch' class='required'
     id='batch'>(e.g 2007,2008,2009,2010)</td></tr>";

    $form->form_text_field('text',$form->Roll_No,'required');

    $form->form_text_field('text',$form->Univ_Roll_No,'');

    $form->form_text_field('text','Date_Of_Admission','required','DOA','');

    $form->form_text_field('text','Date_Of_Joining','','DOJ','');

    $form->form_text_field('text','CET_Rank','','','');

    $form->form_text_field('text','AIEEE_Rank','','','');

    $form->form_dropdown_field('dropdown',array('Miss','Mr.','Ms.','Mrs.','Dr.')
    ,'Title','','','required','','Mr.');

    echo "<tr><td>Upload Image</td><td><input type='file' name='Image_Path'
    id='Image_Path' /></td></tr>";

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

    $form->form_dropdown_field('dropdown','',$form->Gender,'student_main',
    'Gender','required','','Male');

    $form->form_text_field('text',$form->DOB,'required','dob');

    $form->form_dropdown_field('dropdown',array('Hindu','Muslim','Christian',
    'Sikh','Buddhist','Jain','Other'),'Religion','','','required','','Sikh');

    $form->form_dropdown_field('dropdown',array('Rural','Urban'),
    'Rural_Or_Urban','','','required','','Rural');

    $form->form_dropdown_field('dropdown',array('Sikh Minority','Open Quota'),
    'Student_Category','','','required','','Open Quota');

    $form->form_dropdown_field('dropdown',array('Sikh Minority','Open Quota'),
    'Alloted_Category','','','required','','Open Quota');

    $form->form_dropdown_field('dropdown',array('SC','BC','Border Area',
    'Backward Area','Sports Person','Freedom Fighter','Disabled Person',
    'Defence',
    'Paramilitary','Terrorist Victim','General'),'Student_Sub_Category',
    '','','required','','General');

    $form->form_dropdown_field('dropdown',array('SC','BC','Border Area',
    'Backward Area','Sports Person','Freedom Fighter','Disabled Person',
    'Defence','Paramilitary','Terrorist Victim','General'),
    'Alloted_Sub_Category','','','required','','General');

    $form->form_dropdown_field('dropdown','',$form->Blood_Group,
    'student_detail','Blood_Group','required','','A+ve');

    $form->form_dropdown_field('dropdown',array('Yes','No'),
    $form->Hostler,'','','required','','No');	

    echo "<tr><td>Height (In Centimeters)</td><td>
    <input type='text' name='Height' /></td></tr>";

    echo "<tr><td>Weight (In Kilograms)</td><td>
    <input type='text' name='Weight'  /></td></tr>";

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

    $form->form_dropdown_field('dropdown','',$form->_10th_Board,'school_board',
    'Board_Name','required','','Andhra Pradesh Board of Secondary Education');

        echo "<tr><td><b>Only For Non-LEET Students</b></td></tr>";

    $form->form_text_field('text',$form->_12th_Passing_Year,'');

    $form->form_text_field('text',$form->_12th_Max_Marks,'');

    $form->form_text_field('text',$form->_12th_Obtained_Marks,'');

    $form->form_text_field('text',$form->_12th_School_Name,'','');

    $form->form_dropdown_field('dropdown','',$form->_12th_Board,'school_board',
    'Board_Name','','','Andhra Pradesh Board of Secondary Education');

        echo "<tr><td><b>Only For LEET Students</td></b></tr>";

    $form->form_text_field('text',$form->Diploma_Passing_Year,'');

    $form->form_text_field('text',$form->Diploma_Max_Marks,'');

    $form->form_text_field('text',$form->Diploma_Obtained_Marks,'');

    $form->form_text_field('text',$form->Diploma_University,'');

    $form->form_text_field('text',$form->Diploma_College,'');

    echo "<tr><td>Kashmiri Migrant ?</td><td>Yes<input type='radio'
    name='Kashmiri_Migrant' value='Yes' />No<input type='radio' name='Kashmiri_Migrant'
    value='No' / checked='checked'></td></tr>";

    $sql_state = "SELECT State_Name FROM state ORDER BY State_Name ASC";

    $result_state = mysql_query($sql_state);

    echo "<tr><td>State/Union Territory</td><td><select name='State' id='State'
     onchange='changedist(this.value)'>";

    while($row_state = mysql_fetch_array($result_state)){

    	echo "<option value='".$row_state[0]."'>".$row_state[0]."</option>";
		}

    echo "</select></tr></td>";

    echo "<script type='text/javascript'>setstate()</script>";

    echo "<tr><td><div id='dist_name'>District</div></td><td>
    <div id='dist'></div></tr></td>";

    //$form->form_dropdown_field('dropdown','',$form->State,'state','State_Name','required');

    //$form->form_dropdown_field('dropdown','','District','district','District_Name','required');

    $form->form_text_field('text',$form->City,'required');

    $form->form_text_field('text',$form->Pincode,'required');

    echo "<tr><td><input type='submit' name='submit' value='submit'
    onclick='return confirm_add()'></td></tr>

    </table><br />";

    echo "</form>";
}



?>
