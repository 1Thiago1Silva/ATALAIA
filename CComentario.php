<?php
include_once("CConexao.php");
$conexao = new CConexao();

class CComentario{
    public function comentar($id_usuario, $id_ocorrencia, $comentario) {
        $sql = "INSERT INTO comentarios (id_usuario, id_ocorrencia, comentario, dataComentario) VALUES ('$id_usuario', '$id_ocorrencia', '$comentario', NOW())";
        global $conexao;
        $conexao->dml($sql);
        
        session_start();
        if (mysqli_affected_rows($conexao->getConn()) > 0) {
            $_SESSION["cadastrar_comentario"] = 'ok';
        } else {
            $_SESSION["cadastrar_comentario"] = 'erro';
        }
    }

    public function selecionarTodos($id_ocorrencia) {
        $sql = "SELECT C.id, C.id_usuario, C.id_ocorrencia, C.comentario, C.dataComentario,
                    U.nome AS nomeUsuario, U.cargo AS cargoUsuario
                FROM comentarios C
                INNER JOIN usuarios U ON C.id_usuario = U.id
                WHERE C.id_ocorrencia = '$id_ocorrencia'
                ORDER BY C.dataComentario";
        global $conexao;
        return $conexao->dql($sql);
    }
    

    public function deletar($id_comentario) {
        $sql = "DELETE FROM comentarios WHERE id = '$id_comentario'";
        global $conexao;
        $conexao->dml($sql);
        
        session_start();
        if (mysqli_affected_rows($conexao->getConn()) > 0) {
            $_SESSION["deletar_comentario"] = 'ok';
        } else {
            $_SESSION["deletar_comentario"] = 'erro';
        }
    }
}
?>
