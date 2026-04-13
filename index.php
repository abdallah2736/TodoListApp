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

// إضافة مهمة جديدة
if (isset($_POST["addtask"])) {
    $task_ar = Validation::data($_POST["task_ar"]);
    $task_en = Validation::data($_POST["task_en"]);
    $task_ar_desc = Validation::data($_POST["task_ar_desc"]);
    $task_en_desc = Validation::data($_POST["task_en_desc"]);

    if (!empty($task_ar) && !empty($task_en) && !empty($task_ar_desc) && !empty($task_en_desc)) { 

        $stmt = $conn->prepare("INSERT INTO tasks (task_ar, task_en, task_ar_desc, task_en_desc, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $task_ar, $task_en, $task_ar_desc, $task_en_desc, $user_id);
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
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>

    <!-- page icon -->
    <link rel="icon" type="image/x-icon" href="to-do-list.png">

    <!-- style -->
    <link rel="stylesheet" href="style-index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="body-background">
    <!-- container -->
    <div class="container min-vh-100 d-flex flex-column justify-content-center">

        <!-- Language button  -->
        <div class="btn-group position-fixed top-0 end-0 p-4 me-6 p-md-5" role="group" aria-label="Language Switcher">
            <button type="button" onclick="window.location.href='index.php?lang=ar'" class="btn btn-primary">العربية</button>
            <button type="button" onclick="window.location.href='index.php?lang=en'" class="btn btn-primary">English</button>
        </div>
        <!-- end Language button  -->

        <!-- logout button -->
        <div class="position-fixed top-0 end-0 p-4 p-md-5">
            <button onclick="window.location.href='actions/logout_action.php'" class="btn btn-outline-primary"><i class="fa-solid fa-right-from-bracket"></i></button>
        </div>
        <!-- end logout button -->

        <!-- row -->
        <div class=" row mt-5 m-5 d-flex justify-content-center ">
            <!-- col -->
            <div class="col-12 col-md-5 mb-4 ">
                <!--MainCard -->
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header text-center rounded-top-4">
                        <h3 class="p-1 pb-0 pt-2">Todo List</h3>
                    </div>
                    <div class="card-body">
                        <!-- formAddTask  -->
                        <form name="AddTask" action="index.php" method="post">
                            <!-- Enter tasks -->
                            <div class="input-group mb-3">
                                <!-- Enter english -->
                                <span name="task_ar" class="input-group-text">english</span> 
                                <input type="text" name="task_en_desc" name="task_title" class="form-control" placeholder="Task title" required>
                                <input ttype="text" name="task_en" class="form-control" placeholder="Task description" required>
                                <!--  -->
                            </div>
                            <div class="input-group mb-3">
                                <!-- Enter arabic -->
                                <input type="text" name="task_ar_desc" class="form-control" placeholder="وصف المهمة" required>
                                <input type="text" name="task_ar" class="form-control" placeholder="عنوان المهمة" required>
                                <span name="task_ar" class="input-group-text">العربية</span> 
                                <!--  -->
                            </div>
                            <!-- end Enter tasks -->
                            <button type="submit" name="addtask" class="btn btn-primary w-100  rounded-3 mb-2 mt-3">Add Task</button>
                        </form>
                        <!-- end formAddTask  -->

                        <!-- form-task-list  -->
                        <form action="index.php" method="post">

                            <?php while($run = $result->fetch_assoc()): ?>
                            <!-- card: task item -->
                            <div class="card mb-2 mt-2 shadow-sm rounded-3">
                                <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                                    <!-- task name-checkbox -->
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" name="tasks[]" value="<?php echo $run["Task_ID"]; ?>">
                                        <span class="<?php echo $run["status"] == "Complete" ? "text-decoration-line-through" : ""; ?> "><?php echo htmlspecialchars($current_lang == 'ar' ? $run["task_ar"] : $run["task_en"]);?></span>
                                    </div>
                                    <!-- end task name-checkbox -->

                                    <!-- task actions: complete/delete -->
                                    <div class="d-flex gap-2">
                                        <?php if ($run["status"] != "Complete"): ?>
                                        <a href="index.php?Complete=<?php echo $run["Task_ID"]; ?>" class="btn btn-sm border-0 rounded-3" style="background-color: #d1f7e3; color: #28a745;">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </a>
                                        <?php endif; ?>
                                        <a href="index.php?Delete=<?php echo $run["Task_ID"]; ?>" class="btn btn-sm border-0 rounded-3" style="background-color: #f8d7da; color: #dc3545;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                    <!-- end task actions -->
                                </div>
                            </div>
                            <!-- end card: task item -->
                            <?php endwhile; ?>

    
                            <!-- task actions: complete all/delete all -->
                            <?php if ($result->num_rows > 0): ?>
                            <!-- row -->
                            <div class="row g-2 mt-3">
                                <!-- complete all -->
                                <div class="col-6">
                                    <button type="submit" name="bulk_complete" class="btn btn-primary w-100 rounded-3 py-2">
                                            <i class="fa-solid fa-circle-check ms-1"></i> All Selected
                                    </button>
                                </div>
                                <!-- end complete all -->
                                
                                <!-- delete all -->
                                <div class="col-6">
                                    <button type="submit" name="bulk_delete" class="btn btn-primary w-100 rounded-3 py-2">
                                        <i class="fa-solid fa-trash-can ms-1"></i> All Selected
                                    </button>
                                </div>
                                <!-- end delete all -->
                            </div>
                            <!-- end row -->
                            <?php endif; ?>
                            <!-- end task actions: complete all/delete all -->
                        </form>
                        <!-- end form-task-list  -->
                    </div>
                </div>
                <!--end MainCard -->
            </div>
            <!-- end card -->

            <!-- to do: قسم التعليقات -->
            <div class="col-12 col-md-5 mb-4 d-none">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h3>تطبيق المهام 2</h3>
                    </div>
                    <div class="card-body">
                        <form id="taskForm2">
                            <div class="mb-3">
                                <input type="text" id="taskInput2" class="form-control" placeholder="أدخل مهمة جديدة" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">إضافة مهمة</button>
                        </form>
                        <ul id="taskList2" class="list-group mt-4"></ul>
                    </div>
                </div>
            </div>
            <!-- to do: قسم التعليقات -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>