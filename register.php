<?php
require_once 'Database.php';
require_once 'usermanager.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User($conn);

    $result = $user->registerUser(
        $_POST['name'],
        $_POST['email'],
        $_POST['phone_number'],
        $_POST['password'],
        $_POST['confirm_password'],
        null,
        $_FILES['profile_picture']['tmp_name'],
        $_FILES['profile_picture']['name']
    );

    if ($result["success"]) {
        echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('" . $result["message"] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .container-fluid {
      height: 100vh;
    }

    .left-panel {
      background: linear-gradient(135deg, #8e2de2, #4a00e0);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .left-panel h1 {
      font-size: 2rem;
    }

    .left-panel p {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .register-box {
      background-color: #ffffff;
      border-radius: 8px;
      padding: 40px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .btn-purple {
      background-color: #6a11cb;
      background-image: linear-gradient(315deg, #6a11cb 0%, #2575fc 74%);
      border: none;
    }

    .btn-purple:hover {
      opacity: 0.9;
    }

    .form-link {
      text-decoration: none;
      font-size: 0.9rem;
    }

    .form-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row h-100">
      
      <!-- Left panel -->
      <div class="col-md-6 left-panel">
        <h1>Welcome!</h1>
        <p>Let's <strong>Get You Registered</strong></p>
      </div>

      <!-- Right panel -->
      <div class="col-md-6 d-flex justify-content-center align-items-center">
        <div class="register-box w-75">
          <h3 class="mb-4 text-center">Create Account</h3>
          <form method="POST" action="register.php" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
              <label for="profile_picture" class="form-label">Profile Picture</label>
              <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="text" class="form-control" id="phone" name="phone_number">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-purple btn-lg">Register</button>
            </div>

            <div class="text-center">
              <span>Already have an account? <a href="login.php" class="form-link">Login here</a></span>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
