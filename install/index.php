<?php
/* Installation */
require_once("../config.inc.php");
if(defined("MYSQL_DATABASE_PREFIX")==true)
{
	echo "CMS cannot be Re-installed from the installed version. Please install from a fresh copy of CMS. <a href='javascript:history.back()'>Click Here</a> to go back.";
	exit(1);
}
require_once("../lib/error.lib.php");
require_once("step1.php");
require_once("step2.php");
require_once("step3.php");
require_once("install.php");
global $ERRORMSG;
global $INFOMSG;
global $WARNINGMSG;

$errorLevel=4; /* see below */

switch($errorLevel)
{
	case 0 : error_reporting(0); break; // Turn off all error reporting	
	case 1 : error_reporting(E_ERROR | E_WARNING | E_PARSE); break; // Report simple running errors	
	case 2 : error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); break; // Reporting E_NOTICE can be good too
									// (to report uninitialized variables or catch variable name misspellings ...)
	case 3 : error_reporting(E_ALL ^ E_NOTICE); break;// Report all errors except E_NOTICE. This is the default value set in php.ini
	case 4 : error_reporting(E_ALL); break;// Report all PHP errors
}
$PAGETITLE=CMS_VERSION." Installation";
$CMSTITLE=CMS_VERSION;;
$STEP=1;
if(isset($_GET['step']))
{
	$STEP=$_GET['step'];	
	if(!checkValidStep($STEP))
		$STEP=$STEP-1;
}
$STEP1SEL="";
$STEP2SEL="";
$STEP3SEL="";

switch($STEP)
{
	case 1 : $PAGECONTENT=getStep1(); $STEP1SEL="selected"; break;
	case 2 : $PAGECONTENT=getStep2(); $STEP2SEL="selected"; break;
	case 3 : $PAGECONTENT=getStep3(); $STEP3SEL="selected"; break;
	case 4 : $PAGECONTENT=cmsInstall()==true?"Installation Successful!":"Installation Failed!"; break;
	default : $PAGECONTENT=getStep1(); $STEP1SEL="selected"; break;
}

$PAGEMENU=<<<PAGEMENU
<div class='cms-menubar'>
<div class='cms-menuhead'>Installation Step</div>
<div class='cms-menuitem $STEP1SEL '>Step 1</div>
<div class='cms-menuitem $STEP2SEL '>Step 2</div>
<div class='cms-menuitem $STEP3SEL '>Step 3</div>
</div>
PAGEMENU;

require_once("template/index.php");

function checkValidStep($step)
{
	switch($step)
	{
		case 1 : return true;
		case 2 : return checkStep1();
		case 3 : return checkStep2();
		case 4 : return checkStep3();
	}
}


?>

