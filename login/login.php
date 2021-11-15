<? session_start();

require_once "../php/authCookieSessionValidate.php";
$member_id = 1;
if (!$isLoggedIn) {
  $thisuser = $auth->getMemberById(1);
} else {
  $util->redirect("../index.php");
}

if (! empty($_POST["login"])) {
  $isAuthenticated = false;
  
  $username = $_POST["username"];
  $password = md5($_POST["password"]);
  $user = $auth->getMemberByUsername($username);

  if ($password == $user[0]["password"]) {
    if(stristr($user[0]["rank"],"banned")){
      echo "<script>alert('Kullanıcı Girişiniz Yasaklandı.')</script>";
    }else{
      $isAuthenticated = true;
    }
  }
  
  if ($isAuthenticated) {
      $_SESSION["member_id"] = $user[0]["id"];
      echo "A - ";
      // Set Auth Cookies if 'Remember Me' checked
      if (! empty($_POST["remember"])) {
          setcookie("member_login", $username, $cookie_expiration_time);
      echo "B - ";
          
          $random_password = $util->getToken(16);
          setcookie("random_password", $random_password, $cookie_expiration_time);
          
          $random_selector = $util->getToken(32);
          setcookie("random_selector", $random_selector, $cookie_expiration_time);
          
          $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
          $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
          
          $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
          
          // mark existing token as expired
          $userToken = $auth->getTokenByUsername($username, 0);
          if (! empty($userToken[0]["id"])) {
              $auth->markAsExpired($userToken[0]["id"]);
      echo "C - ";
          }
          // Insert new token
          $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
      echo "D - ";
      } else {
          $util->clearAuthCookie();
      }
      echo "e - ";
      $util->redirect("../index.php");
      echo "f - ";
      //header("Location:../index.php");
  } else {
      $message = "Hatalı Kullanıcı Girişi!";
  }
}

$subLink="../";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>LOGIN</title>
  <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
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
                <i class="fa fa-user"></i> Giriş Yap
              </div>
			      </div>
            <div class="card-body p-2">
            <form action="" method="post">
              <div class="error-message"><?php if(isset($message)) { echo $message; } ?></div>
            
              <div class="field-group mt-2">
                <input name="username" type="username" class="form-control" aria-describedby="nickname" placeholder="Kullanıcı Adı" value="<? echo $_GET["username"]; ?>" required>
              </div>
              <div class="field-group mt-2">
                <input name="password" type="password" id="password" class="form-control" aria-describedby="password" placeholder="Şifre" required>
              <i class="fa fa-eye fa-lg" style="position:relative;float:right;top:-28px;left:-15px;z-index:2;" id="togglePassword"></i>
              </div>
              <div class="field-group mt-2 text-left">
                <div class="type-container">
                  <input type="checkbox" id="remember" class="job-style"
                  <?php if(isset($_COOKIE["member_login"])) { ?> checked
                    <?php } ?> />
                  <label for="remember">Beni Hatırla</label>
                  </div>
              </div>
              <input type="submit" name="login" value="Giriş Yap"
                        class="btn btn-danger login mt-2 w-100"></span>
              <button class="btn btn-outline-danger login mt-2 w-100" onclick="window.location = 'register.php';">Kayıt Ol</button>
            </form>
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
	
	togglePassword.addEventListener('click', function (e) {
		// toggle the type attribute
		const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
		password.setAttribute('type', type);
		// toggle the eye slash icon
		this.classList.toggle('fa-eye-slash');
	});
</script>
</html>
