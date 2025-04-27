<?php

include "connection.php";

header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

try {
    $sql = "SELECT quiz_id, title FROM quizzes";
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($quizzes) {
        echo json_encode($quizzes);
        http_response_code(200);
    } else {
        echo json_encode(["message" => "No quizzes found."]);
        http_response_code(404); 
    }
} catch (PDOException $e) {
 
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
