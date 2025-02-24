<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tipos e Subtipos</title>
</head>
<body>
    <h1>Cadastro de Tipos e Subtipos de Ocorrência</h1>

    <!-- Formulário para cadastrar Tipo -->
    <form action="Tipo.php" method="POST">
        <input type="hidden" name="funcao" value="cadastrarTipo">
        <label for="nomeTipo">Nome do Tipo:</label>
        <input type="text" name="nomeTipo" id="nomeTipo" required>
        <button type="submit">Cadastrar Tipo</button>
    </form>

    <br><hr><br>

    <!-- Formulário para cadastrar Subtipo -->
    <form action="Tipo.php" method="POST">
        <input type="hidden" name="funcao" value="cadastrarSubtipo">
        <label for="idTipo">Selecione o Tipo:</label>
        <select name="idTipo" id="idTipo" required>
            <?php
            include("CTipo.php");
            $tipoObj = new CTipo();
            $tipos = $tipoObj->selecionarTipos();

            while ($tipo = mysqli_fetch_array($tipos)) {
                echo "<option value='" . $tipo['idTipo'] . "'>" . $tipo['nomeTipo'] . "</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="nomeSubtipo">Nome do Subtipo:</label>
        <input type="text" name="nomeSubtipo" id="nomeSubtipo" required>
        <button type="submit">Cadastrar Subtipo</button>
    </form>
</body>
</html>