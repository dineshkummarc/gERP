<?php
require_once('config.php');
mysql_select_db("gndec_erp",$conn);
$result = mysql_query("SELECT DISTINCT Branch FROM student_main ORDER BY Branch ASC") or die(mysql_error());
echo "<select name='Branch_Ajax' id='Branch_Ajax'>";
while($row = mysql_fetch_array($result)) {
	if($row[0]=='' or $row[0]=='N/A') {
		continue;
	}
	else {
	echo "<option value='".$row[0]."'>".$row[0]."</option>";
}
}

echo "</select>";
?>
