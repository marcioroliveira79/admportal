<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu com Submenus e Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        /* Oculta a barra de rolagem da página principal */
        body {
            overflow: hidden;
        }

        /* Estilo do menu principal */
        .menu {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            padding: 15px 20px;
            font-size: 1em;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
        }

        .menu a {
            margin: 0 20px;
            color: #1c5243;
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }

        .menu a:hover {
            color: #28a167;
        }

        /* Estilo do submenu em cascata */
        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #ffffff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            min-width: 150px;
        }

        .submenu a {
            display: block;
            padding: 10px;
            color: #1c5243;
            text-decoration: none;
            white-space: nowrap;
        }

        .submenu a:hover {
            background-color: #f0f4f3;
            color: #28a167;
        }

        /* Logo da empresa */
        .logo {
            height: 40px;
            margin-right: 20px;
        }

        /* Estilo do iframe para ocupar a tela toda com rolagem, quando necessário */
        iframe {
            width: 100%;
            height: calc(100vh - 70px); /* Altura total menos o menu */
            border: none;
            overflow-y: auto; /* Ativa a rolagem vertical apenas no iframe */
        }

        /* Estilo do modal */
        .login-modal {
            display: none; /* Oculto por padrão */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 20;
            justify-content: center;
            align-items: center;
        }

        .login-content {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .login-content h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .login-content .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }

        /* Ajustes do link de login */
        .ms-auto {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .ms-auto a {
            text-decoration: none;
            color: #1c5243;
        }

        .ms-auto a:hover {
            color: #28a167;
        }
    </style>
</head>
<body>

    <!-- Menu Principal -->
    <div class="menu">
        <img src="imgs/logo_menu.png" alt="Logo" class="logo">
        <div class="menu-item">
            <a href="#" onclick="toggleSubmenu(event, 'solutionsSubmenu')">Soluções</a>
            <div id="solutionsSubmenu" class="submenu">
                <a href="template_verificacao_ddl.html" target="contentFrame" onclick="closeSubmenu()">Análise de DDL</a>
                <a href="identa.html" target="contentFrame" onclick="closeSubmenu()">Identa DDL</a>
                <a href="prefixo.html" target="contentFrame" onclick="closeSubmenu()">Prefixos</a>
                <a href="abreviador.html" target="contentFrame" onclick="closeSubmenu()">Abreviador</a>
                <a href="DASDL_BDSIACI.html" target="contentFrame" onclick="closeSubmenu()">DB SIACI</a>
                <a href="DASDL_BDSIACIPAR.html" target="contentFrame" onclick="closeSubmenu()">DB SIAPAR</a>
                <a href="lgpd.html" target="contentFrame" onclick="closeSubmenu()">LGPD Marcações</a>
                <a href="lgpd_classificacoes.html" target="contentFrame" onclick="closeSubmenu()">LGPD Classificação</a>
                <a href="manual.html" target="contentFrame" onclick="closeSubmenu()">Manual</a>
            </div>
        </div>
        <div class="menu-item">
            <a href="#" onclick="toggleSubmenu(event, 'aiSubmenu')">Manuais e Documentos</a>
            <div id="aiSubmenu" class="submenu">
                <a href="https://forms.office.com" target="contentFrame" onclick="closeSubmenu()">Formulários Chamado</a>
                <a href="https://unisyscorp.sharepoint.com" target="_blank" onclick="closeSubmenu()">Padrões de Nomenclatura</a>
                <a href="https://login.microsoftonline.com" target="_blank" onclick="closeSubmenu()">Controle de Senhas</a>
            </div>
        </div>
        <!-- Login Link -->
        <div class="ms-auto">
            <a href="module/conectar.php" onclick="openLoginModal()" style="font-weight: bold;">Adm</a>
        </div>
    </div>

    <!-- Modal de Login -->
    <div id="loginModal" class="login-modal">
        <div class="login-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            <h2>Login</h2>
            <form>
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" id="username" class="form-control" placeholder="Digite seu usuário">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" id="password" class="form-control" placeholder="Digite sua senha">
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>

    <!-- Iframe que ocupa toda a área abaixo do menu -->
    <iframe name="contentFrame" srcdoc="<body style='background-color: #1c5243;'></body>"></iframe>

    <script>
        function toggleSubmenu(event, submenuId) {
            event.preventDefault();

            // Fecha outros submenus abertos
            const submenus = document.querySelectorAll('.submenu');
            submenus.forEach(submenu => {
                if (submenu.id !== submenuId) {
                    submenu.style.display = 'none';
                }
            });

            // Alterna a exibição do submenu atual
            const submenu = document.getElementById(submenuId);
            const menuItem = event.currentTarget;

            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
                submenu.style.left = menuItem.offsetLeft + 'px';
            }
        }

        // Fecha o submenu ao clicar em um item
        function closeSubmenu() {
            const submenus = document.querySelectorAll('.submenu');
            submenus.forEach(submenu => {
                submenu.style.display = 'none';
            });
        }

        // Fecha o submenu se clicar fora
        document.addEventListener('click', function(event) {
            const isClickInside = event.target.closest('.menu-item');
            if (!isClickInside) {
                closeSubmenu();
            }
        });
       
    </script>

</body>
</html>
