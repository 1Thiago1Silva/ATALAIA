<?php
include("CUsuario.php");
$funcao = $_POST["funcao"];

switch ($funcao) {
    case "cadastrar":
        $usuario = new CUsuario();
        $usuario->cadastrar(
            $_POST['nome'],
            $_POST['cpf'],
            $_POST['cargo']
        );
        header('cadastrarUsuario.html.php');
    break;

    case "login":
        $usuario = new CUsuario();
        $usuario->login(
            $_POST['cpf'],
            $_POST['cargo']
        );
    break;

    default:
        $msg = "Operação inválida!";
    break;
}
?>