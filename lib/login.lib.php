<?php
function loginForm()
{
	global $pageFullPath;
	$loginForm=<<<LOGINFORM
	<form id='loginform' name='loginform' action='index.php?page=$pageFullPath&action=login&subaction=check' method='POST'>
	<fieldset>
	<legend>Login</legend>
	<table>
	<tr>
	<td>Username/Email :</td><td><input type='text' name='loginform_username' /></td>
	</tr>
	<tr>
	<td>Password :</td><td><input type='password' name='loginform_password' /></td>
	</tr>
	<tr>
	<td><input type='submit' value='Login' /></td>
	<td><input type='button' value='Register' onclick='window.open("index.php?page=$pageFullPath&action=register&subaction=view","_top")' />
	<input type='button' value='Problems?' onclick='window.open("index.php?page=$pageFullPath&action=signinproblems","_top")' /></td>
	</tr>
	
	</table>
	</fieldset>
	</form>
LOGINFORM;
	return $loginForm;
}
function login(&$lastlogin)
{
	if(!isset($_POST['loginform_username']) || !isset($_POST['loginform_password']))
		return 0;
	if($_POST['loginform_username']=="" || $_POST['loginform_password']=="")
	{
		displayError("One or more fields in the login form are incomplete!");
		return 0;
	}
	$userName=$_POST['loginform_username'];
	$userPass=$_POST['loginform_password'];
	$userEmail="";
	//$userInfo=&list($dbUserID,$dbUserPass,$dbUserEmail,$dbUserName,$dbUserLastLogin);
	if(strpos($userName,'@')>-1)
	{
		$userEmail=$userName;
		$userName="";
		list($dbUserID,$dbUserName,,,$dbUserPass,$dbUserLastLogin,,$dbUserActivated)=getUserInfoFromEmail($userEmail);
	}
	else list($dbUserID,,$dbUserEmail,,$dbUserPass,$dbUserLastLogin,,$dbUserActivated)=getUserInfoFromName($userName);
	
	if($dbUserID=="")
	{
		return 0;
	}
	if($dbUserPass!=md5($userPass))
	{
		return 0;
	}
	if($dbUserActivated==0)
	{
		displayError("Your account has not been activated yet!");
		return 0;
	}
	$lastlogin=$dbUserLastLogin;
	updateUserLastLogin($dbUserID);
	return $dbUserID;	
}
?>
