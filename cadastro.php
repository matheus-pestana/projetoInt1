<?php
include 'conexao.php';

$mensagem = '';
$tipo = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_completo = $_POST['nome'] ?? '';
    $nome_usuario  = $_POST['usuario'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if ($senha !== $confirmar_senha) {
        $mensagem = 'As senhas não coincidem!';
        $tipo = 'error';
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tb_usuario (nome_completo, nome_usuario, email, senha) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome_completo, $nome_usuario, $email, $senha_hash);

        if ($stmt->execute()) {
            $mensagem = 'Usuário cadastrado com sucesso!';
            $tipo = 'success';
        } else {
            $mensagem = 'Erro ao cadastrar: ' . $stmt->error;
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
    <title>Cadastro</title>
    <link rel="stylesheet" href="./assets/css/login.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-container">
        <h2><strong>Registrar-se</strong></h2>
        <form class="form" method="POST" action="">
            <label for="nome">Nome completo:</label><br />
            <input type="text" id="nome" name="nome" required /><br />

            <label for="usuario">Nome de Usuário:</label><br />
            <input type="text" id="usuario" name="usuario" required /><br />

            <label for="email">Endereço de Email:</label><br />
            <input type="email" id="email" name="email" required /><br />

            <label for="senha">Senha:</label><br />
            <input type="password" id="senha" name="senha" required /><br />

            <label for="confirmar_senha">Confirmar Senha:</label><br />
            <input type="password" id="confirmar_senha" name="confirmar_senha" required /><br />

            <button type="submit">Cadastrar</button>
        </form>
        <p>Eu tenho uma conta! <a href="index.php">Entrar</a></p>
    </div>

    <?php if (!empty($mensagem)): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: '<?= $tipo ?>',
                    title: '<?= $tipo === "success" ? "Sucesso!" : "Erro!" ?>',
                    text: '<?= $mensagem ?>'
                }).then(() => {
                    <?php if ($tipo === "success"): ?>
                        window.location.href = "index.php";
                    <?php endif; ?>
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>
