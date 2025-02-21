<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    
    <div class="login-container">
      <div class="side-image">
        <img src="img/raj.png" alt="No Image" />
      </div>
      <div class="login-form">
        <h2>Welcome to Myshop</h2>
        <p></p>

        <div class="input-group">
          <label for="username">Username or email</label>
          <input type="text" id="username" name="username" required />
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>

        <div class="remember-forgot">
          <label for="remember">
            <input type="checkbox" id="remember" name="remember" /> Remember Me
          </label>
          <a href="#">Forgot Password?</a>
        </div>

        <button type="submit" class="btn">Sign in</button>

        <div class="signup-link">
          Don't have an account? <a href="{{Route('register')}}">Sign up</a>
        </div>
      </div>
    </div>
  </body>
</html>
