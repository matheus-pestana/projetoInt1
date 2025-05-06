<?php
include 'conexao.php';

$sql = "SELECT MONTH(data_cadastro) AS mes, COUNT(*) AS total FROM usuarios GROUP BY mes ORDER BY mes";
$result = $conn->query($sql);

$dados = [];
while ($row = $result->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados);
?>
