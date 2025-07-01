<?php
session_start();
require_once 'Database.php';
require_once 'usermanager.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    header("Location: login.php");
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = intval($_GET['user_id']);

if ($sender_id === $receiver_id) {
    header("Location: dashboard.php");
    exit;
}

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $msg = trim($_POST['message']);
    if (!empty($msg)) {
        $user->sendMessage($sender_id, $receiver_id, $msg);
    }
    header("Location: chat.php?user_id=" . $receiver_id);
    exit;
}

$receiver = $user->getUserById($receiver_id);
$messages = $user->getConversation($sender_id, $receiver_id);
$otherUsers = $user->getOtherUsers($sender_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat with <?= htmlspecialchars($receiver['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
    }

    .chat-wrapper {
      height: 100vh;
      display: flex;
    }

    .left-panel {
      width: 35%;
      background: linear-gradient(135deg, #8e2de2, #4a00e0);
      color: white;
      display: flex;
      flex-direction: column;
      padding: 20px;
      overflow-y: auto;
    }

    .user-list {
      flex-grow: 1;
    }

    .right-panel {
      width: 65%;
      padding: 40px;
      background-color: #ffffff;
      display: flex;
      flex-direction: column;
    }

    .chat-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .chat-header img {
      border-radius: 50%;
      width: 60px;
      height: 60px;
      object-fit: cover;
    }

    .chat-box {
      flex-grow: 1;
      overflow-y: auto;
      padding: 20px;
      background-color: #f8f9fa;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .message {
      max-width: 65%;
      padding: 10px 15px;
      margin-bottom: 15px;
      border-radius: 15px;
      font-size: 0.95rem;
      position: relative;
      line-height: 1.4;
    }

    .me {
      background-color: #d1e7dd;
      margin-left: auto;
      text-align: right;
    }

    .other {
      background-color: #e2e3e5;
      margin-right: auto;
    }

    .timestamp {
      font-size: 0.7rem;
      color: #6c757d;
      margin-top: 5px;
      display: block;
    }

    .date-separator {
      text-align: center;
      margin: 20px 0 10px;
      color: #6c757d;
      font-size: 0.85rem;
    }

    .btn-purple {
      background-color: #6a11cb;
      background-image: linear-gradient(315deg, #6a11cb 0%, #2575fc 74%);
      border: none;
      color: white;
    }

    .btn-purple:hover {
      opacity: 0.95;
    }

    .form-control {
      border-radius: 25px;
    }

    .user-link {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 15px;
      color: white;
      text-decoration: none;
    }

    .user-link:hover {
      text-decoration: underline;
    }

    .user-link img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    .user-name {
      font-weight: bold;
    }

    .user-email {
      font-size: 0.8rem;
      color: #ccc;
    }

    .dashboard-btn {
      margin-top: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="chat-wrapper">

    <!-- Left Panel -->
    <div class="left-panel">
      <div class="user-list">
        <h4>Chats</h4>
        <?php foreach ($otherUsers as $chatUser): ?>
          <a href="chat.php?user_id=<?= $chatUser['id'] ?>" class="user-link">
            <img src="uploads/<?= htmlspecialchars($chatUser['profile_picture']) ?>" alt="Profile">
            <div>
              <div class="user-name"><?= htmlspecialchars($chatUser['name']) ?></div>
              <div class="user-email"><?= htmlspecialchars($chatUser['email']) ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      
      <div class="dashboard-btn">
        <a href="dashboard.php" class="btn btn-light w-100">← Back to Dashboard</a>
      </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
      <div class="chat-header">
        <img src="uploads/<?= htmlspecialchars($receiver['profile_picture']) ?>" alt="Profile">
        <h4 class="mb-0"><?= htmlspecialchars($receiver['name']) ?></h4>
      </div>

      <div class="chat-box">
        <?php
          $previousDate = null;
          foreach ($messages as $msg):
            $currentDate = date("Y-m-d", strtotime($msg['timestamp']));
            if ($currentDate !== $previousDate):
        ?>
          <div class="date-separator">
            <strong><?= date("F j, Y", strtotime($msg['timestamp'])) ?></strong>
          </div>
        <?php
              $previousDate = $currentDate;
            endif;
        ?>
          <div class="message <?= $msg['sender_id'] == $sender_id ? 'me' : 'other' ?>">
            <?= nl2br(htmlspecialchars($msg['message'])) ?>
            <span class="timestamp"><?= date("h:i A", strtotime($msg['timestamp'])) ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <form method="POST" class="d-flex gap-3">
        <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
        <button class="btn btn-purple px-4">Send</button>
      </form>
    </div>
  </div>
</body>
</html>
