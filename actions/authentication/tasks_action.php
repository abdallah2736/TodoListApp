<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../lang.php';

// الاتصال بقاعده البيانات
require_once __DIR__ . '/../../Config/DB.php';

// استدعاء ملف التحقق من البيانات
require_once __DIR__ . '/../../Validation.php';

$user_id = $_SESSION['id'];
$current_lang = $_SESSION['lang'] ?? 'en'; // تحديد اللغة الحالية للعرض

// إضافة مهمة جديدة
if (isset($_POST["addtask"])) {
    $task_ar = Validation::data($_POST["task_ar"]);
    $task_en = Validation::data($_POST["task_en"]);
    $task_ar_desc = Validation::html($_POST["task_ar_desc"]);
    $task_en_desc = Validation::html($_POST["task_en_desc"]);

    if (!empty($task_ar) && !empty($task_en) && !empty($task_ar_desc) && !empty($task_en_desc)) { 
        
        $stmt = $conn->prepare("INSERT INTO tasks (task_ar, task_en, task_ar_desc, task_en_desc, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $task_ar, $task_en, $task_ar_desc, $task_en_desc, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: /TodoListApp/page/homepage.php");
    exit();
}

// عرض المهام
$stmt = $conn->prepare("SELECT * FROM `tasks` WHERE `user_id` = ? ORDER BY `Task_ID` DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result(); 

// حذف مهمة
if (isset($_GET["Delete"])) {
    $id = $_GET["Delete"];
    $stmt = $conn->prepare("DELETE FROM `tasks` WHERE `Task_ID` = ? AND `user_id` = ?");
    $stmt->bind_param("ii", $id, $user_id); 
    $stmt->execute();
    $stmt->close();
    header("Location: /TodoListApp/page/homepage.php");
    exit();
}


if (isset($_GET["Complete"])) {
    $id = $_GET["Complete"];
    $stmt = $conn->prepare("UPDATE `tasks` SET `status` = 'Complete' WHERE `Task_ID` = ? AND `user_id` = ?"); 
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: /TodoListApp/page/homepage.php");
    exit();
}

if (isset($_POST["bulk_complete"])) {
    if (isset($_POST['tasks'])) {
        foreach ($_POST['tasks'] as $value) {
            $stmt = $conn->prepare("UPDATE `tasks` SET `status` = 'Complete' WHERE `Task_ID` = ? AND `user_id` = ?");
            $stmt->bind_param("ii", $value, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: /TodoListApp/page/homepage.php");
}

if (isset($_POST["bulk_delete"])) {
    if (isset($_POST['tasks'])) {
        foreach ($_POST['tasks'] as $value) {
            $stmt = $conn->prepare("DELETE FROM `tasks` WHERE `Task_ID` = ? AND `user_id` = ?");
            $stmt->bind_param("ii", $value, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: /TodoListApp/page/homepage.php");
}
