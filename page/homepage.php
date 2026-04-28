<!-- <? $lang[] = null; ?> -->
<?php require __DIR__ . "/../Config/SESSION.php"; hasActiveSession(); ?>
<?php require __DIR__ . "/../actions/authentication/tasks_action.php"; ?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" <?php echo $current_lang === 'ar' ? 'dir="rtl"' : 'dir="ltr"'; ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['todo_list']; ?></title>

    <!-- page icon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/to-do-list.png">

    <!-- style -->
    <link rel="stylesheet" href="../assets/css/style-index.css">
    <?php if ($current_lang === 'ar'): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <?php elseif ($current_lang === 'en'): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="body-background">
    <!-- container -->
    <div class="container min-vh-100 d-flex flex-column justify-content-center">

        <!-- Language button  -->
        <div class="btn-group position-fixed top-0 start-0 p-4 ms-5 me-5 p-md-5" role="group" aria-label="<?php echo $lang['language_switcher']; ?>">
            <?php if ($current_lang === 'en'): ?>
            <button type="button" onclick="window.location.href='homepage.php?lang=ar'" class="btn btn-primary">العربية</button>
            <?php elseif ($current_lang === 'ar'): ?>
            <button type="button" onclick="window.location.href='homepage.php?lang=en'" class="btn btn-primary">English</button>
            <?php endif; ?>
        </div>
        <!-- end Language button  -->

        <!-- logout button -->
        <div class="position-fixed top-0 start-0 p-4 p-md-5">
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
                        <h3 class="p-1 pb-0 pt-2"><?php echo $lang['todo_list']; ?></h3>
                    </div>
                    <div class="card-body">
                        <!-- formAddTask  -->
                        <form name="AddTask" action="/TodoListApp/actions/authentication/tasks_action.php" method="post">
                            <!-- Enter tasks -->
                                <div class="  mb-3" dir="ltr" >
                                    <label class="form-label">English</label>
                                    <input type="text" name="task_en" class="form-control mb-2" placeholder="Task title" lang="en" dir="ltr" required>
                                    <textarea id="summernote-en" name="task_en_desc"></textarea>
                                </div>
                                <div class="mb-3" dir="rtl">
                                    <label class="form-label" dir="rtl">العربية</label>
                                    <input type="text" name="task_ar" class="form-control mb-2" placeholder="عنوان المهمة" lang="ar" dir="rtl" required>
                                    <textarea id="summernote-ar" name="task_ar_desc" dir="rtl"></textarea>
                                </div>
                            <!-- end Enter tasks -->
                            <button type="submit" name="addtask" class="btn btn-primary w-100  rounded-3 mb-2 mt-3"><?php echo $lang['add_task']; ?></button>
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
                                            <i class="fa-solid fa-circle-check ms-1"></i> <?php echo $lang['Complete_all_selected']; ?>
                                    </button>
                                </div>
                                <!-- end complete all -->
                                
                                <!-- delete all -->
                                <div class="col-6">
                                    <button type="submit" id="bulkBtn" name="bulk_delete" class="btn btn-primary w-100 rounded-3 py-2 d-none" onclick="return confirm('<?php echo $lang['confirm_delete']; ?>');">
                                        <i class="fa-solid fa-trash-can ms-1"></i> <?php echo $lang['delete_all_selected']; ?>
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
                        <h5 class="modal-title" id="TaskTitleModal"><?php echo $lang['task_title']; ?>:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- end taskTitle -->

                    <!-- modal-body/Comments/Description -->
                    <div class="modal-body">
                        <!-- Description -->
                        <h6 class="mb-3"><?php echo $lang['description']; ?>:</h6>
                        <p id="TaskDescriptionModal"></p>
                        <!-- end Description -->
                        

                        <!-- CommentsSection-->
                        <hr class="my-4">
                        <h6 class="mb-3"><?php echo $lang['comments']; ?>:</h6>
                        
                        <!-- CommentEntry/cancelButton -->
                        <div class="d-flex flex-column w-100 mb-4">
                            <!-- CommentEntry -->
                            <div class=" w-100">
                                <textarea class="form-control" id="commentTextarea" name="comment" rows="3" lang="ar" dir="rtl"
                                    placeholder="<?php echo $lang['add_comment']; ?>" required></textarea>
                            </div>
                            <P id="commentTextareaError" class="d-none"><?php echo $lang['comment_textarea_error']; ?></P>
                            <!-- end CommentEntry -->

                            <!-- cancelButton-->
                            <div <?php echo $current_lang == 'ar' ? 'dir="ltr"' : 'dir="rtl"'; ?> class="mt-2 ">
                                <button type="button" id="submitCommentAdd" class="btn btn-primary btn-sm" >
                                    <?php echo $lang['post_comment']; ?>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $lang['close']; ?></button>
                    </div>
                    <!--  -->
                <!-- end modal-content -->
                </div>
            </div>
        </div>
        <!-- end Modal -->
    </div>
    <!-- end container -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="../assets/js/scriptHomepage.js"></script>
    <script>
        $(document).ready(function() {
            var summernoteConfig = {
                height: 120,
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                    ['color', ['forecolor']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']]
                ],
                callbacks: {
                    onInit: function() {
                        $('.note-btn').addClass('btn-sm');
                    }
                }
            };
            $('#summernote-en').summernote($.extend({}, summernoteConfig, {
                placeholder: 'Write task details...',
                lang: 'en-US'
            }));
            $('#summernote-ar').summernote($.extend({}, summernoteConfig, {
                placeholder: 'اكتب تفاصيل المهمة...',
                lang: 'ar-AR'
            }));
        });
    </script>
</body>
</html>