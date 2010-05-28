<?php
function checkStep3()
{
	$cmsname=$_POST['cms_name'];
	$adminfullname=$_POST['admin_fullname'];
	
	$adminname=$_POST['admin_name'];
	$adminemail=$_POST['admin_email'];
	$adminpasswd=$_POST['admin_passwd'];
	$adminpasswd2=$_POST['admin_passwd2'];
	if($adminfullname=="" || $cmsname=="" || $adminname=="" || $adminemail=="" || $adminpasswd=="")
	{
		displayError("Some of the fields are incomplete!");
		return false;
	}
	if($adminpasswd!=$adminpasswd2)
	{
		displayError("Passwords do not match!");
		return false;
	}
	return true;
}
function getStep3()
{
	
	$step3html=<<<STEP3HTML
	<form name='step2form' action='index.php?step=4' method='POST'>
	<fieldset>
	<legend>Website Configuration</legend>
	<table>
	<tr>
	<td>Website Name :</td><td><input type="text" name="cms_name" /></td>
	</tr>
	<tr>
	<td>Website Administrator Full Name :</td><td><input type="text" name="admin_fullname" /></td>
	</tr>
	<tr>
	<td>Website Administrator Username :</td><td><input type="text" name="admin_name" /></td>
	</tr>
	<tr>
	<td>Website Administrator Email :</td><td><input type="text" name="admin_email" /></td>
	</tr>
	<tr>
	<td>Website Administrator Password :</td><td><input type="password" name="admin_passwd" /></td>
	</tr>
	<tr>
	<td>Website Administrator Password (Verify) :</td><td><input type="password" name="admin_passwd2" /></td>
	</tr>
	
	</table>
	That's all for now! The rest of the settings you can change from "Global Settings" once you login as administrator. Click on "Install".<br/>
	<input type='hidden' name='db_host' value='{$_POST['db_host']}' />
	<input type='hidden' name='db_name' value='{$_POST['db_name']}' />
	<input type='hidden' name='db_user' value='{$_POST['db_user']}' />
	<input type='hidden' name='db_passwd' value='{$_POST['db_passwd']}' />
	<input type='hidden' name='db_prefix' value='{$_POST['db_prefix']}' />
	<input type='submit' value='Install' />
	</fieldset>
	
	</form>
STEP3HTML;
	return $step3html;

	
}
?>
