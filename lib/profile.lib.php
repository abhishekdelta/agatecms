<?php
function profilePage($userId,$submitaction)
{
	global $pageFullPath;
	global $PAGESCRIPT;
	global $PAGETEMPLATE;
	
	list(,$dbUserName,$dbUserEmail,$dbUserFullName,,$dbUserLastLogin,$dbUserRegDate,)=getUserInfoFromID($userId);
	
	$extraInfo=getUserExtraInfoFromID($userId);
	$dbUserContactAddr=$extraInfo[0];
	$dbUserContactNum=$extraInfo[1];
	$PAGESCRIPT.=<<<SCRIPT
	function checkPassword(profileformpass2) {
				profileformpass1=profileformpass2.form.profileform_password;
				if(profileformpass1.value.length==0 && profileformpass2.value.length==0)
				 return true;
				if(profileformpass1.value!=profileformpass2.value) {
					alert("Passwords do not match");
					profileformpass2.value="";
					profileformpass1.value="";
					profileformpass1.focus();
					return false;
				}
				return true;
			}
	function checkEmail(profileformemail) {
				var email=new String(profileformemail.value);
				if(email.indexOf('@')==-1 || email.indexOf('.')==-1)
				{
					alert("Please enter a valid Email Address");
					return false;
				}
				return true;
	}
	function checkProfileForm(profileform) {
				if(profileform.profileform_userfullname.value.length==0) {
					alert("Blank 'Full name' not allowed.");
					return false;
				}
				return (checkEmail(profileform.profileform_email)&&checkPassword(profileform.profileform_password2));
			}
	
		
SCRIPT;
	if($submitaction=="")
	{
		$submitaction="&action=profile&subaction=update";
	}
	$profileForm=<<<PROFILEFORM
	<form name='profileform' id='profileform' action='index.php?page=$pageFullPath$submitaction' method='POST' onsubmit='return checkProfileForm(this)'>
	<fieldset>
	<legend>Profile</legend>
	<table>
	<tr class='required_field'>
	<tr>
	<td>User Name :</td><td>$dbUserName</td>
	</tr>
	<tr>
	<td>User Full Name :</td><td><input type='text' name='profileform_userfullname' id='profileform_userfullname' value='$dbUserFullName' /></td>
	</tr>
	<tr class='required_field'>
	<td>User Email :</td><td><input type='text' name='profileform_email' id='profileform_email' value='$dbUserEmail' /></td>
	</tr>
	<tr>
	<td>Password* :</td><td><input type='password' name='profileform_password'  id='profileform_password' /></td>
	</tr>
	<tr>
	<td>Verify Password* :</td><td><input type='password' name='profileform_password2' id='profileform_password2' /></td>
	</tr>
	<tr>
	<td>Contact Address :</td><td><textarea name='profileform_address' id='profileform_address' />$dbUserContactAddr</textarea></td>
	</tr>
	<tr>
	<td>Contact Number :</td><td><input type='text' name='profileform_number'  id='profileform_number' value='$dbUserContactNum' /></td>
	</tr>
	<tr>
	<td>Last Login :</td><td>$dbUserLastLogin</td>
	</tr>
	<tr>
	<td>Registration Date :</td><td>$dbUserRegDate</td>
	</tr>
	<tr>
	<td><input type='submit' value='Update' />
	<input name='cancel_button' type='button' value='Cancel' onclick="window.open('index.php?page=$pageFullPath','_top')" /></td>
	</tr>
	</table>
	* : Leave blank if you don't want to change your password.
	</fieldset>
	</form>
PROFILEFORM;
	return $profileForm;
}

function updateProfile($userId)
{

	if(!isset($_POST['profileform_userfullname'])||!isset($_POST['profileform_email'])||!isset($_POST['profileform_password']))
		return 0;
	if (($_POST['profileform_email']) == "") {
			displayerror("Blank e-mail not allowed");
			return 0;
		}

	if (($_POST['profileform_userfullname']) == "") {
			displayerror("Please fill in your full name");
			return 0;
		}

	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['profileform_email'])) {
			displayerror("Invalid Email Id");
			return 0;
		}
	$changePassword=false;
	if($_POST['profileform_password']!="" || $_POST['profileform_password2']!="")
	{
		if (($_POST['profileform_password']) != ($_POST['profileform_password2'])) {
			displayerror("Passwords are not same");
			return 0;
		}
		$changePassword=true;
	}
	
	
	$userfullname=trim($_POST['profileform_userfullname']);
	$useremail=trim($_POST['profileform_email']);
	if($changePassword==true)
	{
		$userpasswd=$_POST['profileform_password'];
		$userpasswd2=$_POST['profileform_password2'];
	}
	$usercontactaddr=trim($_POST['profileform_address']);
	$usercontactnum=trim($_POST['profileform_number']);
	
	$userid1=getUserIdFromEmail($useremail);
	
	if($userid1!="" && $userid1!=$userId)
	{
		displayError("Email address is already registered with another user");
		return 0;		
	}
	if($changePassword==true)
		updateUserInfoFromID($userId,$useremail,$userfullname,md5($userpasswd),$usercontactaddr,$usercontactnum);
	else updateUserInfoFromID($userId,$useremail,$userfullname,"",$usercontactaddr,$usercontactnum);
	return 1;
	
	
}
?>
