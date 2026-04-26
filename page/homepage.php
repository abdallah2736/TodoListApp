<?php require __DIR__ . "/../Config/SESSION.php"; hasActiveSession(); ?>
<?php require __DIR__ . "/../actions/authentication/tasks_action.php"; ?>
<!DOCTYPE html>
<html lang="ar" <?php echo $current_lang === 'ar' ? 'dir="rtl"' : 'dir="ltr"'; ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>

    <!-- page icon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/to-do-list.png">

    <!-- style -->
    <link rel="stylesheet" href="../assets/css/style-index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="body-background">
    <!-- container -->
    <div class="container min-vh-100 d-flex flex-column justify-content-center">

        <!-- Language button  -->
        <div class="btn-group position-fixed top-0 end-0 p-4 me-6 p-md-5" role="group" aria-label="Language Switcher">
            <button  dir="rtl" type="button" onclick="window.location.href='homepage.php?lang=ar'" class="btn btn-primary">العربية</button>
            <button  dir="ltr" type="button" onclick="window.location.href='homepage.php?lang=en'" class="btn btn-primary">English</button>
        </div>
        <!-- end Language button  -->

        <!-- logout button -->
        <div class="position-fixed top-0 end-0 p-4 p-md-5">
            <a href="/TodoListApp/actions/authentication/logout_action.php" class="btn btn-outline-primary"><i class="fa-solid fa-right-from-bracket"></i></a>
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
                        <form name="AddTask" action="/TodoListApp/actions/authentication/tasks_action.php" method="post">
                            <!-- Enter tasks -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">English</span> 
                                    <input type="text" name="task_en" class="form-control" placeholder="Task title" lang="en" dir="ltr" required>
                                    <input type="text" name="task_en_desc" class="form-control" placeholder="Task description" lang="en" dir="ltr" required>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" name="task_ar_desc" class="form-control" placeholder="وصف المهمة" lang="ar" dir="rtl" required>
                                    <input type="text" name="task_ar" class="form-control" placeholder="عنوان المهمة" lang="ar" dir="rtl" required>
                                    <span  class="input-group-text" dir="rtl" >العربية</span> 
                                </div>
                            <!-- end Enter tasks -->
                            <button type="submit" name="addtask" class="btn btn-primary w-100  rounded-3 mb-2 mt-3">Add Task</button>
                        </form>
                        <!-- end formAddTask  -->

                        <!-- form-task-list  -->
                        <form action="/TodoListApp/actions/authentication/tasks_action.php" method="post">

                            <!-- card: task item -->
                            <?php while($run = $result->fetch_assoc()): ?>
                            <div class="card mb-2 mt-2 shadow-sm rounded-3">
                                <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                                    <!-- task name-checkbox -->
                                    <div class="d-flex align-items-center">
                                        <!-- //!checkbox -->
                                        <input class="form-check-input me-3" type="checkbox" name="tasks[]" value="<?php echo $run["Task_ID"]; ?>">
                                        <!-- end checkbox -->

                                        <!-- name -->
                                        <!-- php to javascript -->
                                        <span 
                                            id="task-<?php echo $run["Task_ID"]; ?>"
                                            class="card-TaskItem-TaskTitle <?php echo $run["status"] == "Complete" ? "text-decoration-line-through" : ""; ?>"
                                            style="cursor: pointer;" 
                                            data-task-id="<?php echo $run["Task_ID"]; ?>"
                                            data-task-ar-desc="<?php echo htmlspecialchars($run["task_ar_desc"]); ?>"
                                            data-task-en-desc="<?php echo htmlspecialchars($run["task_en_desc"]); ?>"
                                            data-current-lang="<?php echo $current_lang; ?>"
                                        >
                                            <?php echo htmlspecialchars($current_lang == 'ar' ? $run["task_ar"] : $run["task_en"]);?>
                                        </span>
                                        <!-- end php to javascript -->
                                        <!-- end name -->
                                    </div>
                                    <!-- end task name-checkbox -->

                                    <!-- task actions: complete/delete -->
                                    <div class="d-flex gap-2">
                                        <!-- complete -->
                                        <?php if ($run["status"] != "Complete"): ?>
                                        <a href="/TodoListApp/actions/authentication/tasks_action.php?Complete=<?php echo $run["Task_ID"]; ?>" class="btn btn-sm border-0 rounded-3" style="background-color: #d1f7e3; color: #28a745;">
                                            <i class="fa-solid fa-circle-check"></i> 
                                        </a>
                                        <!-- delete -->
                                        <?php endif; ?>
                                        <a href="/TodoListApp/actions/authentication/tasks_action.php?Delete=<?php echo $run["Task_ID"]; ?>" class="btn btn-sm border-0 rounded-3" style="background-color: #f8d7da; color: #dc3545;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                    <!-- end task actions -->
                                </div>
                            </div>
                            <?php endwhile; ?>
                            <!-- end card: task item -->

    
                            <!-- task actions: complete all/delete all -->
                            <?php if ($result->num_rows > 0): ?>
                            <!-- //!row -->
                            <div class="row g-2 mt-3 none">
                                <!-- complete all -->
                                <div class="col-6">
                                    <button type="submit" id="bulkBtn" name="bulk_complete" class="btn btn-primary w-100 rounded-3 py-2 d-none">
                                            <i class="fa-solid fa-circle-check ms-1"></i> Complete All
                                    </button>
                                </div>
                                <!-- end complete all -->
                                
                                <!-- delete all -->
                                <div class="col-6">
                                    <button type="submit" id="bulkBtn" name="bulk_delete" class="btn btn-primary w-100 rounded-3 py-2 d-none" onclick="return confirm('<?php echo $current_lang == 'ar' ? 'هل أنت متأكد من حذف المهام المحددة؟' : 'Are you sure you want to delete the selected tasks?'; ?>');">
                                        <i class="fa-solid fa-trash-can ms-1"></i> Delete All
                                    </button>
                                </div>
                                <!-- end delete all -->
                            </div>
                            <!-- //!end row -->
                            <?php endif; ?>
                            <!-- end task actions: complete all/delete all -->
                        </form>
                        <!-- end form-task-list  -->
                    </div>
                </div>
                <!--end MainCard -->
            </div>
            <!-- end card -->
        </div>
        <!-- end row -->

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <!-- modal-content -->
                <div class="modal-content">
                    <!-- taskTitle -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="TaskTitleModal"><?php echo $current_lang == 'ar' ? 'عنوان المهمة' : 'Task Title'; ?>:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- end taskTitle -->

                    <!-- modal-body/Comments/Description -->
                    <div class="modal-body">
                        <!-- Description -->
                        <h6 class="mb-3"><?php echo $current_lang == 'ar' ? 'الوصف' : 'Description'; ?>:</h6>
                        <p id="TaskDescriptionModal"></p>
                        <!-- end Description -->
                        

                        <!-- CommentsSection-->
                        <hr class="my-4">
                        <h6 class="mb-3"><?php echo $current_lang == 'ar' ? 'التعليقات' : 'Comments'; ?>:</h6>
                        
                        <!-- CommentEntry/cancelButton -->
                        <div class="d-flex flex-column w-100 mb-4">
                            <!-- CommentEntry -->
                            <div class=" w-100">
                                <textarea class="form-control" id="commentTextarea" name="comment" rows="3" lang="ar" dir="rtl"
                                    placeholder="<?php echo $current_lang == 'ar' ? 'أضف تعليق...' : 'Add a comment...'; ?>"></textarea>
                            </div>
                            <!-- end CommentEntry -->

                            <!-- cancelButton-->
                            <div class="mt-2">
                                <button type="button" id="submitCommentAdd" class="btn btn-primary btn-sm">
                                    <?php echo $current_lang == 'ar' ? 'نشر التعليق' : 'Post Comment'; ?>
                                </button>
                            </div>
                            <!-- end cancelButton -->
                        </div>

                        <!-- List of comments-->
                        <div id="commentsList">
                            <!-- Comments upload area-->
                        </div>
                        <!-- end listComments -->
                        <!-- end CommentsSection -->
                    </div>
                    <!-- end modal-body/Comments/Description -->
                    <!--  -->

                    <!-- modal-footer/closeButton -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $current_lang == 'ar' ? 'إغلاق' : 'Close'; ?></button>
                    </div>
                    <!--  -->
                <!-- end modal-content -->
                </div>
            </div>
        </div>
        <!-- end Modal -->
    </div>
    <!-- end container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scriptHomepage.js"></script>
</body>
</html>