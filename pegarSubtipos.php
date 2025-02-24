<?php
include_once("CConexao.php");
include_once("CTipo.php");

$conexao = new CConexao();
$tipoObj = new CTipo();

if (isset($_POST['idTipo']) && isset($_POST['term'])) {
    $idTipo = intval($_POST['idTipo']);
    $termo = $_POST['term'];

    $sql = "SELECT idSubtipo, nomeSubtipo 
            FROM subtipos 
            WHERE idTipo = '$idTipo' AND nomeSubtipo LIKE '%$termo%' 
            ORDER BY nomeSubtipo";
    $resultado = $conexao->dql($sql);

    $subtipos = [];
    while ($subtipo = mysqli_fetch_assoc($resultado)) {
        $subtipos[] = ["value" => $subtipo['idSubtipo'], "label" => $subtipo['nomeSubtipo']];
    }

    echo json_encode($subtipos);
} else {
    echo json_encode([]);
}
