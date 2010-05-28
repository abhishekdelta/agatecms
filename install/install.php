<?php
function cmsInstall() {
	
	if(saveConfigurationSettings()==false)
		return false;
	if(importDatabase()==false)
		return false;
		
	displayInfo("Installation Successful! <a href='..'>Click Here</a> to go to your website.");
	return true;
	
}


/**
 * Save configuration settings submitted from the form.
 * @return bool Boolean value indicating whether the method was successful.
 */
function saveConfigurationSettings() {
	$dbhost=$_POST['db_host'];
	$dbname=$_POST['db_name'];
	$dbuser=$_POST['db_user'];
	$dbpasswd=$_POST['db_passwd'];
	
	
	$dbprefix=($_POST['db_prefix']!=""?$_POST['db_prefix']:"V0_");
	
	global $cmsFolder;
	$configFileText = '';
	require_once('config-prototype.inc.php');
	$writeHandle = @fopen("../config.inc.php", 'w');
	if (!$writeHandle)
	{
		displayError('Could not write to config.inc.php. Please make sure that the file is writable.');
		return false;
	}
	fwrite($writeHandle, $configFileText);
	fclose($writeHandle);
	displayInfo("Configuration Successfully Saved!");

	define("MYSQL_SERVER",$dbhost);
	define("MYSQL_DATABASE",$dbname);
	define("MYSQL_USERNAME",$dbuser);
	define("MYSQL_PASSWORD",$dbpasswd);
	define("MYSQL_DATABASE_PREFIX",$dbprefix);
	
	
	return true;
}



function importDatabase() {
	global $cmsFolder;
	global $libraryFolder;
	mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DATABASE);

	$handle = @fopen("structure.sql", "r");
	$query = '';
	if ($handle) {
	  while (!feof($handle)) {
	    $buffer = fgets($handle, 4096);
	    if (strpos($buffer,"--") !== 0)
	      $query.=$buffer;
	  }
	  fclose($handle);
	}
	$query = str_replace("V0_",MYSQL_DATABASE_PREFIX,$query);
	$singlequeries = explode(";\n",$query);
	foreach ($singlequeries as $singlequery) {
		if (trim($singlequery)!="") {
			$result1 = mysql_query($singlequery);
			if (!$result1) {
				displayError("<h3>Error:</h3><pre>".$singlequery."</pre>\n<br/>Unable to import the rows. ".mysql_error());
				return false;
			}
		}
	}
	
	$adminname=$_POST['admin_name'];
	$adminfullname=$_POST['admin_fullname'];
	$adminemail=$_POST['admin_email'];
	$adminpasswd=$_POST['admin_passwd'];
	$cmsname=$_POST['cms_name'];
	require_once("../$libraryFolder/mysql.lib.php");
	insertGlobalSettings($cmsname,0,0,"default",0);
	insertHomePage(0,"home",0,"Home",0,1,1,1,"article",0,"default");
	insertPageTypes("article","article","page_content");
	insertTemplate("default");
	insertTemplate("default2");
	addAdmin($adminfullname,$adminname,$adminemail,md5($adminpasswd));
	displayInfo("Database Configuration Done!");
	return true;
}

?>
