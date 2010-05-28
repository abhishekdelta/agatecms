<?php
global $configFileText;
$configFileText=<<<CONFIGFILE
<?php
\$templateFolder="templates";
\$uploadFolder="uploads";
\$libraryFolder="lib";
\$widgetFolder="widgets";
\$adminFolder="admin";
\$editorFolder="editor";


\$PAGECONTENT;
\$PAGETITLE;
\$PAGEBREADCRUMB;
\$PAGEMENU;
\$PAGETEMPLATE;
\$PAGEDASHBOARD;
\$PAGESCRIPT;
\$PAGEONLOADFUNC;
\$PAGERIGHTBAR;
\$ERRORMSG;
\$INFOMSG;
\$WARNINGMSG;
\$PAGETEMPLATE;
\$CMSTEMPLATE;
\$CMSTITLE;

\$pageFullPath;
\$cookiesEnabled;
\$action;
\$subaction;
\$userId;



define("MYSQL_SERVER","$dbhost");
define("MYSQL_USERNAME","$dbuser");
define("MYSQL_PASSWORD","$dbpasswd");
define("MYSQL_DATABASE","$dbname");
define("MYSQL_DATABASE_PREFIX","$dbprefix");

define("CMS_VERSION","Agate CMS v0.01");
define("CMS_NAME","Agate CMS");
define("CMS_AUTHOR","Abhishek (jereme)");
define("ADMIN_USERID",-8080); //make sure its a NEGATIVE NUMBER!

/* TODO : Modules, Mailing features, like mail on registration or bulk mail by the site admin to registrants at any time */
/* Modules db functions in pragcms is in common.lib.php */

ini_set('magic_quotes_gpc',1);
ini_set('display_errors',1);
\$errorLevel=4; /* see below */

switch(\$errorLevel)
{
	case 0 : error_reporting(0); break; // Turn off all error reporting	
	case 1 : error_reporting(E_ERROR | E_WARNING | E_PARSE); break; // Report simple running errors	
	case 2 : error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); break; // Reporting E_NOTICE can be good too
									// (to report uninitialized variables or catch variable name misspellings ...)
	case 3 : error_reporting(E_ALL ^ E_NOTICE); break;// Report all errors except E_NOTICE. This is the default value set in php.ini
	case 4 : error_reporting(E_ALL); break;// Report all PHP errors
}
CONFIGFILE;
?>
