<?php
$ad_no = $_GET['q'];
if($ad_no!=''){ 
echo "<input name='Admission_No' type='text' value='".$ad_no."' class='required' />";	
}
else {
	echo "<input name='Admission_No' type='text' class='required' />";	
}

?>
