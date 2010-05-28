<?php
//TODO: Block certain groups from accessing that page...
function settingsPage($pageId)
{

	global $pageFullPath;
	global $CMSTEMPLATE;
	
	list(,$dbPageName,,$dbPageTitle,$dbPageAccess,$dbPageDisplayMenu,$dbPageMenuitemDisplay,,$dbPageType,$dbPageRightbarDisplay,,$dbPageTemplate,$dbLoginRequired)=getPageInfoFromID($pageId);
	$dbPageAccessRoot="";
	$dbPageAccessGuest="";
	$dbPageDisplayMenuYes="";
	$dbPageDisplayMenuNo="";
	$dbPageRightbarDisplayYes="";
	$dbPageRightbarDisplayNo="";
	$dbPageMenuitemDisplayYes="";
	$dbPageMenuitemDisplayNo="";
	
	$dbPageDefTemplate="";
	$dbPageTemplateDisabled="";
	if($dbPageTemplate==$CMSTEMPLATE)
	{
		$dbPageDefTemplate="checked";
		$dbPageTemplateDisabled="disabled=true";
	}
	
	
	$dbPageAccess==0?$dbPageAccessGuest="checked":$dbPageAccessRoot="checked";
	$dbPageDisplayMenu==0?$dbPageDisplayMenuNo="checked":$dbPageDisplayMenuYes="checked";
	$dbPageRightbarDisplay==0?$dbPageRightbarDisplayNo="checked":$dbPageRightbarDisplayYes="checked";
	$dbPageMenuitemDisplay==0?$dbPageMenuitemDisplayNo="checked":$dbPageMenuitemDisplayYes="checked";
	$dbLoginRequired=$dbLoginRequired==0?"":"checked";
	global $PAGESCRIPT;
	$PAGESCRIPT.=<<<PAGESCRIPT
	function toggleSelTemplate()
	{
		var obj=document.getElementsByName('page_template')[0];
		obj.disabled=(obj.disabled==true?false:true);
		

	}
PAGESCRIPT;
	$templates=getAvailableTemplates();
	$settingspage=<<<SETTINGSPAGE
	<form name='page_settings_form' method='POST' action='index.php?page=$pageFullPath&action=settings&subaction=update'>
	<fieldset>
	<legend>Edit Page Settings</legend>
	<table>
	<tr>
	<td>Page Title</td><td><input type='text' name='page_title' value='$dbPageTitle' /></td>
	</tr>
	<tr>
	<td>Page Name *</td><td><input type='text' name='page_name' value='$dbPageName' /></td>
	</tr>
	<tr>
	<td>Page Type</td>
	<td>$dbPageType</td>
	</tr>
	<tr>
	<td>Allow page access to :</td>
	<td><input type='radio' name='page_access' value='root' $dbPageAccessRoot />Root
	<input type='radio' name='page_access' value='everyone' $dbPageAccessGuest />Everyone
	</td><td>
	<input type="checkbox" name='access_propogate' value='yes' />Propogate setting to all child pages
	</td>
	</tr>
	<tr>
	<td>Display left menubar in page ?</td>
	<td><input type='radio' name='page_displaymenu' value='yes' $dbPageDisplayMenuYes />Yes
	<input type='radio' name='page_displaymenu' value='no' $dbPageDisplayMenuNo />No
	</td>
	<td>
	<input type="checkbox" name='displaymenu_propogate' value='yes' />Propogate setting to all child pages
	</td>
	</tr>
	
	<tr>
	<td>Display right bar in page ?</td>
	<td><input type='radio' name='page_rightbardisplay' value='yes' $dbPageRightbarDisplayYes />Yes
	<input type='radio' name='page_rightbardisplay' value='no' $dbPageRightbarDisplayNo />No
	</td><td>
	<input type="checkbox" name='rightbardisplay_propogate' value='yes' />Propogate setting to all child pages
	</td>
	</tr>
	<tr>
	<td>Show this page in the parent menubar ?</td>
	<td><input type='radio' name='page_menuitemdisplay' value='yes' $dbPageMenuitemDisplayYes />Yes
	<input type='radio' name='page_menuitemdisplay' value='no' $dbPageMenuitemDisplayNo />No
	</td>
	<td>
	<input type="checkbox" name='menuitemdisplay_propogate' value='yes' />Propogate setting to all child pages
	</td>
	</tr>
	</table>
	<fieldset>
	<legend>Template</legend>
	<table>
	<tr>
	<td>Use Default Template ?</td>
	<td><input type='checkbox' name='default_template' value='yes' onchange="toggleSelTemplate()" $dbPageDefTemplate /></td>
	<td rowspan=2><input type="checkbox" name='template_propogate' value='yes' />Propogate setting to all child pages
	</td>
	</tr>
	<tr>
	<td>Select Template</td>
	<td><select name='page_template' $dbPageTemplateDisabled>
SETTINGSPAGE;
	for($i=0; $i<count($templates); $i++)
	{
		if($templates[$i]==$dbPageTemplate)
		$settingspage.="<option value='".$templates[$i]."' selected >".ucwords($templates[$i])."</option>";
		else
		$settingspage.="<option value='".$templates[$i]."' >".ucwords($templates[$i])."</option>";
	}
	$settingspage.=<<<SETTINGSPAGE
	</select>
	
	</tr>
	
	</table>
	</fieldset><br/>
	<fieldset>
	<legend>Users</legend>
	<table>
	<tr>
	<td>Login required to access this page ?</td>
	<td><input type='checkbox' name='login_required' value='yes' $dbLoginRequired /></td>
	<td><input type="checkbox" name='login_propogate' value='yes' />Propogate setting to all child pages</td>
	</tr>
	</table>
	</fieldset>
	<br/>
	<input type='submit' value='Update' />
	<input type='button' value='Cancel' onclick="window.open('index.php?page=$pageFullPath','_top')" />
	</fieldset>
	</form>
	<br/>* Displayed in the url, must be unique for a given directory.
SETTINGSPAGE;

	return $settingspage;
}

function updatePageSettings($pageId)
{
	
	global $pageFullPath;
	global $CMSTEMPLATE;
	
	$page_title=$_POST['page_title'];
	$page_name=$_POST['page_name'];
	
	$page_access=$_POST['page_access']=="root"?1:0;
	$page_displaymenu=$_POST['page_displaymenu']=="yes"?1:0;
	$page_menuitemdisplay=$_POST['page_menuitemdisplay']=="yes"?1:0;
	$page_rightbardisplay=$_POST['page_rightbardisplay']=="yes"?1:0;
	if(isset($_POST['default_template']))
		$page_template=$CMSTEMPLATE;
	else $page_template=$_POST['page_template'];
	
	$page_access_propogate=isset($_POST['access_propogate'])?1:0;
	$page_displaymenu_propogate=isset($_POST['displaymenu_propogate'])?1:0;
	$page_rightbardisplay_propogate=isset($_POST['rightbardisplay_propogate'])?1:0;
	$page_menuitemdisplay_propogate=isset($_POST['menuitemdisplay_propogate'])?1:0;
	
	$template_propogate=isset($_POST['template_propogate'])?1:0;
	$login_required=isset($_POST['login_required'])?1:0;
	$login_propogate=isset($_POST['login_propogate'])?1:0;
	
	setPageSettingsFromID($pageId,$page_name,$page_title,$page_access,$page_displaymenu,$page_menuitemdisplay,$page_rightbardisplay,$page_template,$login_required);
	
	//if page name has changed, so $pageFullPath has to be changed too
	
	$pageFullPathArray=explode("/",$pageFullPath);
	$sz=count($pageFullPathArray);
	$pageFullPathArray[$sz-1]=$page_name;
	$pageFullPath=implode("/",$pageFullPathArray);
	
	if($page_access_propogate==1)
	{
		
		setChildAccessFromParentID($pageId,$page_access);
	}
	if($page_displaymenu_propogate==1)
	{
		
		setChildDisplayMenuFromParentID($pageId,$page_displaymenu);
	}
	if($page_rightbardisplay_propogate==1)
	{	
		setChildRightBarDisplayFromParentID($pageId,$page_rightbardisplay);
	}
	if($page_menuitemdisplay_propogate==1)
	{	
		setChildMenuItemDisplayFromParentID($pageId,$page_menuitemdisplay);
	}
	if($template_propogate==1)
	{
		setChildTemplateFromParentID($pageId,$page_template);
	}
	if($login_propogate==1)
	{
		setChildLoginRequiredFromParentID($pageId,$login_required);
	}
	
	
	
}
?>
