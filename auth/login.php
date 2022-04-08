<?
session_start();
$subLink = "../";
require_once "../php/authCookieSessionValidate.php";
$member_id = 1;
if ($isLoggedIn) {
  $util->redirect("../index.php");
}

if (!empty($_POST["login"])) {
  $isAuthenticated = false;

  $username = $_POST["username"];
  $password = md5($_POST["password"]);

  $user = $auth->getMemberByUsername($username)[0];

  if (!empty($user)) {
    if ($user["password"] == $password) {
      if (stristr($user["rank"], "banned")) { // if user has banned rank, don't allow login
        $message = "Banned user cannot login";
      } else if ($user["email_confirm"] == 0) { // if user has not confirmed email, don't allow login
        $message = "Please confirm your email address";
      } else if ($user["admin_confirm"] == 0) { // if user has not confirmed by admin, don't allow login
        $message = "Please wait for admin to confirm your account";
      } else { // if user is not banned, confirmed email, and be confirmed by admin, allow login
        $isAuthenticated = true;
      }
    } else { // if password is incorrect, don't allow login
      $message = "Invalid password";
    }
  } else { // if username is incorrect, don't allow login
    $message = "Account does not exist, Please control your username";
  }

  if ($isAuthenticated) {
    $_SESSION["loggedin_user_id"] = $user["id"];
    // Set Auth Cookies if 'Remember Me' checked
    if (!empty($_POST["remember"])) {
      setcookie("member_login", $username, $cookie_expiration_time);

      $random_password = $util->getToken(16);
      setcookie("random_password", $random_password, $cookie_expiration_time);

      $random_selector = $util->getToken(32);
      setcookie("random_selector", $random_selector, $cookie_expiration_time);

      $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
      $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);

      $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);

      // mark existing token as expired
      $userToken = $auth->getTokenByUsername($username, 0);
      if (!empty($userToken[0]["id"])) {
        $auth->markAsExpired($userToken[0]["id"]);
      }
      // Insert new token
      $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
    } else {
      $util->clearAuthCookie();
    }
    $util->redirect("../dasboard.php");
    //header("Location:../index.php");
  } else {
    //$message .= "Access denied";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="shortcut icon" href="../assets/icon.png" type="image/x-icon" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Login page with PHP">
  <meta name="author" content="Fatih Yarlıgan">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css'>
  <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>

  <body class="fixed-nav sticky-footer" id="body">
    <div class="content-wrapper">
      <div class="container-fluid">
        <center>
          <div class="col-lg-4">
            <div class="card mb-3 mt-5">
              <div class="card-header d-flex justify-content-between">
                <div>
                  <i class="fa fa-user"></i> Login
                </div>
              </div>
              <div class="card-body p-2">
                <form action="login.php" method="post">
                  <div class="error-message">
                    <?php if (isset($message)) {
                      echo $message;
                    } ?>
                  </div>

                  <div class="field-group mt-2">
                    <input name="username" type="text" class="form-control" aria-describedby="username" placeholder="Username" value="<? echo $_GET["username"]; ?>" required 
                      onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)"> <!-- only allow letters and numbers -->
                  </div>
                  <div class="field-group mt-2">
                    <input name="password" type="password" id="password" class="form-control" aria-describedby="password" placeholder="Password" required>
                    <i class="fa fa-eye fa-lg" style="position:relative;float:right;top:-28px;left:-15px;z-index:2;" id="togglePassword"></i>
                  </div>
                  <div class="field-group mt-2 text-left">
                    <div class="type-container">
                      <input type="checkbox" id="remember" class="job-style" <?php if (isset($_COOKIE["member_login"])) { ?> checked <?php } ?> />
                      <label for="remember">Remember Me</label>
                    </div>
                  </div>
                  <input type="submit" name="login" value="Login" class="btn btn-primary login mt-2 w-100">
                </form>
                <button class="btn btn-outline-primary login mt-2 w-100" onclick="window.location = 'signup.php';">Sign Up</button>
              </div>
            </div>
          </div>
        </center>
      </div>
    </div>
    </div>
  </body>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function(e) {
      // toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // toggle the eye slash icon
      this.classList.toggle('fa-eye-slash');
    });
  </script>

</html>