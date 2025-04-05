<?php
include_once("head.html.php");
include_once("COcorrencia.php");

session_start();
if (!isset($_SESSION["login_nome"])) {
    header("Location: login.php");
    exit();
}

$nomeUsuario = $_SESSION["login_nome"];
$cargoUsuario = $_SESSION["login_cargo"];

$cocorrencia = new COcorrencia();
$result = null;

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dataInicio = $_POST["data_inicio"] . " 00:00:00";
    $dataFim = $_POST["data_fim"] . " 23:59:59";

    $result = $cocorrencia->filtrarPorData($dataInicio, $dataFim);
}
?>

<title>Filtrar Ocorrências por Data</title>
<style>
    h3 {
        text-align: center;
        font-weight: bold;
        text-decoration: underline;
    }
    .form-filtro {
        margin: 20px auto;
        max-width: 500px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
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

<body>
<div class='mt-2' style='text-align: center;'>
    <img src='./assets/atalaia.jpg' width='300px'>
</div>

<div class='container mt-4'>
    <h3 class='mb-2'>Ocorrências Criadas Por Data</h3>
    <div class='btn-group-custom'>
        <div>
            <?php
            if ($cargoUsuario === "Teleatendente") {
                echo "<a href='inicioAtendimento.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
            } else {
                echo "<a href='inicioDespacho.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
            };
            ?>
        </div>
    </div>

    <form method="POST" class="form-filtro shadow-sm">
        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data Início:</label>
            <input type="date" class="form-control" name="data_inicio" required>
        </div>
        <div class="mb-3">
            <label for="data_fim" class="form-label">Data Fim:</label>
            <input type="date" class="form-control" name="data_fim" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Filtrar</button>
    </form>

    <div class='mt-4'>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($linha = mysqli_fetch_array($result)) {
                echo "
                <div class='card shadow-sm mb-3'>
                    <div class='card-header'>
                        <strong>Tipo:</strong> $linha[tipo] $linha[subtipo]
                        <span class='divider'>|</span> " . date('d/m/Y H:i:s', strtotime($linha['dataHora'])) . "
                        <span class='divider'>|</span> <strong>Status:</strong> $linha[status]
                    </div>
                    <div class='card-body'>
                        <p><strong>Referência:</strong> $linha[referencia]</p>
                        <p><strong>Localização:</strong> $linha[localizacao], $linha[bairro], $linha[cidade]</p>
                        <p><strong>Solicitante:</strong> $linha[solicitante] | <strong>Telefone:</strong> $linha[telefone]</p>
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
                ";
            }
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
            echo "<p class='text-center'>Nenhuma ocorrência encontrada no intervalo selecionado.</p>";
        }
        ?>
    </div>
</div>
</body>