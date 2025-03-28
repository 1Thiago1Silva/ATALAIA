<?php
class CConexao{
    private $conn;
    public function getConn(){
        return $this->conn;
    }

    private function conexaoBanco(){
        $conn = new MySQLi('localhost','root','','bd_atalaia');
        if($conn->connect_error){
            die("A Conexão Falhou: " .$conn->connect_error);
        }
        return $conn;
    }

    public function dml($sql){
        $this->conn = $this->conexaoBanco();
        if(mysqli_query($this->conn, $sql)){
            
        }else{
            echo "Falha no Comando SQL(dml)";
        }
    }

    public function dql($sql){
        $conn = $this->conexaoBanco();
        $result = mysqli_query($conn, $sql);
        if($result){
            return $result;  
        }else{
            echo "Falha no Comando SQL(dql)";
        }
    }
}
?>