<?php
function isCookiesEnabled() {
	setcookie("cookie_support", "enabled", 0, "/");
	if(isset($_COOKIE['PHPSESSID']) || (isset($_COOKIE['cookie_support']) && $_COOKIE['cookie_support']=="enabled") ) {
		return true;
	} else
		return false;
}

function showCookieWarning() {
	global $cookieSupported;
	if($cookieSupported==false) {
		displayWarning("Cookie support is required beyond this point. <a href=\"http://www.google.com/cookies.html\">Click here</a> to find out " .
				"how to enable cookies.");
		return true;
	}
	else
		return false;
}

function setAuth($user_id) {
	global $userId;
	$userId = $user_id;
	$_SESSION['userId'] = $userId;
	$_SESSION['data'] = $_COOKIE["PHPSESSID"];
	return $user_id;
}

function resetAuth() {
	global $userId;
	if(isset($_SESSION['userId']))
		unset($_SESSION['userId']);
	if(isset($_SESSION['data']))
		unset($_SESSION['data']);
	$userId = 0;
	global $rootPerm;
	$rootPerm=0;
	return $userId;
}

/* Since this can be the point where a userid is defined, so we must check whether the session is authentic or not , something like using a custom PHPSESSID rather than giving PHP the freedom to decide its own value. chk out authenticate.inc.php*/
function getOrSetUserId()
{	
	global $cookiesEnabled;
	if($cookiesEnabled && isset($_SESSION["userId"]))
		return $_SESSION["userId"];
	else return resetAuth();
}


function getUserId() {
	global $userId;
	return $userId;
}

function isAdmin() {
	global $userId;
	return ($userId==ADMIN_USERID);
}



?>
