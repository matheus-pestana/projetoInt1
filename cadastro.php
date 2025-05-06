<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="./assets/css/cadastro.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Registrar-se</h2>
            <form>
                <div class="form-group">
                    <label for="nome_completo">Nome Completo:</label>
                    <input type="text" id="nome_completo" name="nome_completo">
                </div>
                <div class="form-group">
                    <label for="nome_usuario">Nome de Usuário:</label>
                    <input type="text" id="nome_usuario" name="nome_usuario">
                </div>
                <div class="form-group">
                    <label for="email">Endereço de Email:</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha">
                </div>
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha">
                </div>
                <button type="submit" class="register-button">Registrar</button>
            </form>
            <p class="login-link">Eu tenho uma conta. <a href="#">Entrar</a></p>
        </div>
    </div>
</body>
</html>