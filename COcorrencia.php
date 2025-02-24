<?php 
include_once("CConexao.php");
$conexao = new CConexao();

class COcorrencia{
    private $id;
    private $dataHora;
    private $localizacao;
    private $referencia;
    private $bairro;
    private $cidade;
    private $tipo;
    private $subtipo;
    private $solicitante;
    private $telefone;
    
    public function cadastrar($dataHora, $localizacao, $referencia, $bairro, $cidade, $tipo, $subtipo, $solicitante, $telefone) {
        global $conexao;
    
        // Passo 1: Inserir a nova ocorrência
        $sqlOcorrencia = "INSERT INTO ocorrencias (dataHora, localizacao, referencia, bairro, cidade, tipo, subtipo, solicitante, telefone) 
                          VALUES ('$dataHora', '$localizacao', '$referencia', '$bairro', '$cidade', '$tipo', '$subtipo', '$solicitante', '$telefone')";
        $conexao->dml($sqlOcorrencia);
    
        // Obter o último ID inserido
        $idOcorrencia = mysqli_insert_id($conexao->getConn());
        if (!$idOcorrencia) {
            die("Erro ao inserir a ocorrência: " . mysqli_error($conexao->getConn()));
        }
    
        // Passo 2: Obter o idTipo correspondente ao nomeTipo fornecido
        $sqlTipo = "SELECT tr.idTipo 
                    FROM tipo_responsavel tr 
                    INNER JOIN tipos t ON tr.idTipo = t.idTipo 
                    WHERE t.nomeTipo = '$tipo'";
        $resultado = $conexao->dql($sqlTipo);
    
        // Verificar se a consulta retornou resultados
        if (mysqli_num_rows($resultado) === 0) {
            die("Nenhum tipo_responsavel encontrado para o tipo: $tipo");
        }
    
        // Iterar pelos resultados e associar na tabela ocorrencia_responsavel
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $idTipo = $linha['idTipo'];
    
            // Inserir associação
            $sqlAssociacao = "INSERT INTO ocorrencia_responsavel (idOcorrencia, idTipo) VALUES ($idOcorrencia, $idTipo)";
            $conexao->dml($sqlAssociacao);
    
            // Verificar sucesso da inserção
            if (mysqli_error($conexao->getConn())) {
                die("Erro ao associar ocorrência com tipo_responsavel: " . mysqli_error($conexao->getConn()));
            }
        }
    
        // Configurar mensagem de sucesso na sessão
        session_start();
        $_SESSION["cadastrar"] = "1";
    
        // Redirecionar com base no cargo
        $cargo = $_SESSION["login_cargo"];
        if ($cargo === 'Teleatendente') {
            header("Location: inicioAtendimento.html.php");
        } else {
            header("Location: inicioDespacho.html.php");
        }
        exit();
    }               
       
    public function preencher($id, $dataHora, $localizacao, $referencia, $bairro, $cidade, $tipo, $subtipo, $solicitante, $telefone){
        $sql = "UPDATE ocorrencias SET dataHora = '$dataHora', localizacao = '$localizacao', referencia = '$referencia', bairro = '$bairro', cidade = '$cidade', tipo = '$tipo', subtipo = '$subtipo', solicitante = '$solicitante', telefone = '$telefone' WHERE id =  $id";
        global $conexao;
        $conexao->dml($sql);

        session_start();
        $_SESSION["preencher"] = "1";
        // Recuperando o cargo da sessão
        $cargo = $_SESSION["login_cargo"];

        // Verifique o cargo e redirecione
        if ($cargo === 'Teleatendente') {
            header("Location: inicioAtendimento.html.php");
        } else {
            header("Location: inicioDespacho.html.php");
        }
        exit();
    }

    public function editar($idOcorrencia, $dataHora, $localizacao, $referencia, $bairro, $cidade, $tipo, $subtipo, $solicitante, $telefone) {
        global $conexao;
    
        // Verificar se a ocorrência existe antes de editar
        $sqlVerificar = "SELECT id FROM ocorrencias WHERE id = $idOcorrencia";
        $resultadoVerificar = $conexao->dql($sqlVerificar);
    
        if (mysqli_num_rows($resultadoVerificar) === 0) {
            die("Ocorrência não encontrada com o ID fornecido: $idOcorrencia");
        }
    
        // Passo 1: Atualizar os dados básicos da ocorrência
        $sqlAtualizar = "UPDATE ocorrencias 
                         SET dataHora = '$dataHora', 
                             localizacao = '$localizacao', 
                             referencia = '$referencia', 
                             bairro = '$bairro', 
                             cidade = '$cidade', 
                             tipo = '$tipo', 
                             subtipo = '$subtipo', 
                             solicitante = '$solicitante', 
                             telefone = '$telefone' 
                         WHERE id = $idOcorrencia";
        $conexao->dml($sqlAtualizar);
    
        if (mysqli_error($conexao->getConn())) {
            die("Erro ao atualizar a ocorrência: " . mysqli_error($conexao->getConn()));
        }
    
        // Passo 2: Atualizar os tipos de responsáveis associados à ocorrência
        // Deletar associações existentes
        $sqlDeletarAssociacoes = "DELETE FROM ocorrencia_responsavel WHERE idOcorrencia = $idOcorrencia";
        $conexao->dml($sqlDeletarAssociacoes);
    
        if (mysqli_error($conexao->getConn())) {
            die("Erro ao deletar associações antigas: " . mysqli_error($conexao->getConn()));
        }
    
        // Obter os novos idTipo baseados no tipo fornecido
        $sqlTipo = "SELECT tr.idTipo 
                    FROM tipo_responsavel tr 
                    INNER JOIN tipos t ON tr.idTipo = t.idTipo 
                    WHERE t.nomeTipo = '$tipo'";
        $resultadoTipo = $conexao->dql($sqlTipo);
    
        if (mysqli_num_rows($resultadoTipo) === 0) {
            die("Nenhum tipo_responsavel encontrado para o tipo: $tipo");
        }
    
        // Inserir as novas associações
        while ($linha = mysqli_fetch_assoc($resultadoTipo)) {
            $idTipo = $linha['idTipo'];
    
            $sqlAssociacao = "INSERT INTO ocorrencia_responsavel (idOcorrencia, idTipo) VALUES ($idOcorrencia, $idTipo)";
            $conexao->dml($sqlAssociacao);
    
            if (mysqli_error($conexao->getConn())) {
                die("Erro ao associar ocorrência com tipo_responsavel: " . mysqli_error($conexao->getConn()));
            }
        }
    
        // Configurar mensagem de sucesso na sessão
        session_start();
        $_SESSION["editar"] = "1";
    
        // Redirecionar com base no cargo
        $cargo = $_SESSION["login_cargo"];
        if ($cargo === 'Teleatendente') {
            header("Location: inicioAtendimento.html.php");
        } else {
            header("Location: inicioDespacho.html.php");
        }
        exit();
    }
    

    public function alterarStatus($id, $novoStatus) {
        $sql = "UPDATE ocorrencias SET status = '$novoStatus' WHERE id = $id";
        global $conexao;
        return $conexao->dml($sql);
    }

    public function selecionarOcorrencia($id){
        $sql = "SELECT * FROM ocorrencias WHERE id = $id";
        global $conexao;
        return $conexao->dql($sql);
    }

    public function selecionarPorStatus($status) {
        $sql = "SELECT * FROM ocorrencias WHERE status = '$status' ORDER BY dataHora DESC";
        global $conexao;
        $result = $conexao->dql($sql);
        $ocorrencias = [];
        while ($linha = mysqli_fetch_array($result)) {
            $ocorrencias[] = $linha;
        }
        return $ocorrencias;
    }

    public function selecionarTodas(){
        $sql = "SELECT * FROM ocorrencias ORDER BY dataHora DESC";
        //objeto usando a função DQL - Data Query¹ Language  (Linguagem de ¹Consulta de Dados)
        global $conexao;
        return $conexao->dql($sql);
    }

    public function selecionarPorCargo($cargo) {
        global $conexao; // Utilize a conexão global
    
        // Lista de cargos que podem visualizar todas as ocorrências
        $cargosVerTodos = ["Monitor", "Coordenador", "Supervisor", "Suporte"];
        
        if (in_array($cargo, $cargosVerTodos)) {
            // Retorna todas as ocorrências sem filtro
            $sql = "SELECT DISTINCT o.id, o.dataHora, o.localizacao, o.referencia, o.bairro, 
                            o.cidade, o.tipo, o.subtipo, o.solicitante, o.telefone, o.status
                    FROM ocorrencias o
                    ORDER BY o.dataHora DESC";
        } else {
            // Define o órgão responsável com base no cargo do usuário
            $orgaoResponsavel = "";
            if ($cargo === "Despacho_PM") {
                $orgaoResponsavel = "Despacho_PM";
            } elseif ($cargo === "Despacho_BM") {
                $orgaoResponsavel = "Despacho_BM";
            } elseif ($cargo === "Despacho_GCMS") {
                $orgaoResponsavel = "Despacho_GCMS";
            }
    
            // Filtra ocorrências pelo órgão responsável
            $sql = "SELECT DISTINCT o.id, o.dataHora, o.localizacao, o.referencia, o.bairro, 
                            o.cidade, o.tipo, o.subtipo, o.solicitante, o.telefone, o.status
                    FROM ocorrencias o
                    INNER JOIN ocorrencia_responsavel orp ON o.id = orp.idOcorrencia
                    INNER JOIN tipo_responsavel tr ON orp.idTipo = tr.idTipo
                    WHERE tr.responsavel = '$orgaoResponsavel'
                    ORDER BY o.dataHora DESC";
        }
    
        // Retorna o resultado da consulta
        return $conexao->dql($sql);
    }        
        
    public function quantidadeOcorrencia(){
        $sql = "SELECT count(id) AS qtdOcorrencia FROM ocorrencias";
        global $conexao;
        $result = $conexao->dql($sql);
        $linha = mysqli_fetch_array($result);
        return $linha['qtdOcorrencia'];
    }

    public function deletar($id){
        $sql = "DELETE FROM ocorrencias WHERE id = $id";
        global $conexao;
        return $conexao->dml($sql);
    }
}
?>