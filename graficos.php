<?php

session_start();
include 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos</title>
    <link rel="stylesheet" href="./assets/css/graficos.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <div class="avatar"></div>
                <h3>VINÍCIUS CARDOSO</h3>
                <p>emailimaginario@gmail.com</p>
            </div>
            <nav class="menu">
                <div class="menu-items">
                    <a href="perfil.php" class="menu-item">
                        <img src="./assets/icons/lapis.png" alt="Perfil">
                        Perfil
                    </a>
                    <a href="home.php" class="menu-item">
                        <img src="./assets/icons/casa.png" alt="Página Inicial">
                        Página Inicial
                    </a>
                    <a href="#" class="menu-item active">
                        <img src="./assets/icons/grafico_azul.png" alt="Gráficos">
                        Gráficos
                    </a>
                </div>
                <div>
                    <a href="logout.php" class="menu-item sair">
                        <img src="./assets/icons/sair.png" alt="Sair">
                        Sair
                    </a>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            <div class="dashboard-header">
                <h1>GRÁFICOS</h1>
                <p>Total de peças: <span id="total-pecas"></span></p>
            </div>
            <div class="charts-container">
                <div class="material">
                    <h2>Material</h2>
                    <canvas id="materialChart"></canvas>
                </div>
                <div class="tamanho">
                    <h2>Tamanho</h2>
                    <canvas id="tamanhoChart"></canvas>
                </div>
                <div class="data">
                    <h2>Quantidade por data/hora</h2>
                    <canvas id="dataHoraChart"></canvas>
                </div>
                <div class="cores">
                    <h2>Cores</h2>
                    <canvas id="coresChart"></canvas>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('dados.php')
                .then(response => response.json())
                .then(data => {
                    // Atualizar o total de peças
                    document.getElementById('total-pecas').textContent = data.total_pecas;

                    // Gráfico de Material (Pizza)
                    const materialCtx = document.getElementById('materialChart').getContext('2d');
                    new Chart(materialCtx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(data.material),
                            datasets: [{
                                data: Object.values(data.material),
                                backgroundColor: ['#6495ED', '#ADD8E6'],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Desativado
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });

                    // Gráfico de Quantidade por data/hora (Linha)
                    const dataHoraCtx = document.getElementById('dataHoraChart').getContext('2d');
                    new Chart(dataHoraCtx, {
                        type: 'line',
                        data: {
                            labels: data.data_hora.labels,
                            datasets: [{
                                label: 'Quantidade',
                                data: data.data_hora.values,
                                borderColor: '#1E90FF',
                                fill: false,
                                pointRadius: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Desativado
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
                    new Chart(tamanhoCtx, {
                        type: 'line',
                        data: {
                            labels: data.tamanho.labels,
                            datasets: [{
                                label: 'Quantidade',
                                data: data.tamanho.values,
                                backgroundColor: 'rgba(30, 144, 255, 0.3)',
                                borderColor: '#1E90FF',
                                fill: true,
                                pointRadius: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Desativado
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
                    const coresHex = ['yellow', 'skyblue', 'red', 'black'];
                    new Chart(coresCtx, {
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
                            maintainAspectRatio: false, // Desativado
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
                                    position: 'bottom',
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar dados do dashboard:', error);
                });
        });
    </script>


</body>

</html>