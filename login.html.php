<?php
include("head.html.php");
echo"
    <title>CIOPS - Login</title>
    <style>
        h3, img {
            text-align: center;
            font-weight: bold;
        }
        .form-container {
            border: 1px solid #6d6b6b;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
            width: 400px; /* Define largura fixa */
            height: auto; /* Permite altura flexível */
            margin: 0 auto; /* Centraliza horizontalmente */
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ocupa a altura total da janela */
            margin: 0;
            background-color: #e9ecef;
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
    <div class='form-container'>
        <div style='text-align: center;'>
            <img src='./assets/atalaia.jpg' width='300px'>
        </div>
        <form action='Usuario.php' method='POST'>
            <div class='mb-3'>
                <label for='cpf' class='form-label fw-bold'>Cpf:</label>
                <input type='text' class='form-control' id='cpf' name='cpf' required>
            </div>
            <div class='mb-3'>
                <label for='cargo' class='form-label fw-bold'>Função:</label>
                <select class='form-control' id='cargo' name='cargo' required>
                    <option value='Teleatendente'>Teleatendente</option>
                    <option value='Despacho_PM'>Despacho_PM</option>
                    <option value='Despacho_BM'>Despacho_BM</option>
                    <option value='Despacho_GCMS'>Despacho_GCMS</option>
                    <option value='Monitor'>Monitor(a)</option>
                    <option value='Coordenador'>Coordenador(a)</option>
                    <option value='Supervisor'>Supervisor(a)</option>
                    <option value='Suporte'>Suporte</option>
                </select>
            </div>

            <hr class='my-2'>

            <input type='hidden' name='funcao' value='login'>
            <div class='d-flex justify-content-center mt-3'>
                <button type='submit' class='btn btn-primary form-control'>Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
";
?>