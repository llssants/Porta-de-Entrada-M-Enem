<?php
require_once '../cadastro/conexao.php';

// Pontuação por dificuldade
$pontosPorDificuldade = [
    'facil' => 3,
    'media' => 5,
    'dificil' => 7
];

// Penalidade: perde 2 pontos a mais do que ganharia
$penalidadePorErro = [
    'facil' => -4,
    'media' => -6,
    'dificil' => -8
];

// Pontos extras
$pontosCheckpointDiario = 1;
$pontosAtividadeDiaria = 5; // caso queira usar futuramente

// Função para dar checkpoint diário (1 ponto apenas uma vez por dia)
function darCheckpointDiario($id_usuario) {
    $conexao = conectar();

    // Busca data do último checkpoint
    $sql = "SELECT data_ultimo_checkpoint FROM ranking WHERE id_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id_usuario]);
    $ultimaData = $stmt->fetchColumn();

    $hoje = date('Y-m-d');

    if ($ultimaData == $hoje) {
        // Já marcou checkpoint hoje
        return false;
    } else {
        // Atualiza pontos e data do checkpoint
        $sql = "UPDATE ranking SET pontos = pontos + ?, data_ultimo_checkpoint = ? WHERE id_usuario = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$GLOBALS['pontosCheckpointDiario'], $hoje, $id_usuario]);
        return true;
    }
}

// Função para atualizar ranking após responder questão
function registrarRespostaQuestao($id_usuario, $id_questao, $acertou) {
    $conexao = conectar();

    // Busca dificuldade da questão
    $sql = "SELECT dificuldade FROM questoes WHERE id_questao = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id_questao]);
    $dificuldade = $stmt->fetchColumn();

    if (!$dificuldade) {
        throw new Exception("Questão não encontrada.");
    }

    global $pontosPorDificuldade, $penalidadePorErro;

    // Define pontos a somar (positivo ou negativo)
    $pontos = $acertou ? $pontosPorDificuldade[$dificuldade] : $penalidadePorErro[$dificuldade];

    // Atualiza pontos no ranking
    $sql = "UPDATE ranking SET pontos = pontos + ? WHERE id_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$pontos, $id_usuario]);

    // Se acertou, atualiza acertos no desempenho
    if ($acertou) {
        $sql = "UPDATE desempenho SET acertos = acertos + 1 WHERE id_usuario = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$id_usuario]);
    }

    // Atualiza nível se possível
    atualizarNivelUsuario($conexao, $id_usuario);
}

// Função para atualizar nível com base na pontuação atual
function atualizarNivelUsuario($conexao, $id_usuario) {
    // Busca pontos e nível atuais
    $sql = "SELECT pontos, nivel FROM ranking WHERE id_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        throw new Exception("Usuário não encontrado no ranking.");
    }

    $pontos = $usuario['pontos'];
    $nivelAtual = $usuario['nivel'];

    // Defina a regra de avanço (exemplo: 100 pontos para subir de nível)
    $pontosParaProximoNivel = 100 * $nivelAtual;

    if ($pontos >= $pontosParaProximoNivel && $nivelAtual < 12) {
        // Sobe um nível
        $sql = "UPDATE ranking SET nivel = nivel + 1 WHERE id_usuario = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([$id_usuario]);
    }
}

// Exemplo de uso:

$id_usuario = 1; // id do usuário logado
$id_questao = 100; // id da questão respondida
$acertou = true; // boolean se acertou ou não

try {
    // Registrar checkpoint diário (só pontua uma vez por dia)
    if (darCheckpointDiario($id_usuario)) {
        echo "Checkpoint diário registrado! +1 ponto\n";
    } else {
        echo "Você já fez o checkpoint hoje.\n";
    }

    // Registrar resposta de questão
    registrarRespostaQuestao($id_usuario, $id_questao, $acertou);
    echo "Resposta registrada com sucesso!\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
