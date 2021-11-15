<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
ob_start();
//session_start();

require_once "Auth.php";
require_once "Util.php";

$auth = new Auth();
$db_handle = new DBController();
$util = new Util();

date_default_timezone_set('Europe/Istanbul');
$IP_Adresi = $_SERVER["REMOTE_ADDR"];
$Tarih = time();


if(isset($_GET["msg"])){
	$msg=str_replace("</script>","",$_GET["msg"]);
	if($msg!=""){
		echo "<script>alert('$msg')</script>";
	}
}

// Get Current date, time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);

// Set Cookie expiration for 1 month
$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month

$isLoggedIn = false;

// Check if loggedin session and redirect if session exists

//echo $_SESSION["member_id"]);

if (! empty($_SESSION["member_id"])) {
    $isLoggedIn = true;
}
// Check if loggedin session exists
else if (! empty($_COOKIE["member_login"]) && ! empty($_COOKIE["random_password"]) && ! empty($_COOKIE["random_selector"])) {
    // Initiate auth token verification diirective to false
    $isPasswordVerified = false;
    $isSelectorVerified = false;
    $isExpiryDateVerified = false;
    
    // Get token for username
    $userToken = $auth->getTokenByUsername($_COOKIE["member_login"],0);
    
    // Validate random password cookie with database
    if (password_verify($_COOKIE["random_password"], $userToken[0]["password_hash"])) {
        $isPasswordVerified = true;
    }
    
    // Validate random selector cookie with database
    if (password_verify($_COOKIE["random_selector"], $userToken[0]["selector_hash"])) {
        $isSelectorVerified = true;
    }
    
    // check cookie expiration by date
    if($userToken[0]["expiry_date"] >= $current_date) {
        $isExpiryDareVerified = true;
    }
    
    // Redirect if all cookie based validation retuens true
    // Else, mark the token as expired and clear cookies
    if (!empty($userToken[0]["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDareVerified) {
        $isLoggedIn = true;
    } else {
        if(!empty($userToken[0]["id"])) {
            $auth->markAsExpired($userToken[0]["id"]);
        }
        // clear cookies
        $util->clearAuthCookie();
    }
}

$member_id = 1;
if (!$isLoggedIn) {
  $thisuser = $auth->getMemberById(1);
} else {
  $member_id = $_SESSION["member_id"];
  $thisuser = $auth->getMemberById($member_id);
  echo $auth->edit("users", $member_id, "last_activity", $Tarih);
  if (!strpos($thisuser[0]["ip"], $IP_Adresi)) {
    echo $auth->edit("users", $member_id, "ip", $thisuser[0]["ip"] . "," . $IP_Adresi);
  }
  //echo '<script>alert("'.$member_id.'");</script>';
}
?>