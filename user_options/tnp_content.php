<?php
include_once('../includes/paths.php');
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
		<form action=<?php echo $main_url."modules/student/student_details/info.php?mode=student_details"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/student_details.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $main_url."info.php?mode=get_report"; ?> method="post">
		<input type='image' src=<?php echo $media_url.'images/get_report.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $main_url."info.php?mode=tnp_upload_record"; ?> method="post">
		<input type='image' src=<?php echo $media_url.'images/upload_placement_record.png'; ?> />
		</form>
	</td>
</tr>
<tr>
	<td>
		<form action=<?php echo $main_url."info.php?mode=tnp_training_record"; ?> method="post">
		<input type='image' src=<?php echo $media_url.'images/upload_training_record.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $main_url."info.php?mode=tnp_edit_record"; ?> method="post">
		<input type='image' src=<?php echo $media_url.'media/images/edit_records.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $main_url."info.php?mode=tnp_statistics"; ?> method="post">
		<input type='image' src=<?php echo $media_url.'images/statistics.png'; ?> />
		</form>
	</td>
</tr>
<tr>
	<td>
		<form action=<?php echo $main_url."info.php?mode=user_change_password"; ?> method="post">
		<input type='image' src=<?php echo $media_url.'images/change_password.png'; ?> />
		</form>
	</td>
	<td>
		<form action=<?php echo $main_url."docs/user_docs/tnp_help.php"; ?> method="post">
		<input type='image' src=<?php echo $media_url.'images/help.png'; ?> />
		</form>
	</td>
</tr>
</table>
