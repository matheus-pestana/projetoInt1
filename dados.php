<?php
include 'conexao.php'; // Inclua seu arquivo de conexão

// Dados para o gráfico de Material
$sql_material = "SELECT tm.material, COUNT(tp.id_prod) AS quantidade
                 FROM tb_prod tp
                 JOIN tb_material tm ON tp.material = tm.id_material
                 GROUP BY tm.material";
$result_material = $conn->query($sql_material);
$data_material = [];
while ($row = $result_material->fetch_assoc()) {
    $data_material[$row['material']] = $row['quantidade'];
}

// Dados para o gráfico de Tamanho
$sql_tamanho = "SELECT tt.tamanho, COUNT(tp.id_prod) AS quantidade
                FROM tb_prod tp
                JOIN tb_tamanho tt ON tp.tamanho = tt.id_tamanho
                GROUP BY tt.tamanho
                ORDER BY tt.id_tamanho"; // Mantém a ordem pequeno, médio, grande
$result_tamanho = $conn->query($sql_tamanho);
$data_tamanho = [];
$labels_tamanho = [];
$values_tamanho = [];
while ($row = $result_tamanho->fetch_assoc()) {
    $labels_tamanho[] = $row['tamanho'];
    $values_tamanho[] = $row['quantidade'];
}
$data_tamanho['labels'] = $labels_tamanho;
$data_tamanho['values'] = $values_tamanho;

// Dados para o gráfico de Quantidade por data/hora (exemplo das últimas horas)
$sql_data_hora = "SELECT DATE_FORMAT(data_hora, '%d/%m - %Hh') AS hora, COUNT(id_prod) AS quantidade
                  FROM tb_prod
                  WHERE data_hora >= DATE_SUB(NOW(), INTERVAL 6 HOUR)
                  GROUP BY DATE_FORMAT(data_hora, '%d/%m - %Hh')
                  ORDER BY data_hora";
$result_data_hora = $conn->query($sql_data_hora);
$data_data_hora = [];
$labels_data_hora = [];
$values_data_hora = [];
while ($row = $result_data_hora->fetch_assoc()) {
    $labels_data_hora[] = $row['hora'];
    $values_data_hora[] = $row['quantidade'];
}
$data_data_hora['labels'] = $labels_data_hora;
$data_data_hora['values'] = $values_data_hora;

// Dados para o gráfico de Cores
$sql_cores = "SELECT tc.cor, COUNT(tp.id_prod) AS quantidade
              FROM tb_prod tp
              JOIN tb_cor tc ON tp.cor = tc.id_cor
              GROUP BY tc.cor
              ORDER BY tc.id_cor"; // Mantém a ordem verde, amarelo, vermelho, azul
$result_cores = $conn->query($sql_cores);
$data_cores = [];
$labels_cores = [];
$values_cores = [];
$cores_hex = ['green', 'yellow', 'red', 'blue', 'black']; // Cores correspondentes (adicione 'black' para 'Outro' se necessário)
$i = 0;
while ($row = $result_cores->fetch_assoc()) {
    $labels_cores[] = $row['cor'];
    $values_cores[] = $row['quantidade'];
    $i++;
}
// Adicionando "Outro" para o gráfico de Material (se houver outros materiais)
$total_produtos = $conn->query("SELECT COUNT(*) as total FROM tb_prod")->fetch_assoc()['total'];
$total_metal_plastico = array_sum($data_material);
if ($total_produtos > $total_metal_plastico) {
    $data_material['Outro'] = $total_produtos - $total_metal_plastico;
}

// Total de peças
$total_pecas = $total_produtos;

$conn->close();

// Retorna os dados como JSON
$response = [
    'material' => $data_material,
    'tamanho' => $data_tamanho,
    'data_hora' => $data_data_hora,
    'cores' => [
        'labels' => $labels_cores,
        'values' => $values_cores,
        'backgroundColors' => array_slice($cores_hex, 0, count($labels_cores))
    ],
    'total_pecas' => $total_pecas
];

header('Content-Type: application/json');
echo json_encode($response);
