<?php

try {
  $host = "localhost";
  $port = 3306;
  $dbname = "quizapp_db";
  $username = "root";
  $password = "";

  $connection = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
} catch (\Throwable $e) {
  echo $e->getMessage();
}
?>
