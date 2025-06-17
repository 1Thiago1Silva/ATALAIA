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
        text-decoration: underline;
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
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    /* Corrigir cor do texto e adicionar hover no dropdown */
    .dropdown-menu.bg-success .dropdown-item {
        color: white; /* Garante que o texto seja branco */
    }
    .dropdown-menu.bg-success .dropdown-item:hover {
        background-color: #146c43; /* Tom mais escuro de verde para o hover */
        color: white; /* Mantém o texto branco no hover */
    }
    .dropdown-menu.bg-success .dropdown-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.5); /* Divider branco com opacidade */
    }
</style>
</head>
<body>
<div class='container-fluid position-relative'>
    <!-- Imagem centralizada -->
    <div class='text-center'>
        <img src='./assets/atalaia.jpg' width='300px' alt='Atalaia'>
    </div>
    <!-- Dropdown posicionado no canto superior direito -->
    <div class='position-absolute' style='top: 0; right: 0; margin: 1rem;'>
        <div class='dropdown'>
            <button class='btn btn-info dropdown-toggle d-flex align-items-center' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 512' style='width:16px; height:16px; margin-right:4px;'>
                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path d='M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3zM625 177L497 305c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L591 143c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z'/>
                </svg>
                $nomeUsuario
            </button>
            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton'>
                <li><a class='dropdown-item' href='logout.php'>Deslogar</a></li>
            </ul>
        </div>
    </div>
</div>
<div class='container mt-4'>
    <h3 class='mb-4'>Despacho de Ocorrências</h3>
    <div class='btn-group-custom'>
        <div>
            <div class='dropdown'>
                <button class='btn btn-success dropdown-toggle d-flex align-items-center' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' width='16' height='16' style='margin-right: 6px;'>
                        <!-- Ícone de filtro -->
                        <path fill='currentColor' d='M3.9 54.9C10.5 40.9 24.5 32 40 32l432 0c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9 320 448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6l0-79.1L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z'/>
                    </svg>
                    Filtros
                </button>
                <ul class='dropdown-menu bg-success'>
                    <li><a class='dropdown-item text-white' href='ocorrenciasFiltradasPorData.html.php'>Filtrar Por Data</a></li>
                    <li><hr class='dropdown-divider'></li>
                    <li><a class='dropdown-item text-white' href='ocorrenciasFiltradasPorTipo.html.php'>Filtrar Por Tipo</a></li>
                    <li><hr class='dropdown-divider'></li>
                    <li><a class='dropdown-item text-white' href='ocorrenciasFiltradasPorCidade.html.php'>Filtrar Por Cidade</a></li>
                    <li><hr class='dropdown-divider'></li>
                    <li><a class='dropdown-item text-white' href='ocorrenciasFiltradasPorTelefone.html.php'>Filtrar Por Telefone</a></li>
                    <li><hr class='dropdown-divider'></li>
                    <li><a class='dropdown-item text-white' href='ocorrenciasCriadasPorMim.html.php'>Ocorrências Criadas Por Mim</a></li>
                </ul>
            </div>
        </div>
        <div>
            <a href='cadastrarOcorrencia.html.php' class='btn btn-primary'>Nova Ocorrência</a>
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
                        <button class='btn btn-success'>PDF</button>
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