<?php

include "connection.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->quiz_id)) {
    echo json_encode(["message" => "quiz_id is required."]);
    http_response_code(400); 
    exit;
}

$quizId = $data->quiz_id;

try {
    $sql  = "DELETE FROM quizzes WHERE quiz_id = :quiz_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":quiz_id", $quizId);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Quiz deleted successfully!"]);
        http_response_code(200);
    } else {
        echo json_encode(["message" => "Quiz not found."]);
        http_response_code(404);
    }
} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
