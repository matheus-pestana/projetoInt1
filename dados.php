<?php
include 'conexao.php';

header('Content-Type: application/json');

$where_data_hora = "";
if (isset($_GET['data_inicial']) && !empty($_GET['data_inicial'])) {
    $data_inicial = $_GET['data_inicial'];
    $where_data_hora .= " AND data_hora >= '$data_inicial'";
}
if (isset($_GET['data_final']) && !empty($_GET['data_final'])) {
    $data_final = $_GET['data_final'];
    $where_data_hora .= " AND data_hora <= '$data_final'";
}

// Gráfico de Material
$sql_material = "SELECT tm.material, COUNT(tp.id_prod) AS quantidade
                 FROM tb_prod tp
                 JOIN tb_material tm ON tp.material = tm.id_material
                 GROUP BY tm.material";
$result_material = $conn->query($sql_material);
$data_material = [];
while ($row = $result_material->fetch_assoc()) {
    $data_material[$row['material']] = (int)$row['quantidade'];
}

// Gráfico de Tamanho
$sql_tamanho = "SELECT tt.tamanho, COUNT(tp.id_prod) AS quantidade
                 FROM tb_prod tp
                 JOIN tb_tamanho tt ON tp.tamanho = tt.id_tamanho
                 GROUP BY tt.tamanho
                 ORDER BY tt.id_tamanho";
$result_tamanho = $conn->query($sql_tamanho);
$labels_tamanho = [];
$values_tamanho = [];
while ($row = $result_tamanho->fetch_assoc()) {
    $labels_tamanho[] = $row['tamanho'];
    $values_tamanho[] = (int)$row['quantidade'];
}

// Gráfico de Data/Hora
$sql_dataHora = "SELECT DATE_FORMAT(data_hora, '%d/%m %Hh') AS data_formatada, COUNT(id_prod) AS quantidade
                 FROM tb_prod
                 WHERE 1=1 $where_data_hora
                 GROUP BY data_formatada
                 ORDER BY MIN(data_hora)";
$result_dataHora = $conn->query($sql_dataHora);
$labels_dataHora = [];
$values_dataHora = [];
while ($row = $result_dataHora->fetch_assoc()) {
    $labels_dataHora[] = $row['data_formatada'];
    $values_dataHora[] = (int)$row['quantidade'];
}

// Gráfico de Cores
$sql_cores = "SELECT cor, COUNT(id_prod) AS quantidade
              FROM tb_prod
              GROUP BY cor";
$result_cores = $conn->query($sql_cores);
$labels_cores = [];
$values_cores = [];
while ($row = $result_cores->fetch_assoc()) {
    $labels_cores[] = $row['cor'];
    $values_cores[] = (int)$row['quantidade'];
}

// Total de peças (sem filtro de data/hora, se desejar filtrar, adicione a cláusula WHERE)
$sql_total = "SELECT COUNT(id_prod) AS total FROM tb_prod";
$result_total = $conn->query($sql_total);
$total_pecas = 0;
if ($row = $result_total->fetch_assoc()) {
    $total_pecas = (int)$row['total'];
}

echo json_encode([
    'material' => $data_material,
    'tamanho' => [
        'labels' => $labels_tamanho,
        'values' => $values_tamanho
    ],
    'data_hora' => [
        'labels' => $labels_dataHora,
        'values' => $values_dataHora
    ],
    'cores' => [
        'labels' => $labels_cores,
        'values' => $values_cores
    ],
    'total_pecas' => $total_pecas
]);

$conn->close();
