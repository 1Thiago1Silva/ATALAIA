<?php
include("CConexao.php");
$conexao = new CConexao();

class CUsuario{
    public function cadastrar($nome, $cpf, $cargo){
        $sql = "INSERT INTO usuarios (nome, cpf, cargo) VALUES ('$nome', '$cpf', '$cargo')";
        //objeto usando a função DML - Data Manipulation Language
        global $conexao;
        $conexao->dml($sql);
    }

    public function login($cpf, $cargo) {
        $sql = "SELECT * FROM usuarios WHERE cpf = '$cpf' AND cargo = '$cargo'";
        global $conexao;
        $result = $conexao->dql($sql);
        if ($result) {
            $linha = mysqli_fetch_array($result);
            session_start();
            if ($linha) {
                $_SESSION["login"] = 'ok';
                $_SESSION["login_id"] = $linha["id"];
                $_SESSION["login_nome"] = $linha["nome"];
                $_SESSION["login_cargo"] = $linha["cargo"];
                if ($cargo === "Teleatendente") {
                    header("Location:inicioAtendimento.html.php");
                } else {
                    header("Location:inicioDespacho.html.php");
                }
            } else {
                $_SESSION["login"] = "erro";
                header('Location:login.html.php');
            }
        } else {
            echo "Falha no SQL!";
        }
    }

    public function selecionarUsuario($id){
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        global $conexao;
        return $conexao->dql($sql);
    }

    public function quantidadeUsuario(){
        $sql = "SELECT count(id) AS qtdUsuario FROM usuarios";
        global $conexao;
        $result = $conexao->dql($sql);
        $linha = mysqli_fetch_array($result);
        return $linha['qtdUsuario'];
    }

    public function selecionarTodos(){
        $sql = "SELECT * FROM usuarios";
        //objeto usando a função DQL - Data Query¹ Language  (Linguagem de ¹Consulta de Dados)
        global $conexao;
        return $conexao->dql($sql);
    }
}
?>