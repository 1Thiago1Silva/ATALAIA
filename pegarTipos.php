<?php
include_once("CConexao.php");
include_once("CTipo.php");

$conexao = new CConexao();
$tipoObj = new CTipo();

if (isset($_POST['term'])) {
    $termo = $_POST['term'];

    $sql = "SELECT idTipo, nomeTipo FROM tipos WHERE nomeTipo LIKE '%$termo%' ORDER BY nomeTipo";
    $resultado = $conexao->dql($sql);

    $tipos = [];
    while ($tipo = mysqli_fetch_assoc($resultado)) {
        $tipos[] = ["value" => $tipo['idTipo'], "label" => $tipo['nomeTipo']];
    }

    echo json_encode($tipos);
} else {
    echo json_encode([]);
}
