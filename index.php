<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/login.css" />
</head>

<body>
    <div class="login-container">
        <h2><strong>Bem-vindo (a) de volta!</strong></h2>
        <form class="form">
            <label for="email">Endereço de Email:</label><br />
            <input type="email" id="email" name="email" required /><br />

            <label for="senha">Senha:</label><br />
            <input type="password" id="senha" name="senha" required /><br />

            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.php">Registre-se</a></p>
    </div>
</body>

</html>