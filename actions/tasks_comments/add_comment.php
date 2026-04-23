<?php
header('Content-Type: application/json');
require_once "../../Config/DB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receive JSON   
    if (isset($_POST['task_id'])) {
        $task_id = $_POST['task_id'];
    } 
    if (isset($_POST['comment'])) {
        $comment = trim($_POST['comment']);
    }
    // Make sure the comment is not empty
    $stmt = $conn->prepare("INSERT INTO task_comments (Task_ID, comment) VALUES (?, ?)");
    $stmt->bind_param("is", $task_id, $comment);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Comment added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding comment']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 