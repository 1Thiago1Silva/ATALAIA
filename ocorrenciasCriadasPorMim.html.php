<?php
include_once("head.html.php");
include_once("COcorrencia.php");

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["login_nome"])) {
    header("Location: login.php");
    exit();
}

// Obtém Nome e ID do usuário da sessão
$nomeUsuario = $_SESSION["login_nome"];
$idUsuario = $_SESSION["login_id"];
$cargoUsuario = $_SESSION["login_cargo"];

$cocorrencia = new COcorrencia();
$result = $cocorrencia->criadasPorMim($nomeUsuario); // Se a função usa o nome

echo "
<title>Ocorrências Criadas por Mim</title>
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
    <h3 class='mb-4'>Ocorrências Criadas Por Mim</h3>
    <div class='btn-group-custom'>
        <div>";
            if ($cargoUsuario === "Teleatendente") {
                echo "<a href='inicioAtendimento.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
            } else {
                echo "<a href='inicioDespacho.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
            }
        echo"
        </div>
        <div>
            <a href='logout.php' class='btn btn-danger'>Deslogar</a>
        </div>
    </div>
";

// Verifica se há ocorrências para exibir
if ($result && mysqli_num_rows($result) > 0) {
    while ($linha = mysqli_fetch_array($result)) {
        echo "
        <div class='col-12 mb-4'>
            <div class='card shadow-sm position-relative'>
                <div class='card-header shadow-sm position-relative'>
                    <span>
                        <strong>Tipo:</strong> $linha[tipo] $linha[subtipo] 
                        <span class='divider'>|</span>" . date('d/m/Y H:i:s', strtotime($linha['dataHora'])) . "
                        <span class='divider'>|</span> <strong>Status:</strong> $linha[status]
                    </span>
                </div>
                <div class='card-body shadow-sm position-relative'>
                    <div class='line'><strong>Referência:</strong> $linha[referencia]</div>
                    <div class='line'><strong>Cidade:</strong> $linha[cidade] 
                    <strong>Bairro:</strong> $linha[bairro] 
                    <strong>Rua:</strong> $linha[localizacao]</div>
                    <div class='line'><strong>Solicitante:</strong> $linha[solicitante] 
                    <strong>Telefone:</strong> $linha[telefone]</div>
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
} else {
    echo "<p>Nenhuma ocorrência encontrada.</p>";
}

echo "</div>"; // Fecha o container
?>