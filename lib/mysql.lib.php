<?php
//TODO : Since magic_quotes_gpc is ON by default, SQL Injection attacks wont work. But as of PHP 6, its deprecated. So if you're using this CMS on PHP 6, its vulnerable to SQL Injection attacks. A better option is to use a combination of addslashes() and magic_gpc_quotes() to make it work on all PHP versions. How about a function like sanitizeData(&$var) to be called before every query ??"

function connectMySQL() {
	$dbase = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD) or die("Could not connect to server");
	mysql_select_db(MYSQL_DATABASE) or die("Could not connect to database");
	return $dbase;
}

function disconnectMySQL() {
	mysql_close();
}

function getUserNameFromID($userId) {
	if($userId == 0) return "Guest";
	$query = "SELECT `user_name` FROM `".MYSQL_DATABASE_PREFIX."users` WHERE `user_id` = $userId";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

function getUserFullNameFromID($userId) {
	if($userId == 0) return "Anonymous";
	$query = "SELECT `user_fullname` FROM `".MYSQL_DATABASE_PREFIX."users` WHERE `user_id` = $userId";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

function getUserEmailFromID($userId) {
	if($userId == 0) return 'Anonymous';
	$query= "SELECT `user_email` FROM `".MYSQL_DATABASE_PREFIX."users` WHERE `user_id` = $userId";
	$result = mysql_query($query);
	$row= mysql_fetch_row($result);
	return $row[0];
}

function getUserIdFromEmail($email) {
	if(strtolower($email) == 'Anonymous') return 0;
	$query = "SELECT `user_id` FROM `".MYSQL_DATABASE_PREFIX."users` WHERE `user_email` = '$email'";
	
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}
function getUserIdFromName($name) {
	$query = "SELECT `user_id` FROM `".MYSQL_DATABASE_PREFIX."users` WHERE `user_name` = '$name'";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}


function updateUserPassword($user_email,$user_passwd) {
	$query = "UPDATE `" . MYSQL_DATABASE_PREFIX . "users` SET `user_password`= '".md5($user_passwd)."' WHERE `" . MYSQL_DATABASE_PREFIX . "users`.`user_email` = '" . $user_email . "'";
	mysql_query($query) or die(mysql_error() . " in function updateUserPassword");
}

function getUserInfoFromEmail($user_email) {
	$query = "SELECT * FROM `" . MYSQL_DATABASE_PREFIX . "users` WHERE `user_email` = '" . $user_email . "'";
	$result = mysql_query($query) or die(mysql_error() . " in function getUserInfo : mysql.lib.php");
	return mysql_fetch_row($result);
}

function getUserInfoFromID($user_id) {
	$query = "SELECT * FROM `" . MYSQL_DATABASE_PREFIX . "users` WHERE `user_id` = '" . $user_id . "'";
	$result = mysql_query($query) or die(mysql_error() . " in function getUserInfo : mysql.lib.php");
	return mysql_fetch_row($result);
}

function getUserInfoFromName($user_name) {
	$query = "SELECT * FROM `" . MYSQL_DATABASE_PREFIX . "users` WHERE `user_name` = '" . $user_name . "'";
	
	$result = mysql_query($query) or die(mysql_error() . " in function getUserInfo : mysql.lib.php");
	
	return mysql_fetch_row($result);
}

//Returns an array with first element being the page id and next element the parent page id and so on... till the /home page
function getPageIDsFromURL($url) {
	$url = trim($url, '/');
	$pageNames = explode('/', $url);
	$pagesTable = MYSQL_DATABASE_PREFIX."pages";
	$count=count($pageNames);
	$selectString = "SELECT page0.page_id AS pageid0";
 	$fromString=" FROM `$pagesTable` AS page0";
  	$whereString=" WHERE page0.page_id=page0.parent_id";
  	for($i = 1; $i < $count; $i++) {
		if($pageNames[$i] != "") {
			$selectString.=", page".$i.".page_id AS pageid".$i;
			$fromString.=", `$pagesTable` as page".$i;
			$whereString.=" and page".$i.".parent_id = page".($i - 1).".page_id and page".$i.".page_name='".$pageNames[$i]."'";
	  }
	}
  	$query = $selectString.$fromString.$whereString;
  	$result = mysql_query($query);	

  	if($result)
  		if($row=mysql_fetch_array($result,MYSQL_NUM))
  			return array_reverse($row);
 
  	
  	return NULL;
}

function getPageAccessFromPageID($pageId,$rootPerm)
{
	$query= "SELECT page_access FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id=".$pageId;
	$result=mysql_query($query);
	$row=mysql_fetch_array($result,MYSQL_NUM);
	if($row[0]==0)
		return true;
	if($rootPerm==true && $row[0]==1)
		return true;
	return false;
}
function getPagetypeFromPageID($pageId)
{
	$query= "SELECT page_type FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id=".$pageId;
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	$pageType=$row[0];
	return $pageType;
}
function getContentFromPageID($pageId)
{
	$pageType=getPagetypeFromPageID($pageId);
	$query= "SELECT page_content FROM ".MYSQL_DATABASE_PREFIX."$pageType WHERE page_id=".$pageId;
	$result=mysql_query($query);
	$row=mysql_fetch_array($result,MYSQL_NUM);
	return $row[0] ;
}

function getTitleFromPageID($pageId)
{
	$query= "SELECT page_title FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id=".$pageId;
	$result=mysql_query($query);
	$row=mysql_fetch_array($result,MYSQL_NUM);
	return $row[0] ;
}

function getPageBreadcrumbFromPageIDs($pageIdArray)
{
	$query= "SELECT page_id, page_name, page_title FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id IN (".$pageIdArray.")";

	$result= mysql_query($query);
	$pageBreadcrumbArray=array();
	while($row=mysql_fetch_array($result))
	{
	
		$pageBreadcrumbArray[$row['page_id']]=$row['page_title'];
		$pageBreadcrumbArray[$row['page_id']."|name"]=$row['page_name'];
	}
	return $pageBreadcrumbArray;
}

function getPageMenuHeadFromPageID($pageId,&$pageTitle)
{
	$query= "SELECT page_menu_display,page_title FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id=".$pageId;
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	$pageTitle=$row['page_title'];
	return $row['page_menu_display'];
}

function getPageChildrenFromPageID($pageId,&$childNames,&$childTitles)
{
	$query= "SELECT page_name,page_title FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$pageId." AND page_menuitem_display=1 AND NOT page_id=0 ORDER BY page_menuitem_order";
	
	$result=mysql_query($query);
	$childNames=array();
	$childTitles=array();
	$i=0;
	while($row=mysql_fetch_array($result))
	{
		if($row['page_name']!="" && $row['page_title']!="")
		{
			$childNames[$i]=$row['page_name'];
			$childTitles[$i]=$row['page_title'];
			$i++;
		}
	}
	
	
}

function updateUserLastLogin($userId)
{
	$query = "UPDATE `".MYSQL_DATABASE_PREFIX."users` SET `user_lastlogin`=NOW() WHERE `".MYSQL_DATABASE_PREFIX."users`.`user_id`=$userId";
	mysql_query($query);	
}

function addUser($userName,$userEmail,$userFullName,$userPassword,$userContactAddr,$userContactNum, $userActivated)
{
	$query= "INSERT INTO `".MYSQL_DATABASE_PREFIX."users` (`user_name`, `user_email`, `user_fullname`, `user_password`, `user_activated`) VALUES ('$userName', '$userEmail', '$userFullName', '$userPassword', $userActivated)"; 
	mysql_query($query);
	
	$query= "SELECT user_id FROM ".MYSQL_DATABASE_PREFIX."users WHERE user_name='$userName' AND user_email='$userEmail' ";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	$userId=$row[0];
	$query= "INSERT INTO `".MYSQL_DATABASE_PREFIX."profile` (`user_id`, `user_contactaddr`, `user_contactnum`) VALUES ($userId,'$userContactAddr','$userContactNum')";
	mysql_query($query);
	return $userId;
}
function addAdmin($adminFullName, $adminName,$adminEmail,$adminPasswd)
{
	$query= "INSERT INTO `".MYSQL_DATABASE_PREFIX."users` (`user_id`, `user_name`, `user_email`, `user_fullname`, `user_password`, `user_activated`) VALUES (".ADMIN_USERID.", '$adminName', '$adminEmail', '$adminFullName', '$adminPasswd', 1)"; 
	mysql_query($query);
}

function getUserExtraInfoFromID($userId)
{
	$query = "SELECT `user_contactaddr`, `user_contactnum` FROM ".MYSQL_DATABASE_PREFIX."profile WHERE user_id=$userId";
	$result=mysql_query($query);
	return mysql_fetch_row($result);
}


function updateUserInfoFromID($userId,$useremail,$userfullname,$userpasswd,$usercontactaddr,$usercontactnum)
{
	if($userpasswd=="")
		$query= "UPDATE `".MYSQL_DATABASE_PREFIX."users` SET `user_email`='$useremail', `user_fullname`='$userfullname' WHERE `user_id`=$userId";
	else $query= "UPDATE `".MYSQL_DATABASE_PREFIX."users` SET `user_email`='$useremail', `user_fullname`='$userfullname', `user_password`='$userpasswd' WHERE `user_id`=$userId";
	mysql_query($query);
	$query="UPDATE `".MYSQL_DATABASE_PREFIX."profile` SET `user_contactaddr`='$usercontactaddr', `user_contactnum`='$usercontactnum' WHERE `user_id`=$userId";
	mysql_query($query);
}

function getAvailablePageTypes(&$pageTypeNames)
{
	$query="SELECT DISTINCT `page_type`,`page_type_table` FROM ".MYSQL_DATABASE_PREFIX."pagetypes";
	$result=mysql_query($query);
	$typearray=array();
	$pageTypeNames=array();
	$i=0;
	while($row=mysql_fetch_assoc($result))
	{
		$typearray[$i]=$row['page_type_table'];
		$pageTypeNames[$i++]=$row['page_type'];
	}
	return $typearray;
}
function getPageMenuitemOrder($parentId)
{
	$query= "SELECT page_menuitem_order FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$parentId." ORDER BY page_menuitem_order DESC LIMIT 1";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	$pageOrder=$row[0]+1;
	return $pageOrder;	
}
function getPageTypeTableFields($page_type)
{
	$query= "SELECT page_type_table_fields FROM ".MYSQL_DATABASE_PREFIX."pagetypes WHERE page_type='".$page_type."'";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];
}

function pageRightBarDisplay($pageId)
{
	$query= "SELECT page_rightbar_display FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id='".$pageId."'";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];
}
function insertChildPage($pageName,$parentId,$pageTitle,$pageAccess,$pageMenuDisplay,$pageMenuitemDisplay,$pageRightBarDisplay,$pageType,$pageMenuitemOrder,$pageTemplate)
{
	
	$query="INSERT INTO ".MYSQL_DATABASE_PREFIX."pages (`page_name`,`parent_id`,`page_title`,`page_access`,`page_menu_display`,`page_menuitem_display`,`page_menuitem_order`,`page_rightbar_display`,`page_type`,`page_template`) VALUES ('$pageName',$parentId,'$pageTitle',$pageAccess,$pageMenuDisplay,$pageMenuitemDisplay,$pageMenuitemOrder,$pageRightBarDisplay,'$pageType','$pageTemplate')";
	$result=mysql_query($query);
	
	if(mysql_errno()!=0) return 0;
	
	$query="SELECT `page_id` FROM ".MYSQL_DATABASE_PREFIX."pages WHERE `page_name`='$pageName' AND `parent_id`=$parentId";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	$pageId=$row[0];
	

	$query="INSERT INTO ".MYSQL_DATABASE_PREFIX."$pageType (`page_id`) VALUES ($pageId)";
	$result=mysql_query($query);
			
	if(mysql_errno()!=0) return 0;
	
	return $pageId; //will always be greater than 0, since 0 is the Home Page ID
		
}
function insertHomePage($pageId,$pageName,$parentId,$pageTitle,$pageAccess,$pageMenuDisplay,$pageMenuitemDisplay,$pageRightBarDisplay,$pageType,$pageMenuitemOrder,$pageTemplate)
{
	
	$query="INSERT INTO ".MYSQL_DATABASE_PREFIX."pages (`page_id`, `page_name`,`parent_id`,`page_title`,`page_access`,`page_menu_display`,`page_menuitem_display`,`page_menuitem_order`,`page_rightbar_display`,`page_type`,`page_template`) VALUES ($pageId,'$pageName',$parentId,'$pageTitle',$pageAccess,$pageMenuDisplay,$pageMenuitemDisplay,$pageMenuitemOrder,$pageRightBarDisplay,'$pageType','$pageTemplate')";
	$result=mysql_query($query);
	
	if(mysql_errno()!=0) return 0;
	

	$query="INSERT INTO ".MYSQL_DATABASE_PREFIX."$pageType (`page_id`) VALUES ($pageId)";
	$result=mysql_query($query);
			
	if(mysql_errno()!=0) return 0;
	
	return $pageId; //will be 0 for successful op
		
}
function editPageContent($pageType,$pageTypeTableFields,$pageTypeTableFieldTypes,$fieldValues)
{
	
	//An IMPORTANT CODE TO GET THE TABLE TYPE AND INSERT SOME DATA
	$query="INSERT INTO ".MYSQL_DATABASE_PREFIX."article (`page_id`";
	$values=" VALUES ($pageId";
	for($i=0; $i<count($pageTypeTableFields); $i++)
	{
		$query.=",`$pageTypeTableFields[$i]`";
		if($pageTypeTableFieldTypes[$i]=="quoted")
			$values.=",'".$fieldValues[$i]."'";
		else $values.=",".$fieldValues[$i];
		
	}
	$values.=")";
	$query.=") $values";
	
}

function getPageInfoFromID($pageId)
{
	$query="SELECT * FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id=$pageId";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row;
	//$pageInfo=array($row['page_title'],$row['page_name'],$row['page_type'],$row['page_access'],$row['page_menu_display'],$row['page_rightbar_display'],$row['page_menuitem_display'],$row['page_template']);
	
	//return $pageInfo;
}
function setChildAccessFromParentID($parentId,$page_access)
{
	$query= "SELECT page_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$parentId." AND NOT page_id=0";
	$result=mysql_query($query);
	while($row=mysql_fetch_row($result))
	{
		$childPageId=$row[0];
		$query="UPDATE ".MYSQL_DATABASE_PREFIX."pages SET `page_access`=$page_access WHERE `page_id`=$childPageId";
		mysql_query($query);
		setChildAccessFromParentID($childPageId,$page_access);
	}
	
}
function setChildDisplayMenuFromParentID($parentId,$page_displaymenu)
{
	$query= "SELECT page_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$parentId." AND NOT page_id=0";
	
	$result=mysql_query($query);
	while($row=mysql_fetch_row($result))
	{
		$childPageId=$row[0];
		$query="UPDATE ".MYSQL_DATABASE_PREFIX."pages SET `page_menu_display`=$page_displaymenu WHERE `page_id`=$childPageId";
		
		mysql_query($query);
		setChildDisplayMenuFromParentID($childPageId,$page_displaymenu);
	}
	
}
function setChildRightBarDisplayFromParentID($parentId,$page_rightbardisplay)
{
	$query= "SELECT page_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$parentId." AND NOT page_id=0";
	$result=mysql_query($query);
	while($row=mysql_fetch_row($result))
	{
		$childPageId=$row[0];
		$query="UPDATE ".MYSQL_DATABASE_PREFIX."pages SET `page_rightbar_display`=$page_rightbardisplay WHERE `page_id`=$childPageId";
		mysql_query($query);
		setChildRightBarDisplayFromParentID($childPageId,$page_rightbardisplay);
	}
	
}
function setChildMenuItemDisplayFromParentID($parentId,$page_menuitemdisplay)
{
	$query= "SELECT page_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$parentId." AND NOT page_id=0";
	$result=mysql_query($query);
	while($row=mysql_fetch_row($result))
	{
		$childPageId=$row[0];
		$query="UPDATE ".MYSQL_DATABASE_PREFIX."pages SET `page_menuitem_display`=$page_menuitemdisplay WHERE `page_id`=$childPageId";
		mysql_query($query);
		setChildMenuItemDisplayFromParentID($childPageId,$page_menuitemdisplay);
	}
	
}
function setChildTemplateFromParentID($parentId,$page_template)
{
	$query= "SELECT page_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$parentId." AND NOT page_id=0";
	$result=mysql_query($query);
	while($row=mysql_fetch_row($result))
	{
		$childPageId=$row[0];
		$query="UPDATE ".MYSQL_DATABASE_PREFIX."pages SET `page_template`='$page_template' WHERE `page_id`=$childPageId";
		mysql_query($query);
		setChildTemplateFromParentID($childPageId,$page_template);
	}
}
function setChildLoginRequiredFromParentID($parentId,$login_required)
{
	$query= "SELECT page_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$parentId." AND NOT page_id=0";
	$result=mysql_query($query);
	while($row=mysql_fetch_row($result))
	{
		$childPageId=$row[0];
		$query="UPDATE ".MYSQL_DATABASE_PREFIX."pages SET `login_required`=$login_required WHERE `page_id`=$childPageId";
		mysql_query($query);
		setChildLoginRequiredFromParentID($childPageId,$login_required);
	}
}
	

function setPageSettingsFromID($pageId,$page_name,$page_title,$page_access,$page_displaymenu,$page_menuitemdisplay,$page_rightbardisplay,$page_template,$login_required)
{
	$query="UPDATE ".MYSQL_DATABASE_PREFIX."pages SET `page_name`='$page_name',`page_title`='$page_title',`page_access`=$page_access,`page_menu_display`=$page_displaymenu,`page_menuitem_display`=$page_menuitemdisplay,`page_rightbar_display`=$page_rightbardisplay, `page_template`='$page_template', `login_required`=$login_required WHERE page_id=$pageId";
	mysql_query($query);
	
}
function getParentIdFromID($pageId)
{
	$query= "SELECT parent_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE page_id='".$pageId."'";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];
}

function recursiveDeletePageFromID($pageID)
{
	deletePageFromID($pageID);
	
	$query= "SELECT page_id FROM ".MYSQL_DATABASE_PREFIX."pages WHERE parent_id=".$pageID;
	$result=mysql_query($query);
	while($row=mysql_fetch_row($result))
	{
		$childPageId=$row[0];
		recursiveDeletePageFromID($childPageId);
	}
	
	
}
function replaceChildrenParentIdFromID($pageId,$parentId)
{
	$query="UPDATE `".MYSQL_DATABASE_PREFIX."pages` SET `parent_id`=$parentId WHERE `parent_id`=$pageId";
	mysql_query($query);
}
function deletePageFromID($pageId)
{
	$query="SELECT `page_type` FROM `".MYSQL_DATABASE_PREFIX."pages` WHERE `page_id` = $pageId";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	$pageType=$row[0];
	
	$query="DELETE FROM `".MYSQL_DATABASE_PREFIX."pages` WHERE `page_id` = $pageId";
	mysql_query($query);
	
	$query="DELETE FROM `".MYSQL_DATABASE_PREFIX."$pageType` WHERE `page_id` = $pageId";
	mysql_query($query);
	
	
	
}
function getGlobalSettings()
{
	$query="SELECT * FROM `".MYSQL_DATABASE_PREFIX."global`";
	$result=mysql_query($query);
	return mysql_fetch_row($result);

}
function setGlobalSettings($cms_title,$allow_page_header,$allow_page_template,$default_template,$default_user_activate)
{
	$query="UPDATE `".MYSQL_DATABASE_PREFIX."global` SET `cms_title`='$cms_title', `allow_pagespecific_header`=$allow_page_header, `allow_pagespecific_template`=$allow_page_template, `default_template`='$default_template', `default_user_activate`=$default_user_activate";
	mysql_query($query);
}
function insertGlobalSettings($cms_title,$allow_page_header,$allow_page_template,$default_template,$default_user_activate)
{
	$query="INSERT INTO `".MYSQL_DATABASE_PREFIX."global` (`cms_title`, `allow_pagespecific_header`, `allow_pagespecific_template`, `default_template`,`default_user_activate`) VALUES ('$cms_title', $allow_page_header, $allow_page_template, '$default_template', $default_user_activate)";
	mysql_query($query);
}


function isPageTemplateAllowed()
{
	$query="SELECT allow_pagespecific_template FROM `".MYSQL_DATABASE_PREFIX."global`";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	$allow=$row[0]==0?false:true;
	return $allow;
}

function getPageTemplateFromID($pageId)
{
	$query="SELECT page_template FROM `".MYSQL_DATABASE_PREFIX."pages` WHERE `page_id`=$pageId";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];
}
function getLoginRequiredFromPageId($pageId)
{
	$query="SELECT login_required FROM `".MYSQL_DATABASE_PREFIX."pages` WHERE `page_id`=$pageId";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];
}

function getAvailableTemplates()
{
	$query="SELECT template_name FROM `".MYSQL_DATABASE_PREFIX."templates`";
	$result=mysql_query($query);
	$templates=array();
	$i=0;
	while($row=mysql_fetch_row($result))
	{
		$templates[$i]=$row[0];
		$i++;
	}
	
	return $templates;
}

function getDefaultTemplate()
{
	$query="SELECT `default_template` FROM `".MYSQL_DATABASE_PREFIX."global`";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];

}
function getCMSTitle()
{
	$query="SELECT `cms_title` FROM `".MYSQL_DATABASE_PREFIX."global`";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];

}
function isDefaultUserActivated()
{
	$query="SELECT `default_user_activate` FROM `".MYSQL_DATABASE_PREFIX."global`";
	$result=mysql_query($query);
	$row=mysql_fetch_row($result);
	return $row[0];
}
function updateArticleFromPageID($pageId,$content)
{
	$query= "UPDATE ".MYSQL_DATABASE_PREFIX."article SET `page_content`='".$content."' WHERE page_id=".$pageId;
	mysql_query($query);
}

function getAllUsersInfo(&$userId,&$userName,&$userEmail,&$userFullName,&$userPassword,&$userLastLogin,&$userRegDate,&$userActivated)
{
	$query="SELECT * FROM ".MYSQL_DATABASE_PREFIX."users";
	$result=mysql_query($query);
	$userId=array();
	$userEmail=array();
	$userName=array();
	$userFullName=array();
	$userPassword=array();
	$userLastLogin=array();
	$userRegDate=array();
	$userActivated=array();
	$i=0;
	while($row=mysql_fetch_assoc($result))
	{
		$userId[$i]=$row['user_id'];
		$userName[$i]=$row['user_name'];
		$userEmail[$i]=$row['user_email'];
	
		$userFullName[$i]=$row['user_fullname'];
		$userPassword[$i]=$row['user_password'];
		$userLastLogin[$i]=$row['user_lastlogin'];
		$userRegDate[$i]=$row['user_regdate'];
		$userActivated[$i]=$row['user_activated'];
		$i++;
	}
	
}

function activateUserByUserName($username)
{
	$query="UPDATE ".MYSQL_DATABASE_PREFIX."users SET user_activated=1 WHERE user_name='$username'";
	mysql_query($query);
}
function deActivateUserByUserName($username)
{
	$query="UPDATE ".MYSQL_DATABASE_PREFIX."users SET user_activated=0 WHERE user_name='$username'";
	mysql_query($query);
}
function deleteUserByUserName($username)
{
	$query="DELETE FROM ".MYSQL_DATABASE_PREFIX."users WHERE user_name='$username'";
	mysql_query($query);
}
function getTableFieldsName($tablename)
{
	$query="SELECT * FROM ".MYSQL_DATABASE_PREFIX.$tablename;
	$result=mysql_query($query);
	$numfields=mysql_num_fields($result);
	$fields=array();
	$i=0;
	while($i<$numfields)
	{
		$meta=mysql_fetch_field($result,$i);
		if($meta)
		{
			$fields[$i]=$meta->name;
		}
		$i++;
	}
	return $fields;
}
function getUserTableFields()
{
	return getTableFieldsName("users");
}
function insertTemplate($template)
{
	$query="INSERT INTO ".MYSQL_DATABASE_PREFIX."templates VALUES ('$template') ";
	mysql_query($query);
}
function insertPageTypes($pageType,$pageTypeTable,$pageTypeTableFields)
{
	$query="INSERT INTO ".MYSQL_DATABASE_PREFIX."pagetypes VALUES ('$pageType','$pageTypeTable','$pageTypeTableFields' ) ";
	mysql_query($query);
}



?>
