<?php
function getPageDashboard($pageId,$rootPerm,$userId,$action,$access)
{
	$dashboard="<div id='cms-dashboard'>";	
	global $pageFullPath;
	$actions="";
	if($userId==0)
	{
		$actions.=<<<ACTIONS
		<a href='index.php?page=$pageFullPath&action=login&subaction=view'><div class='cms-dashboard-but'>Login</div></a>
		<a href='index.php?page=$pageFullPath&action=register&subaction=view'><div class='cms-dashboard-but'>Register</div></a>
		
ACTIONS;
	}
	else
	{
		$actions.=<<<ACTIONS
		<a href='index.php?page=$pageFullPath&action=logout'><div class='cms-dashboard-but'>Logout</div></a>
		<a href='index.php?page=$pageFullPath&action=profile&subaction=view'><div class='cms-dashboard-but'>Profile</div></a>
ACTIONS;
		
	}
	if($access==true)
	{
	$actions.=<<<ACTIONS
	<a href='index.php?page=$pageFullPath&action=view'><div class='cms-dashboard-but'>View</div></a>
ACTIONS;
	}
	if($rootPerm==true)
	{
		$actions.=<<<ACTIONS
		<a href='index.php?page=$pageFullPath&action=users&subaction=view'><div class='cms-dashboard-but'>Users</div></a>
		<a href='index.php?page=$pageFullPath&action=global&subaction=view'><div class='cms-dashboard-but'>Global Settings</div></a>
		
		
		<a href='index.php?page=$pageFullPath&action=settings&subaction=view'><div class='cms-dashboard-but'>Page Settings</div></a>
		<a href='index.php?page=$pageFullPath&action=delete&subaction=view'><div class='cms-dashboard-but'>Delete</div></a>
		<a href='index.php?page=$pageFullPath&action=create&subaction=view'><div class='cms-dashboard-but'>Create</div></a>
		
		<a href='index.php?page=$pageFullPath&action=edit&subaction=view'><div class='cms-dashboard-but'>Edit</div></a>	
ACTIONS;
	}
	$dashboard.=$actions."</div>";
	return $dashboard;
		
	
}

?>
