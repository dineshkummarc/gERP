<?php
/* This is the Main File which Contains all the forms
  This Function is used to display the various Form based on the type of form given
*/
require_once('paths.php');
require_once($includes_dir.'config.inc');
require_once('functions.php');
CheckForLogin();
mysql_select_db("gndec_erp",$conn);
require_once ($main_dir.'input_form_class.php');
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
		
}

?>
