<?php

session_start();
include 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario']; // Garanta que isso seja um inteiro confiável

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeCompleto = $_POST['nome_completo'];
    $emailUsuario = $_POST['email'];
    $senhaNova = $_POST['senha'];

    // Validação dos campos nomeCompleto e email
    if (empty($nomeCompleto) || empty($emailUsuario)) {
        $_SESSION['mensagem'] = "Por favor, preencha todos os campos obrigatórios.";
        $_SESSION['tipo_mensagem'] = 'error';
        header("Location: perfil.php");
        exit();
    }

    // Tratamento de erros para a conexão
    if (!$conn) {
        error_log("Erro de conexão com o banco de dados: " . mysqli_connect_error());
        $_SESSION['mensagem'] = "Erro de conexão com o banco de dados.";
        $_SESSION['tipo_mensagem'] = 'error';
        header("Location: perfil.php");
        exit();
    }

    $sql = "UPDATE tb_usuario SET nome_completo = ?, email = ?";
    $params = [$nomeCompleto, $emailUsuario];
    $tipos = 'ss';

    if (!empty($senhaNova)) {
        $senhaCriptografada = password_hash($senhaNova, PASSWORD_DEFAULT);
        $sql .= ", senha = ?";
        $params[] = $senhaCriptografada;
        $tipos .= 's';
    }

    $sql .= " WHERE id_usuario = ?";
    $params[] = $idUsuario;
    $tipos .= 'i';

    $stmt = $conn->prepare($sql);

    // Corrigido: Construir a string de tipos e array de parâmetros de forma consistente
    $bindParams = [$tipos]; // Inicializa o array com a string de tipos
    foreach ($params as $param) {
        $bindParams[] = &$params[array_search($param, $params)]; // Passa por referência
    }

    call_user_func_array(array($stmt, 'bind_param'), $bindParams);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Perfil atualizado com sucesso!";
        $_SESSION['tipo_mensagem'] = 'success';

        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $arquivo = $_FILES['foto_perfil'];
            $tipoArquivo = $arquivo['type'];
            $caminhoTemporario = $arquivo['tmp_name'];

            // Validação do tipo MIME
            $tiposAceitos = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($tipoArquivo, $tiposAceitos, true)) {
                $_SESSION['mensagem'] = "Tipo de arquivo inválido. Apenas JPG, PNG e GIF são permitidos.";
                $_SESSION['tipo_mensagem'] = 'error';
            } else {
                if (!file_exists($caminhoTemporario)) {
                    $_SESSION['mensagem'] = "Erro ao processar o upload: arquivo temporário não encontrado.";
                    $_SESSION['tipo_mensagem'] = 'error';
                } else {
                    $sqlFoto = "UPDATE tb_usuario SET foto_usuario = ?, tipo_foto = ? WHERE id_usuario = ?";
                    $stmtFoto = $conn->prepare($sqlFoto);
                    if (!$stmtFoto) {
                        $_SESSION['mensagem'] = "Erro ao preparar a query de foto: " . $conn->error;
                        $_SESSION['tipo_mensagem'] = 'error';
                    } else {
                        $null = NULL;
                        $stmtFoto->bind_param("bsi", $null, $tipoArquivo, $idUsuario);

                        // Envio do conteúdo do arquivo em partes, se necessário
                        $fp = fopen($caminhoTemporario, 'rb');
                        while (!feof($fp)) {
                            $stmtFoto->send_long_data(0, fread($fp, 8192));
                        }
                        fclose($fp);

                        if ($stmtFoto->execute()) {
                            $_SESSION['mensagem'] = "Foto de perfil atualizada com sucesso!";
                            $_SESSION['tipo_mensagem'] = 'success';
                        } else {
                            $_SESSION['mensagem'] = "Erro ao salvar a foto de perfil no banco de dados: " . $stmtFoto->error;
                            $_SESSION['tipo_mensagem'] = 'error';
                        }
                        $stmtFoto->close();
                    }
                }
            }
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar o perfil: " . $stmt->error;
        $_SESSION['tipo_mensagem'] = 'error';
    }

    if ($stmt) $stmt->close();
    if ($conn) $conn->close();
    header("Location: perfil.php");
    exit();
}

$nomeCompleto = "";
$emailUsuario = "";
$fotoUsuario = "./assets/img/user.png";

if (isset($_SESSION['id_usuario'])) {
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
        }
        $stmt->close();
    }
    $conn->close();
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="./assets/css/perfil.css">
    <link rel="stylesheet" href="./assets/css/perfil_responsivo.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarLogout(event) {
            event.preventDefault();

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
                    window.location.href = "logout.php";
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
                    <img src="<?php echo $fotoUsuario; ?>" alt="Avatar do Usuário">
                </div>
                <h3><?php echo $nomeCompleto; ?></h3>
                <p><?php echo $emailUsuario; ?></p>
            </div>
            <nav class="menu">
                <div class="menu-items">
                    <a href="perfil.php" class="menu-item active">
                        <img src="./assets/icons/lapis_azul.png" alt="Perfil">
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

            <div class="banner">
                <div class="foto-perfil">
                    <img src="<?php echo $fotoUsuario; ?>" alt="Foto de Perfil">
                </div>
            </div>

            <button class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">☰</button>
            <button class="close-btn" id="closeBtn" onclick="toggleMenu()">✖</button>

            <div class="formulario">
                <form class="form" action="perfil.php" method="POST" enctype="multipart/form-data">
                    <div class="inputs">
                        <label for="nome_completo">Nome Completo</label>
                        <input type="text" id="nome_completo" name="nome_completo" value="<?php echo $nomeCompleto; ?>" />
                    </div>
                    <div class="inputs">
                        <label for="email">Endereço de Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $emailUsuario; ?>" />
                    </div>
                    <div class="inputs">
                        <label for="senha">Nova Senha (deixe em branco para não alterar)</label>
                        <input type="password" id="senha" name="senha" />
                    </div>
                    <div class="inputs">
                        <label for="foto_perfil">Foto de Perfil</label>
                        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" />
                        <p class="observacao">Formatos aceitos: JPG, JPEG, PNG.</p>
                    </div>
                    <button type="submit">Salvar Alterações</button>
                </form>
            </div>

            <footer class="footer">
                <div class="footer-icons">
                    <img src="./assets/icons/instagram_footer.png" alt="Instagram">
                    <img src="./assets/icons/linkedin_footer.png" alt="LinkedIn">
                    <img src="./assets/icons/facebook_footer.png     " alt="Facebook">
                </div>
                <p>2025 SENAI. Todos os direitos reservados</p>
            </footer>
        </main>
    </div>

    <?php
    if (isset($_SESSION['mensagem'])) {
        echo '<script>
        Swal.fire({
            title: "' . $_SESSION['tipo_mensagem'] . '!",
            text: "' . $_SESSION['mensagem'] . '",
            icon: "' . $_SESSION['tipo_mensagem'] . '",
            confirmButtonText: "OK"
        });
    </script>';
        unset($_SESSION['mensagem']);
        unset($_SESSION['tipo_mensagem']);
    }
    ?>

    <script src="./assets/js/hamburguer.js"></script>

</body>

</html>