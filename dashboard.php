<? 
session_start();

require_once "./php/authCookieSessionValidate.php";

if (!$isLoggedIn) {
    header("Location: auth/login.php?msg=Please login first");
  } else {
    echo 'Welcome '.$thisUser["name"];
  }

?>