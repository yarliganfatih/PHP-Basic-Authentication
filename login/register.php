<? session_start();

require_once "../php/authCookieSessionValidate.php";
$member_id = 1;
if (!$isLoggedIn) {
  $thisuser = $auth->getMemberById(1);
} else {
  $util->redirect("../index.php");
}

$subLink="../"

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>REGISTER</title>
  <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css'>
  <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<?
require_once "../php/authCookieSessionValidate.php";
$user = $auth->getMemberById(1);
echo $user[0]["username"];
//echo $_SESSION["member_id"];

if (! empty($_POST["register"])) {
  $email = ($_POST["email"]);
  $name = ($_POST["name"]);
  $fname = ($_POST["fname"]);
  $username = ($_POST["username"]);
  $password = md5($_POST["password"]);
  $ppfoto = "";
  $bgfoto = "";
  $user = $auth->getMemberByUsername($username);
  $userm = $auth->getMemberByEmail($email);
  if ( filter_var($email, FILTER_VALIDATE_EMAIL) ){ //Email filtresi
    if(isset($_POST["sozlesme"])){
      if(count($user)<=0 && count($userm)<=0){

        $hostsql = "INSERT INTO users(id, date, last_activity, memory, ip, visible, username, email, password, name, family_name, pp_photo, bg_photo, contact, theme, rank, balance, puan) VALUES ('','$Tarih','$Tarih','encryptedPassword','$IP_Adresi','1','$username','$email','$password','$name','$fname','$ppfoto','$bgfoto','','light','normal,admin','500','500')";
        $sql = $auth->sqlcode($hostsql);
        if(true){ //TODO
          $util->redirect("login.php?id=".mysqli_insert_id($hostconn)."username=".$username."&msg=Aramıza Hoşgeldin.");
        }

      }else{
      echo '<script>alert("Bu kullanıcı zaten bulunmaktadır.");</script>';
      }
    }else{
      echo '<script>alert("Kayıt olmak için sözleşmeleri onaylamalısınız.");</script>';
    }
  }else{
    echo '<script>alert("Geçersiz E-posta adresi.");</script>';
  }
}

?>
<body>
<!-- partial:index.partial.html -->
<body class="fixed-nav sticky-footer" id="body">
  <!-- Navigation-->
  <div class="content-wrapper">
    <div class="container-fluid">
      <center>
        <div class="col-lg-4">
          <div class="card mb-3 mt-5">
            <div class="card-header d-flex justify-content-between">
              <div>
                <i class="fa fa-user-plus"></i> Kayıt Ol
              </div>
			      </div>
            <div class="card-body p-2">
            <form action="" method="post">
              <div class="field-group mt-2">
                <input name="email" type="email" class="form-control" aria-describedby="E-Mail" placeholder="E-mail" required>
              </div>
              <div class="field-group mt-2">
                <input name="name" type="text" class="form-control" aria-describedby="İsim" placeholder="İsim" required>
              </div>
              <div class="field-group mt-2">
                <input name="fname" type="text" class="form-control" aria-describedby="Soyisim" placeholder="Soyisim" required>
              </div>
              <div class="field-group mt-2">
                <input name="username" type="username" class="form-control" aria-describedby="Kullanıcı Adı" placeholder="Kullanıcı Adı" required>
              </div>
              <div class="field-group mt-2">
                <input name="password" type="password" id="password" class="form-control" aria-describedby="Şifre" placeholder="Şifre" required>
                <i class="fa fa-eye fa-lg" style="position:relative;float:right;top:-28px;left:-15px;z-index:2;" id="togglePassword"></i>
              </div>
              <div class="field-group mt-2 text-left">
                <div class="type-container">
                  <input type="checkbox" id="sozlesme" name="sozlesme" class="job-style" />
                  <label for="sozlesme">Kaydolarak Kullanıcı Sözleşmesi ve Gizlilik Sözleşmesini kabul ediyorum.</label>
                  </div>
              </div>
              <input type="submit" name="register" value="Kayıt Ol"
                        class="btn btn-danger login mt-2 w-100"></span>
              <button class="btn btn-outline-danger login mt-2 w-100" onclick="window.location = 'login.php';">Giriş Yap</button>
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
