<?php
require_once ('config.php');
mysql_select_db("gndec_erp",$conn);


// Class contain all Student Personal Data And dynamic form gentration function
class student_form
{

	//Declare Form Variables for Student Class
	var $Roll_No='Roll_No';
	var $Student_First_Name='Student_First_Name';
	var $Student_Middle_Name='Student_Middle_Name';
	var $Student_Last_Name='Student_Last_Name';
	var $Father_First_Name='Father_First_Name';
	var $Father_Middle_Name='Father_Middle_Name';
	var $Father_Last_Name='Father_Last_Name';
	var $Father_Occupation='Father_Occupation';
	var $Mother_First_Name='Mother_First_Name';
	var $Mother_Middle_Name='Mother_Middle_Name';
	var $Mother_Last_Name='Mother_Last_Name';
	var $Mother_Occupation='Mother_Occupation';
	var $Univ_Roll_No='Univ_Roll_No';
	var $Gender='Gender';
	var $DOB='DOB';
	var $Batch='Batch';
	var $Branch='Branch';
	var $Course='Course';
	var $Category='Category';
	var $Blood_Group='Blood_Group';
	var $Hostler='Hostler';
	var $Hostel_Id='Hostel_Id';
	var $Height='Height';
	var $Weight='Weight';
	var $Mobile='Mobile';
	var $Parent_Phone='Parent_Phone';
	var $Email='Email';
	var $Alt_Email='Alt_Email';
	var $Address_Line1='Address_Line1';
	var $Address_Line2='Address_Line2';
	var $City='City';
	var $State='State';
	var $Pincode='Pincode';
	var $_10th_Passing_Year='10th_Passing_Year';
	var $_10th_Max_Marks='10th_Max_Marks';
	var $_10th_Obtained_Marks='10th_Obtained_Marks';
	var $_10th_School_Name = '10th_School_Name';
	var $_10th_Board='10th_Board';
	var $_12th_Passing_Year='12th_Passing_Year';
	var $_12th_Max_Marks='12th_Max_Marks';
	var $_12th_Obtained_Marks='12th_Obtained_Marks';
	var $_12th_School_Name = '12th_School_Name';
	var $_12th_Board='12th_Board';
	var $Diploma_Passing_Year='Diploma_Passing_Year';
	var $Diploma_Max_Marks='Diploma_Max_Marks';
	var $Diploma_Obtained_Marks='Diploma_Obtained_Marks';
	var $Diploma_University='Diploma_University';
	var $Diploma_College='Diploma_College';
	var $Subject = 'Subject';
	var $Subject_Code = 'Subject_Code';
	var $Obtained_Marks = 'Obtained_Marks';
	var $Max_Marks = 'Max_Marks';
	var $Sessional_No = 'Sessional_No';
	var $Semester = 'Semester';
	var $Absent = 'Absent';
	var $Teacher_Username ='Teacher_Username';
	var $Board_Name = 'Board_Name';
	var $District_Name = 'District_Name';
	var $Start_Date = 'Start_Date';
	var $End_Date = 'End_Date';
	var $Total_Lecture = 'Total_Lecture';
	//Start of HTML Form using functions for different fields
	// First Function for Text And Password Field
	public function form_text_field($type,$name,$class,$id,$value)
	{
		if ($type=="text"||"password"||"hidden")
		{
			
			echo "<tr>";
			if($type!='hidden')
			{
				echo "<td>".str_replace('_',' ',$name)."</td>";
			}
			echo "<td><input type='".$type."' name='".$name."' class='".$class."' id='".$id."' value='".$value."' /></td>";
			echo "</tr>";	
		}
	}
	//file Field for Picture
	public function form_file_field($type,$name)
	{
		if ($type=="file")
		{
			echo "<tr>";
			echo "<td>".str_replace('_',' ',$name)."</td>";
			echo "<td><input type='".$type."' name='".$name."' size='25'/></td>";
			echo "</tr>";	
		}
	}
	// Function For TextArea Field
	public function form_textarea_field($type,$name,$rows,$cols)
	{
		if ($type =="textarea")
		{
			echo "'".$name."'";
			echo "<textarea name='".$name."' rows='".$rows."' cols='".$cols."'>";
			echo "</textarea>";
		}
	}
	// Function for DropDown Field
	public function form_dropdown_field($type,$options,$name,$table,$table_colume,$class,$id,$value)
	{
		if($options[0]!='')
		{
			echo "<tr><td>".str_replace('_',' ',$name)."<td><select name='".$name."' class='".$class."' id='".$id."'>";
			echo "<option value='".$value."' selected='selected'>".$value."</option>";
			foreach($options as $key=>$option)
			{
				echo "<option value='".$option."'>".str_replace('_',' ',$option)."</option>";
			}
			echo "</select></tr></td>";
		}
		else
		{
			if($type=="dropdown")
			{	
				$sql="Select distinct ".$table_colume." from ".$table."";
				$result = mysql_query($sql) or die("Error in Query: $sql " . mysql_error()); 
				echo "<tr><td>".str_replace('_',' ',$name)."<td><select name='".$name."' class='".$class."' id='".$id."'>";
				echo "<option value='".$value."' selected='selected'>".$value."</option>";
				while($row=mysql_fetch_array($result))
				{	
					if($row[0]!='' && $row[0]!='N/A')	
					echo " <option value='".$row[0]."'>".$row[0]."</option> ";
				}
				echo "</select></tr></td>";
			}
		}
	}
	// Function for Checkbox Field 
	public function from_checkbox_field($type,$name,$table,$table_colume)
	{
		if ($type=="checkbox"||"radio")
		{
			$sql="Select distinct".$table_colume." from".$table." where 1";
			$result = mysql_query($sql, $conn) or die("Error in Query: $sql " . mysql_error()); 
			while($row=mysql_fetch_array($result))
			{
				if($row[0]!="")
				{
					echo str_replace('_',' ',$name);
					echo "<input type='".$type."' name='".$name."' value='".$row[0]."'/>".$row[0]."<br />";
				}
				else
				{
					echo " Data not found";
				}
			}
		}
	}
}	
