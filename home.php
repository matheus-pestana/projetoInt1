<?php

session_start();
include 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$nomeCompleto = "";
$emailUsuario = "";
$fotoUsuario = "./assets/img/user.png"; // Imagem padrão

if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];

    $sql = "SELECT nome_completo, email, foto_usuario, tipo_foto FROM tb_usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Erro ao preparar a query de seleção: " . $conn->error);
        // Tratar o erro adequadamente (exibir mensagem, redirecionar, etc.)
    } else {
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            $nomeCompleto = $usuario['nome_completo'];
            $emailUsuario = $usuario['email'];
            // Se a foto estiver salva como BLOB, exibe diretamente
            if (isset($usuario['foto_usuario']) && !empty($usuario['foto_usuario']) && isset($usuario['tipo_foto'])) {
                $fotoBase64 = base64_encode($usuario['foto_usuario']);
                $fotoUsuario = 'data:' . $usuario['tipo_foto'] . ';base64,' . $fotoBase64;
            }
        } else {
            $nomeCompleto = "Usuário Desconhecido";
            $emailUsuario = "";
        }

        $stmt->close();
    }
    $conn->close();
} else {
    $nomeCompleto = "Não Logado";
    $emailUsuario = "";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quem Somos?</title>
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/css/home_responsivo.css">
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
            <div class="title">
                <h1>QUEM SOMOS?</h1>
            </div>
            <button class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">☰</button>
            <button class="close-btn" id="closeBtn" onclick="toggleMenu()">✖</button>
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

    <script src="./assets/js/hamburguer.js"></script>

</body>

</html>