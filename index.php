<? 
session_start();

require_once "../php/authCookieSessionValidate.php";

if (!$isLoggedIn) {
    header("login/login.php?msg=Lütfen Kullanıcı Girişi Yapınız.");
  } else {
    echo 'Hoşgeldiniz '.$thisuser[0]["name"];
  }

?>