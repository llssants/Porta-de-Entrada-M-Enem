<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Caminho do arquivo JSON
$arquivo = "../JSON/mensagens.json";

if(!file_exists($arquivo)) {
    file_put_contents($arquivo, "[]"); // cria o arquivo se não existir
}

$dados = file_get_contents($arquivo);
$mensagens = json_decode($dados, true);

