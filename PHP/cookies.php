<?php
if (!isset($_SESSION)) {
    session_start();
}

$cookie_nome = "site_usuario";
$usuario = "aceito";

setcookie($cookie_nome, $usuario, time() + 31536000, "/", "", false, true);

// Redireciona para a página inicial para verificar se o cookie foi salvo
header("Location: index.php");
exit();
?>
