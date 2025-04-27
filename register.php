<?php

include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $email = $data->email;
    $password = $data->password;

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Email already registered.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashedPassword);
            
            if ($stmt->execute()) {
                echo "User registered successfully!";
            } else {
                echo "Failed to register user.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

} else {
    echo "Only POST method is allowed.";
}
?>
