<?php
session_start();
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Função para buscar o status dos usuários
        function getUsersStatus($conexao) {
            $query = "
                SELECT 
                u.id AS usuario_id,
                u.nome || ' ' || u.sobre_nome AS nome_completo,
                CASE 
                    WHEN la.data_saida IS NULL THEN 'online'
                    ELSE 'offline'
                END AS status
            FROM 
                administracao.adm_usuario u
            LEFT JOIN LATERAL (
                SELECT 
                    data_saida
                FROM 
                    administracao.adm_log_acesso la
                WHERE 
                    la.fk_usuario = u.id
                ORDER BY 
                    la.data_acesso DESC
                LIMIT 1
            ) la ON true
            WHERE 
                u.ativo = true
            ORDER BY 
                status DESC, nome_completo ASC;
        
            ";

            $result = pg_query($conexao, $query);

            $users = [];
            if ($result) {
                while ($row = pg_fetch_assoc($result)) {
                    $users[] = $row;
                }
            }

            return $users;
        }

        // Retorna JSON para AJAX se solicitado
        if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
            header('Content-Type: application/json');
            $users = getUsersStatus($conexao);
            echo json_encode($users);
            exit();
        }
        ?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Status dos Usuários</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    max-width: 800px;
                    margin: 40px auto;
                }
                .status-icon {
                    font-size: 18px;
                    margin-right: 10px;
                }
                .status-online {
                    color: green;
                }
                .status-offline {
                    color: red;
                }
            </style>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                function loadUserStatus() {
                    $.ajax({
                        url: '?ajax=true',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            const tableBody = $('#userStatusTable tbody');
                            tableBody.empty();

                            data.forEach(user => {
                                const statusIcon = user.status === 'online' 
                                    ? '<span class="status-icon status-online">●</span>'
                                    : '<span class="status-icon status-offline">●</span>';

                                tableBody.append(`
                                    <tr>
                                        <td>${statusIcon}${user.nome_completo}</td>
                                        <td>${user.status === 'online' ? 'Online' : 'Offline'}</td>
                                    </tr>
                                `);
                            });
                        },
                        error: function() {
                            console.error('Erro ao carregar o status dos usuários.');
                        }
                    });
                }

                $(document).ready(function() {
                    loadUserStatus();
                    setInterval(loadUserStatus, 20000); // Atualiza a cada 20 segundos
                });
            </script>
        </head>
        <body>
        <div class="container">
            <div class="table-container">
                <h1 class="text-center">Status dos Usuários</h1>
                <table class="table table-bordered" id="userStatusTable">
                    <thead>
                        <tr>
                            <th>Nome do Usuário</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dados serão preenchidos pelo AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        </body>
        </html>

        <?php
    } elseif ($acesso != "TELA AUTORIZADA") {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
