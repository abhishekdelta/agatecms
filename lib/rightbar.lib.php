<?php
function getPageRightBar($pageId,$rootPerm,$userId,$action,$access)
{
	$allow=pageRightBarDisplay($pageId);
	if($allow==0)
		return "";
	
	$date = date("l d M , h:ia");	
	$RIGHTBARCONTENT = "<center id=\"sidebarcontent\">$date</center>";
	return $RIGHTBARCONTENT;
}
?>
