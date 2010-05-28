<?php
function globalSettingsForm($pageId)
{
	global $pageFullPath;
	global $CMSTEMPLATE;
	list($db_cms_title,$allow_pagespecific_header,$allow_pagespecific_template,,$activate_useronreg)=getGlobalSettings();

	$allow_pagespecific_header=$allow_pagespecific_header==0?"":"checked";
	$allow_pagespecific_template=$allow_pagespecific_template==0?"":"checked";
	$activate_useronreg=$activate_useronreg==0?"":"checked";
	$globalform=<<<globalform
	<form name='admin_page_form' method='POST' action='index.php?page=$pageFullPath&action=global&subaction=exec'>
	<fieldset>
	<legend>Global Settings</legend>
	<table>
	<tr>
	<td>Website Name :</td>
	<td><input type="text" name='cms_title' value='$db_cms_title'></td>
	</tr>
	<tr>
	<td>Allow Page-specific Headers ?</td>
	<td><input name='allow_page_header' type='checkbox' $allow_pagespecific_header></td>
	</tr>
	<tr>
	<td>Allow Page-specific Template ?</td>
	<td><input name='allow_page_template' type='checkbox' $allow_pagespecific_template></td>
	</tr>
	
	<tr>
	<td>Default template :</td>
	<td><select name='default_template' >
globalform;

	$templates=getAvailableTemplates();
	for($i=0; $i<count($templates); $i++)
	{
		if($templates[$i]==$CMSTEMPLATE)
		$globalform.="<option value='".$templates[$i]."' selected >".ucwords($templates[$i])."</option>";
		else
		$globalform.="<option value='".$templates[$i]."' >".ucwords($templates[$i])."</option>";
	}

$globalform.=<<<globalform
	</select>
	</td>
	</tr>
	<tr>
	<td>Activate User On Registration ?</td>
	<td><input name='activate_useronreg' type='checkbox' $activate_useronreg></td>
	</tr>
	<tr>
	<td><input type='submit' value='Update' />
	<input type='button' value='Cancel' onclick="window.open('index.php?page=$pageFullPath','_top')" /></td>
	</tr>
	</table>
	</fieldset>
	</form>
globalform;
	return $globalform;
}

function updateGlobalSettings($pageId)
{
	global $CMSTEMPLATE;
	global $CMSTITLE;
	$allow_pagespecific_header=isset($_POST['allow_page_header'])?1:0;
	$allow_pagespecific_template=isset($_POST['allow_page_template'])?1:0;
	$activate_useronreg=isset($_POST['activate_useronreg'])?1:0;
	$CMSTITLE=$_POST['cms_title'];
	$CMSTEMPLATE=$_POST['default_template'];
	setGlobalSettings($CMSTITLE,$allow_pagespecific_header,$allow_pagespecific_template,$CMSTEMPLATE,$activate_useronreg);
	
}
?>
