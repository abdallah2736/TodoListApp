
# 📝 PHP To-Do List Web Application
A simple web application for task lists, created using PHP, HTML, CSS, and MySQL. This application allows users to create an account and log in. They can add, delete, and update tasks through an easy-to-use interface.

## 🌟 Features
- Create your own account 💻
- Add new tasks 🆕
- Mark tasks as completed ✔️
- Delete tasks ❌


## 🛠️ Technologies Used
- **Frontend**: HTML, CSS
- **Backend**: PHP, MySQL

## 📝 Setup Instructions

Follow these steps to set up the project locally:

### 1. Set Up the Database
1. Start your local server using XAMPP
2. Open phpMyAdmin and create a new database named `todo_app`.
3. Execute the following queries create the tables:
   ```sql
   CREATE DATABASE todo_app;
   USE todo_app;
   
   CREATE TABLE `users` (
       `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
       `name` varchar(255) NOT NULL,
       `email` varchar(255) NOT NULL,
       `password` varchar(255) NOT NULL,
       PRIMARY KEY (`id`),
       UNIQUE KEY `email_unique` (`email`)
   )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

   CREATE TABLE `tasks` (
       `Task_ID` int(11) NOT NULL AUTO_INCREMENT,
       `user_id` int(10) UNSIGNED NOT NULL,
       `task` varchar(255) NOT NULL,
       `status` varchar(128) NOT NULL DEFAULT 'pending',
       PRIMARY KEY (`Task_ID`),
       FOREIGN KEY (`user_id`) REFERENCES users(`id`) ON DELETE CASCADE
   )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
   ```

### 2. Configure the Database Connection
Edit `DB.php` with your database connection details:
```php
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "todo_app";


$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");
?>
```

### 3. Run the Application
Open a web browser and navigate to `http://localhost/TodoListApp/index.php`.
