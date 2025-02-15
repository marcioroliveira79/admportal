<?php
session_start();

if (isset($_SESSION['global_id_usuario']) && !empty($_SESSION['global_id_usuario']) && $acao != null) {
    $acesso = ItemAccess($_SESSION['global_id_perfil'], $acao, $conexao);
    $acao_existe = isFileExists($acao, $_SESSION['global_path']);

    if ($acesso == "TELA AUTORIZADA") {
        // Inicialização de variáveis
    ?>
    
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>UNISYS Data Schema Analysis</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/themes/prism.min.css" rel="stylesheet">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/prism.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/plugins/line-numbers/prism-line-numbers.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/components/prism-sql.min.js"></script>
            

            <style>
                body {
                    background-color: #f0f4f3;
                    font-family: Arial, sans-serif;
                    color: #333;
                    margin: 20px;
                    font-size: 1.0em;
                    line-height: 1.6;
                }
                h1 {
                    color: #1c5243;
                    text-align: center;
                    margin-top: 20px;
                    font-size: 1.8em;
                    font-weight: bold;
                }
                h2 {
                    color: #1c5243;
                    text-align: left;            
                    margin-top: 20px;
                    font-size: 1.0em;
                    font-weight: bold;
                }
                h3 {
                    color: #1c5243;
                    text-align: left;
                    margin-top: 20px;
                    font-size: 0.7em;
                    font-weight: normal;
                }
                textarea {
                    width: 100%;
                    padding: 10px;
                    margin: 15px 0;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    font-size: 1em;
                }
                /* Estilização do botão e spinner */
                .button-container {
                    position: relative;
                    display: inline-block;
                    text-align: center;
                }
                #parseButton {
                    display: block;
                    width: 100%;
                    max-width: 120px;
                    margin: 20px auto;
                    padding: 10px 25px;
                    background-color: #33b27a;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    position: relative;
                    transition: background-color 0.3s;
                }
                #parseButton:hover {
                    background-color: #28a167;
                }
                #parseButton:active {
                    animation: glitch 0.5s ease-in-out;
                }
                .spinner-border {
                    display: none;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 1.5rem;
                    height: 1.5rem;
                    transform: translate(-50%, -50%);
                    color: white;
                }
                .loading {
                    font-weight: bold;
                    color: #1c5243;
                    text-align: center;
                }
                /* Estilo da Tabela */
                table {
                    width: 90%;
                    margin: 20px auto;
                    border-collapse: collapse;
                    background-color: #ffffff;
                    color: #1c5243;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                th, td {
                    border: 1px solid #e1e1e1;
                    padding: 12px;
                    text-align: left;
                    font-size: 0.7em;
                }
                th {
                    background-color: #d9e8e3;
                    color: #1c5243;
                    font-weight: bold;
                }
                td {
                    background-color: #f7fdfb;
                    color: #333;
                }
                tr.blank-row td {
                    height: 30px;
                    background-color: #f0f4f3;
                }
                tr:hover td {
                    background-color: #d3e9e1;
                }
                .status-column-idx {
                    width: 250px; 
                    text-align: left;
                    font-weight: normal;
                    color: #000000;
                }
                .status-column {
                    width: 250px; 
                    text-align: left;
                    font-weight: normal;
                    color: #000000;
                }
                #deleteTruncateTable .status-column {
                    width: 550px;
                }
                .code-container {
                    
                    font-size: 0.50em; /* Ajuste o valor conforme necessário, 0.85em é um exemplo */
                    position: relative;
                    width: 100%;
                    margin: 20px auto;
                    background-color: #f7fdfb;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    padding: 10px;
                    font-size: 1em;
                    overflow: auto; /* Adiciona rolagem horizontal e vertical */
                    max-height: 400px; /* Limita a altura do contêiner, com rolagem se o conteúdo ultrapassar */
                }

                .code-container pre {
                    white-space: pre-wrap; /* Quebra de linha para palavras longas */
                    word-wrap: break-word;
                }

                .line-numbers {
                    padding-left: 40px; /* Espaço extra para acomodar a numeração de linhas */
                }
                .status-column {
                    width: 50px; /* Largura padrão */
                    text-align: left;
                    font-weight: normal;
                    color: #000000;
                }

                .status-column.wide {
                    width: 200px; /* Aumenta a largura quando há uma mensagem longa */
                }
                .status-column-wide {
                width: 300px; /* Ajuste o valor conforme necessário */
                }

                /* Classe para alinhar o código à esquerda */
                .code-column {
                    text-align: left;
                }
            
                /* Animação Glitch estilo Black Mirror */
                @keyframes glitch {
                    0% {
                        background-color: #ff0043;
                        color: #000;
                        transform: translateX(0);
                    }
                    20% {
                        background-color: #000;
                        color: #fff;
                        transform: translateX(-2px);
                    }
                    40% {
                        background-color: #ff0043;
                        color: #000;
                        transform: translateX(2px);
                    }
                    60% {
                        background-color: #33b27a;
                        color: white;
                        transform: translateX(-1px);
                    }
                    80% {
                        background-color: #000;
                        color: #ff0043;
                        transform: translateX(1px);
                    }
                    100% {
                        background-color: #33b27a;
                        color: white;
                        transform: translateX(0);
                    }
                }
                
            </style>
            
        </head>
        <body>

            <h3>Versão: 1.03 Beta</h3>
            <!-- 1.01 - Ajuste para incluir DROP TRIGGER e DROP SEQUENCE, Ajuste para correção da comparação dos tipos: NUMBER(XX) com NUMBER -->
            <!-- 1.02 - Ajuste para incluir verificação de Constraints -->
            <div class="code-container" style="display: flex; position: relative;">        
                <div style="width: 60%; margin-left: 0;font-size: 0.70em">
                    <textarea id="ddlInput" rows="19" placeholder="Insira aqui a DDL..." oninput="updateHighlighting(); cleanSQLInput()" onpaste="cleanSQLInput()" spellcheck="false"></textarea>
                </div>
                <pre class="line-numbers language-sql" style="font-size: 0.70em; width: 40%"><code id="highlightedSQL"></code></pre>
            </div>
            
            <!-- Botões lado a lado com altura uniforme e espaçamento -->
            <div class="button-container" style="display: flex; gap: 10px; align-items: center; margin-top: 10px;">
                <!-- Botão de carregar arquivo -->
                <button onclick="document.getElementById('fileInput').click();" id="uploadButton" 
                    style="display: flex; align-items: center; justify-content: center; background-color: #33b27a; color: white; 
                        border: none; border-radius: 5px; font-size: 16px; padding: 10px 25px; height: 50px; cursor: pointer; 
                        transition: background-color 0.3s; width: 200px;">
                    <span style="display: inline-flex; align-items: center;">📁 Carregar SQL</span>
                </button>       
            
                <!-- Botão OK -->
                <button onclick="parseDDL()" id="parseButton" 
                    style="display: flex; align-items: center; justify-content: center; background-color: #33b27a; color: white; 
                    border: none; border-radius: 5px; font-size: 16px; padding: 10px 25px; height: 50px; cursor: pointer; 
                    transition: background-color 0.3s; width: 200px;">
                    <span style="display: inline-flex; align-items: center;">Analisar</span>
                    <span class="display: inline-flex; align-items: center;" id="buttonSpinner" role="status" 
                        style="display: none; margin-left: 10px;">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                </button>
            
                <input type="file" id="fileInput" style="display:none;" accept=".sql" onchange="loadSQLFile(event)" />
            </div>
            
            
            <div id="loadingMessage" class="loading" style="display:none;">Processando...</div>

            <!-- Tabelas para cada seção, ocultas inicialmente -->
            <div id="definitions" style="display:none;">
                <h2>Definições de TABELA E ATRIBUTOS</h2>
                <table id="resultTable" class="table table-striped" style="display:none;">
                    <thead>
                        <tr><th>Schema</th><th>Tabela</th><th>Descrição Tabela</th><th>Atributo</th><th>Tipo</th><th>Comentário</th><th>Prefixo</th><th>Status</th></tr>
                    </thead>
                    <tbody id="resultBody"></tbody>
                </table>
                <h2>Definições de CONSTRAINTS</h2>
                    <table id="constraintTable" class="table table-striped" style="display:none;">
                        <thead>
                            <tr>                        
                                <th>Schema</th> <!-- Nova coluna -->
                                <th>Tabela</th> <!-- Nova coluna -->
                                <th>Nome Constraint</th> <!-- Nova coluna -->
                                <th>Tipo Constraint</th> <!-- Nova coluna -->
                                <th>Sufixo</th> <!-- Nova coluna -->
                                <th>Atributo(s) Chave</th> <!-- Nova coluna -->
                                <th class="status-column">Status</th>
                            </tr>
                        </thead>
                        <tbody id="constraintBody"></tbody>
                    </table>
                <h2>Definições de VIEW</h2>
                <table id="viewTable" class="table table-striped" style="display:none;">
                    <thead>
                        <tr><th>Schema</th><th>View</th><th>Descrição View</th><th>Coluna</th><th>Comentário</th><th>Prefixo</th><th>Status</th></tr>
                    </thead>
                    <tbody id="viewBody"></tbody>
                </table>
                <h2>Definições de ALTER</h2> 
                <table id="alterTable" class="table table-striped" style="display:none;">
                    <thead>
                        <tr><th>Alter</th>
                            <th>Schema</th>
                            <th>Tabela</th>
                            <th>Tabela Referência</th>
                            <th>Atributo/Constraint/Tabela</th>
                            <th>Tipo/Novo Atributo</th>
                            <th>Comando</th>
                            <th>Comentário</th>
                            <th>Prefixo/Sufixo</th>
                            <th>Status</th></tr>
                    </thead>
                    <tbody id="alterBody"></tbody>
                </table>
                <h2>Definições de SEQUENCE</h2>
                <table id="sequenceTable" class="table table-striped" style="display:none;">
                    <thead>
                        <tr>
                            <th>Schema</th>
                            <th>Sequence</th>
                            <th>Prefixo</th>  
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="sequenceBody"></tbody>
                </table>
                <h2>Definições de SYNONYM</h2>
                <table id="synonymTable" class="table table-striped" style="display:none;">
                    <thead>
                        <tr>
                            <th class="code-column">SYNONYM</th> <!-- Adicionando uma classe para alinhar o código -->
                            <th class="status-column-wide">Status</th> <!-- Definindo uma nova classe para aumentar a largura -->
                        </tr>
                    </thead>
                    <tbody id="synonymBody"></tbody>
                </table>

                <h2>Definições de INDEX</h2>
                <table id="indexTable" class="table table-striped" style="display:none;">
                    <thead>
                        <tr>
                            <th>INDEX</th>
                            <th>Schema</th> <!-- Nova coluna -->
                            <th>Tabela</th> <!-- Nova coluna -->
                            <th>Nome Indíce</th> <!-- Nova coluna -->
                            <th>Tipo Indíce</th> <!-- Nova coluna -->
                            <th class="status-column">Status</th>
                        </tr>
                    </thead>
                    <tbody id="indexBody"></tbody>
                </table>

                <h2>Definições de DROP</h2>
                <table id="dropTable" class="table table-striped" style="display:none;">
                    <thead><tr><th>DROP</th><th class="status-column">Status</th></tr></thead>
                    <tbody id="dropBody"></tbody>
                </table>

                <h2>Definições de GRANT</h2>
                <table id="grantTable" class="table table-striped" style="display:none;">
                    <thead><tr><th>GRANT</th><th class="status-column">Status</th></tr></thead>
                    <tbody id="grantBody"></tbody>
                </table>

                <h2>Definições de DELETE e TRUNCATE</h2>
                <table id="deleteTruncateTable" class="table table-striped" style="display:none;">
                    <thead><tr><th>Comando</th><th class="status-column">Status</th></tr></thead>
                    <tbody id="deleteTruncateBody"></tbody>
                </table>

                <h2>Definições de UPDATE</h2>
                <table id="updateTable" class="table table-striped" style="display:none;">
                    <thead><tr><th>UPDATE</th><th class="status-column">Status</th></tr></thead>
                    <tbody id="updateBody"></tbody>
                </table>

                <h2>Definições de INSERT</h2>
                <table id="insertTable" class="table table-striped" style="display:none;">
                    <thead><tr><th>INSERT</th><th class="status-column">Status</th></tr></thead>
                    <tbody id="insertBody"></tbody>
                </table>
                
            </div>
            <script>
                function parseDDL() {
                    const ddlText = document.getElementById("ddlInput").value.trim();
                    if (!ddlText) {
                        alert("Por favor, insira uma DDL para análise.");
                        return;
                    }
                    document.getElementById("loadingMessage").style.display = "block";
                    document.getElementById("buttonSpinner").style.display = "inline-block";
                    clearTables();

                    const ddlWithoutComments = removeComments(ddlText);

                    parseAllTables(ddlWithoutComments);
                    parseConstraints(ddlWithoutComments); // Chamada para processar constraints
                    parseAllViews(ddlWithoutComments); // Chamada para processar views
                    parseAlterTable(ddlWithoutComments);
                    parseSynonyms(ddlWithoutComments);
                    parseSequences(ddlWithoutComments);
                    parseIndexes(ddlWithoutComments);
                    parseDrops(ddlWithoutComments);
                    parseGrants(ddlWithoutComments);
                    parseDeleteAndTruncate(ddlWithoutComments);
                    parseUpdates(ddlWithoutComments);
                    parseInserts(ddlWithoutComments);

                    document.getElementById("resultTable").style.display = resultBody.innerHTML ? "table" : "none";
                    document.getElementById("constraintTable").style.display = constraintBody.innerHTML ? "table" : "none"; // Exibir tabela de constraints
                    document.getElementById("viewTable").style.display = viewBody.innerHTML ? "table" : "none"; // Exibir tabela de views
                    document.getElementById("alterTable").style.display = alterBody.innerHTML ? "table" : "none"; 
                    document.getElementById("synonymTable").style.display = synonymBody.innerHTML ? "table" : "none";
                    document.getElementById("indexTable").style.display = indexBody.innerHTML ? "table" : "none";
                    document.getElementById("dropTable").style.display = dropBody.innerHTML ? "table" : "none";
                    document.getElementById("grantTable").style.display = grantBody.innerHTML ? "table" : "none";
                    document.getElementById("deleteTruncateTable").style.display = deleteTruncateBody.innerHTML ? "table" : "none";
                    document.getElementById("updateTable").style.display = updateBody.innerHTML ? "table" : "none";
                    document.getElementById("insertTable").style.display = insertBody.innerHTML ? "table" : "none";

                    document.getElementById("definitions").style.display = "block";
                    document.getElementById("loadingMessage").style.display = "none";
                    document.getElementById("buttonSpinner").style.display = "none";
                }

                function clearTables() {
                    const tableIds = [
                        "resultBody", 
                        "viewBody", 
                        "synonymBody", 
                        "indexBody", 
                        "sequenceBody", 
                        "dropBody", 
                        "grantBody", 
                        "deleteTruncateBody", 
                        "updateBody", 
                        "insertBody", 
                        "alterBody",
                        "constraintBody" // Adicionando constraintBody se não estiver sendo limpo
                    ];
                    tableIds.forEach(id => document.getElementById(id).innerHTML = "");
                }

                // Função para remover comentários
                function removeComments(text) {
                    return text.split('\n').filter(line => !line.trim().startsWith('--')).join('\n');
                }

                // Nova função para mapear comentários da view
                function mapTableCommentsView(ddlText) {
                    const tableCommentsMap = {};
                    const tableCommentRegex = /COMMENT\s+ON\s+VIEW\s+(\w+\.\w+|\w+)\s+IS\s+['"](.*?)['"];/gi;
                    let match;
                    while ((match = tableCommentRegex.exec(ddlText)) !== null) {
                        const fullTableName = match[1].toUpperCase();
                        const tableComment = match[2];
                        tableCommentsMap[fullTableName] = tableComment;
                    }
                    return tableCommentsMap;
                }
                
                // Nova função para mapear comentários de tabela
                function mapTableComments(ddlText) {
                    const tableCommentsMap = {};
                    const tableCommentRegex = /COMMENT\s+ON\s+TABLE\s+("?\w+"?\."?\w+"?|"?\w+"?)\s+IS\s+['"](.*?)['"];/gi;
                    let match;
                    while ((match = tableCommentRegex.exec(ddlText)) !== null) {
                        const fullTableName = match[1].replace(/"/g, '').toUpperCase(); // Remove as aspas e converte para maiúsculas
                        const tableComment = match[2];
                        tableCommentsMap[fullTableName] = tableComment;
                    }
                    return tableCommentsMap;
                }


                // Função para capturar e processar todas as tabelas
                function parseAllTables(ddlText) {
                    const tableRegex = /CREATE\s+TABLE\s+(?:"?(\w+)"?\.)?"?(\w+)"?\s*\(\s*((?:[^\(\)]+|\([^)]*\))+)\)\s*(PCTFREE|TABLESPACE|STORAGE|PARTITION|PARALLEL|COMPRESS|SEGMENT\s+CREATION\s+IMMEDIATE\s+ORGANIZATION\s+EXTERNAL)?/gi;
                    const tableCommentsMap = mapTableComments(ddlText); // Captura comentários de tabela
                    let match;

                    while ((match = tableRegex.exec(ddlText)) !== null) {
                        const schema = match[1] ? match[1].replace(/"/g, '').toUpperCase() : ''; // Remove aspas e converte para maiúsculas
                        const tableName = match[2].replace(/"/g, '').toUpperCase(); // Remove aspas e converte para maiúsculas
                        const attributesText = match[3];
                        const isExternal = match[4]?.toUpperCase().includes("ORGANIZATION EXTERNAL"); // Verifica se a tabela é externa
                        const tableDescription = tableCommentsMap[`${schema}.${tableName}`] || tableCommentsMap[tableName] || ""; // Busca a descrição em caixa alta

                        // Adiciona uma linha em branco para separar tabelas com o nome
                        addBlankRowWithTableName(tableName);

                        // Processa os atributos da tabela
                        const commentsMap = mapComments(ddlText); // Mapeia comentários de atributos
                        processTableAttributes(attributesText, schema, tableName, tableDescription, commentsMap, isExternal, ddlText);
                    }

                    document.getElementById("resultTable").style.display = resultBody.innerHTML ? "table" : "none";
                }



                function mapComments(ddlText) {
                    const commentsMap = {};
                    const commentRegex = /COMMENT\s+ON\s+COLUMN\s+("?\w+"?\."?\w+"?\."?\w+"?)\s+IS\s+['"](.*?)['"];/gi;
                    let match;

                    while ((match = commentRegex.exec(ddlText)) !== null) {
                        const fullColumnName = match[1].replace(/"/g, '').toUpperCase(); // Remove aspas e converte para maiúsculas
                        const comment = match[2];
                        commentsMap[fullColumnName] = comment;
                    }

                    return commentsMap;
                }

                
                function processTableAttributes(attributesText, schema, tableName, tableDescription, commentsMap, isExternal, ddlText) {
                    const attributeRegex = /^\s*"?([\w]+)"?\s+([A-Z]+)/i;
                    const lines = attributesText.split(/\n|,/);

                    lines.forEach(line => {
                        const trimmedLine = line.trim();

                        if (/^(CONSTRAINT|PRIMARY KEY|USING INDEX|TABLESPACE)/i.test(trimmedLine)) {
                            return; // Ignorar linhas de constraints e outras declarações irrelevantes
                        }

                        const match = trimmedLine.match(attributeRegex);
                        if (match) {
                            const attribute = match[1];
                            const type = match[2].trim().toUpperCase();

                            // Cria o nome completo do atributo (schema.tableName.attribute) em maiúsculas
                            const fullColumnName = `${schema}.${tableName}.${attribute}`.toUpperCase();
                            const comment = commentsMap[fullColumnName] || ''; // Busca o comentário do atributo

                            // Adiciona a lógica existente para validações, prefixos e mensagens
                            addAttributeToTable(trimmedLine, schema, tableName, tableDescription, commentsMap, attributeRegex, isExternal, ddlText);
                        }
                    });
                }
                
                let sensitiveFields = {};

                // Função para carregar o JSON de campos sensíveis
                async function loadSensitiveFields() {
                    try {
                        const response = await fetch('data/lgpd.txt'); // Caminho do arquivo JSON
                        sensitiveFields = await response.json();
                        console.log("Campos sensíveis carregados com sucesso:", sensitiveFields);
                    } catch (error) {
                        console.error("Erro ao carregar os campos sensíveis:", error);
                    }
                }

                // Chama a função ao iniciar a página
                loadSensitiveFields();

                // Lista de padrões de prefixo
                let prefixList = {};

                async function loadPrefixList() {
                    try {
                        const response = await fetch('data/prefixo.txt'); // Caminho do arquivo JSON
                        prefixList = await response.json();
                        console.log("Lista de prefixos carregada com sucesso:", prefixList);
                    } catch (error) {
                        console.error("Erro ao carregar a lista de prefixos:", error);
                    }
                }

                // Chama a função ao iniciar a página
                loadPrefixList();

                    function checkPrimaryKey(tableName, ddlText) {
                        // Expressão regular para capturar PRIMARY KEY no comando CREATE TABLE
                        const createTablePKRegex = new RegExp(`CREATE\\s+TABLE\\s+.*?${tableName}\\s*\\(.*?(?:CONSTRAINT\\s+\\w+\\s+)?PRIMARY\\s+KEY\\s*\\(.*?\\)`,"si");

                        // Expressão regular para capturar PRIMARY KEY no comando ALTER TABLE
                        const alterTablePKRegex = new RegExp(
                            `ALTER\\s+TABLE\\s+.*?${tableName}\\s+ADD\\s+PRIMARY\\s+KEY\\s*\\(.*?\\)`,
                            "si"
                        );

                        // Verifica se a PRIMARY KEY está presente no CREATE TABLE ou ALTER TABLE
                        const hasPKInCreateTable = createTablePKRegex.test(ddlText);
                        const hasPKInAlterTable = alterTablePKRegex.test(ddlText);

                        // Retorna o status
                        if (hasPKInCreateTable || hasPKInAlterTable) {
                            return "";
                        } else {
                            return "⛔ A tabela não possui uma PRIMARY KEY definida.";
                        }
                    }

                    function addAttributeToTable(line, schema, tableName, tableDescription, commentsMap, attributeRegex, isExternal, ddlText) {
                        const cleanLine = line.split(',')[0];
                        const match = cleanLine.trim().match(attributeRegex);

                        const pkStatus = checkPrimaryKey(tableName, ddlText);

                        if (match) {
                            const attribute = match[1];
                            const type = match[2].trim().toUpperCase();
                            const fullColumnName = `${schema}.${tableName}.${attribute}`.toUpperCase(); // Nome completo em maiúsculas
                            const comment = commentsMap[fullColumnName] || ''; // Busca o comentário do atributo em maiúsculas

                            // Captura o prefixo do atributo
                            const prefix = attribute.includes('_') ? attribute.split('_')[0].toUpperCase() : '';

                            let statusMessages = [];

                            // Adiciona lógica específica para tabelas externas
                            if (isExternal) {
                                statusMessages.push("⚠️ Tabela externa: ausência de descrição não será criticada.");
                            } else {
                                if (!tableDescription) {
                                    statusMessages.push("⛔ A tabela deve ter uma descrição.");
                                }
                            }

                            // Verifica se o prefixo está nos padrões
                            let prefixComment = "";
                            if (!prefixList[prefix]) {
                                statusMessages.push("⛔ O prefixo não corresponde aos padrões");
                            } else {
                                prefixComment = prefixList[prefix].comentario; // Captura o comentário associado ao prefixo
                            }

                            if (!comment && !isExternal) {
                                statusMessages.push("⛔ A coluna deve ter uma descrição.");
                            }

                            if (schema.length === 0) {
                                statusMessages.push("⛔ Necessário definir o schema");
                            }

                            if(pkStatus.length !== 0){
                                statusMessages.push(pkStatus);
                            }

                            if (statusMessages.length === 0) {
                                statusMessages.push("✅");
                            }

                            

                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${schema}</td>
                                <td>${tableName}</td>
                                <td>${tableDescription}</td>
                                <td>${attribute}</td>
                                <td>${type}</td>
                                <td>${comment}</td>
                                <td title="${prefixComment}">${prefix}</td>
                                <td>${statusMessages.join("<br>")}</td>
                            `;
                            resultBody.appendChild(row);
                        }
                    }


                // Função parseAlterTable                 
                const oracleDataTypes = [
                "VARCHAR",
                "VARCHAR2",
                "CHAR",
                "NCHAR",
                "NVARCHAR2",
                "NUMBER",
                "DATE",
                "TIMESTAMP",
                "CLOB",
                "BLOB",
                "RAW",
                "LONG",
                "FLOAT",
                "XMLTYPE"
                ];    

                function parseAlterTable(ddlText) {
                    ddlText = ddlText.replace(/ALTER\s+TABLE\s+"?(\w+)"?\."?(\w+)"?/gi, 'ALTER TABLE $1.$2');
                    ddlText = ddlText.replace(/ADD\s+CONSTRAINT\s+"(\w+)"/gi, 'ADD CONSTRAINT $1');
                    const alterTableRegex = /ALTER\s+TABLE\s+[\s\S]+?;/gi;
                    let match;
                    
                    while ((match = alterTableRegex.exec(ddlText)) !== null) {
                        const content = match[0]; // Captura a declaração completa "ALTER TABLE ... ;"

                        // Extraindo schema e tableName da declaração ALTER TABLE
                        const tableNameMatch = content.match(/ALTER\s+TABLE\s+((\w+)\.)?(\w+)/i);

                        let schema = "", tableName = "";
                        let statusMessages = []; // Lista de mensagens de erro/aviso
                        let prefix = ""; // Prefixo da constraint (PK ou FK)
                        
                        if (tableNameMatch) {
                            schema = tableNameMatch[2] || ""; // Captura o schema (se presente)
                            tableName = tableNameMatch[3]; // Nome da tabela
                        }
                        
                        // Adiciona uma mensagem de erro se o schema estiver ausente
                        if (!schema) {
                            statusMessages.push("⛔ Necessário definir o schema");
                        }

                        // Identificar tipo de comando
                        let typeCommand = ""; // Tipo de comando padrão
                        if (/ADD\s+CONSTRAINT/i.test(content)) {
                            typeCommand = "ADD CONSTRAINT";
                        } else if (/ADD\s+\(/i.test(content) || /ADD\s+\w+\s+[\w\s,()]+/i.test(content)) {
                            typeCommand = "ADD";
                        } else if (/DROP\s+COLUMN/i.test(content)) {
                            typeCommand = "DROP COLUMN";
                        } else if (/DROP\s+\(/i.test(content)) {
                            typeCommand = "DROP (COLUMNS)";
                        } else if (/DROP\s+CONSTRAINT/i.test(content)) {
                            typeCommand = "DROP CONSTRAINT";
                        } else if (/MODIFY\s+\w+\s+INVISIBLE/i.test(content)) {
                            typeCommand = "INVISIBLE";
                        } else if (/MODIFY\s+\w+\s+VISIBLE/i.test(content)) {
                            typeCommand = "VISIBLE";
                        } else if (/MODIFY\s*\(/i.test(content) || /MODIFY\s+\w+\s+[\w\s,()]+/i.test(content)) { // Suporte para MODIFY
                            typeCommand = "MODIFY";
                        } else if (/RENAME\s+COLUMN/i.test(content)) {
                            typeCommand = "RENAME COLUMN";
                        } else if (/RENAME\s+TO/i.test(content)) {
                            typeCommand = "RENAME TO";
                        } else {
                            typeCommand = "UNKNOWN"; // Caso nenhum tipo seja identificado
                        }

                        // Processar ADD CONSTRAINT (PRIMARY KEY ou FOREIGN KEY)
                        if (typeCommand === "ADD CONSTRAINT") {
                            const constraintMatch = content.match(/ADD\s+CONSTRAINT\s+(\w+)\s+(PRIMARY KEY|FOREIGN KEY)\s+\(([\s\S]+?)\)/i);
                            if (constraintMatch) {
                                const constraintName = constraintMatch[1].toUpperCase(); // Nome da constraint em maiúsculas
                                let constraintType = constraintMatch[2];                        
                                const parts = constraintName.split("_");
                                let prefix = parts[parts.length - 1];
                                const columns = constraintMatch[3].split(',').map(col => col.trim()).join(', ');

                                // Inicializa tableNameChild como vazio
                                let tableNameChild = "";

                                // Validação do nome da constraint
                                if (constraintType === "PRIMARY KEY") {
                                    // Verifica se o nome da PRIMARY KEY segue o padrão esperado
                                    const expectedName = `${tableName.toUpperCase()}_PK`;
                                    
                                    constraintType = "ADD CONSTRAINT PRIMARY KEY"; 
                                    if (constraintName !== expectedName) {
                                        statusMessages.push(`⛔ Nome da constraint PRIMARY KEY inválido. Esperado: ${expectedName}`);
                                    }
                                                                

                                } else if (constraintType === "FOREIGN KEY") {
                                    // Capturar o nome da tabela de referência
                                    const referenceTableMatch = content.match(/REFERENCES\s+((\w+)\.)?(\w+)/i);
                                    if (referenceTableMatch) {
                                        tableNameChild = referenceTableMatch[3]; // Nome da tabela de referência (tabela pai)
                                    } else {
                                        statusMessages.push("⛔ Não foi possível identificar a tabela de referência para a FOREIGN KEY.");
                                    }

                                    // Validar o nome da FOREIGN KEY
                                    if (tableNameChild) {
                                        // Regex para validar o nome no formato correto
                                        const fkNameRegex = new RegExp(
                                            `^${tableNameChild.toUpperCase()}_${tableName.toUpperCase()}_\\d{2}_FK$`
                                        );

                                        if (!fkNameRegex.test(constraintName)) {
                                            statusMessages.push(
                                                `⛔ Nome da FOREIGN KEY inválido. Esperado: ${tableNameChild.toUpperCase()}_${tableName.toUpperCase()}_NN_FK (NN é um número com dois dígitos). Encontrado: ${constraintName}`
                                            );
                                        }
                                    } else {
                                        statusMessages.push("⛔ Não foi possível validar o nome da FOREIGN KEY sem identificar a tabela de referência.");
                                    }  
                                    constraintType="ADD CONSTRAINT FOREIGN KEY";
                                    prefix = "FK"; // Define o prefixo como FK
                                }

                                // Adiciona a linha para a tabela ALTER TABLE
                                addRowToAlterTable(
                                    content,
                                    schema,
                                    tableName,
                                    tableNameChild, // Para PRIMARY KEY, será vazio
                                    constraintName, // Nome da constraint como atributo
                                    "", // Adiciona o prefixo PK ou FK
                                    constraintType,
                                    "",
                                    prefix,
                                    statusMessages.join("<br>")
                                );
                            }
                        }else if (typeCommand === "ADD" || typeCommand === "MODIFY") {
                            
                            // Verificar se é ADD/MODIFY com múltiplas colunas entre parênteses
                            const multipleMatch = content.match(/(?:ADD|MODIFY)\s+\(([\s\S]+)\)/i);
                            if (multipleMatch) {                        
                                const columnsText = multipleMatch[1].trim();
                                const columns = columnsText.split(','); // Divide as colunas em partes

                                columns.forEach(columnText => {
                                    const columnMatch = columnText.trim().match(/(\w+)\s+([\w\s,()]+)/i); // Captura nome e tipo de cada coluna
                                    if (columnMatch) {                                  
                                        processAddColumn(schema, tableName, columnMatch[1], columnMatch[2], statusMessages, content, typeCommand);
                                    }
                                });
                            } else {
                                
                                // Verificar se é ADD/MODIFY com uma única coluna sem parênteses
                                const singleMatch = content.match(/(?:ADD|MODIFY)\s+(\w+)\s+([\w\s,()]+)/i);
                                if (singleMatch) {
                                    
                                    if(typeCommand === "ADD" ){
                                        typeCommand = "ADD PRIMARY KEY"
                                    } 
                                                            
                                    processAddColumn(schema, tableName, singleMatch[1], singleMatch[2], statusMessages, content, typeCommand);
                                }
                            }
                        } else if (typeCommand === "DROP COLUMN" || typeCommand === "DROP (COLUMNS)") {
                            const dropMatch = content.match(/COLUMN\s+(\w+)/i) || content.match(/\(([\w\s,]+)\)/i);
                            if (dropMatch) {
                                let attribute = dropMatch[1]; // Nome da coluna ou colunas
                                addRowToAlterTable(content, schema, tableName,"", attribute, "", typeCommand, "", "", "", "");
                            }
                        
                        } else if (typeCommand === "RENAME COLUMN") {
                            const renameMatch = content.match(/RENAME\s+COLUMN\s+(\w+)\s+TO\s+(\w+)/i);
                            if (renameMatch) {
                                const oldColumnName = renameMatch[1]; // Nome antigo da coluna
                                const newColumnName = renameMatch[2]; // Novo nome da coluna (após o TO)

                                // Verificar se o novo nome segue os padrões de prefixo esperados
                                const prefix = newColumnName.includes("_") 
                                    ? newColumnName.split("_")[0] // Pega a parte antes do _
                                    : newColumnName; // Pega o nome inteiro se não houver _

                                // Exemplo de validação para prefixo (adapte conforme sua regra)
                                if (prefix.length > 3) {
                                    statusMessages.push(`⛔ O prefixo "${prefix}" no novo nome "${newColumnName}" é inválido. Deve ter 3 caracteres.`);
                                }

                                // Adiciona o comando na tabela com as mensagens de erro
                                addRowToAlterTable(
                                    content,
                                    schema,
                                    tableName,
                                    "",
                                    oldColumnName, // Novo nome da coluna                            
                                    newColumnName, // Nome antigo da coluna (como atributo relacionado)
                                    typeCommand,
                                    "",
                                    prefix,
                                    statusMessages.join("<br>") // Mensagens de erro/aviso
                                );
                            }
                        }
                    }

                    // Função para processar colunas adicionadas ou modificadas
                    function processAddColumn(schema, tableName, attribute, fullType, statusMessages, content, typeCommand) {
                        let type = (fullType.split(/\(/)[0]).trim().toUpperCase(); // Extrai o tipo básico antes do parêntese
                        let prefix = attribute.split('_')[0].toUpperCase();     
                        attribute = prefix;
                        let prefixComment = "";
                        let comment = "";
                        let localStatusMessages = [...statusMessages]; // Copiar as mensagens globais

                        
                        // Críticas de prefixo
                        if (!prefixList[prefix]) {
                            localStatusMessages.push("⛔ O prefixo não corresponde aos padrões");
                        } else if (![prefixList[prefix].tipo].flat().includes(type.toUpperCase())) {
                            localStatusMessages.push(`⛔ O tipo '${type}' não corresponde ao tipo esperado '${prefixList[prefix].tipo}' para o prefixo '${prefix}'`);
                        } else {
                            prefixComment = prefixList[prefix].comentario; // Comentário do prefixo se estiver correto
                        }

                        // Verificar o comentário associado ao atributo
                        const commentRegexWithSchema = new RegExp(`COMMENT\\s+ON\\s+COLUMN\\s+${schema}\\.${tableName}\\.${attribute}\\s+IS\\s+['"](.*?)['"];`, 'i');
                        const commentRegexWithoutSchema = new RegExp(`COMMENT\\s+ON\\s+COLUMN\\s+${tableName}\\.${attribute}\\s+IS\\s+['"](.*?)['"];`, 'i');
                        const commentMatch = schema ? commentRegexWithSchema.exec(ddlText) : commentRegexWithoutSchema.exec(ddlText);

                        if (commentMatch) {
                            comment = commentMatch[1]; // Captura o comentário associado
                        } else if (typeCommand !== "MODIFY") { 
                            localStatusMessages.push("⛔ Coluna adicionada sem comentário");
                        }

                        // Verificar se o atributo é sensível (LGPD)
                        const upperCaseAttribute = attribute.toUpperCase();
                        const matchedField = Object.keys(sensitiveFields).find(field => upperCaseAttribute.includes(field));

                        if (matchedField) {
                            const classification = sensitiveFields[matchedField];

                            // Verificar se o comentário contém uma marcação LGPD válida
                            const lgpdTagRegex = /#LGPD#(.*?)#(.*?)#/i;
                            if (comment) {
                                const lgpdMatch = comment.match(lgpdTagRegex);
                                if (!lgpdMatch) {
                                    localStatusMessages.push(`⛔ Necessário adicionar marcação LGPD no comentário - Classificação: ${classification}`);
                                }
                            } else {
                                localStatusMessages.push(`⛔ O atributo deve ter uma descrição com marcação LGPD - Classificação: ${classification}`);
                            }
                        }
                        // Limpa mensagem de erro de se for ADD PRIMARY KEY
                        if (typeCommand.toUpperCase() ==='ADD PRIMARY KEY' ) {                    
                            localStatusMessages = []; // Limpa mensagens de erro para esta condição
                            const singleMatchContent = fullType; // Conteúdo de singleMatch[2]
                            const keyColumnsMatch = singleMatchContent.match(/KEY\s*\(\s*([^)]+?)\s*\)/i);
                            
                            let keyColumns = null; // Variável para armazenar o conteúdo capturado
                            if (keyColumnsMatch) {
                                keyColumns = keyColumnsMatch[1].trim(); // Captura o conteúdo entre os parênteses e remove espaços extras
                            }
                            attribute = keyColumns;
                        }
                        // Adicionar a linha para a coluna
                        const status = localStatusMessages.join("<br>");
                        addRowToAlterTable(content, schema, tableName,"", attribute, type, typeCommand, comment, prefix, status, prefixComment);
                    }
                }

                // Função auxiliar para adicionar linha na tabela alterTable com tooltip para o prefixo
                function addRowToAlterTable(alter, schema, tableName,tableNameChild, attribute, type, typeCommand, comment, prefix, status, prefixComment) {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td><pre class="line-numbers"><code class="language-sql">${alter}</code></pre></td> <!-- Formatação consistente com SYNONYM e INDEX -->
                        <td>${schema}</td>
                        <td>${tableName}</td>
                        <td>${tableNameChild}</td>
                        <td>${attribute}</td>
                        <td>${type}</td>
                        <td>${typeCommand}</td>
                        <td>${comment}</td>
                        <td title="${prefixComment || ''}">${prefix}</td> <!-- Adiciona o tooltip com a descrição do prefixo -->
                        <td>${status}</td>`
                    ;
                    alterBody.appendChild(row);
                }

                function parseSequences(ddlText) {
                    const sequenceRegex = /CREATE\s+SEQUENCE\s+(?:"?(\w+)"?\.)?"?(\w+)"?/gi;
                    let match;

                    while ((match = sequenceRegex.exec(ddlText)) !== null) {
                        const schema = match[1] || "";
                        const sequenceName = match[2];
                        
                        // Extrair o prefixo da direita para a esquerda até o primeiro '_'
                        const prefixMatch = sequenceName.match(/([^_]+)$/);
                        const prefix = prefixMatch ? prefixMatch[1] : "N/A";
                        
                        // Inicializar status e empilhar mensagens de erro conforme as condições
                        let status = " ⚠️ Verifique se o nome da sequence corresponde a uma tabela ex: {NOME_TABELA_SEQ}";
                        if (!schema) {
                            status += "⛔ Necessário definir o schema<br>";
                        }
                        if (prefix !== "SEQ") {
                            status += `⛔ A tabela '${prefix}' deve ser ter o sufixo SEQ<br>`;
                        }
                        if (sequenceName.length > 30) {
                            status += "⛔ Nome da sequence excede 30 caracteres<br>";
                        }

                        // Se não houver erros, definir o status como sinal verde
                        if (!status) {
                            status = "";
                        } else {
                            // Remover a última quebra de linha extra, se houver
                            status = status.replace(/<br>$/, "");
                        }

                        addRowToSequenceTable(sequenceBody, schema, sequenceName, prefix, status);
                    }

                    sequenceTable.style.display = sequenceBody.innerHTML ? "table" : "none";
                }

                function addRowToSequenceTable(tableBody, schema, sequenceName, prefix, status) {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${schema}</td>
                        <td>${sequenceName}</td>
                        <td>${prefix}</td>
                        <td>${status}</td>
                    `;
                    tableBody.appendChild(row);
                }

                // Funcoes para VIEW
                function parseAllViews(ddlText) {
                    const viewRegex = /^CREATE\s+(?:OR\s+REPLACE\s+)?(?:MATERIALIZED\s+)?VIEW\s+(?:"?(\w+)"?\.)?"?(\w+)"?\s*(?:NOLOGGING\s*)?(?:PARALLEL\s*\(\s*DEGREE\s+\w+\s*\)\s*)?(?:BUILD\s+\w+\s*)?(?:USING\s+INDEX\s*)?(?:REFRESH\s+\w+\s+ON\s+\w+\s*)?\s+AS\s+SELECT\s+([\s\S]+?)\s+FROM\s+([a-zA-Z0-9_]+(?:\.[a-zA-Z0-9_]+)?)/gim;
                    const viewCommentsMap = mapTableCommentsView(ddlText);
                    let match;

                    while ((match = viewRegex.exec(ddlText)) !== null) {
                        let schema = match[1] || ""; // Captura o schema da view
                        const viewName = match[2] || "NOME_AUSENTE"; // Nome da view ou placeholder se ausente
                        const attributesText = match[3]; // Texto do SELECT para atributos
                        let fromClause = match[4]; // Captura apenas o nome da tabela ou `schema.nome_tabela`

                        // Verifica se `fromClause` inclui um schema; caso contrário, usa o schema da view
                        if (!fromClause.includes(".")) {
                            fromClause = `${schema}.${fromClause}`; // Adiciona o schema da view ao nome da tabela
                        }

                        const fullViewName = schema ? `${schema}.${viewName}` : viewName;
                        const viewDescription = viewCommentsMap[fullViewName] || ""; // Obtém o comentário da view, se disponível

                        // Adiciona linha em branco com o nome da view
                        addBlankRowWithViewName(viewName);

                        // Extrai os comentários para os atributos
                        const commentsMap = mapComments(ddlText);
                        processViewAttributes(attributesText, schema, viewName, viewDescription, commentsMap);
                    }

                    // Exibe a tabela de views se houver conteúdo
                    document.getElementById("viewTable").style.display = viewBody.innerHTML ? "table" : "none";
                }


                function processViewAttributes(attributesText, schema, viewName, viewDescription, commentsMap) {
                    const attributeRegex = /^\s*,?\s*"?(\w+(?:\.\w+)?|\w+\(.+\))"?(?:\s+AS\s+"?(\w+)"?|"?\s+(\w+)"?)?(?:\s+([A-Z]+(?:\(\d+(?:,\d+)?\))?))?/i;

                    const lines = [];
                    let currentLine = '';
                    let openParens = 0;

                    attributesText.split('\n').forEach(line => {
                        line.split(',').forEach(part => {
                            openParens += (part.match(/\(/g) || []).length;
                            openParens -= (part.match(/\)/g) || []).length;

                            if (openParens > 0) {
                                currentLine += (currentLine ? ',' : '') + part.trim();
                            } else {
                                currentLine += (currentLine ? ',' : '') + part.trim();
                                lines.push(currentLine);
                                currentLine = '';
                            }
                        });
                    });

                    lines.forEach(line => {
                        const trimmedLine = line.trim();
                        
                        if (/^(CONSTRAINT|PRIMARY KEY|USING INDEX|TABLESPACE)/i.test(trimmedLine)) {
                            return;
                        }
                        addAttributeToView(trimmedLine, schema, viewName, viewDescription, commentsMap, attributeRegex);
                    });
                }

                function showMessage(text) {
                    alert(text);
                }

                function parseConstraints(ddlText) {            
                    const tableRegex = /CREATE\s+TABLE\s+"?(\w+)"?\."?(\w+)"?\s*\(\s*((?:[^\(\)]+|\([^)]*\))+)\)\s*(PCTFREE|TABLESPACE|STORAGE|PARTITION|PARALLEL|COMPRESS|SEGMENT\s+CREATION\s+IMMEDIATE\s+ORGANIZATION\s+EXTERNAL)?/gi;
                    let match;
                    

                    while ((match = tableRegex.exec(ddlText)) !== null) {
                        console.log("Tabela encontrada:", match); // Depuração
                        const schema = match[1] || ""; // Schema (opcional)
                        const tableName = match[2]; // Nome da tabela
                        const tableBody = match[3]; // Conteúdo entre parênteses da tabela

                        // Regex para capturar constraints (permitindo quebras de linha nas colunas)
                        const constraintRegex = /CONSTRAINT\s+"?(\w+)"?\s+(PRIMARY\s+KEY|FOREIGN\s+KEY|UNIQUE|CHECK)\s*\(([\s\S]*?)\)/gi;
                        let constraintMatch;

                        while ((constraintMatch = constraintRegex.exec(tableBody)) !== null) {
                            
                            const constraintName = constraintMatch[1]; // Nome da constraint
                            const constraintType = constraintMatch[2]; // Tipo da constraint
                            const constraintColumns = constraintMatch[3].replace(/\s+/g, " ").trim(); // Colunas da constraint (remover quebras de linha e espaços extras)
                            const suffix = constraintName.split("_").pop().toUpperCase(); // Obtém o sufixo da constraint

                            let statusMessages = [];

                            // Regras para PRIMARY KEY
                            if (constraintType.toUpperCase() === "PRIMARY KEY") {
                                const expectedPKName = `${tableName}_PK`.toUpperCase(); // Nome esperado, em maiúsculas
                                if (suffix !== "PK") {
                                    statusMessages.push("⛔ O sufixo de PRIMARY KEY deve ser 'PK'.");
                                }
                                if (constraintName.toUpperCase() !== expectedPKName) { // Comparação em maiúsculas
                                    statusMessages.push(`⛔ O nome da constraint de PRIMARY KEY deve ser '${expectedPKName}'.`);
                                }
                            }

                            // Regras para FOREIGN KEY
                            if (constraintType.toUpperCase() === "FOREIGN KEY") {
                                if (!["FK", "SK"].includes(suffix)) {
                                    statusMessages.push("⛔ O sufixo de FOREIGN KEY deve ser 'FK' ou 'SK'.");
                                }
                            }

                            // Validação geral do sufixo
                            if (!["PK", "FK", "SK", "UK", "CK"].includes(suffix)) {
                                statusMessages.push("⛔ Sufixo da constraint não corresponde ao padrão esperado.");
                            }

                            // Adiciona um status OK se não houver erros
                            if (statusMessages.length === 0) {
                                statusMessages.push("✅");
                            }

                            // Adiciona a linha à tabela de constraints
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${schema}</td>
                                <td>${tableName}</td>
                                <td>${constraintName}</td>
                                <td>${constraintType}</td>
                                <td>${suffix}</td>
                                <td>${constraintColumns}</td> <!-- Nova coluna Campo Chave -->
                                <td>${statusMessages.join("<br>")}</td>
                            `;
                            constraintBody.appendChild(row);
                        }
                    }

                    // Exibe a tabela de constraints se houver conteúdo
                    document.getElementById("constraintTable").style.display = constraintBody.innerHTML ? "table" : "none";
                }



                function addAttributeToView(line, schema, viewName, viewDescription, commentsMap, attributeRegex) {
                    const cleanLine = line.split(',')[0]; // Remove o que vier após uma vírgula (agora apenas vírgulas externas)
                    const match = cleanLine.trim().match(attributeRegex); // Aplica o regex na linha limpa
                    let statusMessages = [];
                    
                    if (!schema) {                    
                        statusMessages.push(`⛔ Necessário definir o schema`);
                    }

                    if (!viewDescription) {
                        statusMessages.push("⛔ A view deve ter uma descrição.");
                    }

                    if (match) {
                        // Captura o alias se presente, senão o nome da coluna ou expressão completa
                        let attribute;
                        if (match[2]) { // Caso com `AS alias`
                            attribute = match[2];
                        } else if (match[3]) { // Caso com `alias` sem `AS`
                            attribute = match[3];
                        } else { // Caso sem alias
                            attribute = match[1];
                        }

                        // Remove o prefixo da tabela se estiver presente (ex: "c.coluna" se torna "coluna")
                        if (!attribute.includes("(")) { // Preserva expressões como `nvl(...)` se não houver alias
                            attribute = attribute.includes('.') ? attribute.split('.')[1] : attribute;
                        }

                        const type = match[4] || ''; // Tipo de dado, se presente
                        const comment = commentsMap[attribute] || ''; // Comentário do atributo, se presente

                        // Captura o prefixo a partir do nome do atributo
                        const prefix = attribute.includes('_') ? attribute.split('_')[0].toUpperCase() : '';

                        let prefixComment = "";
                        if (!prefixList[prefix]) {
                            statusMessages.push("⛔ O prefixo não corresponde aos padrões");
                        } else {
                            prefixComment = prefixList[prefix].comentario;
                        }

                        if (!comment) {
                            statusMessages.push("⚠️ A coluna poderia ter uma descrição.");
                        }

                        // Cria a linha da tabela
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${schema}</td>
                            <td>${viewName}</td>
                            <td>${viewDescription}</td>
                            <td>${attribute}</td>                    
                            <td>${comment}</td>
                            <td title="${prefixComment}">${prefix}</td>
                            <td>${statusMessages.join("<br>")}</td>`;
                        viewBody.appendChild(row);
                    }
                }



                function addBlankRowWithViewName(viewName) {
                    const blankRow = document.createElement("tr");
                    blankRow.classList.add("blank-row");
                    blankRow.innerHTML = `<td colspan="8" style="text-align: center; font-weight: bold; padding: 15px; color: #1c5243; background-color: #ffffff; border-bottom: 2px solid black;">${viewName}</td>`;
                    viewBody.appendChild(blankRow);
                }
                
                // Funcoes para SYNONYM
                function parseSynonyms(ddlText) {
                    const synonymRegex = /\bCREATE\s+(?:OR\s+REPLACE\s+)?(?:PUBLIC\s+)?SYNONYM\s+(\w+\.\w+|\w+)\s+FOR\s+(\w+\.\w+|\w+);/gi;
                    let match;

                    while ((match = synonymRegex.exec(ddlText)) !== null) {
                        const content = match[0]; // Captura a declaração completa do SYNONYM

                        // Verifica se "PUBLIC" aparece antes de "SYNONYM"
                        const isPublic = /PUBLIC\s+SYNONYM/i.test(content);

                        // Define a mensagem de status com base na presença de "PUBLIC"
                        let status = isPublic ? "⛔ Não é permitido SYNONYM público" : "⚠️";

                        // Adiciona a linha na tabela com o conteúdo e o status
                        addRowWithStatus(synonymBody, content, status);
                    }

                    // Exibir a tabela de SYNONYM se houver conteúdo
                    synonymTable.style.display = synonymBody.innerHTML ? "table" : "none";
                }

                // Funcoes para INDICES        
                function parseIndexes(ddlText) {
                    // Captura o código completo do índice
                    const indexRegex = /CREATE\s+(?:.*?\s+)?INDEX\s+[\s\S]+?;/gi;

                    // Captura o nome do índice e o nome da tabela            
                    const indexRegex1 = /CREATE\s+(?:.*?\s+)?INDEX\s+([^\s]+)\s+ON\s+([^\s]+)\s*\([^)]*\)\s*;?/i;

                    let match;
                    while ((match = indexRegex.exec(ddlText)) !== null) {
                        const fullIndexStatement = match[0]; // Captura a declaração completa do índice

                        // Executa a segunda regex no código completo para extrair o nome do índice e da tabela
                        const innerMatch = indexRegex1.exec(fullIndexStatement);
                        if (innerMatch) {
                            let indexName = innerMatch[1].replace(/[^a-zA-Z0-9._]/g, ''); // Nome do índice
                            let tableName = innerMatch[2].replace(/[^a-zA-Z0-9._]/g, ''); // Nome da tabela

                            // Remover aspas duplas do schema, tabela e nome do índice
                            indexName = indexName.replace(/"/g, '').toUpperCase();
                            tableName = tableName.replace(/"/g, '').toUpperCase();

                            let schemaNameIdx, NomeIndex;
                            let schemaName, tableNameIdx;

                            if (indexName.includes('.')) {
                                [schemaNameIdx, NomeIndex] = indexName.split('.').map(part => part ? part.replace(/"/g, '') : null);
                            } else {
                                schemaNameIdx = indexName.replace(/"/g, '');
                                NomeIndex = indexName.replace(/"/g, '');
                            }

                            if (tableName.includes('.')) {
                                [schemaName, tableNameIdx] = tableName.split('.').map(part => part ? part.replace(/"/g, '') : null);
                            } else {
                                schemaName = '';
                                tableNameIdx = tableName.replace(/"/g, '');
                            }

                            // Inicializa status
                            let status = "";

                            // Extrai o tipo de índice com base no nome ou na definição
                            let tipoIndice = "Indefinido";

                            // Verificação pelo nome do índice
                            if (NomeIndex.toUpperCase().includes("_PK")) {
                                tipoIndice = "PK";
                            } else if (NomeIndex.toUpperCase().includes("_UK")) {
                                tipoIndice = "UK";
                            } else {
                                // Verificação pela definição no código completo
                                if (fullIndexStatement.toUpperCase().includes("UNIQUE")) {
                                    tipoIndice = "UK";
                                } else if (fullIndexStatement.toUpperCase().includes("BITMAP")) {
                                    tipoIndice = "BT";
                                } else if (["AK", "GAK", "GIX", "GBT", "IX"].some(t => NomeIndex.toUpperCase().includes(`_${t}_`))) {
                                    tipoIndice = NomeIndex.toUpperCase().match(/_(AK|GAK|GIX|GBT|IX)_/)[1];
                                }
                            }
                            
                            if (tipoIndice === "Indefinido") {
                                const parts = tableName.split("."); // Divide pelo caractere "."
                                const tableNameRight = parts[parts.length - 1]; // Obtém a parte à direita
                                const expectedNameIX = `${tableNameRight.toUpperCase()}_IX_NN`;

                            // Validação adicional para o padrão esperado
                                const expectedPattern = new RegExp(`^${tableNameRight.toUpperCase()}_IX_\\d+$`); // Regex para validar o padrão
                                if (!expectedPattern.test(NomeIndex.toUpperCase())) {
                                    status += `⛔ Nome do índice não está no padrão. Esperado algo como ${tableNameRight.toUpperCase()}_IX_01, ${tableNameRight.toUpperCase()}_IX_02, etc.<br>`;
                                }
                            }
                                            
                            // Extrai o prefixo do índice (parte do nome antes do tipo e número)
                            const prefixoIndice = tipoIndice === "Indefinido"
                                ? NomeIndex
                                : NomeIndex.split(`_${tipoIndice}`)[0];

                            // Verificação de correspondência entre o prefixo do índice e o nome da tabela
                            if (prefixoIndice !== tableNameIdx) {
                                const parts = tableName.split(".");
                                const tableNameRight = parts[parts.length - 1]; // Obtém a parte à direita

                                if (tipoIndice === "Indefinido") {
                                    tipoIndice = "IX";
                                }

                                // Ajusta o padrão esperado de acordo com o tipo do índice
                                const expectedNameIdx = `${tableNameRight.toUpperCase()}_${tipoIndice}_NN`;
                                const expectedPattern = new RegExp(`^${tableNameRight.toUpperCase()}_${tipoIndice}_\\d+$`); // Regex para validar o padrão

                                // Valida se o nome do índice segue o padrão esperado
                                if (!expectedPattern.test(NomeIndex)) {
                                    status += `⛔ Nome do índice não está no padrão. Esperado algo como ${expectedNameIdx.replace('_NN', '_01')}, ${expectedNameIdx.replace('_NN', '_02')}, etc.<br>`;
                                }
                            }

                            // Outras mensagens de erro
                            status += schemaName ? "" : "⛔ Necessário definir o schema<br>";
                            status += tableNameIdx ? "" : "⛔ Nome da tabela está vazio<br>";
                            status += indexName ? "" : "⛔ Necessário definir nome do índice<br>";

                            // Remove qualquer <br> extra no final e define "⚠️" se não houver erros
                            status = status.replace(/<br>$/, "") || "⚠️";

                            addRowWithIndexName(indexBody, fullIndexStatement, NomeIndex, tableNameIdx, tipoIndice, status, schemaName);
                        }
                    }
                    indexTable.style.display = indexBody.innerHTML ? "table" : "none";
                }

                // Função para adicionar a linha com o nome do índice, o nome da tabela, tipo de índice e o código completo do índice
                function addRowWithIndexName(tableBody, content, indexName, tableName, tipoIndice, status, schemaName) {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td><pre class="line-numbers"><code class="language-sql">${content}</code></pre></td>        
                        <td>${schemaName}</td>
                        <td>${tableName}</td>
                        <td>${indexName}</td>
                        <td>${tipoIndice}</td> <!-- Coluna do Tipo Índice com o indicador extraído do nome -->
                        <td class="status-column-idx">${status}</td> <!-- Coluna de Status com erros e alertas -->
                    `;
                    tableBody.appendChild(row);
                }

                // Função para capturar drops
                function parseDrops(ddlText) {
                    const dropRegex = /DROP\s+(TABLE|TRIGGER|INDEX|SEQUENCE|VIEW|MATERIALIZED\s+VIEW)\s+[\w\s.]+\s*;/gi;
                    let match;
                    
                    while ((match = dropRegex.exec(ddlText)) !== null) {
                        const status = "⚠️";
                        addRowWithStatus(dropBody, match[0], status);
                    }
                    
                    dropTable.style.display = dropBody.innerHTML ? "table" : "none";
                }

                // Função para capturar grants
                function parseGrants(ddlText) {
                    const grantRevokeRegex = /(GRANT|REVOKE)\s+[\s\S]+?;/gi;
                    let match;

                    while ((match = grantRevokeRegex.exec(ddlText)) !== null) {
                        const command = match[0];

                        // Lista de palavras-chave que devem gerar alertas
                        const alertKeywords = [
                            "INSERT", "UPDATE", "DELETE", "ALTER", "EXECUTE", "DROP", "WRITE", "ALL", 
                            "REVOKE", "INDEX", "REFERENCES", "READ", "COMMIT REFRESH", 
                            "QUERY REWRITE", "DEBUG", "FLASHBACK"
                        ];

                        // Verifica se o comando contém alguma das palavras-chave de alerta
                        const isAlert = alertKeywords.some(keyword => new RegExp(`\\b${keyword}\\b`, "i").test(command));
                        const status = isAlert ? "⚠️" : "";

                        addRowWithStatus(grantBody, command, status);
                    }

                    // Exibe a tabela de GRANTs se houver conteúdo
                    grantTable.style.display = grantBody.innerHTML ? "table" : "none";
                }

                // Função para capturar delete e truncate
                function parseDeleteAndTruncate(ddlText) {
                    const deleteTruncateRegex = /(DELETE\s+FROM\s[\s\S]+?;|TRUNCATE\s+TABLE\s+[\s\S]+?;)/gi;
                    let match;
                    while ((match = deleteTruncateRegex.exec(ddlText)) !== null) {
                        const command = match[0];
                        let status = "⚠️";

                        // Verifica se é um comando DELETE sem WHERE
                        if (/DELETE\s+FROM\s[\s\S]+?;/i.test(command) && !/WHERE\s+/i.test(command)) {
                            status = "⛔ Comando DELETE sem WHERE: é necessário incluir uma cláusula WHERE";
                        }

                        addRowWithStatus(deleteTruncateBody, command, status);
                    }
                    deleteTruncateTable.style.display = deleteTruncateBody.innerHTML ? "table" : "none";
                }

                // Função para capturar updates com diferentes variações
                function parseUpdates(ddlText) {
                    const updateRegex = /UPDATE\s+[\s\S]+?SET\s+[\s\S]+?(?:WHERE\s+[\s\S]+?)?(?:RETURNING\s+[\s\S]+?INTO\s+[\s\S]+?)?;/gi;
                    let match;
                    while ((match = updateRegex.exec(ddlText)) !== null) {
                        const status = "⚠️";
                        addRowWithStatus(updateBody, match[0], status);
                    }
                    updateTable.style.display = updateBody.innerHTML ? "table" : "none";
                }  

                // Função para capturar inserts com valores ou com SELECT
                function parseInserts(ddlText) {
                    const insertRegex = /INSERT\s+INTO\s+[\s\S]+?(VALUES\s*\([\s\S]+?\)|SELECT\s+[\s\S]+?);/gi;
                    let match;
                    while ((match = insertRegex.exec(ddlText)) !== null) {
                        const status = "⚠️";
                        addRowWithStatus(insertBody, match[0], status);
                    }
                    insertTable.style.display = insertBody.innerHTML ? "table" : "none";
                }

                // Funções auxiliares para adicionar linhas com ou sem status
                function addRowToTable(tableBody, content) {
                    const row = document.createElement("tr");
                    row.innerHTML = `<td><pre class="line-numbers"><code class="language-sql">${content}</code></pre></td>`;
                    tableBody.appendChild(row);
                }

                function addRowWithStatus(tableBody, content, status) {
                    const row = document.createElement("tr");
                    row.innerHTML = `<td><pre class="line-numbers"><code class="language-sql">${content}</code></pre></td><td>${status}</td>`;
                    tableBody.appendChild(row);
                }

                function addBlankRowWithTableName(tableName) {
                    const blankRow = document.createElement("tr");
                    blankRow.classList.add("blank-row");
                    blankRow.innerHTML = `<td colspan="8" style="text-align: center; font-weight: bold; padding: 15px; color: #1c5243; background-color: #ffffff; border-bottom: 2px solid black;">${tableName}</td>`;
                    resultBody.appendChild(blankRow);
                }
                function replaceSingleSlashWithSemicolon(text) {
                    // Substituir linhas que contêm apenas uma barra "/" por ";"
                    return text.replace(/^\s*\/\s*$/gm, ";");
                }

                function cleanSQLInput() {
                    let input = document.getElementById("ddlInput").value;

                    // Substituir barras solitárias "/" por ponto e vírgula ";"
                    input = replaceSingleSlashWithSemicolon(input);

                    // Remover aspas de schema, tabela e coluna em comentários
                    input = input.replace(/COMMENT\s+ON\s+COLUMN\s+"?(\w+)"?\."?(\w+)"?\."?(\w+)"?\s+IS\s+['"](.*?)['"];/gi, 
                                        (match, schema, table, column, comment) => {
                        return `COMMENT ON COLUMN ${schema}.${table}.${column} IS '${comment}';`;
                    });

                    // Remover espaços antes de ponto e vírgula
                    input = input.replace(/\s*;/g, ';');  // Remove qualquer espaço antes de ";"
                    
                    // Reduz múltiplas linhas em branco para uma única linha em branco
                    input = input.replace(/(\n\s*){2,}/g, '\n\n');

                    // Atualiza o campo textarea com o texto limpo
                    document.getElementById("ddlInput").value = input;

                    // Chama o destaque para garantir que o texto formatado seja atualizado
                    updateHighlighting();
                }


                function updateHighlighting() {
                    const ddlInput = document.getElementById("ddlInput").value;
                    const highlightedSQL = document.getElementById("highlightedSQL");
                    highlightedSQL.textContent = ddlInput; // Atualiza o conteúdo

                    // Reprocessa o código para aplicar a formatação e a numeração de linhas com Prism.js
                    Prism.highlightElement(highlightedSQL);
                }

                function loadSQLFile(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('ddlInput').value = e.target.result;
                        updateHighlighting(); // Atualiza a formatação
                    };
                    reader.readAsText(file);
                }

            
            }
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
