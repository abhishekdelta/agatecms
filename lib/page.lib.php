<?php

function getPageContent($pageId,&$rootPerm,&$userId,$action,$subaction,&$access)
{
	global $libraryFolder;
	global $adminFolder;
	global $pageFullPath;
	
	

	if($action=="login" && $subaction=="view")
	{
		if($userId==0)
		{
			
			require_once($libraryFolder."/login.lib.php");
			return loginForm();	
		}
		else
		{
			displayInfo("You are already logged in!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	
	
	if($action=="login" && $subaction=="check")
	{
		if($userId==0)
		{
			
			require_once($libraryFolder."/login.lib.php");
			$lastlogin;
			$userId=login($lastlogin);
			if($userId==0)
			{
				displayError("Login Failed!");
				return loginForm();
			}
			setAuth($userId);
			$rootPerm=isAdmin();
			if($lastlogin=="0000-00-00 00:00:00")
				$lastlogin="This is your first login.";
			else $lastlogin="You last logged in on ".$lastlogin;
			displayInfo("Welcome, ".getUserFullNameFromID($userId)."! ".$lastlogin);
			$access=getPageAccessFromPageID($pageId,$rootPerm);
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		else
		{
			displayInfo("You are already logged in!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	if($action=="logout")
	{
		if($userId==0)
		{
			displayInfo("You need to login first!");
			return getPageContent($pageId,$rootPerm,$userId,"login","view",$access);
		}
		else
		{
			
			resetAuth();
			$userId=0;
			$rootPerm=0;
			$access=getPageAccessFromPageID($pageId,$rootPerm);
			displayInfo("You have been successfully logged out!");
			return getPageContent($pageId,isAdmin(),$userId,"view","",$access);
		}
	}
	if($action=="register" && $subaction=="view")
	{
		if($userId==0)
		{
			
			require_once($libraryFolder."/register.lib.php");
			return registerForm();
		}
		else
		{
			displayInfo("Please logout first!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	
	if($action=="register" && $subaction=="exec")
	{
		if($userId==0)
		{
			
			require_once($libraryFolder."/register.lib.php");
			$newUserId=register();
			if($newUserId==0)
			{
				displayError("Registration Failed! Try again with correct values.");
				return registerForm();
			}
			
			displayInfo("Thank you ".getUserFullNameFromID($newUserId).", You have been successfully registered! You may login now.");
			
			return getPageContent($pageId,$rootPerm,$userId,"login","view",$access);
		}
		else
		{
			displayInfo("Please logout first!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	
	if($action=="profile" && $subaction=="view")
	{
		if($userId==0)
		{
			displayInfo("You need to login to view your profile!");
			return getPageContent($pageId,$rootPerm,$userId,"login","view",$access);
		}
		else
		{
			require_once($libraryFolder."/profile.lib.php");
			return profilePage($userId,"");
		}
	}
	if($action=="profile" && $subaction=="update")
	{
		if($userId==0)
		{
			displayInfo("You need to login to update your profile!");
			return getPageContent($pageId,$rootPerm,$userId,"login","view",$access);
		}
		else
		{
			require_once($libraryFolder."/profile.lib.php");
			
			$success=updateProfile($userId);
			if($success==true)
				displayInfo("Profile successfully updated!");
			else displayError("Your profile was not updated");
			return getPageContent($pageId,$rootPerm,$userId,"profile","view",$access);
		}
	}
	
	if($action=="edit" && $subaction=="view")
	{
		//TODO:implement features for adding a CSS or a JS file and simultaneously update in <head> also
		if($rootPerm)
		{
			require_once($adminFolder."/editor.lib.php");
			return editPage($pageId,NULL);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	
	if($action=="edit" && $subaction=="save")
	{
		//TODO:implement features for adding a CSS or a JS file and simultaneously update in <head> also
		if($rootPerm)
		{
			require_once($adminFolder."/editor.lib.php");
			if(isset($_POST['update']))
			{
				savePage($pageId);
				displayInfo("Page Contents are successfully updated!");
			}
			else
			{
				$content=getPreview($pageId);
				return $content."<br/><br/>".editPage($pageId,$content);
				
			}
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	
	if($action=="settings" && $subaction=="view")
	{
		//TODO:make sure if the access of this page is set to ROOT then all its child also has ROOT access only but not in case of GUEST
		//asked by the user
		
		if($rootPerm)
		{
			require_once($adminFolder."/settings.lib.php");
			return settingsPage($pageId);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	if($action=="settings" && $subaction=="update")
	{
		
		if($rootPerm)
		{
			require_once($adminFolder."/settings.lib.php");
			updatePageSettings($pageId);
			displayInfo("Page Settings are successfully updated!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	
	
	if($action=="global" && $subaction=="view")
	{
		//site as a whole
		//option to change register profile options
		
		if($rootPerm)
		{
			require_once($adminFolder."/global.lib.php");
			return globalSettingsForm($pageId);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	if($action=="global" && $subaction=="exec")
	{
		//site as a whole
		//option to change register profile options
		
		if($rootPerm)
		{
			require_once($adminFolder."/global.lib.php");
			updateGlobalSettings($pageId);
			displayInfo("Global Settings updated successfully!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
	}
	
	if($action=="create" && $subaction=="view")
	{
		if($rootPerm)
		{
			require_once($adminFolder."/create.lib.php");
			return createPageForm($pageId);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		
	}
	if($action=="create" && $subaction=="exec")
	{
		if($rootPerm)
		{
			require_once($adminFolder."/create.lib.php");
			$newPageId=createPage($pageId,$newPageName);
			if($newPageId>0)
			{

				displayInfo("Your page has been successfully created! Click <a href='index.php?page=$pageFullPath/$newPageName'>here</a>
				to view your page");
				
			}
			else
			{
				displayError("Some Internal Error Occurred!");
				return getPageContent($pageId,$rootPerm,$userId,"create","view",$access);
			}
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		
	}
	if($action=="delete" && $subaction=="view")
	{
		if($pageId==0)
		{
			displayWarning("Home Page cannot be deleted!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		if($rootPerm)
		{
			require_once($adminFolder."/delete.lib.php");
			return deletePageForm($pageId);
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		
	}
	if($action=="delete" && $subaction=="exec")
	{
		if($pageId==0)
		{
			displayWarning("Home Page cannot be deleted!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		if($rootPerm)
		{
			require_once($adminFolder."/delete.lib.php");
			$status=deletePage($pageId);
			if($status==1) //Page was deleted
			{

				displayInfo("Your page has been successfully deleted!");
				global $pageId,$pageFullPath,$pageIds;
				$pageId=0;
				$pageFullPath="/home";
				$pageIds=getPageIDsFromURL($pageFullPath);
				return getPageContent(0,$rootPerm,$userId,"view","",$access);
				
			}
			if($status==2) //Going to Step 2 meaning creating the parent page
			{
				
				return deletePageNewParentForm($pageId);				
			}
			if($status==3) //Going to Step 2 meaning selecting the parent page
			{
				displayWarning("To be implemented with Page Tree /Site Map Widget!");	
				return getPageContent($pageId,$rootPerm,$userId,"view","",$access);		
			}
			if($status==4) //User selected not to delete the page
			{
				displayWarning("The page was not deleted");
				return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
			}
			else //error
			{
				displayError("Some Internal Error Occurred!");
				return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
			}
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		
	}
	if($action=="users" && $subaction=="view")
	{
		
		if($rootPerm)
		{
			require_once($adminFolder."/user.lib.php");
			return userMgmtForm();
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		
	}
	if($action=="users" && $subaction=="exec")
	{
		if($rootPerm)
		{
			require_once($adminFolder."/user.lib.php");
			return handleUserMgmt();
		}
		else
		{
			displayWarning("You are not authorized to perform this action!");
			return getPageContent($pageId,$rootPerm,$userId,"view","",$access);
		}
		
	}
	//Any other action (even invalid) is treated as view..
	if($userId==0)
	{
		$loginrequired=getLoginRequiredFromPageId($pageId);
		if($loginrequired==1)
		{
			displayInfo("You need to login to access this page!");
			return getPageContent($pageId,$rootPerm,$userId,"login","view",$access);
		}
	}
	if($access==false)
	{
		displayError("Access is denied to the page!");
		return "";
	}
	$content=getContentFromPageID($pageId); 
	return $content;
	
}

function getPageTitle($pageId,$rootPerm,$userId,$action,$access)
{
	if($access==false)
	{
		return "Access Denied";
	}
	$actionText;
	
	
	if($action=="view") $actionText="";
	else if($action=="login") $actionText=" | Login";
	else if($action=="register") $actionText=" | Register New User";
	else if($action=="logout") $actionText=" | Logout";
	else if($action=="profile") $actionText=" | User Profile";
	else $actionText="";
	if($rootPerm==true)
	{
		if($action=="edit") $actionText=" | Edit Page";
		else if($action=="settings") $actionText=" | Page Settings";
		else if($action=="global") $actionText=" | Global Settings";
		else if($action=="create") $actionText=" | New Child Page";
		else if($action=="users") $actionText=" | User Management";
		else if($action=="delete") $actionText=" | Delete Page";
		
		return getTitleFromPageID($pageId).$actionText;
	}
	
	return getTitleFromPageID($pageId).$actionText;

}

function getPageTemplate($pageId)
{
 	global $templateFolder;
 	global $CMSTEMPLATE;
 	
 	$allowed=isPageTemplateAllowed();
 	
 	if($allowed==false)
 		return $templateFolder."/".$CMSTEMPLATE;
 	$template=getPageTemplateFromID($pageId);
 	if($template=="")
 		return $templateFolder."/".$CMSTEMPLATE;
 	return $templateFolder."/".$template;
}



function displayPage()
{
	global $PAGECONTENT;
	global $PAGETITLE;
	global $PAGEBREADCRUMB;
	global $PAGEMENU;
	global $PAGEDASHBOARD;
	global $WARNINGMSG;
	global $ERRORMSG;
	global $INFOMSG;
	global $PAGESCRIPT;
	global $PAGEONLOADFUNC;
	global $PAGETEMPLATE;
	global $PAGERIGHTBAR;
	global $CMSTITLE;
	global $CMSTEMPLATE;
	
	require_once($PAGETEMPLATE."/index.php");
	
	/*
	$pageHTML=<<<HTML
<html>
<head>
<title>$PAGETITLE</title>
</head>
<body>
$PAGEBREADCRUMB
$PAGEDASHBOARD
$PAGEMENU
$INFOMSG
$WARNINGMSG
$ERRORMSG
$PAGECONTENT
</body>
</html>
HTML;
	echo $pageHTML;*/
}
