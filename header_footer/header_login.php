<?php
$path = $_SERVER['SCRIPT_NAME'];
$_gERP = explode('gERP',$path);
$gERP = $_gERP[0]."gERP";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><title>GNDEC ERP SYSTEM</title>
<script type='text/javascript' src='js/validate.js'>
</script>
<link rel="shortcut icon" href=<?php echo $gERP."images/favicon.ico"; ?> >
<table align='center' width='100%'>
        <tr><td>
<link rel="stylesheet" type="text/css" href=<?php echo $gERP."/styles/style.css"; ?> />
<div id="logo_login">
<img src='media/images/logo.png' height="100" width="600" />
</div>
</head>
<body id='login'>
