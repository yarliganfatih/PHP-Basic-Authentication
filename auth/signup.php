<?
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="shortcut icon" href="../assets/icon.png" type="image/x-icon" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sign up page with PHP">
  <meta name="author" content="Fatih Yarlıgan">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css'>
  <link rel="stylesheet" type="text/css" href="../assets/style.css">
</head>
<?
require_once "../php/authCookieSessionValidate.php";
$user_id = 1;
if ($isLoggedIn) {
  $util->redirect("../index.php");
}

if (!empty($_POST["signup"])) {
  $email = ($_POST["email"]);
  $name = ($_POST["name"]);
  $surname = ($_POST["surname"]);
  $username = ($_POST["username"]);
  $email_spam = ($_POST["email_spam"]);
  $password = md5($_POST["password"]);
  $password2 = md5($_POST["password2"]);
  $user = $auth->getMemberByUsername($username);
  $userm = $auth->getMemberByEmail($email);
  if ($password == $password2) { // passwords match filter
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) { //Email filter
      if (isset($_POST["privacy"])) { // Privacy checkbox filter
        if (count($user) <= 0 && count($userm) <= 0) { // Username and Email are unique
          $hostsql = "INSERT INTO users(ip, username, email, password, name, surname) VALUES ('$IP_Address','$username','$email','$password','$name','$surname')";
          $sql = $auth->sqlcode($hostsql);
          if (true) { //TODO fix this
            header("Location: login.php?id=" . mysqli_insert_id($hostconn) . "&username=" . $username . "&msg=Welcome to our website. Please login to continue");
          }
        } else {
          $message = "Username or email already exists";
        }
      } else {
        $message = "Please agree to our privacy policy";
      }
    } else {
      $message = "Please enter a valid email";
    }
  } else {
    $message = "Passwords do not match";
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
                  <i class="fa fa-user-plus"></i> Sign Up
                </div>
              </div>
              <div class="card-body p-2">
                <form action="" method="post">
                  <div class="error-message">
                    <?php if (isset($message)) {
                      echo $message;
                    } ?>
                  </div>
                  <div class="field-group mt-2">
                    <input name="email" type="email" class="form-control" aria-describedby="E-mail" placeholder="E-mail" required>
                  </div>
                  <div class="field-group mt-2">
                    <input name="name" type="text" class="form-control" aria-describedby="Name" placeholder="Name" required>
                  </div>
                  <div class="field-group mt-2">
                    <input name="surname" type="text" class="form-control" aria-describedby="Surname" placeholder="Surname" required>
                  </div>

                  <div class="field-group mt-2">
                    <input name="username" type="text" class="form-control" aria-describedby="username" placeholder="Username" value="<? echo $_GET["username"]; ?>" required 
                    onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)"> <!-- only allow letters and numbers -->
                  </div>

                  <div class="field-group mt-2">
                    <input name="password" type="password" id="password" class="form-control" aria-describedby="Password" placeholder="Password" required>
                    <i class="fa fa-eye fa-lg" style="position:relative;float:right;top:-28px;left:-15px;z-index:2;" id="togglePassword"></i>
                  </div>
                  <div class="field-group mt-2">
                    <input name="password2" type="password" id="password2" class="form-control" aria-describedby="Retype Password" placeholder="Retype Password" required>
                    <i class="fa fa-eye fa-lg" style="position:relative;float:right;top:-28px;left:-15px;z-index:2;" id="togglePassword2"></i>
                  </div>

                  <div class="form-check type-container">
                    <input class="form-check-input mr-3" type="checkbox" value="1" name="email_spam" id="email_spam" checked onclick="handleCheckboxClick(this);" />
                    <label for="email_spam" style="display: inline-block; vertical-align: top;">
                      I would like to be informed about campaigns, announcements and notifications by e-mail.
                    </label>
                  </div>
                  <div class="form-check type-container">
                    <input class="form-check-input mr-3" type="checkbox" value="0" name="privacy" id="privacy" onclick="handleCheckboxClick(this);" required />
                    <label for="privacy" style="display: inline-block; vertical-align: top;">
                      I have read and accept the <a href="../privacy.pdf" target="_blank">Terms of Service</a>.
                    </label>
                  </div>
                  <input type="submit" name="signup" value="Sign Up" class="btn btn-primary btn-block mt-2">
                </form>
                <button class="btn btn-outline-primary login mt-2 w-100" onclick="window.location = 'login.php';">Log in</button>
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

    function handleCheckboxClick(checkbox) {
      if (checkbox.checked) {
        checkbox.value = "1";
      } else {
        checkbox.value = "0";
      }
    }
  </script>

</html>