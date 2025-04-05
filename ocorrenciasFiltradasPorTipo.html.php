<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== 'ok') {
    header("Location: login.html.php");
    exit();
}

$nomeUsuario   = $_SESSION["login_nome"];
$idUsuario     = $_SESSION["login_id"];
$cargoUsuario  = $_SESSION["login_cargo"];
date_default_timezone_set('America/Sao_Paulo');
$dataHoraAtual = date('Y-m-d\TH:i');

include_once("head.html.php");
include_once("COcorrencia.php");

$ocorrencia = new COcorrencia();

// Inicialmente, nenhuma ocorrência é exibida.
$resultado = null;
$tipoBusca = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tipo'])) {
    $tipoBusca = trim($_POST['tipo']);
    $resultado = $ocorrencia->filtrarPorTipo($tipoBusca);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Filtrar por Tipo</title>
    <style>
        h3 {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .btn-group-custom {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }
    </style>
    <!-- Inclua os links dos estilos e scripts do Bootstrap se necessário -->
</head>
<body>
<div class="mt-2" style="text-align: center;">
    <img src="./assets/atalaia.jpg" width="300px">
</div>
<div class="container mt-4">
    <h3 class="mb-4">Filtrar por Tipo</h3>
    <div class="btn-group-custom">
        <!-- Formulário de busca por Tipo via POST -->
        <form method="POST" action="ocorrenciasFiltradasPorTipo.html.php" class="d-flex">
            <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Digite o tipo..." value="<?php echo htmlspecialchars($tipoBusca); ?>" autocomplete="off">
            <button type="submit" class="btn btn-success ms-2">Buscar</button>
        </form>
        <div>
            <?php
                if ($cargoUsuario === "Teleatendente") {
                    echo "<a href='inicioAtendimento.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
                } else {
                    echo "<a href='inicioDespacho.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
                }
            ?>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            // Converte o resultado em array associativo para filtrar por status
            $resultArray = $resultado->fetch_all(MYSQLI_ASSOC);
            $ocorrenciasAbertas      = array_filter($resultArray, function($ocorrencia) { return $ocorrencia['status'] === 'Aberta'; });
            $ocorrenciasDespachadas  = array_filter($resultArray, function($ocorrencia) { return $ocorrencia['status'] === 'Despachada'; });
            $ocorrenciasRetidas      = array_filter($resultArray, function($ocorrencia) { return $ocorrencia['status'] === 'Retida'; });
            $ocorrenciasFinalizadas  = array_filter($resultArray, function($ocorrencia) { return $ocorrencia['status'] === 'Finalizada'; });
            ?>
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#abertas">Abertas</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#despachadas">Despachadas</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#retidas">Retidas</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#finalizadas">Finalizadas</a></li>
            </ul>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="abertas">
                    <?php echo gerarTabelaOcorrencias($ocorrenciasAbertas, $cargoUsuario); ?>
                </div>
                <div class="tab-pane fade" id="despachadas">
                    <?php echo gerarTabelaOcorrencias($ocorrenciasDespachadas, $cargoUsuario); ?>
                </div>
                <div class="tab-pane fade" id="retidas">
                    <?php echo gerarTabelaOcorrencias($ocorrenciasRetidas, $cargoUsuario); ?>
                </div>
                <div class="tab-pane fade" id="finalizadas">
                    <?php echo gerarTabelaOcorrencias($ocorrenciasFinalizadas, $cargoUsuario); ?>
                </div>
            </div>
        <?php
        } else {
            echo "<p>Nenhuma ocorrência encontrada para o tipo informado.</p>";
        }
    }
    ?>
</div>
<!-- Inclusão dos scripts necessários -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="autocomplete.js"></script>
<!-- Inclua os scripts do Bootstrap se necessário -->
</body>
</html>
<?php
function gerarTabelaOcorrencias($ocorrencias, $cargoUsuario) {
    if (empty($ocorrencias)) {
        return "<p>Nenhuma ocorrência encontrada.</p>";
    }
    
    // Cargos autorizados para visualizar o botão "Gerar PDF" e alterar status
    $cargosAutorizados = ["Monitor", "Coordenador", "Supervisor", "Suporte"];
    
    $html = "<table>
                <thead>
                    <tr>
                        <th>ID Ocorrência</th>
                        <th>Tipo</th>
                        <th>Subtipo</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>";
                
    foreach ($ocorrencias as $linha) {
        $html .= "<tr>
                    <td>{$linha['id']}</td>
                    <td>{$linha['tipo']}</td>
                    <td>{$linha['subtipo']}</td>
                    <td>{$linha['cidade']}</td>
                    <td>
                        <div class='d-flex justify-content-center gap-2'>";
                        
        // Se o cargo do usuário for Teleatendente, mostra somente o botão Editar.
        if ($cargoUsuario === "Teleatendente") {
            $html .= "<form action='editarOcorrencia.html.php' method='post' class='m-0 d-inline'>
                        <input type='hidden' name='id' value='{$linha['id']}'>
                        <button class='btn btn-warning'>Editar</button>
                      </form>";
        } else {
            // Botão Gerar PDF (apenas para cargos autorizados)
            if (in_array($cargoUsuario, $cargosAutorizados)) {
                $html .= "<form action='gerarPDF.php' method='post' class='m-0'>
                            <input type='hidden' name='id' value='{$linha['id']}'>
                            <input type='hidden' name='tipo' value='{$linha['tipo']}'>
                            <input type='hidden' name='subtipo' value='{$linha['subtipo']}'>
                            <input type='hidden' name='localizacao' value='{$linha['localizacao']}'>
                            <input type='hidden' name='bairro' value='{$linha['bairro']}'>
                            <input type='hidden' name='cidade' value='{$linha['cidade']}'>
                            <input type='hidden' name='referencia' value='{$linha['referencia']}'>
                            <input type='hidden' name='solicitante' value='{$linha['solicitante']}'>
                            <input type='hidden' name='telefone' value='{$linha['telefone']}'>
                            <input type='hidden' name='dataHora' value='{$linha['dataHora']}'>
                            <button class='btn btn-success'>Gerar PDF</button>
                          </form>";
            }
            
            // Botão Editar
            $html .= "<form action='editarOcorrencia.html.php' method='post' class='m-0 d-inline'>
                        <input type='hidden' name='id' value='{$linha['id']}'>
                        <button class='btn btn-warning'>Editar</button>
                      </form>";
                      
            // Formulário para alterar status
            $html .= "<form action='Ocorrencia.php' method='post' class='m-0 d-inline'>
                        <input type='hidden' name='id' value='{$linha['id']}'>
                        <input type='hidden' name='funcao' value='alterarStatus'>
                        <div class='d-inline-flex align-items-center gap-2'>
                            <select name='novoStatus' class='form-select' style='width: auto;'>";
            $statuses = ['Aberta', 'Despachada', 'Retida', 'Finalizada'];
            foreach ($statuses as $status) {
                $selected = ($linha['status'] == $status) ? 'selected' : '';
                $html .= "<option value='{$status}' {$selected}>{$status}</option>";
            }
            $html .= "          </select>
                            <button type='submit' class='btn btn-primary'>Alterar</button>
                        </div>
                      </form>";
        }
                        
        $html .= "  </div>
                    </td>
                  </tr>";
    }
    $html .= "</tbody></table>";
    return $html;
}
?>
