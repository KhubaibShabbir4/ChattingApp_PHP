<?php
session_start();
require_once 'Database.php';
require_once 'usermanager.php';


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$db = new Database();
$conn = $db->connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($conn);
    $result = $user->loginUser($email, $password);

    if ($result["success"]) {
        $_SESSION['user_id'] = $result["user_id"];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $result["name"]; 

        echo "<script>alert('Login successful!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('" . $result["message"] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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

    .login-box {
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
        <h1>Hello!</h1>
        <p>Have a <strong>GOOD DAY</strong></p>
      </div>

      <!-- Right panel -->
      <div class="col-md-6 d-flex justify-content-center align-items-center">
        <div class="login-box w-75">
          <h3 class="mb-4 text-center">Login</h3>
          <form method="POST" action="login.php">
            <div class="mb-3">
              <label for="username" class="form-label">Email</label>
              <input type="email" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-purple btn-lg">Login</button>
            </div>
            <div class="text-center">
              <span>Don't have an account? <a href="register.php" class="form-link">Create an account</a></span>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
