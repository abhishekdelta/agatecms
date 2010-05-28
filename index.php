<?php
/**
 * Agate CMS by Abhishek Shrivastava 
**/

require_once("config.inc.php");
require_once($libraryFolder."/mysql.lib.php");
require_once($libraryFolder."/error.lib.php");
require_once($libraryFolder."/auth.lib.php");
require_once($libraryFolder."/page.lib.php");
require_once($libraryFolder."/breadcrumb.lib.php");
require_once($libraryFolder."/dashboard.lib.php");
require_once($libraryFolder."/menu.lib.php");
require_once($libraryFolder."/rightbar.lib.php");


$cookiesEnabled=isCookiesEnabled();
if($cookiesEnabled==true)
	session_start();
	
connectMySQL();


$userId=getOrSetUserId();

$CMSTEMPLATE=getDefaultTemplate();
$CMSTITLE=getCMSTitle();

$pageFullPath = strtolower(isset($_GET['page']) ? $_GET['page'] : '');
$action = strtolower(isset($_GET['action']) ? ($_GET['action'] != "" ? $_GET['action'] : "view") : "view");
$subaction =strtolower(isset($_GET['subaction']) ? $_GET['subaction'] : '');

$pageIds=getPageIDsFromURL($pageFullPath);
if($pageIds == NULL)
{
	$serverInfo=$_SERVER["SERVER_SIGNATURE"];
	$cmsVersion=CMS_VERSION;
	$error404=<<<ERROR404
	<html>
	<head><title>Error 404 Not Found</title></head>
	<body><h1>Not Found</h1>
	<p>The requested URL $pageFullPath was not found on this server.
	</p><hr/>$cmsVersion $serverInfo
	</body>
	</html>
ERROR404;
	header("HTTP/1.0 404 Not Found");
	echo $error404;
	disconnectMySQL();
	exit();
}



/* What if the request being made is a file download? Can we put a file download helper or something!*/
$pageId=$pageIds[0];
$rootPerm = isAdmin();
$access=getPageAccessFromPageID($pageId,$rootPerm);
$PAGECONTENT = getPageContent($pageId,$rootPerm,$userId,$action,$subaction,$access); //This may also change many global variables so this should be the first one
$PAGETITLE = getPageTitle($pageId,$rootPerm,$userId,$action,$access);
$PAGEBREADCRUMB = getPageBreadcrumb($rootPerm,$userId,$pageIds,$access);
$PAGEMENU = getPageMenu($pageId,$rootPerm,$userId,$action,$access);
$PAGEDASHBOARD = getPageDashboard($pageId,$rootPerm,$userId,$action,$access);
$PAGERIGHTBAR= getPageRightBar($pageId,$rootPerm,$userId,$action,$access);
//setcookie("cookie_support","enabled",0,"\"); //REMOVE  THIS WHEN CMS IS DONE
$PAGETEMPLATE = getPageTemplate($pageId);

displayPage();

disconnectMySQL();
exit();


?>
