<?php

$host = 'localhost';
$usuario = 'root';
$senha = '';
$database = 'porta_entrada';

$conexão = new mysqli($host, $usuario, $senha, $database);

if ($conexão->connect_error) {
    die("Falha na conexão: " . $conexão->connect_error);
}
?>