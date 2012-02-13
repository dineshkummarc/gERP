<?php
include_once('../includes/paths.php');
?>
<p id='introduction'>
	<table align='center'>
		<tr>
			<td>
				<p>Below are The operations you can perform</p>
			</td>
		</tr>
	</table>
</p>
<div id='student_content'>
<table class="info" align="center">
	<tr>
		<td>
			<form action=<?php echo $main_url."modules/student/student_details/info.php?mode=view_details"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/view_details.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."info.php?mode=attendence_student"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/attendence.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."info.php?mode=sessional_marks"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/check_marks.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $main_url."info.php?mode=student_assignment"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/assignments.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."info.php?mode=user_change_password"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/change_password.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $main_url."docs/user_docs/student_help.php"; ?> method="post">
			<input type='image' src=<?php echo $media_url.'images/help.png'; ?> />
		</td>
	</tr>
</table>
