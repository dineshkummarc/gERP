<?php
$path = $_SERVER['SCRIPT_NAME'];
$_gERP = explode('gERP',$path);
$gERP = $_gERP[0]."gERP";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><title>GNDEC ERP SYSTEM</title>
<link rel="shortcut icon" href=<?php echo $gERP."/media/images/favicon.ico"; ?> />
<link rel="stylesheet" type="text/css" href=<?php echo $gERP."/styles/style.css"; ?> />
<div id="logo" align="center">
<a href=<?php echo $gERP.'/options.php';?> >
	<img src=<?php echo $gERP.'/images/logo.png';?> height="100" width="600" alt='logo'></a>
<table>
	<tr>
		<td><a href=<?php echo $gERP.'/options.php'; ?>><h2>Home</h2></a></td>
		<td><a href=<?php echo $gERP.'/index.php?mode=logout'; ?>><h2>Logout</h2></a></td>
	</tr>
</table>
</div>

</head>
<body id='application'>
