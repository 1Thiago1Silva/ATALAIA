<?php
include("CComentario.php");

if (!isset($_POST['funcao']) || !isset($_POST['id_usuario']) || !isset($_POST['id_ocorrencia'])) {
    die("Dados incompletos.");
}

$funcao = $_POST['funcao'];
$id_usuario = $_POST['id_usuario'];
$id_ocorrencia = $_POST['id_ocorrencia'];
$comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

if (empty($id_usuario)) {
    session_start();
    $_SESSION["login_comentario"] = 'erro';
    header('Location: login.html.php');
    exit();
}

$comentarioObj = new CComentario();

switch ($funcao) {
    case "comentar":
        $comentarioObj->comentar($id_usuario, $id_ocorrencia, $comentario);
        header("Location: editarOcorrencia.html.php?id=$id_ocorrencia");
    break;
    
    case "deletar":
        $comentarioObj->deletar($_POST['id_comentario']);
        header('Location: preencherOcorrencia.html.php');
    break;

    default:
        echo "Operação inválida.";
    break;
}
?>
