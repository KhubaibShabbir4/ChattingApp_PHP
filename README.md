**ChatBot Web Application**
This is a simple PHP-based chat web application that allows users to register, log in, and chat with other registered users. The app features user authentication, profile picture uploads, and a modern Bootstrap UI.

**Features**
1.User registration with profile picture upload
2.Secure login/logout system
3.Dashboard listing all other users for chat
4.One-to-one chat functionality with message history
5.Responsive UI using Bootstrap 5

**Folder Structure**
**Setup Instructions**
1.Clone or copy the project files to your web server directory (e.g., chatbot).
2.Create a MySQL database named mydb.

3.**Create the required tables:**
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  profile_picture VARCHAR(255),
  phone_number VARCHAR(20),
  password VARCHAR(255) NOT NULL
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  receiver_id INT NOT NULL,
  message TEXT NOT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id),
  FOREIGN KEY (receiver_id) REFERENCES users(id)
);

4.Ensure the uploads directory exists and is writable by the web server.
5.Update database credentials in Database.php if needed.
6.Start your web server and access register.php to create a new account.**

**Usage**
1.Register a new user via register.php.
2.Log in via Login.php.
3.After login, you'll be redirected to the dashboard where you can start chatting with other users.

**Screenshots**
Dashboard, chat, and registration UI screenshots are included in the project folder.

**License**
This project is for educational purposes.

Main files:

dashboard.php
chat.php
register.php
Login.php
usermanager.php
Database.php
uploads
