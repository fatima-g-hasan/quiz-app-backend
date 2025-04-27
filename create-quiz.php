<?php
include "connection.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->title)) {
    echo json_encode(["message" => "Quiz title is required."]);
    http_response_code(400);
    exit;
}

$title = trim($data->title);

try {
    $sql = "INSERT INTO quizzes (title) VALUES (:title)";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":title", $title);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Quiz created successfully!"]);
        http_response_code(201);
    } else {
        echo json_encode(["message" => "Failed to create quiz."]);
        http_response_code(500);
    }
} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
