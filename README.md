
# 📝 PHP To-Do List Web Application

A web application for managing tasks with multilingual support. Users can create accounts, add and manage tasks with descriptions in English or Arabic, and interact with comments through a dynamic interface.

## ✨ Features

- **User Authentication**: Create accounts and secure login
- **Task Management**: Add, delete, and update tasks easily
- **Task Descriptions**: Support for English and Arabic descriptions
- **Comments**: Add comments to tasks with real-time loading
- **Like System**: Like and unlike comments without page refresh
- **Multilingual Support**: Full support for English and Arabic
- **Responsive Design**: Works on desktop and mobile devices
- **AJAX Functionality**: Smooth interactions without page reloads
- **Data Validation**: Input validation and security measures
- **Error Handling**: Display all validation errors together at once

## 🛠️ Technologies Used
- 🎨 **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (AJAX)
- ⚙️ **Backend**: PHP 8.2.12
- 🗄️ **Database**: MySQL (MariaDB 10.4.32)
- 🖥️ **Server**: Apache (XAMPP)

## 📊 Database Schema

### Users Table
```sql
CREATE TABLE `users` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Tasks Table
```sql
CREATE TABLE `tasks` (
    `Task_ID` int(11) NOT NULL AUTO_INCREMENT,
    `task_en_desc` text DEFAULT NULL,
    `task_ar_desc` text DEFAULT NULL,
    `user_id` int(10) UNSIGNED NOT NULL,
    `task_en` varchar(255) NOT NULL,
    `task_ar` varchar(255) NOT NULL,
    `status` varchar(128) NOT NULL DEFAULT 'pending',
    PRIMARY KEY (`Task_ID`),
    FOREIGN KEY (`user_id`) REFERENCES users(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Task Comments Table
```sql
CREATE TABLE `task_comments` (
    `comment_ID` int(11) NOT NULL AUTO_INCREMENT,
    `Task_ID` int(11) NOT NULL,
    `comment` mediumtext NOT NULL,
    `Like` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`comment_ID`),
    FOREIGN KEY (`Task_ID`) REFERENCES tasks(`Task_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## 🚀 Setup Instructions

### 1️⃣ Database Setup
1. Start XAMPP and open phpMyAdmin
2. Create a new database named `todo_app`
3. Import the SQL dump or run the table creation queries above
4. Verify all three tables are created: `users`, `tasks`, and `task_comments`

### 2️⃣ Database Configuration
Edit `DB.php` with your database details:
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

### 3️⃣ Run the Application
Open your browser and go to `http://localhost/TodoListApp/index.php`
