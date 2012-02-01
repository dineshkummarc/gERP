<?php
echo "<script type='text/javascript' src='js/validate.js'></script>";
echo "<script type='text/javascript' src='js/jquery-1.5.min.js'></script>";
echo "<script type='text/javascript' src='js/jquery-validate/jquery.validate.js'></script>";
echo "<link href='style.css' rel='stylesheet' type='text/css'>";
echo "<link href='js/datepick/redmond.datepick.css' rel='stylesheet' type='text/css'>";
echo "<script type='text/javascript' src='js/datepick/jquery.datepick.js'></script>";
echo "<script>
  $(document).ready(function(){
    $('#teacher_attendence').validate({
   messages: {
     Start_Date: '<div id=\'error\'>Please specify Start Date</div>',
     End_Date: '<i>Please Specify End Date<i>'
   }
});
    $('#upload_marks').validate();
    $('#assignment_details').validate();
    $('#placement_record').validate();
    $('#New_Group').validate();
    $('#New_Subgroup').validate();
    $('#assi_date').datepick({dateFormat:'dd-MM-yyyy'});
    $('#teacher_assignment').validate();
	$('#attendence_teacher').validate();
    $('#add_user').validate();
    $('#add_admin').validate();
    $('#sdate').datepick({dateFormat:'dd-mm-yyyy'});
    $('#edate').datepick({dateFormat:'dd-mm-yyyy'});
    $('#DOA').datepick({dateFormat:'yyyy-mm-dd'});
    $('#DOJ').datepick({dateFormat:'yyyy-mm-dd'});
    $('#dop').datepick({dateFormat:'yyyy-mm-dd'});
    $('#dob').datepick({dateFormat:'yyyy-mm-dd', yearRange: '1956:2080'});
  });
  </script>";
?>
