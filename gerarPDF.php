<?php
session_start(); // Inicia a sessão

include("libs/fpdf/fpdf.php");
include("CComentario.php"); // Inclui a classe para buscar comentários

// Verifica se a variável de sessão está definida
if (!isset($_SESSION['login_id'])) {
    die("Erro: Sessão expirada. Faça login novamente.");
}

// Recupera os dados da ocorrência via POST
$id = $_POST['id'];
$tipo = $_POST['tipo'];
$subtipo = $_POST['subtipo'];
$localizacao = $_POST['localizacao'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$referencia = $_POST['referencia'];
$solicitante = $_POST['solicitante'];
$telefone = $_POST['telefone'];
$dataHora = $_POST['dataHora']; // Certifique-se de enviar este campo no formulário

// Recupera os comentários da ocorrência
$comentarioObj = new CComentario();
$comentariosResult = $comentarioObj->selecionarTodos($id); // Busca os comentários pelo ID da ocorrência

// Configurações de fonte e layout
class PDF extends FPDF
{
    function Header()
    {
        // Adiciona os brasões e o texto centralizado
        $this->Image('assets/brasao.jpg', 10, 10, 30); // Brasão CIOPS no canto esquerdo
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(50, 15);
        $this->MultiCell(110, 8, mb_convert_encoding("CIOPS - COORDENADORIA INTEGRADA DE OPERAÇÕES DE SEGURANÇA - CÉLULA SOBRAL", 'ISO-8859-1', 'UTF-8'), 0, 'C');
        $this->Image('assets/sspdsce.jpg', 170, 10, 30); // Brasão SSPDS no canto direito
        $this->Ln(5); // Espaçamento após o cabeçalho
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);

// Título com o número da ocorrência e data/hora
$dataHoraFormatada = date('d/m/Y H:i:s', strtotime($dataHora));
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, mb_convert_encoding("OCORRÊNCIA Nº $id - $dataHoraFormatada", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, mb_convert_encoding("$tipo $subtipo", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(10);

// Informações principais
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 8, mb_convert_encoding(
    "Localização: $localizacao\nBairro: $bairro  -  Cidade: $cidade\nReferência: $referencia\nSolicitante: $solicitante  -  Telefone: $telefone",
    'ISO-8859-1',
    'UTF-8'
), 0, 'L');

// Adiciona os comentários
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, mb_convert_encoding('Comentários:', 'ISO-8859-1', 'UTF-8'), 0, 1);

$pdf->SetFont('Arial', '', 12);
if ($comentariosResult) {
    while ($comentario = mysqli_fetch_array($comentariosResult)) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, mb_convert_encoding("{$comentario['nomeUsuario']} ({$comentario['cargoUsuario']}):", 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 6, mb_convert_encoding($comentario['comentario'], 'ISO-8859-1', 'UTF-8'));
        $pdf->Ln(5); // Espaçamento entre os comentários
    }
} else {
    $pdf->Cell(0, 10, mb_convert_encoding('Nenhum comentário encontrado.', 'ISO-8859-1', 'UTF-8'), 0, 1);
}

// Gera o PDF
$pdf->Output('D', "Ocorrencia $id $tipo $subtipo.pdf");
?>
