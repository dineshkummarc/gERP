<?php
$path = $_SERVER['SCRIPT_NAME'];
$_gERP = explode('gERP',$path);
$gERP = $_gERP[0]."gERP";
?>
<div id='admin_content'>
<table class="info" align="center">
	<tr>
		<td>
			<form action=<?php echo $gERP."/modules/student/student_details/info.php?mode=student_details"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/student_details.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=add_user"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/add_student.png'; ?> />
			</form>
		</td>
		<td>	
			<form action=<?php echo $gERP."/info.php?mode=add_other"; ?> method="post">
			<input type='hidden' name='usertype' value='Admin' />
			<input type='image' src=<?php echo $gERP.'/media/images/add_other.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=edit_login"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/manage_users.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=admin_send_sms"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/send_sms.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=get_report"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/get_report.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=assign_subjects"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/assign_subjects.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=user_change_password"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/change_password.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=create_groups"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/create_groups.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=admin_check_records"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/check_records.png'; ?> />
			</form>
		<td>
			<form action=<?php echo $gERP."/docs/user_docs/admin_help.php"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/help.png'; ?> />
			</form>
		</td>
	</tr>
</table>
