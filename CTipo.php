<?php
include_once("CConexao.php");
$conexao = new CConexao();

class CTipo {
    public function cadastrarTipo($nomeTipo) {
        $sql = "INSERT INTO tipos (nomeTipo) VALUES ('$nomeTipo')";
        global $conexao;
        $conexao->dml($sql);

        session_start();
        if (mysqli_affected_rows($conexao->getConn()) > 0) {
            $_SESSION["cadastrar_tipo"] = "ok";
        } else {
            $_SESSION["cadastrar_tipo"] = "erro";
        }
    }

    public function cadastrarSubtipo($nomeSubtipo, $idTipo) {
        $sql = "INSERT INTO subtipos (nomeSubtipo, idTipo) VALUES ('$nomeSubtipo', '$idTipo')";
        global $conexao;
        $conexao->dml($sql);

        session_start();
        if (mysqli_affected_rows($conexao->getConn()) > 0) {
            $_SESSION["cadastrar_subtipo"] = "ok";
        } else {
            $_SESSION["cadastrar_subtipo"] = "erro";
        }
    }

    public function selecionarTipos() {
        $sql = "SELECT idTipo, nomeTipo FROM tipos ORDER BY nomeTipo";
        global $conexao;
        return $conexao->dql($sql);
    }

    public function selecionarSubtiposPorTipo($idTipo) {
        $sql = "SELECT idSubtipo, nomeSubtipo FROM subtipos WHERE idTipo = '$idTipo'";
        global $conexao;
        return $conexao->dql($sql);
    }
}
?>