<?php
function registerForm()
{
//TODO : Implement AJAX Username CHECKER
//TODO : Implement REGEX for Contact Number Country Code matching... +91 should not say number is invalid...

	global $pageFullPath;
	global $PAGESCRIPT;
	global $PAGETEMPLATE;;
	$PAGESCRIPT.=<<<SCRIPT
	function checkPassword(regformpass2) {
				regformpass1=regformpass2.form.regform_password;
				if(regformpass1.value!=regformpass2.value) {
					alert("Passwords do not match");
					regformpass2.value="";
					regformpass1.value="";
					regformpass1.focus();
					return false;
				}
				return true;
			}
	function checkEmail(regformemail) {
				var email=new String(regformemail.value);
				if(email.indexOf('@')==-1 || email.indexOf('.')==-1)
				{
					alert("Please enter a valid Email Address");
					return false;
				}
				return true;
	}
	function checkRegForm(regform) {
				if(regform.regform_password.value.length==0) {
					alert("Blank password not allowed.");
					return false;
				}
				if(regform.regform_username.value.length==0) {
					alert("Blank 'User name' not allowed.");
					return false;
				}
				if(regform.regform_userfullname.value.length==0) {
					alert("Blank 'Full name' not allowed.");
					return false;
				}
				return (checkEmail(regform.regform_email)&&checkPassword(regform.regform_password2));
			}
	/*function checkUserName(regform)
	{
		username=regform.value;
		makeAJAXRequest("index.php?page=$pageFullPath&action=checkusername&username="+username,showUserNameStatus);
	}
	function showUserNameStatus(stat)
	{
		if(stat=="available")
			document.getElementById("ajaxloader").innerHTML="Available!";
		else document.getElementById("ajaxloader").innerHTML="Not Available!";
	}*/
		
SCRIPT;
	$registerForm=<<<REGISTERFORM
	<form name='regform' id='regform' action='index.php?page=$pageFullPath&action=register&subaction=exec' method='POST' onsubmit='return checkRegForm(this)'>
	<fieldset>
	<legend>Register</legend>
	<table>
	<tr class='required_field'>
	<td>Username* :</td><td><input type='text' name='regform_username' id='regform_username' />
	<!--<input type='button' id='ajaxloader' value='Check Availability' onclick='checkUserName(this)' />-->
	</td>
	</tr>
	<tr class='required_field'>
	<td>User Full Name* :</td><td><input type='text' name='regform_userfullname' id='regform_userfullname' /></td>
	</tr>
	<tr class='required_field'>
	<td>User Email* :</td><td><input type='text' name='regform_email' id='regform_email' /></td>
	</tr>
	<tr class='required_field'>
	<td>Password* :</td><td><input type='password' name='regform_password'  id='regform_password'/></td>
	</tr>
	<tr class='required_field'>
	<td>Verify Password* :</td><td><input type='password' name='regform_password2' id='regform_password2' /></td>
	</tr>
	<tr>
	<td>Contact Address :</td><td><textarea name='regform_address' id='regform_address'></textarea></td>
	</tr>
	<tr>
	<td>Contact Number :</td><td><input type='text' name='regform_number'  id='regform_number' /></td>
	</tr>
	<tr>
	<td><input type='submit' value='Register' /></td>
	</tr>
	</table>
	* : Compulsory Fields
	</fieldset>
	</form>
REGISTERFORM;
	return $registerForm;
}

function register()
{
	if(!isset($_POST['regform_username'])||!isset($_POST['regform_userfullname'])||!isset($_POST['regform_email'])||!isset($_POST['regform_password']))
	return 0;
	if ((($_POST['regform_email']) == "") || (($_POST['regform_password']) == "")) {
			displayError("Blank e-mail/password NOT allowed");
			return 0;
		}

	if ((($_POST['regform_username']) == "") || (($_POST['regform_userfullname']) == "")) {
			displayError("Please fill in your username and full name");
			return 0;
		}

	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $_POST['regform_email'])) {
			displayError("Invalid Email Id");
			return 0;
		}
	if (($_POST['regform_password']) != ($_POST['regform_password2'])) {
			displayError("Passwords are not same");
			return 0;
		}
	
	if(isset($_POST['regform_number']) && eregi("[^0-9]",$_POST['regform_number']))
	{
		displayError("Invalid Contact Number");
		return 0;
	}
	$username=trim($_POST['regform_username']);
	$userfullname=trim($_POST['regform_userfullname']);
	$useremail=trim($_POST['regform_email']);
	$userpasswd=$_POST['regform_password'];
	$userpasswd2=$_POST['regform_password2'];
	$usercontactaddr=trim($_POST['regform_address']);
	$usercontactnum=trim($_POST['regform_number']);
	
	$userid1=getUserIdFromEmail($useremail);
	
	if($userid1!=NULL)
	{
		displayError("Email address is already registered");
		return 0;		
	}
	$userid2=getUserIdFromName($username);
	if($userid2!=NULL)
	{
		displayError("Username is already registered");
		return 0;		
	}
	$useractivate=isDefaultUserActivated();
	$newuserid=addUser($username,$useremail,$userfullname,md5($userpasswd),$usercontactaddr,$usercontactnum,$useractivate);
	return $newuserid;
		
	
}
?>
