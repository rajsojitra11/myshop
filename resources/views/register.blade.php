<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <div class="login-container">
      <!-- Side Image -->
      <div class="side-image">
        <img src="img/raj.png" alt="No Image" />
      </div>
  
      <!-- Login Form -->
      <div class="login-form">
        @if ($errors->any())
    <div id="error-message" style="background: #dc3545; color: #fff; padding: 12px; border-radius: 5px; margin-bottom: 10px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        // Auto hide after 4 seconds
        setTimeout(function () {
            let box = document.getElementById('error-message');
            if (box) {
                box.style.transition = "opacity 0.5s ease";
                box.style.opacity = "0";
                setTimeout(() => box.remove(), 500); // remove after fade out
            }
        }, 4000);
    </script>
@endif

        <h2>Welcome to Myshop</h2>
  
        <form action="{{ route('registers') }}" method="POST">
          <!-- CSRF Token for Laravel -->
          @csrf
  
          <!-- First Name -->
          <div class="input-group">
            <label for="firstname">Enter Name</label>
            <input type="text" id="firstname" name="firstname" required />
          </div>
  
          <!-- Email -->
          <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />
          </div>
  
          <!-- Password -->
          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
          </div>
  
          <!-- Confirm Password -->
          <div class="input-group">
            <label for="confirmpassword">Confirm Password</label>
            <input type="password" name="password_confirmation" required>
          </div>
  
          <!-- Agree to Terms Checkbox -->
          <div class="remember-forgot">
            <label for="agree">
              <input type="checkbox" id="agree" name="agree" required />
              I agree to the terms and conditions
            </label>
          </div>
  
          <!-- Submit Button -->
          <button type="submit" class="btn">Sign up</button>
        </form>
  
        <!-- Login Link -->
        <div class="signup-link">
          Already have an account? <a href="{{ route('index') }}">Sign in</a>
        </div>
      </div>
    </div>
  </body>
  
</html>
