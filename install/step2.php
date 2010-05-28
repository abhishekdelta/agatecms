<?php

function checkPHPMagicGPC()
{
	return get_magic_quotes_gpc();
}
function checkStep2()
{
	return true;
}
function getStep2()
{
	$gpcenabled=(checkPHPMagicGPC()==1)?"ON":"OFF";
	$scriptPathWithFolder = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/'));
	$scriptPath = substr($scriptPathWithFolder , 0, strrpos($scriptPathWithFolder , '/'));

	$step2html=<<<STEP2HTML
	<form name='step2form' action='index.php?step=3' method='POST'>
	<fieldset>
	<legend>Installation Step 2</legend>
	<fieldset>
	<legend>PHP Configuration</legend>
	<table>
	<tr><td>Magic Quotes is $gpcenabled</td></tr>
	<tr><td>.htaccess must be supported by your server. 
			The default location of httpd.conf is <mono>/etc/httpd/conf/httpd.conf</mono>, but may be different for you according to your installation.
			<br /><br />
			Add the following lines in the httpd.conf of your webserver :
			<pre><xmp>
				<Directory "$scriptPath">
					AllowOverride All
				</Directory>
			</xmp></pre>
			
			<p>If you have done this, Click on the "Next" button.</p></td>
	</tr>
	</table>
	</fieldset> 
	<input type='hidden' name='db_host' value='{$_POST['db_host']}' />
	<input type='hidden' name='db_name' value='{$_POST['db_name']}' />
	<input type='hidden' name='db_user' value='{$_POST['db_user']}' />
	<input type='hidden' name='db_passwd' value='{$_POST['db_passwd']}' />
	<input type='hidden' name='db_prefix' value='{$_POST['db_prefix']}' />
	<input type='submit' value='Next' />
	</fieldset>
	</form>
STEP2HTML;
	
	return $step2html;
}
?>
