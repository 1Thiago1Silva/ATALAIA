<?php

include_once("head.html.php");
include_once("COcorrencia.php");

// Define Nome e Id do Usuário
$nomeUsuario = "";
$idUsuario = "";

session_start();

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
            justify-content: flex-end;
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
    <div class='mt-2' style='text-align: center;'>
        <img src='./assets/atalaia.jpg' width='300px'>
    </div>
    <div class='container mt-4'>
        <h3 class='mb-4'>Lista de Ocorrências</h3>
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

                    <!-- Botão Deletar -->
                    <form action='Ocorrencia.php' method='post' class='m-0'>
                        <input type='hidden' name='id' value='$linha[id]'>
                        <input type='hidden' name='funcao' value='deletar'>
                        <button class='btn btn-danger btn-deletar'>Deletar</button>
                    </form>
                </div>
            </div>
        </div>
        ";
    }

    echo "</div>"; // Fecha o container
}
?>