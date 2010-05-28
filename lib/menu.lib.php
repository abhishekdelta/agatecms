<?php

function getPageMenu($pageId,$rootPerm,$userId,$action,$access)
{
	global $pageFullPath;
	$menu="<div class='cms-menubar'><a href='index.php?page=$pageFullPath'><div class='cms-menuhead'>";
	if($access==false)
	{
		$menu.="Access Denied</div></a></div>";
		return $menu;
	}
	
	$display=getPageMenuHeadFromPageID($pageId,$pageTitle);
	if($display==0)
		return "";
	$menu.=$pageTitle."</div></a>";
		
	getPageChildrenFromPageID($pageId,$childNames,$childTitles);
	
	for( $i=0; $i < count($childNames); $i++)
	{
		if($childNames[$i]!="")
		$menu.="<a href='index.php?page=".$pageFullPath."/".$childNames[$i]."'><div class='cms-menuitem'>".$childTitles[$i]."</div></a>";
	}
	
	$menu.="</div>";
	return $menu;
}
?>
