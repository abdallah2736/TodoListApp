<!-- التاكد من انشاء حساب -->
<?php
session_start();
require 'lang.php';

// انقل السمتخدم لصفحه تسجيل الدخول اذا لم يسجل الدخول في الجلسه
if (!isset($_SESSION['email'], $_SESSION['name'],$_SESSION['id'])) {
    header("Location: Login.php");
    exit();
}

// الاتصال بقاعده البيانات
require_once 'DB.php';
require_once 'Validation.php';

$user_id = $_SESSION['id'];
$current_lang = $_SESSION['lang'] ?? 'en'; // تحديد اللغة الحالية للعرض

if (isset($_POST["addtask"])) {
    $task_ar = Validation::data($_POST["task_ar"]);
    $task_en = Validation::data($_POST["task_en"]);

    if (!empty($task_ar) && !empty($task_en)) { 

        $stmt = $conn->prepare("INSERT INTO tasks (task_ar, task_en, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $task_ar, $task_en, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php");
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
    header("Location: index.php");
    exit();
}


if (isset($_GET["Complete"])) {
    $id = $_GET["Complete"];
    $stmt = $conn->prepare("UPDATE `tasks` SET `status` = 'Complete' WHERE `Task_ID` = ? AND `user_id` = ?"); 
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
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
    header("Location: index.php");
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
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="icon" type="image/x-icon" href="to-do-list.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="header-actions">
                <div class="lang-links">
                    <a href="index.php?lang=en" class="lang-item">English</a>
                    <span class="separator">|</span>
                    <a href="index.php?lang=ar" class="lang-item">العربية</a>
                </div>
            <a href="actions/logout_action.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>

        <div class="todo-card">
            <h2><?php echo $lang['todo_list']; ?></h2> 
            <form action="index.php" method="post" class="todo-form">
                <input type="text" name="task_en" placeholder="Task in English" id="task-input" required>
                <input type="text" name="task_ar" placeholder="المهمة بالعربي" id="task-input" required>
                <button type="submit" name="addtask" id="add-btn"><i class="fa-notdog fa-solid fa-plus"></i></button>
            </form>
            <form action="index.php" method="post">
                <ul class="task-list">
                    <?php while($run = $result->fetch_assoc()): ?> 
                        <li class="task-item <?php echo $run["status"]; ?>">
                            <input type="checkbox" name="tasks[]" value="<?php echo $run["Task_ID"]; ?>">
                            <span class="task-text"><?php echo htmlspecialchars($current_lang == 'ar' ? $run["task_ar"] : $run["task_en"]);?></span>
                            
                            <div class="actions">
                                <?php if ($run["status"] != "Complete"): ?>
                                    <a href="index.php?Complete=<?php echo $run["Task_ID"]; ?>" class="btn-complete"><i class="fa-solid fa-check-circle"></i></a>
                                <?php endif; ?>
                                <a href="index.php?Delete=<?php echo $run["Task_ID"]; ?>" class="btn-delete"><i class="fa-solid fa-trash-alt"></i></a>
                            </div>
                        </li>
                    <?php endwhile ?>
                </ul>
                
                <div class="btn-bulk">
                    
                    <button type="submit" name="bulk_complete" ><?php echo $lang['Complete_all_selected']; ?></button>
                    <button type="submit" name="bulk_delete" ><?php echo $lang['delete_all_selected']; ?></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
