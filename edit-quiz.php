<?php

include "connection.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->quiz_id) || empty($data->title)) {
    echo json_encode(["message" => "Quiz ID and new title are required."]);
    http_response_code(400);
    exit;
}

$quiz_id = (int) $data->quiz_id;
$title   = trim($data->title);

try {
    $sql = "UPDATE quizzes SET title = :title WHERE quiz_id = :quiz_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":quiz_id", $quiz_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Quiz updated successfully!"]);
        http_response_code(200);
    } else {
        echo json_encode(["message" => "Failed to update quiz."]);
        http_response_code(500);
    }
} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
