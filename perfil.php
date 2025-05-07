<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="./assets/css/perfil.css">
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
                    <a href="#" class="menu-item active">
                        <img src="./assets/icons/lapis_azul.png" alt="Perfil">
                        Perfil
                    </a>
                    <a href="home.php" class="menu-item">
                        <img src="./assets/icons/casa.png" alt="Página Inicial">
                        Página Inicial
                    </a>
                    <a href="graficos.php" class="menu-item">
                        <img src="./assets/icons/grafico.png" alt="Gráficos">
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

            <div class="banner">
                <div class="foto-perfil">
                    <div class="editar-icone">
                        <img src="./icons/lapis.png" alt="Editar" />
                    </div>
                </div>
            </div>

            <div class="formulario">
                <form>
                    <div>
                        <label for="nome">Primeiro Nome</label>
                        <input type="text" id="nome" />
                    </div>
                    <div>
                        <label for="sobrenome">Último Nome</label>
                        <input type="text" id="sobrenome" />
                    </div>
                    <div>
                        <label for="email">Endereço de Email</label>
                        <input type="email" id="email" />
                    </div>
                    <div>
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" />
                    </div>
                    <button type="submit">Salvar</button>
                </form>
            </div>

            <footer class="footer">
                <div class="footer-icons">
                    <img src="./assets/icons/instagram_footer.png" alt="Instagram">
                    <img src="./assets/icons/linkedin_footer.png" alt="LinkedIn">
                    <img src="./assets/icons/facebook_footer.png    " alt="Facebook">
                </div>
                <p>2025 SENAI. Todos os direitos reservados</p>
            </footer>
        </main>
    </div>

</body>

</html>