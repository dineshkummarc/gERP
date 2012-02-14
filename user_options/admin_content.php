<?php
include_once('../includes/paths.php');
?>
<div id='admin_content'>
<table class="info" align="center">
	<tr>
		<td>
			<form action=<?php echo $main_url."modules/student/student_details/info.php?mode=student_details"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/student_details.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."modules/student/admission/info.php?mode=add_user"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/add_student.png'; ?> />
			</form>
		</td>
		<td>	
			<form action=<?php echo $main_url."modules/employee/addEmployee/info.php?mode=add_other"; ?> method="post">
			<input type='hidden' name='usertype' value='Admin' />
			<input type='image' src=<?php echo $media_url.'images/add_other.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $main_url."info.php?mode=edit_login"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/manage_users.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."info.php?mode=admin_send_sms"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/send_sms.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."info.php?mode=get_report"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/get_report.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $main_url."info.php?mode=assign_subjects"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/assign_subjects.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."info.php?mode=user_change_password"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/change_password.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."info.php?mode=create_groups"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/create_groups.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $main_url."info.php?mode=admin_check_records"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/check_records.png'; ?> />
			</form>
		<td>
			<form action=<?php echo $main_url."docs/user_docs/admin_help.php"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/help.png'; ?> />
			</form>
		</td>
	</tr>
</table>
