<?php
header('Content-Type: application/json');
require_once "DB.php";

if (isset($_GET["task_id"])) {
    $task_id = $_GET["task_id"];
}

$stmt = $conn->prepare("SELECT comment_ID, Task_ID, comment, `Like` FROM task_comments WHERE Task_ID = ? ORDER BY comment_ID DESC");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
$stmt->close();

echo json_encode($comments);
?>