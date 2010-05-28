<?php
function editPage($pageId,$content)
{
	$pageType=getPagetypeFromPageID($pageId);
	if($pageType=="article")
	{
		return ckEditor($pageId,$content);
	}
}
function ckEditor($pageId,$content)
{
	global $pageFullPath;
	global $editorFolder;
	if($content=="")
		$content=htmlspecialchars(stripslashes(getContentFromPageID($pageId))); 
	$ckeditorcode=<<<CKEDITOR
	<script type="text/javascript" src="editor/ckeditor.js"></script>
	<script src="editor/compatibility.js" type="text/javascript"></script>
	<form action="index.php?page=$pageFullPath&action=edit&subaction=save" method="post">
		<p>
			<label for="editor1">
				Editor 1:</label><br />
			<textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10">$content</textarea>
		</p>
		<p>
			<input name="update" type="submit" value="Update" />
			<input name="preview" type="submit" value="Preview" />
			<input name="cancel" type="button" onclick="window.open('index.php?page=$pageFullPath','_top')" value="Cancel" />
		</p>
	</form>
CKEDITOR;
	return $ckeditorcode;
}
function savePage($pageId)
{
	
	$pageType=getPagetypeFromPageID($pageId);
	if($pageType=="article")
	{
		$value = $_POST["editor1"];
	
		if ( get_magic_quotes_gpc() )
			$postedValue = $value;
		else
			$postedValue = addslashes($value);
			
		updateArticleFromPageID($pageId,$postedValue);
	}
}
function getPreview($pageId)
{
	$pageType=getPagetypeFromPageID($pageId);
	if($pageType=="article")
	{
		$value = $_POST["editor1"];
	
		if ( get_magic_quotes_gpc() )
			$postedValue = stripslashes($value);
		else
			$postedValue = $value;
		
		return $postedValue;
	}
	return NULL;
}
function editPage2($pageId)
{
	global $pageFullPath;
	global $editorFolder;
	$ckeditorcode=<<<CKEDITOR
	<fieldset title="Output">
		<legend>Edit Page</legend>
		<form action="index.php?page=$pageFullPath&action=edit&subaction=save" method="post">
			<p>
CKEDITOR;
			
	require_once($editorFolder."/ckeditor.php");
	// The initial value to be displayed in the editor.
	$initialValue = '<p>This is some <strong>sample text</strong>.</p>';
	// Create class instance.
	$CKEditor = new CKEditor();
	// Path to CKEditor directory, ideally instead of relative dir, use an absolute path:
	//   $CKEditor->basePath = '/ckeditor/'
	// If not set, CKEditor will try to detect the correct path.
	$CKEditor->basePath = $editorFolder."/";
	// Create textarea element and attach CKEditor to it.
	$ckeditorcode.=$CKEditor->editor("editor1", $initialValue);
	$ckeditorcode.=<<<CKEDITOR
				<input type="submit" value="Submit"/>
			</p>
		</form>
	</fieldset>
CKEDITOR;

	return $ckeditorcode;
}
?>
