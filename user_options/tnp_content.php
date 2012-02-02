<?php
$path = $_SERVER['SCRIPT_NAME'];
$_gERP = explode('gERP',$path);
$gERP = $_gERP[0]."gERP";
?>
<p id='introduction'>
	<table align='center'>
		<tr>
			<td><p>Below are The operations you can perform</p></td>
		</tr>
	</table>
<div id='tnp_content'>
<table class="info" align="center">
<tr>
	<td>	
		<form action=<?php echo $gERP."/info.php?mode=student_details"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/student_details.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $gERP."/info.php?mode=get_report"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/get_report.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $gERP."/info.php?mode=tnp_upload_record"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/upload_placement_record.png'; ?> />
		</form>
	</td>
</tr>
<tr>
	<td>
		<form action=<?php echo $gERP."/info.php?mode=tnp_training_record"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/upload_training_record.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $gERP."/info.php?mode=tnp_edit_record"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/edit_records.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $gERP."/info.php?mode=tnp_statistics"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/statistics.png'; ?> />
		</form>
	</td>
</tr>
<tr>
	<td>
		<form action=<?php echo $gERP."/info.php?mode=user_change_password"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/change_password.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $gERP."/docs/user_docs/tnp_help.php"; ?> method="post">
		<input type='image' src=<?php echo $gERP.'/media/images/help.png'; ?> />
		</form>
	</td>
</tr>
</table>
