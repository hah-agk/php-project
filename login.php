<?php
session_start();
      if (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] == true 
      &&  isset($_SESSION['UorM']) && $_SESSION['UorM'] == "manager") {
          header("Location: manager.php");
          session_destroy();
          exit();
      }elseif (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] == true 
      &&  isset($_SESSION['UorM']) && $_SESSION['UorM']=="users") {
          header("Location: user.php");
          exit();
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/index1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="form-box login">
            <form action="login_action.php" method="post">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text"  value= "<?php echo $_SESSION['email'] ?? '';?>"  name="email"  placeholder="email" required> 
                    <i class="bx bxs-user" id="aa" ></i>
                </div>
                <div class="input-box">
                    <input type="password" placeholder="password" name="password" required>
                    <i class="bx bxs-lock-alt" id="aa"></i>
                </div>
                <div class="forget-link">
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn" name="button">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="https://www.google.com/"><i class="bx bxl-google" ></i></a>
                    <a href="https://www.facebook.com/"><i class="bx bxl-facebook" ></i></a>
                    <a href="https://github.com/"><i class="bx bxl-github" ></i></a>
                    <a href="https://www.linkedin.com/"><i class="bx bxl-linkedin" ></i></a>
                </div>
            </form>
        <p style="color: red;">
            <?php
                if (isset($_GET['err'])) {
                    switch ($_GET['err']) {
                        case 1:
                            echo "Missing Parameters";
                            break;
                        case 2:
                            echo "Wrong email or password";
                            break;
                        case 3:
                            echo " Failed to login , Contact admin";
                    }
                }
                $_SESSION['email']="";
                ?>
        </div>
    </div>

</body>
</html>