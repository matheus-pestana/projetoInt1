<?php
session_start();
include 'conexao.php';

$mensagem = '';
$tipo = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $mensagem = 'Por favor, preencha todos os campos.';
        $tipo = 'error';
    } else {
        $sql = "SELECT id_usuario, nome_usuario, senha FROM tb_usuario WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                header("Location: home.php"); // Redirecionar para a página do painel
                exit();
            } else {
                $mensagem = 'Email ou senha incorretos.';
                $tipo = 'error';
            }
        } else {
            $mensagem = 'Email ou senha incorretos.';
            $tipo = 'error';
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/login.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-container">
        <h2><strong>Bem-vindo (a) de volta!</strong></h2>
        <form class="form" method="POST" action="">
            <label for="email">Endereço de Email:</label><br />
            <input type="email" id="email" name="email" required /><br />

            <label for="senha">Senha:</label><br />
            <input type="password" id="senha" name="senha" required /><br />

            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.php">Registre-se</a></p>
    </div>

    <?php if (!empty($mensagem)): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: 'Erro!',
                    text: '<?= $mensagem ?>'
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>