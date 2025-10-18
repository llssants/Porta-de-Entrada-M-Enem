<?php
include '../cadastro/conexao.php';

$id = $_GET['id_disciplina'] ?? 0;
$dados = [];

$sql = "SELECT id_topico, nome FROM topicos WHERE id_disciplina = $id ORDER BY nome";
$result = $conexao->query($sql);

if ($result && $result->num_rows > 0) {
  while ($t = $result->fetch_assoc()) {
    $dados[] = $t;
  }
}

header('Content-Type: application/json');
echo json_encode($dados);
?>
