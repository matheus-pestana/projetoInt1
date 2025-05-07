<?php
$host = 'localhost';
$user = 'root'; // ou outro usuário
$pass = ''; // senha do seu MySQL
$db = 'db_prod';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
