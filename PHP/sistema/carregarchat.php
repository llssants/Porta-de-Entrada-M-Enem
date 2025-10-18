<?php
$arquivo = "../JSON/mensagens.json";

if(file_exists($arquivo)) {
    $dados = file_get_contents($arquivo);
    $mensagens = json_decode($dados, true);

    if(is_array($mensagens)) {
        foreach($mensagens as $msg) {
            echo "<p><strong>" . htmlspecialchars($msg['usuario']) . "</strong> [" . $msg['hora'] . "]: " . htmlspecialchars($msg['mensagem']) . "</p>";
        }
    }
}
?>
