<?php

function displayError($error_desc) {
	global $ERRORMSG;
	$ERRORMSG .= "<div class=\"cms-error\">$error_desc</div>";
}

function displayInfo($error_desc) {
	global $INFOMSG;
	$INFOMSG .= "<div class=\"cms-info\">$error_desc</div>";
}

function displayWarning($error_desc) {
	global $WARNINGMSG;
	$WARNINGMSG .= "<div class=\"cms-warning\">$error_desc</div>";
}

?>
