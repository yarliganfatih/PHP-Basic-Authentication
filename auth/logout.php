<?php
session_start();

require "../php/Util.php";
$util = new Util();

//Clear Session
$_SESSION["loggedin_user_id"] = "";
session_destroy();

// clear cookies
$util->clearAuthCookie();

header("Location: ../");
?>