<?php
$path = $_SERVER['SCRIPT_NAME'];
$_gERP = explode('gERP',$path);
$gERP = $_gERP[0]."gERP";
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

<div id='teacher_content'>

<table class="info" align="center">
	<tr>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=student_details"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/student_details.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=attendence_teacher"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/upload_attendence.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=upload_marks"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/upload_marks.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=teacher_assignment"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/upload_assignment.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=teacher_edit_record"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/edit_records.png'; ?> />
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
			<form action=<?php echo $gERP."/info.php?mode=user_change_password"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/change_password.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/docs/user_docs/teacher_help.php"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/help.png'; ?> />
			</form>
		</td>
	</tr>
</table>
