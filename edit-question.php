<?php

include "connection.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

$input = json_decode(file_get_contents("php://input"));

if (empty($input->question_id) || empty($input->question_text)) {
    echo json_encode(["message" => "question_id and question_text are required."]);
    http_response_code(400);
    exit;
}

$questionId = (int) $input->question_id;
$newText = $input->question_text;

try {
    $sql = "UPDATE questions SET question_text = :question_text WHERE question_id = :question_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":question_text", $newText);
    $stmt->bindParam(":question_id", $questionId);
    $stmt->execute();

    echo json_encode(["message" => "Question updated successfully!"]);
    http_response_code(200);
} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
