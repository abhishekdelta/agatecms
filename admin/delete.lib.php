<?php
//TODO : 'select_page' and sitemap/pagetree widget
//TODO : handlePageNameCOnflicts() and selectNewParentFromSitetree()

function deletePageForm()
{
	global $pageFullPath;
	global $PAGESCRIPT;
	$PAGESCRIPT.=<<<PAGESCRIPT
	function showWarning(selected)
	{
		var obj=document.getElementById('delete_warning');
		var warning;
		if(selected=="no_parent")
		{
			warning="The children pages will become orphan and inacessible";	
			document.delete_page_form.submit_button.value="Delete";
		}
		else if(selected=="home_page")
		{
			warning="The entire child tree will become the child of the website home page";
			document.delete_page_form.submit_button.value="Delete";
		}
		else if(selected=="parent_page")
		{
			warning="The entire child tree will become the child of the parent of the current page";
			document.delete_page_form.submit_button.value="Delete";
		}
		else if(selected=="new_page")
		{
			warning="You must create the new page in the next step to complete deletion";
			document.delete_page_form.submit_button.value="Next";
		}
		else if(selected=="select_page")
		{
			warning="You must select the new parent in the next step to complete deletion";
			document.delete_page_form.submit_button.value="Next";
		}
		obj.innerHTML="<div class='cms-warning'>"+warning+"</div>";
	}
	function enableParentSelTab()
	{
		document.getElementsByName("new_parent")[0].disabled=false;
	}
	function disableParentSelTab()
	{
		document.getElementsByName("new_parent")[0].disabled=true;
	}
PAGESCRIPT;
	
	$deletepageform=<<<DELETEPAGEFORM
	<form name='delete_page_form' method='POST' action='index.php?page=$pageFullPath&action=delete&subaction=exec'>
	<fieldset>
	<legend>Delete Page</legend>
	<table>
	<tr>
	<td>Delete child pages recursively ? </td>
	<td><input type='radio' name='delete_children' value='yes' onclick="disableParentSelTab()" />Yes
	<input type='radio' name='delete_children' value='no' onclick="enableParentSelTab()" checked />No
	</td>
	</tr>
	<tr>
	<td>Please select the new parent for the children of this page </td>
	<td><select name='new_parent' onchange='showWarning(this.options[this.selectedIndex].value)'>
	<option value='home_page' checked>Home Page</option>
	<option value='parent_page' >Parent of this page</option>
	<option value='new_page' >Create New Page</option>
	<option value='select_page' >Select from Page Tree</option>
	<option value='no_parent' >No Parent</option>
	</td>
	</tr>
	<tr>
	<td id='delete_warning' colspan="2"></td>
	</tr>
	</table>
	<fieldset>
	<legend>Confirm</legend>
	<table>
	<tr>
	<td>Are you sure you want to delete this page? </td>
	<td><input name='submit_button' type='submit' value='Delete' /></td>
	<td><input name='cancel_button' type='button' value='Cancel' onclick="window.open('index.php?page=$pageFullPath','_top')" /></td>
	</tr>
	</table>
	</fieldset>
	
	
	
	</fieldset>
	</form>
DELETEPAGEFORM;
	return $deletepageform;
	
}

function deletePage($pageId)
{

	if(isset($_POST['delete_page_create_parent']))
	{
		global $adminFolder;
		require_once($adminFolder."/create.lib.php");
		$parentId=getParentIdFromID($pageId);
		$newPageId=createPage($parentId,$newPageName);
		if($newPageId<=0)
		{
			
			return 0;
		}
	
		replaceChildrenParentIdFromID($pageId,$newPageId);
		deletePageFromID($pageId);
		return 1;
			
	}
	
	
	$del_page=1;
	$del_children=$_POST['delete_children']=="yes"?1:0;
	$new_parent=$_POST['new_parent'];
	
	if($del_page==0)
		return 4;
	if($del_children==1)
	{
		recursiveDeletePageFromID($pageId);
		return 1;
	}
	if($new_parent=="home_page")
	{
		replaceChildrenParentIdFromID($pageId,0); //what if there's a page name CONFLICT ??????????????????
		deletePageFromID($pageId);
		handlePageNameConflicts();
		return 1;
	}
	if($new_parent=="parent_page")
	{
		$parentId=getParentIdFromID($pageId);
		replaceChildrenParentIdFromID($pageId,$parentId);
		deletePageFromID($pageId);
		handlePageNameConflicts();
		return 1;
	}
	if($new_parent=="new_page")
	{
		return 2;
	}
	if($new_parent=="select_page")
	{
		return 3;
	}
	if($new_parent=="no_parent")
	{
		
		deletePageFromID($pageId);
		return 1;
	}
	
}

function deletePageNewParentForm($pageId)
{
	global $pageFullPath;
	$pageTypes=getAvailablePageTypes(&$pageTypeNames);
	$templates=getAvailableTemplates();
	$createpage=<<<CREATEPAGE
	<form name='create_parent_form' method='POST' action='index.php?page=$pageFullPath&action=delete&subaction=exec'>
	<fieldset>
	<legend>Create New Parent Page</legend>
	<table>
	<tr>
	<td>Page Title</td><td><input type='text' name='page_title' /></td>
	</tr>
	<tr>
	<td>Page Name</td><td><input type='text' name='page_name' /></td>
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
	<tr><td><input type='hidden' name='delete_page_create_parent' value='create'/></td></tr>
	
	</table>
	</fieldset>
	<br/>
	<input type='submit' value='Delete Page and Create New Parent' />
	</fieldset>
	</form>
CREATEPAGE;
	return $createpage;
	
}
function handlePageNameConflicts()
{
	return 0;
}
function deletePageSelectParentForm($pageId)
{
	return 0;
}
?>
