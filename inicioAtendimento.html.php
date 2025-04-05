<?php
session_start();

include_once("head.html.php");
include_once("COcorrencia.php");

// Define Nome e Id do Usuário
$nomeUsuario = $_SESSION["login_nome"];
$idUsuario = $_SESSION["login_id"];

if (isset($_SESSION["preencher"])) {
    if ($_SESSION["preencher"] == "1") {
        unset($_SESSION["preencher"]);
    } elseif ($_SESSION["preencher"] == "2") {
        unset($_SESSION["preencher"]);
        echo "<script>alert('Erro ao preencher Ocorrência.');</script>";
    }
}

if (isset($_SESSION['deletar'])) {
    if ($_SESSION['deletar'] == "1") {
        echo "<script>alert('Ocorrência deletada com sucesso!');</script>";
    } elseif ($_SESSION['deletar'] == "2") {
        echo "<script>alert('Erro ao deletar a ocorrência.');</script>";
    }
    unset($_SESSION['deletar']);
}

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
$dataHoraAtual = date('Y-m-d\TH:i');

$cocorrencia = new COcorrencia();
$result = $cocorrencia->selecionarTodas();

// Verifica se retornou algo do banco de dados
if ($result) {
    echo "
    <title>Início - Atendimento</title>
    <style>
        h3 {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
        }
        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, 0.99);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .divider {
            margin: 0 8px;
            color: #6c757d;
        }
        .card-body .line {
            margin-bottom: 8px;
        }
        .btn-group-custom {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .card-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 2px;
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
    </div>
    <div class='container mt-4'>
        <h3 class='mb-4'>Lista de Ocorrências</h3>
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
                    <ul class='dropdown-menu'>
                        <li><a class='dropdown-item' href='ocorrenciasFiltradasPorData.html.php'>Filtrar Por Data</a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='ocorrenciasFiltradasPorTipo.html.php'>Filtrar Por Tipo</a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='ocorrenciasFiltradasPorCidade.html.php'>Filtrar Por Cidade</a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='ocorrenciasFiltradasPorTelefone.html.php'>Filtrar Por Telefone</a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='ocorrenciasCriadasPorMim.html.php'>Ocorrências Criadas Por Mim</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <a href='cadastrarOcorrencia.html.php' class='btn btn-primary'>Nova Ocorrência</a>
            </div>
        </div>
    ";

    // Enquanto houver linha na tabela, continuar
    while ($linha = mysqli_fetch_array($result)) {
        echo "
        <div class='col-12 mb-4'>
            <div class='card shadow-sm position-relative'>
                <div class='card-header shadow-sm position-relative'>
                    <span>
                        <strong>Tipo:</strong> $linha[tipo] $linha[subtipo] <span class='divider'>|</span>" . date('d/m/Y H:i:s', strtotime($linha['dataHora'])) . "<span class='divider'>|</span> <strong>Status:</strong> $linha[status]
                    </span>
                </div>
                <div class='card-body shadow-sm position-relative'>
                    <div class='line'> <strong>Referência:</strong> $linha[referencia] </div>
                    <div class='line'><strong>Cidade:</strong> $linha[cidade] <strong>Bairro:</strong> $linha[bairro] <strong>Rua:</strong> $linha[localizacao]</div>
                    <div class='line'><strong>Solicitante:</strong> $linha[solicitante] <strong>Telefone:</strong> $linha[telefone]</div>
                </div>
                <div class='card-footer'>
                
                    <!-- Botão Editar -->
                    <form action='editarOcorrencia.html.php' method='post' class='m-0'>
                        <input type='hidden' name='id' value='$linha[id]'>
                        <input type='hidden' name='funcao' value='editar'>
                        <button class='btn btn-warning btn-editar'>Editar</button>
                    </form>
                </div>
            </div>
        </div>
        ";
    }

    echo "</div>"; // Fecha o container
}
?>