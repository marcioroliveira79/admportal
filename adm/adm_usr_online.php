<?php
session_start();
require_once("module/conecta.php");
require_once("module/functions.php");

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);
    
    if ($acesso == "TELA AUTORIZADA") {
        ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status dos Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
                if (window.top === window.self) {
                    // Se a página não estiver sendo exibida dentro de um iframe, redireciona para o index
                    window.location.href = 'index.php';
                }
            </script>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 40px auto;
            max-width: 800px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 24px;
            color: #4a4a4a;
        }
        .alert {
            background-color: #d9edf7;
            color: #31708f;
            border: 1px solid #bce8f1;
            border-radius: 4px;
            padding: 15px;
            max-width: 800px;
            margin: 10px auto;
            text-align: center;
        }
        .status-indicator {
            display: inline-flex;
            align-items: center;
        }
        .status-indicator .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .status-indicator.online .dot {
            background-color: green;
        }
        .status-indicator.offline .dot {
            background-color: red;
        }
    </style>
    <!-- jQuery para AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Caso haja alguma mensagem para exibir, você pode usar um alerta -->
        <?php if (isset($mensagem) && !empty($mensagem)): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="form-title">Usuários Conectados</div>
            <table class="table table-striped" id="statusTable">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Data Acesso</th>                        
                        <th>Data Ult. Verificação</th>                        
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Os dados serão carregados via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Função que busca os dados via AJAX
        function fetchStatus() {
            $.ajax({
                url: 'adm/adm_usr_online_ajax.php', // script que retorna os dados da view
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var rows = "";
                        $.each(response.data, function(index, item) {
                            var statusIndicator = (item.status === "online") 
                                ? "<span class='status-indicator online'><span class='dot'></span>Online</span>" 
                                : "<span class='status-indicator offline'><span class='dot'></span>Offline</span>";

                            rows += "<tr>";
                            rows += "<td>" + item.nome_formatado + "</td>";
                            rows += "<td>" + item.data_acesso + "</td>";                            
                            rows += "<td>" + item.data_ping + "</td>";                            
                            rows += "<td>" + statusIndicator + "</td>";
                            rows += "</tr>";
                        });
                        $("#statusTable tbody").html(rows);
                    } else {
                        console.error("Erro: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro AJAX: " + error);
                }
            });
        }
        
        // Chama a função ao carregar a página e a cada 30 segundos
        $(document).ready(function(){
            fetchStatus();
            setInterval(fetchStatus, 30000);
        });
    </script>
</body>
</html>
<?php
    } else {
        // Se o acesso não for autorizado, exibe a página de erro 403
        include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
