<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="signup_action.php" method="post">
        <h1>Sign Up</h1>
        <div>
            <input type="text" name="name" placeholder="Name" required>
        </div>
        <div>
            <input type="text" name="phone" placeholder="Phone" required>
        </div>
        <div>
            <input type="text" name="address" placeholder="Address" required>       
        </div>
          <div>
            <input type="text" name="email" placeholder="Email" required>
        </div>
        <div>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" name="button">Sign Up</button>
</body>
</html>
