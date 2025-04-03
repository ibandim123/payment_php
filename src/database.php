<?php
$servername = "host.docker.internal";
$username = "root";
$password = "1234";
$database = "payment_php";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
