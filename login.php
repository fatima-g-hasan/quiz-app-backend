<?php

include "connection.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["message" => "Method not allowed"]);
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["message" => "Email and Password are required."]);
    http_response_code(400);
    exit;
}

$email = trim($data->email);
$password = trim($data->password);

try {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo json_encode(["message" => "Login successful!", "user_id" => $user['user_id']]);
        http_response_code(200);
    } else {
        echo json_encode(["message" => "Invalid email or password."]);
        http_response_code(401);
    }

} catch (PDOException $e) {
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    http_response_code(500);
}

?>
