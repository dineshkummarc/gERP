<?php

$q = $_GET[q];
$d = $_GET[d];
require_once('../../../config.php');
mysql_select_db("gndec_erp",$conn);
$sql = "SELECT District_Name FROM district WHERE State_Name='".$q."' ORDER BY District_Name ASC";
$result = mysql_query($sql);
echo "<select name='District' id='District' >";
if($d!='undefined' && $d!='')
{
	echo "<option value='".$d."' selected='selected'>".$d."</option>";

}
while($dist = mysql_fetch_array($result)){
	
	echo "<option value='".$dist[0]."'>".$dist[0]."</option>";
}
echo "</select></td></tr>";
?>
