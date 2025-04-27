<?php

include "connection.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

$input = json_decode(file_get_contents("php://input"));

if (empty($input->question_id)) {
    echo json_encode(["message" => "question_id is required."]);
    http_response_code(400);
    exit;
}

$questionId = (int) $input->question_id;

try {
    $sql = "DELETE FROM questions WHERE question_id = :question_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":question_id", $questionId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["message" => "Question deleted successfully!"]);
    http_response_code(200);
} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
