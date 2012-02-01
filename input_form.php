<?php
require_once ('config.php');
require_once ('input_form_class.php');

echo "<script type='text/javascript' src='js/validate.js'></script>";
echo "<script type='text/javascript' src='js/jquery-1.5.min.js'></script>";
echo "<script type='text/javascript' src='js/jquery-validate/jquery.validate.js'></script>";
echo "<link href='js/datepick/jquery.datepick.css' rel='stylesheet' type='text/css'>";
echo "<script type='text/javascript' src='js/datepick/jquery.datepick.js'></script>";
//echo "<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js'></script>";
echo "<script>
  $(document).ready(function(){
    $('#add_user').validate();
    $('#dob').datepick({dateFormat:'dd-MM-yyyy'});
    $('#edate').datepick({dateFormat:'dd-MM-yyyy'});
  });
  </script>";
// function for display simple form


function add_user_form()
{
		$form = new student_form();
		echo "<form id='add_user' action='insert_user.php' method='post'>";
		echo "<table align='center' id='student_details'>";
		$form->form_text_field('text',$form->Student_First_Name,'required');
		$form->form_text_field('text',$form->Student_Middle_Name,'required');
		$form->form_text_field('text',$form->Student_Last_Name,'required');
		$form->form_text_field('text',$form->Roll_No,'required');
		$form->form_text_field('text',$form->University_Roll_No,'required');
		/*$form->form_dropdown_field('dropdown',$form->Gender,'student_main','Gender');
		$form->form_dropdown_field('dropdown',$form->Batch,'student_main','Batch');
		$form->form_dropdown_field('dropdown',$form->Course,'student_main','Course');
		$form->form_dropdown_field('dropdown',$form->Branch,'student_main','Branch');
		$form->form_text_field('text',$form->Father_First_Name);
		$form->form_text_field('text',$form->Father_Middle_Name);
		$form->form_text_field('text',$form->Father_Last_Name);
		$form->form_text_field('text',$form->Mother_First_Name);
		$form->form_text_field('text',$form->Mother_Middle_Name);
		$form->form_text_field('text',$form->Mother_Last_Name);*/
		$form->form_text_field('text',$form->DOB,'required','dob');
		/*$form->form_file_field('file',$form->Photo);
		$form->form_dropdown_field('dropdown',$form->Category,'student_detail','Category');
		$form->form_dropdown_field('dropdown',$form->Blood_Group,'student_detail','Blood_Group');
		$form->form_dropdown_field('dropdown',$form->Hostler,'student_detail','Hostler');	
		$form->form_text_field('text',$form->Height);
		$form->form_text_field('text',$form->Weight);
		$form->form_text_field('text',$form->Mobile);
		$form->form_text_field('text',$form->Parent_Moblie);
		$form->form_text_field('text',$form->Email);
		$form->form_text_field('text',$form->Alt_Email);
		$form->form_text_field('text',$form->Address_Line_1);
		$form->form_text_field('text',$form->Address_Line_2);
		$form->form_text_field('text',$form->City);
		$form->form_text_field('text',$form->State);
		$form->form_text_field('text',$form->Pincode);*/
		echo "<tr><td><input type='submit' name='submit' value='submit'></td></tr>
		</table><br />";
		echo "</form>";
}
?>
