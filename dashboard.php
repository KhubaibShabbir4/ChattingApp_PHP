<?php
session_start();
require_once 'Database.php';
require_once 'usermanager.php';

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

$loggedInId = $_SESSION['user_id'];
$loggedInUser = $user->getUserById($loggedInId); 
$allUsers = $user->getOtherUsers($loggedInId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
    }

    .dashboard-wrapper {
      height: 100vh;
      display: flex;
    }

    .left-panel {
      width: 40%;
      background: linear-gradient(135deg, #8e2de2, #4a00e0);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      text-align: center;
    }

    .left-panel h1 {
      font-size: 2rem;
    }

    .left-panel p {
      font-size: 1.4rem;
    }

    .right-panel {
      width: 60%;
      padding: 40px;
      background-color: #ffffff;
      overflow-y: auto;
    }

    .user-card {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px 20px;
      border-bottom: 1px solid #eee;
      transition: background-color 0.2s;
      border-radius: 12px;
      margin-bottom: 10px;
    }

    .user-card:hover {
      background-color: #f8f9fa;
    }

    .user-img {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #8e2de2;
    }

    .user-name {
      font-weight: 600;
    }

    .logout-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 14px;
      border-radius: 5px;
      text-decoration: none;
      margin-top: 20px;
    }

    .logout-btn:hover {
      background-color: #c82333;
    }

    .header-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .header-info h3 {
      margin: 0;
    }

    a {
      text-decoration: none;
      color: inherit;
    }
  </style>
</head>
<body>

<div class="dashboard-wrapper">

  <!-- Left Panel -->
  <div class="left-panel">
    <h1>Welcome <?= htmlspecialchars($loggedInUser['name']) ?>!</h1>
    <p>Start a new conversation</p>
    <a href="dashboard.php?logout=true" class="logout-btn">Logout</a>
  </div>

  <!-- Right Panel -->
  <div class="right-panel">
    <div class="header-info">
    <h3>Chats</h3>
    </div>

    <?php if (empty($allUsers)): ?>
      <div class="text-muted text-center">No other users found.</div>
    <?php else: ?>
      <?php foreach ($allUsers as $u): ?>
        <a href="chat.php?user_id=<?= $u['id']; ?>">
          <div class="user-card">
            <img src="uploads/<?= htmlspecialchars($u['profile_picture']) ?>" alt="Profile" class="user-img">
            <div>
              <div class="user-name"><?= htmlspecialchars($u['name']) ?></div>
              <div class="text-muted small"><?= htmlspecialchars($u['email']) ?></div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

</body>
</html>
