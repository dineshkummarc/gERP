<?php
$path = __DIR__;
$_gERP = explode('gERP',$path);
$gERP = $_gERP[0]."gERP";
include_once($gERP.'/header_footer/header_login.php');
require_once('config.php');
require_once('functions.php');
session_start();
$alphabets = array('a','b','c','d','e','f','g','h','i','j','k','m','n','p','q',
'r','s','t','u','v','w','x','y','z');
for($i=0;$i<=2;$i++) {
	$rand_no[] = rand(2,9);
	$rand_al[] = $alphabets[rand(0,24)];
}
$system_answer = $rand_al[0].$rand_no[0].$rand_al[1].$rand_no[1].$rand_al[2].
$rand_no[2];

switch($_GET['mode']) {
	case "logout":
		session_destroy();
		break;
	case "auth":
	if($_POST['user_answer']!= $_POST['system_answer']) {
		echo "<p>Incorrect Response, Try Again Please.</p>";
		break;
	}
	else {
		if($_POST['User_Type']=='Student')
		{
			$sql = "SELECT Roll_No, Password,User_Type 
					FROM users WHERE Roll_No='".$_POST['user']."' 
					AND Password='".md5($_POST['Password'])."' 
					AND User_Type='".$_POST['User_Type']."'";
			$result = mysql_query($sql);
			$num = mysql_num_rows($result);
			if($num==1)
			{
				$_SESSION['usertype'] = $_POST['User_Type'];
				$_SESSION['rollno'] = $_POST['user'];
				$_SESSION['password'] = $_POST['Password'];
				header("location:options.php");
			}
			else
			{
				echo "<p>Wrong Username/Password !</p>";
				session_destroy();
				
			}
		}
		
		if($_POST['User_Type']=='Teacher' 
		   or $_POST['User_Type']=='Admin' 
		   or $_POST['User_Type']=='Training And Placement')
		{
			$sql = "SELECT Username, Full_Name, Password,User_Type,Department
					FROM users 
					WHERE Username='".$_POST['user']."' 
					AND Password='".md5($_POST['Password'])."' 
					AND User_Type='".$_POST['User_Type']."'";
			$result = mysql_query($sql);
			$fullname=mysql_fetch_assoc($result);
			$num = mysql_num_rows($result);
			if($num==1)
			{
				$_SESSION['usertype'] = $_POST['User_Type'];
				$_SESSION['username'] = $_POST['user'];
				$_SESSION['fullname']=$fullname['Full_Name'];
				$_SESSION['department']=$fullname['Department'];
				$_SESSION['password'] = $_POST['Password'];
				header("location:options.php");
			}
			
			else
			{
				echo "<p>Wrong Username/Password !</p>";
				session_destroy();
				
			}
		}
	}
		
		break;
		
	
	case "login_updated":
		
		echo "<p>Login Details Successfully Updated, Re-Login With your New 
		Credentials</p>";
		
	default:
		if(isset ($_SESSION['username'])){
			header("location:options.php");
		}
	}

?>
<div id='erp_image'>
	<img src='images/erp_system.jpg' height='268' width='448' /></div>
<div id='login_box'>
		<form name="login" action="index.php?mode=auth" method="post">
				<table id='student_details' align='center'>
	<tr>
		<td>User Type:</td>
		<td><select name='User_Type' >
					<option value='Student'>Student</option>
					<option value='Admin'>Admin</option>
					<option value='Teacher'>Teacher</option>
					<option value='Training And Placement'>Training And Placement</option>
					</select></td></tr>
	<tr>
		<td>Username/Roll No:</td><td><input type="text" name="user" /></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" name="Password" /></td>
	</tr>
	<tr>
		<td>Captcha:</td>
		<td><?php echo "<img src='image.php?txt=".$system_answer."'"; ?></td>
	</tr>
	<tr>
		<td>Response:</td>
		<td><input type='text' name='user_answer' autocomplete='off' /></td>
	</tr>
	<input type='hidden' name='system_answer' value='<?php echo $system_answer; ?>' />
	<tr>
		<td><input type="submit" value="Login" /></td>
	</tr>
	</table>
	
	</form>
	</div>
<?php
include_once('header_footer/footer_login.php');
?>
</body>
</html>
