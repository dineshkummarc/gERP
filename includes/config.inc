<?php
$db_hostname = "localhost";
$db_username = base64_decode('aGFyYmhhZw==');
$db_password = base64_decode('Z25kZWMgICBoYXI=');

$conn = mysql_connect($db_hostname,$db_username,$db_password) or die("Could not connect: " . mysql_error());
?>
