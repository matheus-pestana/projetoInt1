<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$nomeCompleto = "Usuário Desconhecido";
$emailUsuario = "";
$fotoUsuario = "./assets/img/user.png";

if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];

    $sql = "SELECT nome_completo, email, foto_usuario, tipo_foto FROM tb_usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        $nomeCompleto = $usuario['nome_completo'];
        $emailUsuario = $usuario['email'];
        if (!empty($usuario['foto_usuario']) && !empty($usuario['tipo_foto'])) {
            $fotoUsuario = 'data:' . $usuario['tipo_foto'] . ';base64,' . base64_encode($usuario['foto_usuario']);
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos</title>
    <link rel="stylesheet" href="./assets/css/graficos.css">
    <link rel="stylesheet" href="./assets/css/graficos_responsivo.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarLogout(event) {
            event.preventDefault(); // Impede o comportamento padrão do link

            Swal.fire({
                title: 'Deseja realmente sair?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, sair!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "logout.php"; // Redireciona para a página de logout
                }
            });
        }
    </script>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <div class="avatar">
                    <img src="<?php echo $fotoUsuario; ?>" alt="Foto de Perfil">
                </div>
                <h3><?php echo $nomeCompleto; ?></h3>
                <p><?php echo $emailUsuario; ?></p>
            </div>
            <nav class="menu">
                <div class="menu-items">
                    <a href="perfil.php" class="menu-item">
                        <img src="./assets/icons/lapis.png" alt="Perfil">
                        Perfil
                    </a>
                    <a href="home.php" class="menu-item <?php if (basename($_SERVER['PHP_SELF']) == 'home.php') echo 'active'; ?>">
                        <img src="./assets/icons/casa<?php if (basename($_SERVER['PHP_SELF']) == 'home.php') echo '_azul';
                                                        else echo ''; ?>.png" alt="Página Inicial">
                        Página Inicial
                    </a>
                    <a href="graficos.php" class="menu-item <?php if (basename($_SERVER['PHP_SELF']) == 'graficos.php') echo 'active'; ?>">
                        <img src="./assets/icons/grafico<?php if (basename($_SERVER['PHP_SELF']) == 'graficos.php') echo '_azul';
                                                        else echo ''; ?>.png" alt="Gráficos">
                        Gráficos
                    </a>
                </div>
                <div>
                    <a href="logout.php" class="menu-item sair" onclick="confirmarLogout(event)">
                        <img src="./assets/icons/sair.png" alt="Sair">
                        Sair
                    </a>
                </div>
            </nav>
        </aside>

        <main class="main-content">
        <button class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">☰</button>
        <button class="close-btn" id="closeBtn" onclick="toggleMenu()">✖</button>
            <div class="dashboard-header">
                <h1>GRÁFICOS</h1>
                <p>Total de peças: <span id="total-pecas"></span></p>
            </div>
            <div class="filter-container">
                <label for="data-inicial">Data/Hora Inicial:</label>
                <input type="datetime-local" id="data-inicial" name="data-inicial">

                <label for="data-final">Data/Hora Final:</label>
                <input type="datetime-local" id="data-final" name="data-final">

                <button onclick="atualizarGraficoDataHora()">Filtrar</button>
            </div>
            <div class="charts-container">
                <div class="material">
                    <h2>Material</h2>
                    <canvas id="materialChart"></canvas>
                </div>
                <div class="tamanho">
                    <h2>Quantidade por data/hora</h2>
                    <canvas id="dataHoraChart"></canvas>
                </div>
                <div class="data">
                    <h2>Tamanho</h2>
                    <canvas id="tamanhoChart"></canvas>
                </div>
                <div class="cores">
                    <h2>Cores</h2>
                    <canvas id="coresChart"></canvas>
                </div>
            </div>
            <footer class="footer">
                <div class="footer-icons">
                    <a href="https://www.instagram.com/senaitaubate/" target="_blank">
                        <img src="./assets/icons/instagram_footer.png" alt="Instagram">
                    </a>
                    <a href="https://www.linkedin.com/company/escolaefaculdadesenaitaubate/" target="_blank">
                        <img src="./assets/icons/linkedin_footer.png" alt="LinkedIn">
                    </a>
                    <a href="https://www.facebook.com/senaisp.taubate/" target="_blank">
                        <img src="./assets/icons/facebook_footer.png    " alt="Facebook">
                    </a>
                </div>
                <p>2025 SENAI. Todos os direitos reservados</p>
            </footer>
        </main>
    </div>
    <script>
        let materialChart;
        let dataHoraChart;
        let tamanhoChart;
        let coresChart;

        document.addEventListener('DOMContentLoaded', function() {
            carregarDadosGraficos();
        });

        function carregarDadosGraficos(dataInicial = null, dataFinal = null) {
            let url = 'dados.php';
            const params = new URLSearchParams();
            if (dataInicial) {
                params.append('data_inicial', dataInicial);
            }
            if (dataFinal) {
                params.append('data_final', dataFinal);
            }
            const queryString = params.toString();
            if (queryString) {
                url += '?' + queryString;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Atualizar o total de peças
                    document.getElementById('total-pecas').textContent = data.total_pecas;

                    // Gráfico de Material (Pizza)
                    const materialCtx = document.getElementById('materialChart').getContext('2d');
                    if (materialChart) {
                        materialChart.destroy();
                    }
                    materialChart = new Chart(materialCtx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(data.material),
                            datasets: [{
                                data: Object.values(data.material),
                                backgroundColor: ['#559bdb', '#212b71'],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });

                    // Gráfico de Quantidade por data/hora (Linha)
                    const dataHoraCtx = document.getElementById('dataHoraChart').getContext('2d');
                    if (dataHoraChart) {
                        dataHoraChart.destroy();
                    }
                    dataHoraChart = new Chart(dataHoraCtx, {
                        type: 'line',
                        data: {
                            labels: data.data_hora.labels,
                            datasets: [{
                                label: 'Quantidade',
                                data: data.data_hora.values,
                                borderColor: '#212b71',
                                fill: false,
                                pointRadius: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });

                    // Gráfico de Tamanho (Área)
                    const tamanhoCtx = document.getElementById('tamanhoChart').getContext('2d');
                    if (tamanhoChart) {
                        tamanhoChart.destroy();
                    }
                    tamanhoChart = new Chart(tamanhoCtx, {
                        type: 'line',
                        data: {
                            labels: data.tamanho.labels,
                            datasets: [{
                                label: 'Quantidade',
                                data: data.tamanho.values,
                                backgroundColor: '#212b71',
                                borderColor: '#212b71',
                                fill: true,
                                pointRadius: 3,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });

                    // Gráfico de Cores (Barras)
                    const coresCtx = document.getElementById('coresChart').getContext('2d');
                    if (coresChart) {
                        coresChart.destroy();
                    }
                    const coresHex = ['green', 'yellow', 'red', '#212b71'];
                    coresChart = new Chart(coresCtx, {
                        type: 'bar',
                        data: {
                            labels: data.cores.labels,
                            datasets: [{
                                label: 'Quantidade',
                                data: data.cores.values,
                                backgroundColor: coresHex.slice(0, data.cores.labels.length)
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar dados do dashboard:', error);
                });
        }

        function atualizarGraficoDataHora() {
            const dataInicial = document.getElementById('data-inicial').value;
            const dataFinal = document.getElementById('data-final').value;
            carregarDadosGraficos(dataInicial, dataFinal);
        }
    </script>

    <script src="./assets/js/hamburguer.js"></script>

</body>

</html>