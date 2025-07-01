<?php

class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    
    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    // to get all user except the user that is logged in by escaping the user id of the logged in user
    public function getOtherUsers($loggedInUserId) {
        $stmt = $this->conn->prepare("SELECT id, name, email, profile_picture FROM users WHERE id != ?");
        $stmt->bind_param("i", $loggedInUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    // Registration function
    public function registerUser($name, $email, $phone, $password, $confirmPassword, $dob, $profile_img_tmp, $profile_img_name) {
        if ($password !== $confirmPassword) {
            return ["success" => false, "message" => "Passwords do not match."];
        }

        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?"); // check if email already exists
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return ["success" => false, "message" => "Email already registered."];
        }

        $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                 mkdir($upload_dir, recursive: true); 
    }   


        $target = $upload_dir . basename($profile_img_name);
        if (!move_uploaded_file($profile_img_tmp, $target)) {
            return ["success" => false, "message" => "Image upload failed."];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, profile_picture, phone_number, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $profile_img_name, $phone, $hashedPassword);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "User registered successfully."];
        } else {
            return ["success" => false, "message" => $stmt->error];
        }
    }

    
    public function loginUser($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            return ["success" => false, "message" => "Email not registered."];
        }

        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            return ["success" => true, "user_id" => $id];
        } else {
            return ["success" => false, "message" => "Invalid password."];
        }
    }


public function sendMessage($fromUserId, $toUserId, $message) {
    if ($fromUserId === $toUserId) { // to prevent self chat
        return false; 
    }

    $stmt = $this->conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $fromUserId, $toUserId, $message);
    return $stmt->execute();
}

public function getConversation($user1Id, $user2Id) {
    $stmt = $this->conn->prepare("
        SELECT m.*, u.name AS sender_name, u.profile_picture AS sender_pic
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
        ORDER BY timestamp ASC
    ");
    $stmt->bind_param("iiii", $user1Id, $user2Id, $user2Id, $user1Id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    return $messages;
}


 public function getUserById($id) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
}

?>
