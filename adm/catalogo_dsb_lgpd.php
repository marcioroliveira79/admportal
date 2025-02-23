<?php
session_start();

// Exemplo de verificação de acesso
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;
if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {

    // Supondo que você tenha funções de verificação
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    if ($acesso == "TELA AUTORIZADA") {

        // Não exibimos título nos combos, apenas as opções
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Dashboard LGPD - Combos</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <style>
            body {
                background-color: #f5f5f5;
                font-family: Arial, sans-serif;
            }
            .combo-container {
                display: flex;
                align-items: flex-start; /* ou 'stretch', caso prefira */
                justify-content: flex-start; /* garante alinhamento horizontal à esquerda */
                gap: 10px;
                margin: 10px;
            }
            .combo-container select {
                font-size: 11px; /* sem label, apenas o combo menor */
            }
            </style>
        </head>
        <body>
            <div class="container">

                <!-- Container para os combos -->
                <div class="combo-container">
                    <select id="comboAmbiente" class="form-select form-select-sm" style="width:150px;">
                        <option value="">Ambiente...</option>
                    </select>
                    <select id="comboService" class="form-select form-select-sm" style="width:150px;" disabled>
                        <option value="">Service...</option>
                    </select>
                    <select id="comboSchema" class="form-select form-select-sm" style="width:150px;" disabled>
                        <option value="">Schema...</option>
                    </select>
                </div>

                <!-- Aqui ficaria um container para gráfico ou algo do tipo -->
                <div id="graficoContainer" class="mt-4" style="margin: 20px;">
                    <!-- Conteúdo do gráfico será inserido depois -->
                    <p>Gráfico aqui...</p>
                </div>
            </div>

            <script>
            $(document).ready(function(){

                // Carrega ambientes
                $.ajax({
                    url: 'catalogo_dsb_lgpd_ajax.php',
                    method: 'GET',
                    data: { action: 'getAmbientes' },
                    dataType: 'json',
                    success: function(resp){
                        if(resp.success){
                            var options = '<option value="">Ambiente...</option>';
                            resp.data.forEach(function(amb){
                                options += '<option value="'+ amb +'">'+ amb +'</option>';
                            });
                            $('#comboAmbiente').html(options);
                        }
                    }
                });

                // Ao mudar ambiente, carrega services
                $('#comboAmbiente').on('change', function(){
                    var ambiente = $(this).val();
                    $('#comboService').html('<option value="">Service...</option>').prop('disabled', true);
                    $('#comboSchema').html('<option value="">Schema...</option>').prop('disabled', true);

                    if(ambiente){
                        $.ajax({
                            url: 'catalogo_dsb_lgpd_ajax.php',
                            method: 'GET',
                            data: { action: 'getServices', ambiente: ambiente },
                            dataType: 'json',
                            success: function(resp){
                                if(resp.success){
                                    var options = '<option value="">Service...</option>';
                                    resp.data.forEach(function(srv){
                                        options += '<option value="'+ srv +'">'+ srv +'</option>';
                                    });
                                    $('#comboService').html(options).prop('disabled', false);
                                }
                            }
                        });
                    }
                });

                // Ao mudar service, carrega schemas
                $('#comboService').on('change', function(){
                    var ambiente = $('#comboAmbiente').val();
                    var service  = $(this).val();
                    $('#comboSchema').html('<option value="">Schema...</option>').prop('disabled', true);

                    if(service){
                        $.ajax({
                            url: 'catalogo_dsb_lgpd_ajax.php',
                            method: 'GET',
                            data: { action: 'getSchemas', ambiente: ambiente, service_name: service },
                            dataType: 'json',
                            success: function(resp){
                                if(resp.success){
                                    var options = '<option value="">Schema...</option>';
                                    resp.data.forEach(function(sch){
                                        options += '<option value="'+ sch +'">'+ sch +'</option>';
                                    });
                                    $('#comboSchema').html(options).prop('disabled', false);
                                }
                            }
                        });
                    }
                });

                // Ao mudar schema, poderíamos disparar algo (ex: atualizar gráfico)
                $('#comboSchema').on('change', function(){
                    var ambiente = $('#comboAmbiente').val();
                    var service  = $('#comboService').val();
                    var schema   = $(this).val();

                    if(schema){
                        // Aqui você poderia chamar algo para atualizar um gráfico
                        // Exemplo:
                        // atualizaGrafico(ambiente, service, schema);
                        console.log('Schema selecionado:', schema);
                    }
                });

            });
            </script>
        </body>
        </html>
        <?php
    } else {
        @include("html/403.html");
    }
} else {
    header("Location: login.php");
}
?>
