<?php
session_start();  // Inicia a sessão

// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redireciona para a página de login
header('Location: login.html.php');
exit();  // Certifica-se de que nenhum código após o redirecionamento seja executado
?>