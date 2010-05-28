<?php
//TODO create and manage groups
function userMgmtForm()
{
	global $pageFullPath;
	$usermgmtform=<<<USERFORM
	<form name='user_mgmt_form' action='index.php?page=$pageFullPath&action=users&subaction=exec' method='POST'>
	<fieldset>
	<legend>User Management</legend>
	Fields:
USERFORM;

	$usertablefields=getUserTableFields();
	$userfieldprettynames=array("User ID","Username","Email","Full Name","Password","Last Login","Registration","Activated");
	for($i=0;$i<count($usertablefields);$i++)
	{
		$usermgmtform.="&nbsp;&nbsp;<input type='checkbox' name='{$usertablefields[$i]}' checked />{$userfieldprettynames[$i]}";
	}
	$usermgmtform.=<<<USERFORM
	<br/><br/>
	<fieldset style="float:left;">
	<legend>All Registered</legend>
	<input type='submit' value='View' name='view_reg_users'/>
	<input type='submit' value='Edit' name='edit_reg_users'/>
	</fieldset>
	<fieldset style="float:left;">
	<legend>Activated Users</legend>
	<input type='submit' value='View' name='view_activated_users'/>
	<input type='submit' value='Edit' name='edit_activated_users'/>
	</fieldset>
	<fieldset style="float:left;">
	<legend>Non-Activated Users</legend>
	<input type='submit' value='View' name='view_nonactivated_users'/>
	<input type='submit' value='Edit' name='edit_nonactivated_users'/>
	</fieldset>
	</fieldset>
	</form>
USERFORM;
	return $usermgmtform;
}
function handleUserMgmt()
{
	if(isset($_POST['user_activate']))
	{
		activateUserByUserName($_GET['username']);
		displayInfo("User Successfully Activated!");
		return registeredUsersList($_POST['editusertype'],"edit",true);
	}
	else if(isset($_POST['user_deactivate']))
	{
		$userId=getUserIdFromName($_GET['username']);
		if($userId==ADMIN_USERID)
		{
			displayError("You cannot deactivate administrator!");
			return registeredUsersList($_POST['editusertype'],"edit",true);
		}
		
		deActivateUserByUserName($_GET['username']);
		displayInfo("User Successfully Deactivated!");
		return registeredUsersList($_POST['editusertype'],"edit",true);
	}
	else if(isset($_POST['user_delete']))
	{
		$userId=getUserIdFromName($_GET['username']);
		if($userId==ADMIN_USERID)
		{
			displayError("You cannot delete administrator!");
			return registeredUsersList($_POST['editusertype'],"edit",true);
		}
		deleteUserByUserName($_GET['username']);
		displayInfo("User Successfully Deleted!");
		return registeredUsersList($_POST['editusertype'],"edit",true);
	}
	else if(isset($_POST['user_profile']) || (isset($_GET['profile']) && $_GET['profile']=="update"))
	{	
		global $libraryFolder;
		require_once($libraryFolder."/profile.lib.php");
		$username=$_GET['username'];
		$userId=getUserIdFromName($username);
		if(isset($_GET['profile']) && $_GET['profile']=="update")
		{
			if(updateProfile($userId)==1)
				displayInfo("Profile Successfully Updated!");
			else displayInfo("Profile Not Updated!");
		}
		$submit_action="&action=users&subaction=exec&profile=update&username=$username";
		return userMgmtForm()."<br/><br/>".profilePage($userId,$submit_action);
	}
	else if(isset($_POST['view_reg_users']))
	{
		return registeredUsersList("all","view",false);
	}
	else if(isset($_POST['edit_reg_users']))
	{
		return registeredUsersList("all","edit",false);
	}
	else if(isset($_POST['view_activated_users']))
	{
		return registeredUsersList("activated","view",false);
	}
	else if(isset($_POST['edit_activated_users']))
	{
		return registeredUsersList("activated","edit",false);
	}
	else if(isset($_POST['view_nonactivated_users']))
	{
		return registeredUsersList("nonactivated","view",false);
	}
	else if(isset($_POST['edit_nonactivated_users']))
	{
		return registeredUsersList("nonactivated","edit",false);
	}
}

function registeredUsersList($type,$act,$allfields)
{
	global $pageFullPath;	
	getAllUsersInfo($userId,$userName,$userEmail,$userFullName,$userPassword,$userLastLogin,$userRegDate,$userActivated);
	$userfieldprettynames=array("User ID","Username","Email","Full Name","Password","Last Login","Registration","Activated");
	$userfieldvars=array("userId","userName","userEmail","userFullName","userPassword","userLastLogin","userRegDate","userActivated");
	$userlist="";
	$columns=8;
	if($act=="edit")
	{
		$userlist.="<form name='user_edit_form' method='POST' action='index.php?page=$pageFullPath&action=users&subaction=exec&username=' >\n";
		$userlist.="<input type='hidden' name='editusertype' value='$type' />";
		$columns+=3;
	}
	
	$userlist.=<<<USERLIST
	
	<table class="userlisttable" border="1">
	<tr><th colspan="$columns">Users Registered on the Website</th></tr>
	<tr>
USERLIST;

	$usertablefields=getUserTableFields();
	$displayfieldsindex=array();
	$c=0;
	for($i=0;$i<count($usertablefields);$i++)
	{
		if(isset($_POST[$usertablefields[$i]]) || $allfields)
		{
			$userlist.="<th>".$userfieldprettynames[$i]."</th>";
			$displayfieldsindex[$c++]=$i;
		}
	}
	
	if($act=="edit")
	{
		$userlist.="<th>De/Activate</th><th>Edit Profile</th><th>Delete User</th>";
	}
	$userlist.="</tr>";
	$rowclass="oddrow";
	
	for($i=0; $i<count($userId); $i++)
	{
		if($type=="activated" && $userActivated[$i]==0)
			continue;
		if($type=="nonactivated" && $userActivated[$i]==1)
			continue;
			
		$userlist.="<tr class='$rowclass'>";
		
		for($j=0; $j<count($displayfieldsindex); $j++)
		{
			$userlist.="<td>".${$userfieldvars[$displayfieldsindex[$j]]}[$i]."</td>";
		}
		if($act=="edit")
		{
			if($userActivated[$i]==0)
				$userlist.="<td><input type='submit' onclick=\"this.form.action+='{$userName[$i]}'\" name='user_activate' value='Activate'></td>\n";
			else $userlist.="<td><input type='submit' onclick=\"this.form.action+='{$userName[$i]}'\" name='user_deactivate' value='Deactivate'></td>\n";
			$userlist.="<td><input type='submit' onclick=\"this.form.action+='{$userName[$i]}'\" name='user_profile' value='Profile'></td>\n";
			$userlist.="<td><input type='submit' onclick=\"this.form.action+='{$userName[$i]}'\" name='user_delete' value='Delete'></td>\n";
			
		}
		$userlist.="</tr>";
		$rowclass=$rowclass=="evenrow"?"oddrow":"evenrow";
	}
	$userlist.="</table>";
	return userMgmtForm()."<br/><br/>".$userlist;
}


?>
