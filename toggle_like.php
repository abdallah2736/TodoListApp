<?php
header('Content-Type: application/json');
require_once "DB.php";

if (isset($_GET["comment_id"]) && isset($_GET["status"])) {
    $comment_id = $_GET["comment_id"];
    $status = $_GET["status"];

    // Validate status value (only 0 or 1 allowed)
    if ($status !== "0" && $status !== "1") {
        echo json_encode(["success" => false, "message" => "Invalid status value"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE task_comments SET `Like` = ? WHERE comment_ID = ?");
    $stmt->bind_param("ii", $status, $comment_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Missing parameters"]);
}

$conn->close();
?>