<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GrainFarm Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

  <!-- Updated dependencies -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">

  <style>
    /* 🌾 Enhanced Background */
    body {
      background: linear-gradient(rgba(50, 30, 10, 0.7), rgba(70, 40, 15, 0.7)),
                  url('https://images.unsplash.com/photo-1500076656116-558758c991c1?q=80&w=2070&auto=format&fit=crop') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Roboto', sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      overflow: hidden;
    }

    /* 🌿 Refined Glassy Login Card */
    .login-box {
      width: 100%;
      max-width: 450px;
      background: rgba(255, 250, 240, 0.2);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.4);
      border-radius: 25px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
      padding: 50px 40px;
      color: #fff;
      animation: fadeInUp 1s ease-out;
      transition: transform 0.3s ease;
    }

    .login-box:hover {
      transform: translateY(-5px);
    }

    /* 🌾 Enhanced Logo */
    .login-logo {
      text-align: center;
      margin-bottom: 25px;
    }

    .login-logo a {
      color: #ffeb99;
      font-size: 2.8rem;
      font-weight: 700;
      letter-spacing: 2.5px;
      text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.6);
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .login-logo a:hover {
      color: #fff;
      text-shadow: 0 0 25px #f8d57e, 0 0 35px #d3a34d;
    }

    /* ✨ Improved Message */
    .login-box-msg {
      color: #ffeb99;
      font-size: 1.2rem;
      font-weight: 400;
      text-align: center;
      margin-bottom: 30px;
      letter-spacing: 0.8px;
    }

    /* 📝 Form Inputs */
    .form-control {
      border-radius: 12px;
      border: 1px solid #e3b867;
      background: rgba(255, 255, 255, 0.95);
      color: #3a2208;
      padding: 14px 20px;
      font-size: 1.05rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #d4a25a;
      box-shadow: 0 0 12px rgba(212, 162, 90, 0.6);
      background: #fff;
    }

    .form-control-feedback {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #b98c47;
      font-size: 1.2rem;
    }

    /* 🌟 Enhanced Button */
    .btn-primary {
      background: linear-gradient(135deg, #b98c47, #e3b867);
      border: none;
      border-radius: 12px;
      padding: 14px;
      width: 100%;
      font-weight: 600;
      font-size: 1.1rem;
      color: #3a2208;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #d4a25a, #ffcb74);
      box-shadow: 0 6px 20px rgba(212, 162, 90, 0.6);
      transform: translateY(-4px);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    /* ⚠️ Improved Error Text */
    .error-message {
      color: #ff9999;
      font-size: 1rem;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 500;
      background: rgba(255, 75, 75, 0.2);
      padding: 10px;
      border-radius: 8px;
    }

    /* 📜 Refined Remember Me */
    .icheck-primary label {
      color: #fff8e1;
      font-size: 1rem;
      cursor: pointer;
    }

    .icheck-primary input:checked + label::before {
      background-color: #d4a25a;
      border-color: #d4a25a;
    }

    /* 🌾 Footer */
    .footer-note {
      text-align: center;
      color: #ffeb99;
      margin-top: 30px;
      font-size: 0.95rem;
      text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
    }

    /* 🎨 Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* 📱 Responsive Design */
    @media (max-width: 576px) {
      .login-box {
        padding: 30px 25px;
        max-width: 90%;
      }

      .login-logo a {
        font-size: 2.3rem;
      }

      .login-box-msg {
        font-size: 1.1rem;
      }

      .form-control {
        padding: 12px 15px;
        font-size: 0.95rem;
      }

      .btn-primary {
        padding: 12px;
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>
  <div class="login-box">
    <div class="login-logo">
      <a href="<?php echo base_url('auth'); ?>"><b>Emcell Ricemill</b></a>
    </div>

    <p class="login-box-msg">Sign in to access</p>

    <?php if (!empty(validation_errors()) || !empty($errors)): ?>
      <div class="error-message">
        <?php echo validation_errors(); ?>
        <?php if (!empty($errors)) { echo $errors; } ?>
      </div>
    <?php endif; ?>

    <form action="<?php echo base_url('auth/login') ?>" method="post">
      <div class="form-group position-relative mb-4">
        <input type="email" class="form-control" name="email" placeholder="Enter your email" autocomplete="off" required>
        <span class="fas fa-envelope form-control-feedback"></span>
      </div>

      <div class="form-group position-relative mb-4">
        <input type="password" class="form-control" name="password" placeholder="Enter your password" autocomplete="off" required>
        <span class="fas fa-lock form-control-feedback"></span>
      </div>

      <div class="row align-items-center mb-3">
        <div class="col-7">
          <div class="icheck-primary">
            <input type="checkbox" id="remember">
            <label for="remember">Remember Me</label>
          </div>
        </div>
        <div class="col-5">
          <button type="submit" class="btn btn-primary">Sign In</button>
        </div>
      </div>
    </form>

    <p class="footer-note">© 2025 Smart System. All rights reserved.</p>
  </div>

  <!-- Updated scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>