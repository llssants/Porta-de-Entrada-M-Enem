<?php
require_once '../PHP/cadastro/conexao.php'; // caminho do teu arquivo de conexão
header('Content-Type: application/json');

// --- LER DADOS VINDOS DO FETCH ---
$data = json_decode(file_get_contents('php://input'), true);
$id_usuario = $data['id_usuario'] ?? 0;
$id_questao = $data['id_questao'] ?? 0;
$acertou = $data['acertou'] ?? 0;
$nivel = strtolower($data['nivel'] ?? 'médio');
$data_resposta = date('Y-m-d H:i:s');

if (!$id_usuario || !$id_questao) {
    echo json_encode(['status'=>'erro', 'msg'=>'Dados incompletos.']);
    exit;
}

// --- DEFINIR PONTOS POR NÍVEL ---
$pontos = 0;
if ($acertou == 1) {
    switch ($nivel) {
        case 'fácil':
        case 'facil':
            $pontos = 1;
            break;
        case 'médio':
        case 'medio':
            $pontos = 2;
            break;
        case 'difícil':
        case 'dificil':
            $pontos = 3;
            break;
        default:
            $pontos = 1; // padrão
    }
}

// --- REGISTRAR EM questoes_feitas ---
$sql = "INSERT INTO questoes_feitas (id_usuario, id_questao, acertou, data_resposta)
        VALUES (?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("iiis", $id_usuario, $id_questao, $acertou, $data_resposta);
$stmt->execute();

// --- VERIFICAR SE USUÁRIO JÁ TEM REGISTRO EM desempenho ---
$sql_check = "SELECT * FROM desempenho WHERE id_usuario = ?";
$stmt2 = $conexao->prepare($sql_check);
$stmt2->bind_param("i", $id_usuario);
$stmt2->execute();
$res = $stmt2->get_result();

if ($res->num_rows > 0) {
    // Atualiza o desempenho existente
    $d = $res->fetch_assoc();

    $acertos = $d['acertos'] + ($acertou ? 1 : 0);
    $erros = $d['erros'] + ($acertou ? 0 : 1);
    $pontos_totais = $d['pontos'] + $pontos;

    $sql_up = "UPDATE desempenho SET acertos=?, erros=?, pontos=? WHERE id_usuario=?";
    $stmt3 = $conexao->prepare($sql_up);
    $stmt3->bind_param("iiii", $acertos, $erros, $pontos_totais, $id_usuario);
    $stmt3->execute();

} else {
    // Cria o primeiro registro
    $acertos = $acertou ? 1 : 0;
    $erros = $acertou ? 0 : 1;

    $sql_ins = "INSERT INTO desempenho (id_usuario, acertos, media_redacao, erros, pontos)
                VALUES (?, ?, 0, ?, ?)";
    $stmt4 = $conexao->prepare($sql_ins);
    $stmt4->bind_param("iiii", $id_usuario, $acertos, $erros, $pontos);
    $stmt4->execute();
}

echo json_encode(['status'=>'ok', 'msg'=>'Resposta registrada e desempenho atualizado.']);
?>
