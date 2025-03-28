<?php
session_start();
if (isset($_SESSION["cadastrar"])){  
    if($_SESSION["cadastrar"] == "1") {
        unset($_SESSION["cadastrar"]);
        echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
    }
    else if ($_SESSION["cadastrar"] == "2"){
        unset($_SESSION["cadastrar"]);
        echo "<script>alert('Erro ao cadastrar Usuário.');</script>";
    }
}

include("head.html.php");

echo"
    <title>Cadastrar Usuário</title>
    <style>
        h3{
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
    </style>
</head>
<body>
    <div class='container mt-4 mb-4'>
        <h3>Cadastro de Usuário</h3>
        <div class='form-container'>
            <form action='Usuario.php' method='POST'>
                <div class='row g-3'>
                    <!-- Primeira Coluna -->
                    <div class='col-md-12'>
                        <div class='mb-2'>
                            <label for='nome' class='form-label'>Nome</label>
                            <input type='text' class='form-control' id='nome' name='nome'>
                        </div>
                        <div class='mb-2'>
                            <label for='cpf' class='form-label'>Cpf</label>
                            <input type='text' class='form-control' id='cpf' name='cpf'>
                        </div>
                        <div class='mb-2'>
                            <label for='cargo' class='form-label'>Função</label>
                            <select class='form-control' id='cargo' name='cargo'>
                                <option value='Teleatendente'>Teleatendente</option>
                                <option value='Despacho_PM'>Despacho_PM</option>
                                <option value='Despacho_BM'>Despacho_BM</option>
                                <option value='Despacho_GCMS'>Despacho_GCMS</option>
                                <option value='Monitor'>Monitor</option>
                                <option value='Coordenador'>Coordenador</option>
                                <option value='Supervisor'>Supervisor</option>
                                <option value='Suporte'>Suporte</option>
                            </select>                            
                        </div>
                    </div>
                </div>

                <!-- Linha divisória -->
                <hr class='my-2'>

                <!-- Botão de Cadastrar -->
                <input type= 'hidden' name='funcao' value='cadastrar'>
                <div class='d-flex justify-content-end mt-1'>
                    <button type='submit' class='btn btn-success'>Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
";
?>