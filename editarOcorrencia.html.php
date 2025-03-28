<?php
$cargoUsuario = "";
$nomeUsuario = "";
$idUsuario = "";

session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== 'ok') {
    header("Location: login.html.php");
    exit();
}

if (isset($_SESSION["login"])) {
    if ($_SESSION["login"] == 'ok') {  
        $idUsuario = $_SESSION["login_id"];
        $nomeUsuario = $_SESSION["login_nome"];
        $cargoUsuario = $_SESSION["login_cargo"];
    } else {
        $nomeUsuario = "";
    }
}

if (isset($_SESSION["editar"])){  
    if($_SESSION["editar"] = "1") {
        unset($_SESSION["editar"]);
        //echo "<script>alert('Ocorrência editadada com Sucesso!');</script>";
    }
    else if ($_SESSION["editar"] = "2"){
        unset($_SESSION["editar"]);
        echo "<script>alert('Erro ao editar Ocorrência.');</script>";
    }
}

include("head.html.php");
include("COcorrencia.php");
include("CComentario.php");
include("CTipo.php");

$ocorrencia =  new COcorrencia();
$id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if ($id === null) {
    echo "<script>alert('ID da ocorrência não informado.');</script>";
    exit();
}

$result = $ocorrencia->selecionarOcorrencia($id);
if (!$result) {
    echo "<script>alert('Ocorrência não encontrada.');</script>";
    exit();
}
$comentarioObj = new CComentario();
$tipoObj = new CTipo();
$tipos = $tipoObj->selecionarTipos();


if ($result) {
    $linha = mysqli_fetch_array($result);

    // Busca os comentários relacionados à ocorrência
    $comentariosResult = $comentarioObj->selecionarTodos($id);
    echo"
    <title>Editar Ocorrência</title>
    <script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>
    <script src='https://code.jquery.com/ui/1.13.2/jquery-ui.min.js'></script>
    <link rel='stylesheet' href='https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css'>
    <style>
        h3 {
            text-align: center;
            font-weight: bold;
        }
        p {
            color: red;
            font-weight: bold;
        }
        .form-container {
            border: 1px solid #6d6b6b;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }
        .input-group .btn svg {
            width: 1em;
            height: 1em;
            vertical-align: middle;
            fill: white;
        }
        .btn-group-custom {
            display: flex;
            justify-content: end;
            gap: 10px;
            margin-bottom: 10px;
        }
        .list-group-item small {
            font-size: 12px;
            color: #555;
            display: block;
        }

        .list-group-item div {
            font-size: 14px;
            color: #333;
        }
        
        .list-group-item small span {
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>
    <div class='container mt-4 mb-4'>
        <h3>Formulário de Ocorrência</h3>
        <div class='btn-group-custom'>
            <div>";
                if ($cargoUsuario === "Teleatendente") {
                    echo "<a href='inicioAtendimento.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
                } else {
                    echo "<a href='inicioDespacho.html.php' class='btn btn-primary'>Ir para Página Inicial</a>";
                }
            echo"
            </div>
        </div>
        <div class='form-container'>
            <form action='Ocorrencia.php' method='POST' id='formOcorrencia'>
                <div class='row g-3'>
                    <!-- Primeira Coluna -->
                    <div class='col-md-6 border-end'>
                        <div class='mb-2'>
                            <p>ID da Ocorrencia: $linha[id]</p>
                        </div>
                        <div class='mb-2'>
                            <label for='dataHora' class='form-label'>Data e Hora</label>
                            <input type='datetime-local' class='form-control' name='dataHora' value='$linha[dataHora]' readonly>
                        </div>
                        <div class='mb-2'>
                            <label for='localizacao' class='form-label'>Localização</label>
                            <input type='text' class='form-control' id='localizacao' name='localizacao' value='$linha[localizacao]' required>
                        </div>
                        <div class='mb-2'>
                            <label for='referencia' class='form-label'>Ponto de Referência</label>
                            <input type='text' class='form-control' id='referencia' name='referencia' value='$linha[referencia]'>
                        </div>
                        <div class='mb-2'>
                            <label for='bairro' class='form-label'>Bairro</label>
                            <input type='text' class='form-control' id='bairro' name='bairro' value='$linha[bairro]'>
                        </div>
                        <div class='mb-2'>
                            <label for='cidade' class='form-label'>Cidade</label>
                            <input type='text' class='form-control' id='cidade' name='cidade' value='$linha[cidade]' required>
                        </div>
                        <div class='mb-2'>
                            <label for='tipo' class='form-label'>Tipo</label>
                            <input type='text' class='form-control' id='tipo' name='tipo' value='$linha[tipo]' placeholder='Digite o tipo...' autocomplete='off'>
                        </div>
                        <div class='mb-2'>
                            <label for='subtipo' class='form-label'>Subtipo</label>
                            <input type='text' class='form-control' id='subtipo' name='subtipo' value='$linha[subtipo]' placeholder='Digite o subtipo...' autocomplete='off'>
                        </div>
                        <div class='mb-2'>
                            <label for='solicitante' class='form-label'>Solicitante</label>
                            <input type='text' class='form-control' id='solicitante' name='solicitante' value='$linha[solicitante]' required>
                        </div>
                        <div class='mb-2'>
                            <label for='telefone' class='form-label'>Telefone</label>
                            <input type='tel' class='form-control' id='telefone' name='telefone' value='$linha[telefone]' required>
                        </div>
                        <!-- Botão de Enviar -->
                        <div class='d-flex justify-content-end mt-1'>
                            <input type='hidden' name='id' value='$linha[id]'> 
                            <input type='hidden' name='funcao' value='editar'>
                            <button type='submit' class='btn btn-success'>Salvar</button>
                        </div>
                    </div>
            </form>
                    <!-- Segunda Coluna -->
                    <div class='col-md-6'>
                        <form action='Comentario.php?id=$id' method='POST'>
                            <div class='mb-2'>
                                <label for='comentarioInput' class='form-label'><b>Adicionar Comentário:</b></label>
                                <div class='input-group'>
                                    <textarea class='form-control' id='comentarioInput' name='comentario' rows='1' placeholder='Digite seu comentário'></textarea>
                                    <input type='hidden' name='id_usuario' value='$idUsuario'>
                                    <input type='hidden' name='id_ocorrencia' value='$linha[id]'>
                                    <input type='hidden' name='funcao' value='comentar'>
                                    <button type='submit' class='btn btn-dark' id='adicionarComentario'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                                            <path d='M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480l0-83.6c0-4 1.5-7.8 4.2-10.8L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z'/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class='comentarios mb-2'>
                            <label class='form-label fw-bold'>Comentários</label>
                            <ul class='list-group' id='comentarios'>
    ";
    if ($comentariosResult) {
        while ($comentario = mysqli_fetch_array($comentariosResult)) {
            echo "
            <li class='list-group-item mb-3'>
                <div>{$comentario['comentario']}</div>
                <hr class='my-1'>
                <small class='text-muted'>
                    {$comentario['nomeUsuario']} ({$comentario['cargoUsuario']})
                </small>
            </li>";
        }
    }
    echo "
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class='my-2'>
        </div>
    </div>
    <script src='autocomplete.js'></script>
</body>
    ";
}
?>