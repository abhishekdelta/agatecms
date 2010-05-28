<?php
function checkStep1()
{
	$dbhost=$_POST['db_host'];
	$dbname=$_POST['db_name'];
	$dbuser=$_POST['db_user'];
	$dbpasswd=$_POST['db_passwd'];
	if($dbpasswd!=$_POST['db_passwd2'])
	{
		displayError("Passwords do not match.");
		return false;
	}
	$dblink=mysql_connect($dbhost,$dbuser,$dbpasswd);
	if($dblink==false)
	{
		displayError("Could not connect to database on '$dbhost' using username '$dbuser' and password '$dbpasswd'.");
		return false;
	}
	$db=mysql_select_db($dbname);
	if($db==false)
	{
		displayError("Could not select the database '$dbname'.<br/> Please make sure the database exists and the user $dbuser has permissions over it.");
			return false;
	}
	$res=mysql_query("CREATE TABLE IF NOT EXISTS `testtable948823` ( `testuserid` int(10) )");
	if($res==false)
	{
		displayError("The User '$dbuser' does not have permissions to CREATE tables in '$dbname'.");
		return false;
	}
	$res=mysql_query("INSERT INTO `testtable948823` VALUES (123)");
	if($res==false)
	{
		displayError("The User '$dbuser' does not have permissions to INSERT values in tables of database '$dbname'.");
		return false;
	}
	$res=mysql_query("UPDATE `testtable948823` SET testuserid=0");
	if($res==false)
	{
		displayError("The User '$dbuser' does not have permissions to UPDATE values in tables of database '$dbname'.");
		return false;
	}
	$res=mysql_query("SELECT * FROM `testtable948823`");
	if($res==false)
	{
		displayError("The User '$dbuser' does not have permissions to SELECT values in tables of database '$dbname'.");
		return false;
	}
	$res=mysql_query("DROP TABLE `testtable948823`");
	if($res==false)
	{
		displayError("The User '$dbuser' does not have permissions to DROP tables of database '$dbname'.");
		return false;
	}
	return true;
	
}
function getStep1()
{
	
	$step1html=<<<STEP1HTML
	<form name='step1form' action='index.php?step=2' method='POST' >
	<fieldset>
	<legend>Installation Step 1</legend>
	<fieldset>
	<legend>Database(MySQL) Configuration</legend>
	<table>
	<tr>
	<td>Database Server :</td><td><input type='text' name='db_host' /></td>
	</tr>
	<tr>
	<td>Database Name :</td><td><input type='text' name='db_name' /></td>
	</tr>
	<tr>
	<td>Database User :</td><td><input type='text' name='db_user' /></td>
	</tr>
	<tr>
	<td>Database Password :</td><td><input type='password' name='db_passwd' /></td>
	</tr>
	<tr>
	<td>Database Password (Verify) :</td><td><input type='password' name='db_passwd2' /></td>
	</tr>
	<tr>
	<td>Database Prefix (Optional) :</td><td><input type='text' name='db_prefix' /></td>
	</tr>
	</table>
	</fieldset>
	
	<input type='submit' value='Next' /><input type='reset' value='Reset' /><br/>
	* All fields are required.
	</fieldset>
	</form>
STEP1HTML;

	return $step1html;
}


?>
