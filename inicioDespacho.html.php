<?php
include_once("head.html.php");
include_once("COcorrencia.php");

// Define Nome e Id do Usuário
session_start();

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== 'ok') {
    // Redireciona para a página de login se não estiver autenticado
    header("Location: login.html.php");
    exit();
}

$nomeUsuario = $_SESSION["login_nome"];
$idUsuario = $_SESSION["login_id"];
$cargoUsuario = $_SESSION["login_cargo"];

date_default_timezone_set('America/Sao_Paulo');
$dataHoraAtual = date('Y-m-d\TH:i');

// Instancia a classe de Ocorrências
$ocorrencia = new COcorrencia();

// Obtem todas as ocorrências separadas por cargo, como objeto mysqli_result
$resultadoFetch = $ocorrencia->selecionarPorCargo($cargoUsuario);

// Converte o resultado para um array de uma única vez
$result = $resultadoFetch->fetch_all(MYSQLI_ASSOC);

// Agora, filtre para cada status
$ocorrenciasAbertas = array_filter($result, function($ocorrencia) {
    return $ocorrencia['status'] === 'Aberta';
});
$ocorrenciasDespachadas = array_filter($result, function($ocorrencia) {
    return $ocorrencia['status'] === 'Despachada';
});
$ocorrenciasRetidas = array_filter($result, function($ocorrencia) {
    return $ocorrencia['status'] === 'Retida';
});
$ocorrenciasFinalizadas = array_filter($result, function($ocorrencia) {
    return $ocorrencia['status'] === 'Finalizada';
});

echo "
<title>Início - Despacho</title>
<style>
    h3 {
        text-align: center;
        font-weight: bold;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .btn-group-custom {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 10px;
    }
</style>
</head>
<body>
<div class='mt-2' style='text-align: center;'>
    <img src='./assets/atalaia.jpg' width='300px'>
</div>
<div class='container mt-4'>
    <h3 class='mb-4'>Despacho de Ocorrências</h3>
    <div class='btn-group-custom'>
        <div>
            <input type='datetime-local' class='form-control' id='dataHora' name='dataHora' value='$dataHoraAtual' readonly>
        </div>
        <div>
            <a href='cadastrarOcorrencia.html.php' class='btn btn-primary'>Nova Ocorrência</a>
        </div>
        <div>
            <a href='logout.php' class='btn btn-danger'>Deslogar</a>
        </div>
    </div>
    <ul class='nav nav-tabs'>
        <li class='nav-item'><a class='nav-link active' data-bs-toggle='tab' href='#abertas'>Abertas</a></li>
        <li class='nav-item'><a class='nav-link' data-bs-toggle='tab' href='#despachadas'>Despachadas</a></li>
        <li class='nav-item'><a class='nav-link' data-bs-toggle='tab' href='#retidas'>Retidas</a></li>
        <li class='nav-item'><a class='nav-link' data-bs-toggle='tab' href='#finalizadas'>Finalizadas</a></li>
    </ul>
    <div class='tab-content mt-3'>
        <div class='tab-pane fade show active' id='abertas'>";
        echo gerarTabelaOcorrencias($ocorrenciasAbertas);
        echo "</div>
        <div class='tab-pane fade' id='despachadas'>";
        echo gerarTabelaOcorrencias($ocorrenciasDespachadas);
        echo "</div>
        <div class='tab-pane fade' id='retidas'>";
        echo gerarTabelaOcorrencias($ocorrenciasRetidas);
        echo "</div>
        <div class='tab-pane fade' id='finalizadas'>";
        echo gerarTabelaOcorrencias($ocorrenciasFinalizadas);
        echo "</div>
    </div>
</div>
</body>
</html>
";

function gerarTabelaOcorrencias($ocorrencias) {
    if (empty($ocorrencias)) {
        return "<p>Nenhuma ocorrência encontrada.</p>";
    }

    // Verificar cargo do usuário
    $cargoUsuario = $_SESSION["login_cargo"] ?? '';

    // Lista de cargos autorizados a ver o botão "Gerar PDF"
    $cargosAutorizados = ["Monitor", "Coordenador", "Supervisor", "Suporte"];

    $html = "<table>
                <thead>
                    <tr>
                        <th>ID Ocorrência</th>
                        <th>Tipo</th>
                        <th>Subtipo</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>";
    foreach ($ocorrencias as $linha) {
        $html .= "
        <tr>
            <td>{$linha['id']}</td>
            <td>{$linha['tipo']}</td>
            <td>{$linha['subtipo']}</td>
            <td>{$linha['cidade']}</td>
            <td>
                <div class='d-flex justify-content-center gap-2'>";

        // Exibe o botão "Gerar PDF" apenas para os cargos autorizados
        if (in_array($cargoUsuario, $cargosAutorizados)) {
            $html .= "
                    <form action='gerarPDF.php' method='post' class='m-0'>
                        <input type='hidden' name='id' value='{$linha['id']}'>
                        <input type='hidden' name='tipo' value='{$linha['tipo']}'>
                        <input type='hidden' name='subtipo' value='{$linha['subtipo']}'>
                        <input type='hidden' name='localizacao' value='{$linha['localizacao']}'>
                        <input type='hidden' name='bairro' value='{$linha['bairro']}'>
                        <input type='hidden' name='cidade' value='{$linha['cidade']}'>
                        <input type='hidden' name='referencia' value='{$linha['referencia']}'>
                        <input type='hidden' name='solicitante' value='{$linha['solicitante']}'>
                        <input type='hidden' name='telefone' value='{$linha['telefone']}'>
                        <input type='hidden' name='dataHora' value='{$linha['dataHora']}'>
                        <button class='btn btn-success'>Gerar PDF</button>
                    </form>";
        }

        $html .= "  <!-- Botão Editar -->
                    <form action='editarOcorrencia.html.php' method='post' class='m-0 d-inline'>
                        <input type='hidden' name='id' value='{$linha['id']}'>
                        <button class='btn btn-warning'>Editar</button>
                    </form>

                    <!-- Alterar Status -->
                    <form action='Ocorrencia.php' method='post' class='m-0 d-inline'>
                        <input type='hidden' name='id' value='{$linha['id']}'>
                        <input type='hidden' name='funcao' value='alterarStatus'>
                        <div class='d-inline-flex align-items-center gap-2'>
                            <select name='novoStatus' class='form-select' style='width: auto;'>
                                <option value='Aberta' " . ($linha['status'] == 'Aberta' ? 'selected' : '') . ">Aberta</option>
                                <option value='Despachada' " . ($linha['status'] == 'Despachada' ? 'selected' : '') . ">Despachada</option>
                                <option value='Retida' " . ($linha['status'] == 'Retida' ? 'selected' : '') . ">Retida</option>
                                <option value='Finalizada' " . ($linha['status'] == 'Finalizada' ? 'selected' : '') . ">Finalizada</option>
                            </select>
                            <button type='submit' class='btn btn-primary'>Alterar</button>
                        </div>
                    </form>
                </div>
            </td>
        </tr>";
    }
    $html .= "</tbody></table>";
    return $html;
}
?>