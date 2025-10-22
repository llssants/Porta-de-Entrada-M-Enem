<?php
require_once '../PHP/cadastro/conexao.php'; // já cria $conexao

header('Content-Type: application/json');

// Recebe os dados do JS
$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['id_usuario']) || !isset($data['id_questao'])){
    echo json_encode(['status'=>'erro', 'msg'=>'Parâmetros ausentes']);
    exit;
}

$id_usuario = intval($data['id_usuario']);
$id_questao = intval($data['id_questao']);
$area = $data['area'] ?? 'natureza'; // default: natureza

try {
    // Checa se a questão já foi feita
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM questoes_feitas WHERE id_usuario = ? AND id_questao = ? AND area = ?");
    $stmt->bind_param("iis", $id_usuario, $id_questao, $area);
    $stmt->execute();
    $stmt->bind_result($jaFeita);
    $stmt->fetch();
    $stmt->close();

    if($jaFeita > 0){
        echo json_encode(['status'=>'ok','msg'=>'Questão já registrada']);
        exit;
    }

    // Insere no banco
    $stmt = $conexao->prepare("INSERT INTO questoes_feitas (id_usuario, id_questao, area, data) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $id_usuario, $id_questao, $area);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        echo json_encode(['status'=>'ok','msg'=>'Questão registrada com sucesso']);
    } else {
        echo json_encode(['status'=>'erro','msg'=>'Falha ao registrar questão']);
    }

    $stmt->close();

} catch(Exception $e){
    echo json_encode(['status'=>'erro','msg'=>$e->getMessage()]);
}
