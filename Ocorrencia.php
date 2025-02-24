<?php
include("COcorrencia.php");
$funcao = $_POST["funcao"];

switch($funcao) {
    case "cadastrar":
        $ocorrencia = new COcorrencia();
        $ocorrencia->cadastrar(
            $_POST['dataHora'],
            $_POST['localizacao'],
            $_POST['referencia'],
            $_POST['bairro'],
            $_POST['cidade'],
            $_POST['tipo'],
            $_POST['subtipo'],
            $_POST['solicitante'],
            $_POST['telefone']
        );
    break;

    case "preencher":
        $ocorrencia = new COcorrencia();
        $ocorrencia->preencher(
            $_POST['id'],
            $_POST['dataHora'],
            $_POST['localizacao'],
            $_POST['referencia'],
            $_POST['bairro'],
            $_POST['cidade'],
            $_POST['tipo'],
            $_POST['subtipo'],
            $_POST['solicitante'],
            $_POST['telefone']
        );
    break;

    case "editar":
        $ocorrencia = new COcorrencia();
        $ocorrencia->editar(
            $_POST['id'],
            $_POST['dataHora'],
            $_POST['localizacao'],
            $_POST['referencia'],
            $_POST['bairro'],
            $_POST['cidade'],
            $_POST['tipo'],
            $_POST['subtipo'],
            $_POST['solicitante'],
            $_POST['telefone']
        );
    break;

    case "alterarStatus":
        $id = $_POST['id'];
        $novoStatus = $_POST['novoStatus'];
    
        $ocorrencia = new COcorrencia();
        if ($ocorrencia->alterarStatus($id, $novoStatus)) {
            session_start();
            $_SESSION["alterarStatus"] = "1"; // Sucesso
        } else {
            session_start();
            $_SESSION["alterarStatus"] = "2"; // Falha
        }
        header("Location: inicioDespacho.html.php");
    break;
    

    case "deletar":
        $ocorrencia = new COcorrencia();
        session_start();
        if ($ocorrencia->deletar($_POST['id'])) {
            $_SESSION["deletar"] = "1"; // Sucesso
        } else {
            $_SESSION["deletar"] = "2"; // Falha
        }
        header("Location: inicioAtendimento.html.php");
    break;

    default:
        $msg="Função Inválida - Na verdade nem é inválida,
        provavelmente a função esteja errada,
        ou não está sendo passada pelo formulário";  
    break;   
}
?>