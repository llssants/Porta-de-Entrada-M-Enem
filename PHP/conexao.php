<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';            // <-- senha vazia
$database = 'porta_entrada';

$conexao = new mysqli($host, $usuario, $senha, $database);

if ($conexao->connect_error) {
    die("Falha na conexao: " . $conexao->connect_error);
}
?>
