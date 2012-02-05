<?php
?>
<script type='text/javascript' src='js/validate.js'></script>
<script type='text/javascript' src='js/jquery-1.5.min.js'></script>
<script type='text/javascript' src='js/jquery-validate/jquery.validate.js'></script>
<link href='../../../js/datepick/redmond.datepick.css' rel='stylesheet' type='text/css'>
<script type='text/javascript' src='js/datepick/jquery.datepick.js'></script>
<script>
  $(document).ready(function(){
    $('#teacher_attendence').validate({
   messages: {
     Start_Date: '<div id=\'error\'>Please specify Start Date</div>',
     End_Date: '<i>Please Specify End Date<i>'
   }
});
    $('#add_user').validate();
    $('#add_admin').validate();
    $('#DOA').datepick({dateFormat:'yyyy-mm-dd'});
    $('#DOJ').datepick({dateFormat:'yyyy-mm-dd'});
    $('#dop').datepick({dateFormat:'yyyy-mm-dd'});
    $('#dob').datepick({dateFormat:'yyyy-mm-dd', yearRange: '1956:2080'});
  });
 </script>
