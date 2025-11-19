<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../css/index1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="form-box login">
            <form action="php.phppage" method="post">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" placeholder="username" name="username" required>
                    <i class="bx bxs-user" id="aa" ></i>
                </div>
                <div class="input-box">
                    <input type="password" placeholder="password"  name="password" required name="">
                    <i class="bx bxs-lock-alt" id="aa"></i>
                </div>
                <div class="forget-link">
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn" name="button">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class="bx bxl-google" ></i></a>
                    <a href="#"><i class="bx bxl-facebook" ></i></a>
                    <a href="#"><i class="bx bxl-github" ></i></a>
                    <a href="#"><i class="bx bxl-linkedin" ></i></a>
                </div>
            </form>

        </div>
    </div>
</body>
</html>
