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

if (isset($_SESSION["cadastrar"])) {  
    if ($_SESSION["cadastrar"] == "1") {
        unset($_SESSION["cadastrar"]);
        //echo "<script>alert('Ocorrência cadastrada com sucesso!');</script>";
    } else if ($_SESSION["cadastrar"] == "2") {
        unset($_SESSION["cadastrar"]);
        echo "<script>alert('Erro ao cadastrar ocorrência.');</script>";
    }
}

include("head.html.php");
include("COcorrencia.php");
include("CComentario.php");
include("CTipo.php");

$ocorrencia = new COcorrencia();
$result = $ocorrencia->selecionarTodas();
$comentarioObj = new CComentario();
$tipoObj = new CTipo();
$tipos = $tipoObj->selecionarTipos();

// Define o valor padrão para o campo dataHora
date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
$dataHoraAtual = date('Y-m-d\TH:i');

echo "
    <title>Gerar Ocorrência</title>
    <script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>
    <script src='https://code.jquery.com/ui/1.13.2/jquery-ui.min.js'></script>
    <link rel='stylesheet' href='https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css'>
    <style>
        h3 {
            text-align: center;
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
    </style>
</head>
<body>
    <div class='container mt-4 mb-4'>
        <h3>Gerar Nova Ocorrência</h3>
        <div class='btn-group-custom'>
            <div>
                <p class='form-control'>ID do Usuário: $idUsuario</p>
            </div>
            <div>
                <p class='form-control'>$nomeUsuario</p>
            </div>
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
                            <label for='dataHora' class='form-label'>Data e Hora</label>
                            <input type='datetime-local' class='form-control' id='dataHora' name='dataHora' value='$dataHoraAtual' readonly>
                        </div>
                        <div class='mb-2'>
                            <label for='localizacao' class='form-label'>Localização</label>
                            <input type='text' class='form-control' id='localizacao' name='localizacao'>
                        </div>
                        <div class='mb-2'>
                            <label for='referencia' class='form-label'>Ponto de Referência</label>
                            <input type='text' class='form-control' id='referencia' name='referencia'>
                        </div>
                        <div class='mb-2'>
                            <label for='bairro' class='form-label'>Bairro</label>
                            <input type='text' class='form-control' id='bairro' name='bairro'>
                        </div>
                        <div class='mb-2'>
                            <label for='cidade' class='form-label'>Cidade</label>
                            <input type='text' class='form-control' id='cidade' name='cidade'>
                        </div>
                        <div class='mb-2'>
                            <label for='tipo' class='form-label'>Tipo</label>
                            <input type='text' class='form-control' id='tipo' name='tipo' placeholder='Digite o tipo...' autocomplete='off' required>
                        </div>
                        <div class='mb-2'>
                            <label for='subtipo' class='form-label'>Subtipo</label>
                            <input type='text' class='form-control' id='subtipo' name='subtipo' placeholder='Digite o subtipo...' autocomplete='off'>
                        </div>
                        <div class='mb-2'>
                            <label for='solicitante' class='form-label'>Solicitante</label>
                            <input type='text' class='form-control' id='solicitante' name='solicitante'>
                        </div>
                        <div class='mb-2'>
                            <label for='telefone' class='form-label'>Telefone</label>
                            <input type='tel' class='form-control' id='telefone' name='telefone'>
                        </div>
                    </div>
                    <!-- Segunda Coluna -->
                    <div class='col-md-6'>
                        <div class='mb-2'>
                            <label for='comentarioInput' class='form-label'><b>Adicionar Comentário:</b></label>
                            <!--
                            <div class='input-group'>
                                <textarea class='form-control' id='comentarioInput' rows='1' placeholder='Digite seu comentário'></textarea>
                                <button type='button' class='btn btn-dark' id='adicionarComentario'>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                                        <path d='M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480l0-83.6c0-4 1.5-7.8 4.2-10.8L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z'/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class='comentarios mb-2'>
                            <label class='form-label fw-bold'>Comentários</label>
                            <ul class='list-group' id='comentarios'>
                                <!-- Comentários serão exibidos aqui -->
                                <p> É preciso Gerar a Ocorrência para poder Adicionar um Comentário.</p>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Linha divisória -->
                <hr class='my-2'>

                <!-- Botão de Cadastrar -->
                <div class='d-flex justify-content-end mt-1'>
                    <input type='hidden' name='usuario' value='$nomeUsuario'>
                    <input type='hidden' name='funcao' value='cadastrar'>
                    <button type='submit' class='btn btn-success'>Gerar Ocorrência</button>
                </div>
            </form>
        </div>
    </div>
    <script src='autocomplete.js'></script>
</body>;"
?>