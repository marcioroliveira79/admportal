<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {

        // Processamento AJAX para atualização do valor inline
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_valor') {
            // Limpa qualquer conteúdo existente no buffer
            if (ob_get_length()) {
                ob_clean();
            }
            header('Content-Type: application/json');

            $id_atributo = (int)$_POST['id_atributo'];
            $novo_valor = $_POST['novo_valor'];

            $query_update = "UPDATE administracao.adm_pseudo_tabela_atributos SET valor_item = $1 WHERE id = $2";
            $result_update = pg_query_params($conexao, $query_update, [$novo_valor, $id_atributo]);

            if ($result_update) {
                echo json_encode(['success' => true, 'message' => 'Valor atualizado com sucesso!']);
            } else {
                $error = pg_last_error($conexao);
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar valor: ' . $error]);
            }
            exit();
        }

        // Consulta para listar as tabelas, atributos, descrições e valores
        $query = "
            SELECT 
                a.id, 
                a.nome_item, 
                a.descricao, 
                a.valor_item, 
                t.nome_tabela 
            FROM administracao.adm_pseudo_tabela_atributos a
            JOIN administracao.adm_pseudo_tabela t ON a.fk_atributo = t.id
            ORDER BY t.nome_tabela, a.nome_item ASC
        ";

        $result = pg_query($conexao, $query);
        if (!$result) {
            $erro_banco = pg_last_error($conexao);
            $data = [];
        } else {
            $data = pg_fetch_all($result);
        }
        ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Tabelas, Atributos e Valores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .form-container, .table-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 40px auto;
            max-width: 900px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 24px;
            color: #4a4a4a;
        }
        .editable {
            cursor: pointer;
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
    </style>
    <!-- jQuery para manipulação do DOM e AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <?php if (isset($erro_banco)): ?>
            <div class="alert alert-danger">
                Erro na consulta: <?= htmlspecialchars($erro_banco) ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="form-title">Tabelas, Atributos, Descrições e Valores</div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tabela</th>
                        <th>Atributo</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome_tabela']) ?></td>
                                <td><?= htmlspecialchars($row['nome_item']) ?></td>
                                <td><?= htmlspecialchars($row['descricao']) ?></td>
                                <!-- Célula editável com o id do atributo -->
                                <td class="editable" data-id="<?= $row['id'] ?>">
                                    <?= htmlspecialchars($row['valor_item']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Nenhum registro encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.editable').click(function(){
                var cell = $(this);
                if(cell.find('input').length > 0){
                    return;
                }
                var currentValue = cell.text().trim();
                var attributeId = cell.data('id');
                var input = $('<input type="text" class="form-control" value="'+currentValue+'">');
                cell.html(input);
                input.focus();
                input.blur(function(){
                    var newValue = $(this).val().trim();
                    if(newValue === currentValue){
                        cell.text(currentValue);
                        return;
                    }
                    $.ajax({
                        url: '', // A própria página processará a requisição
                        type: 'POST',
                        data: {
                            action: 'update_valor',
                            id_atributo: attributeId,
                            novo_valor: newValue
                        },
                        dataType: 'json',
                        success: function(response){
                            if(response.success){
                                cell.text(newValue);
                            } else {
                                alert(response.message);
                                cell.text(currentValue);
                            }
                        },
                        error: function(xhr, status, error){
                            console.log("Status: " + status);
                            console.log("Error: " + error);
                            console.log("Response: " + xhr.responseText);
                            alert('Erro na requisição. Verifique o console para mais detalhes.');
                            cell.text(currentValue);
                        }
                    });
                });
                input.keypress(function(e){
                    if(e.which === 13){
                        $(this).blur();
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
    } elseif ($acesso != "TELA AUTORIZADA") {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
