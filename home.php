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
    <title>Quem Somos?</title>
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="icon" href="./assets/icons/Logo.png">
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
                    <a href="#" class="menu-item active">
                        <img src="./assets/icons/casa_azul.png" alt="Página Inicial">
                        Página Inicial
                    </a>
                    <a href="graficos.php" class="menu-item">
                        <img src="./assets/icons/grafico.png" alt="Gráficos">
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
            <div class="title">
                <h1>QUEM SOMOS?</h1>
            </div>
            <div class="profiles">
                <section class="profile-card">
                    <img src="./assets/img/matheus.png" alt="Matheus Arcangelo" class="foto">
                    <div class="info">
                        <h2>MATHEUS ARCANGELO PESTANA</h2>
                        <p>Técnico em Desenvolvimento de Sistemas e cursando Análise e Desenvolvimento de Sistemas,
                            ambos pelo SENAI Félix Guisard. Sou apaixonado pela tecnologia e suas inovações. Sempre
                            busco dedicar meu máximo aos trabalhos que me são atribuídos e em manter a integridade e
                            andamento da equipe em que estiver.</p>
                        <div class="links">
                            <a href="https://www.linkedin.com/in/matheus-arcangelo/" target="_blank">
                                <img src="./assets/icons/linkedin.png" alt="LinkedIn">
                            </a>
                            <a href="https://github.com/matheus-pestana" target="_blank">
                                <img src="./assets/icons/github.png" alt="GitHub">
                            </a>
                        </div>
                    </div>
                </section>

                <section class="profile-card">
                    <img src="./assets/img/vinicius.png" alt="Vinícius Cardoso" class="foto">
                    <div class="info">
                        <h2>VINÍCIUS CARDOSO DE PAULA</h2>
                        <p>Técnico em Multimídia e cursando Análise e Desenvolvimento de Sistemas pela faculdade SENAI
                            Félix Guisard. Dedico meu tempo ao uso da minha criatividade e ao aprendizado constante,
                            cada desafio me anima mais a construir um caminho profissional de sucesso.</p>
                        <div class="links">
                            <a href="https://www.linkedin.com/in/vinícius-cardoso-de-paula/" target="_blank">
                                <img src="./assets/icons/linkedin.png" alt="LinkedIn">
                            </a>
                            <a href="https://www.instagram.com/vini7.c/" target="_blank">
                                <img src="./assets/icons/instagram.png" alt="Instagram">
                            </a>
                        </div>
                    </div>
                </section>
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

</body>

</html>