<?php
include("CTipo.php");

if (!isset($_POST['funcao'])) {
    die("Nenhuma função especificada.");
}

$funcao = $_POST['funcao'];
$tipoObj = new CTipo();

switch ($funcao) {
    case "cadastrarTipo":
        $nomeTipo = $_POST['nomeTipo'];
        if (!empty($nomeTipo)) {
            $tipoObj->cadastrarTipo($nomeTipo);
        }
        header("Location: cadastrarTipoSubTipo.php");
        break;

    case "cadastrarSubtipo":
        $nomeSubtipo = $_POST['nomeSubtipo'];
        $idTipo = $_POST['idTipo'];
        if (!empty($nomeSubtipo) && !empty($idTipo)) {
            $tipoObj->cadastrarSubtipo($nomeSubtipo, $idTipo);
        }
        header("Location: cadastrarTipoSubTipo.php");
        break;

    default:
        echo "Operação inválida.";
        break;
}
?>