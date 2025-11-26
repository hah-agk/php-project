<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in || Sign up form</title>
    <!-- font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css stylesheet -->
    <link rel="stylesheet" href="css/index1.css">
</head>
<body>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="signup_action.php" method="post">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <div class="infield">
                    <input type="text" placeholder="Name" name="name" />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="text" placeholder="Email" name="email"/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password"  name="password"/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="text" placeholder="phone"  name="phone"/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="text" placeholder="address"  name="address"/>
                    <label></label>
                </div>
                <input type="submit" value="signUn" />  
                <input type="radio" id="user" name="user_type" value="user" checked>
                <label for="user">User</label>  
                <input type="radio" id="manager" name="user_type" value="manager">
                <label for="manager">Manager</label>    
                
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="login_action.php" method="post">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <div class="infield">
                    <input type="text" placeholder="Email" name="email"/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" />
                    <label></label>
                </div>
                <a href="#" class="forgot">Forgot your password?</a>
                <input type="submit" value="signIn" />  
            </form>
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
        
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button type="button" class="overlay-signin">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button type="button" class="overlay-signup">Sign Up</button>
                </div>
            </div>
            <input type="submit" id="overlayBtn">
        </div>
    </div>

   
    <script>
        const container = document.getElementById('container');
        const overlayBtn = document.getElementById('overlayBtn');
        const overlaySignin = document.querySelector('.overlay-signin');
        const overlaySignup = document.querySelector('.overlay-signup');
        
        // Function to toggle between panels
        function togglePanel() {
            container.classList.toggle('right-panel-active');
            overlayBtn.classList.remove('btnScaled');
            window.requestAnimationFrame(() => {
                overlayBtn.classList.add('btnScaled');
            });
        }
        
        // Add click events
        overlayBtn.addEventListener('click', togglePanel);
        overlaySignin.addEventListener('click', togglePanel);
        overlaySignup.addEventListener('click', togglePanel);
    </script>
</body>
</html>