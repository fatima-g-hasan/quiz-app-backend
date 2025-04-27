<?php

include "connection.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->quiz_id) || empty($data->question)) {
    echo json_encode(["message" => "quiz_id and question are required."]);
    http_response_code(400);
    exit;
}

$quiz_id       = $data->quiz_id;
$question_text = $data->question;

try {
    $sql  = "INSERT INTO questions (quiz_id, question_text) VALUES (:quiz_id, :question_text)";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":quiz_id", $quiz_id);
    $stmt->bindParam(":question_text", $question_text);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Question created successfully!"]);
        http_response_code(201);
    } else {
        echo json_encode(["message" => "Failed to create question."]);
    }
} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
