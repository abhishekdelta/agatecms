<?php
function createPageForm($pageId)
{

//	NOTE : IF YOU CHANGE IN THIS FORM, PLEASE ALSO CHANGE IN delete.lib.php CREATE NEW PAGE on DELETE FORM if NECESSARY!

	global $pageFullPath;
	global $PAGESCRIPT;
	$PAGESCRIPT.=<<<PAGESCRIPT
	function toggleSelTemplate()
	{
		var obj=document.getElementsByName('page_template')[0];
		obj.disabled=(obj.disabled==true?false:true);
		
	}
PAGESCRIPT;

	$pageTypes=getAvailablePageTypes(&$pageTypeNames);
	$templates=getAvailableTemplates();
	
	$createpage=<<<CREATEPAGE
	<form name='create_page_form' method='POST' action='index.php?page=$pageFullPath&action=create&subaction=exec'>
	<fieldset>
	<legend>Create New Page</legend>
	<table>
	<tr>
	<td>Page Title</td><td><input type='text' name='page_title' /></td>
	</tr>
	<tr>
	<td>Page Name*</td><td><input type='text' name='page_name' /></td>
	</tr>
	<tr>
	<td>Page Type</td>
	<td><select name='page_type' >
CREATEPAGE;
	for($i=0; $i<count($pageTypes); $i++)
		$createpage.="<option value='".$pageTypes[$i]."'>".ucwords($pageTypeNames[$i])."</option>";

	$createpage.=<<<CREATEPAGE
	</select>
	</td>
	</tr>
	<tr>
	<td>Allow page access to :</td>
	<td><input type='radio' name='page_access' value='root' />Root
	<input type='radio' name='page_access' value='everyone' checked />Everyone
	</td>
	</tr>
	<tr>
	<td>Display left menubar in page ?</td>
	<td><input type='radio' name='page_displaymenu' value='yes' checked />Yes
	<input type='radio' name='page_displaymenu' value='no' />No
	</td>
	</tr>
	<tr>
	<td>Display right bar in page ?</td>
	<td><input type='radio' name='page_rightbardisplay' value='yes' checked/>Yes
	<input type='radio' name='page_rightbardisplay' value='no' />No
	</td>
	</tr>
	<tr>
	<td>Show this page in the parent menubar ?</td>
	<td><input type='radio' name='page_menuitemdisplay' value='yes' checked/>Yes
	<input type='radio' name='page_menuitemdisplay' value='no' />No
	</td>
	</tr>
	</table>
	<fieldset>
	<legend>Template</legend>
	<table>
	<tr>
	<td>Use Default Template ?</td>
	<td><input type='checkbox' name='default_template' value='yes' onchange="toggleSelTemplate()" checked /></td>
	</tr>
	<tr>
	<td>Select Template</td>
	<td><select name='page_template' disabled=true>
CREATEPAGE;
	for($i=0; $i<count($templates); $i++)
		$createpage.="<option value='".$templates[$i]."'>".ucwords($templates[$i])."</option>";
	$createpage.=<<<CREATEPAGE
	</select>
	</td>
	</tr>
	
	</table>
	</fieldset>
	<br/>
	<input type='submit' value='Create' />
	<input type='button' value='Cancel' onclick="window.open('index.php?page=$pageFullPath','_top')" />
	</fieldset>
	</form>
	<br/>* Displayed in the url, must be unique for a given directory.
CREATEPAGE;
	return $createpage;
	
}	

function createPage($pageId,&$newPageName)
{
	global $CMSTEMPLATE;
	getPageChildrenFromPageID($pageId,$childNames,$childTitles);
	$page_title=$_POST['page_title'];
	$page_name=$_POST['page_name'];
	$page_type=$_POST['page_type'];
	if(isset($_POST['default_template']))
		$page_template=$CMSTEMPLATE;
	else $page_template=$_POST['page_template'];
	$page_access=$_POST['page_access']=="root"?1:0;
	$page_displaymenu=$_POST['page_displaymenu']=="yes"?1:0;
	$page_menuitemdisplay=$_POST['page_menuitemdisplay']=="yes"?1:0;
	$page_rightbardisplay=$_POST['page_rightbardisplay']=="yes"?1:0;
	
	for($i=0; $i<count($childNames); $i++)
	{
	 if(strtolower($page_name)==strtolower($childNames[$i]))
	 {
	 	displayError("The parent page already has a child with the page name you specified.<br/>Please select a different pagename.");
	 	return 0;
	 }
	}
	
	$page_order=getPageMenuitemOrder($pageId);
	$newPageName=$page_name;

	return insertChildPage($page_name,$pageId,$page_title,$page_access,$page_displaymenu,$page_menuitemdisplay,$page_rightbardisplay,$page_type,$page_order,$page_template);	
}

?>
