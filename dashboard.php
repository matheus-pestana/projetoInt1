<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Usuários</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Usuários cadastrados por mês</h2>
    <canvas id="grafico"></canvas>

    <script>
    fetch('dados.php')
        .then(response => response.json())
        .then(dados => {
            const labels = dados.map(item => `Mês ${item.mes}`);
            const valores = dados.map(item => item.total);

            new Chart(document.getElementById('grafico'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Usuários',
                        data: valores,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    }]
                }
            });
        });
    </script>
</body>
</html>
