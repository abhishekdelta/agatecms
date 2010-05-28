<?php
function getPageBreadcrumb($rootPerm,$userId,$pageIds,$access)
{
	$pageId=$pageIds[0];
	$pageIdsArray=implode(",",$pageIds);
	$pageBreadcrumbArray= getPageBreadcrumbFromPageIDs($pageIdsArray);
	$pageHREF="index.php?page=";
	$breadcrumb="<div id='cms-breadcrumb'><ul class='cms-breadcrumbs'>";
	
	for( $i=count($pageIds)-1; $i > 0; $i--)
	{
	
		$pageTitle=$pageBreadcrumbArray[$pageIds[$i]];
		$pageHREF.="/".$pageBreadcrumbArray[$pageIds[$i]."|name"];
		$breadcrumb.="<li class='cms-breadcrumb-prev'><a href='".$pageHREF."'>".$pageTitle."</a></li>";
	}
	$pageTitle=$pageBreadcrumbArray[$pageId];
	$pageHREF.="/".$pageBreadcrumbArray[$pageId."|name"];
	if($access==false)
	{
		$pageTitle="-";
		$pageHREF="";
	}
	
	
	$breadcrumb.="<li class='cms-breadcrumb-curr'><a href='".$pageHREF."'>".$pageTitle."</a></li>";
	$breadcrumb.="</ul></div>";
	return $breadcrumb;
	
}
?>
