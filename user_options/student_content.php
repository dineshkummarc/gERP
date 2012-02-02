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
<div id='student_content'>
<table class="info" align="center">
	<tr>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=view_details"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/view_details.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=attendence_student"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/attendence.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=sessional_marks"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/check_marks.png'; ?> />
			</form>
		</td>
	</tr>
	<tr>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=student_assignment"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/assignments.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/info.php?mode=user_change_password"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/change_password.png'; ?> />
			</form>
		</td>
		<td>
			<form action=<?php echo $gERP."/docs/user_docs/student_help.php"; ?> method="post">
			<input type='image' src=<?php echo $gERP.'/media/images/help.png'; ?> />
		</td>
	</tr>
</table>
