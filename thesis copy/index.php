<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">  

</head>
<body>
    <!--    ------Login------  -->
    <div class="container" id="login">
        <h1 class="form-title">Login</h1>
        <form action="register.php" method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <p class="recover"><a href="#">Forgot Password?</a></p>
            <input type="submit" value="Login" class="btn" name="login">
        </form>
        <p class="or">OR</p>
        <div class="icons">
            <a href="#" class="icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fab fa-google"></i></a>
            <a href="#" class="icon"><i class="fab fa-twitter"></i></a>
        </div>
        <div class="signup-link">
            <p>Don't have an account? <button id="signupBtn">Sign up</button></p>
        </div>
    </div>



    <!-- --------Register----------- -->
    <div class="container" id="signup" style="display: none;">
        <h1 class="form-title">Sign Up</h1>
        <form action="register.php" method="post">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="Fname" placeholder="First Name" required>
                <label for="Fname">First Name</label>
            </div>    
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="Lname" placeholder="Last Name" required>
                <label for="Lname">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" value="Signup" class="btn" name="signUp">
        </form>
        <p class="or">OR</p>
        <div class="icons">
            <a href="#" class="icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fab fa-google"></i></a>
            <a href="#" class="icon"><i class="fab fa-twitter"></i></a>
        </div>
        <div class="login-link">
            <p>Already have an account? <button id="loginBtn">Login here</button></p>
        </div>
    </div>



</body>
</html>