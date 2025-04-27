<?php

include "connection.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

if (empty($_GET['quiz_id'])) {
    echo json_encode(["message" => "quiz_id is required."]);
    http_response_code(400);
    exit;
}

$quizId = (int) $_GET['quiz_id'];

try {
    $sql = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":quiz_id", $quizId);
    $stmt->execute();

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($questions) {
        echo json_encode(["questions" => $questions]);
        http_response_code(200);
    } else {
        echo json_encode(["message" => "No questions found."]);
        http_response_code(404);
    }

} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
