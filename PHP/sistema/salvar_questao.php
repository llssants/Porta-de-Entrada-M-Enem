<?php
include '../cadastro/conexao.php';

function salvarImagem($file){
    $pasta = realpath(__DIR__ . '/../../IMG/Upload');
    if (!$pasta){
        $pasta = __DIR__ . '/../../IMG/Upload';
        mkdir($pasta, 0777, true);
    }

    if(isset($file) && $file['error'] === UPLOAD_ERR_OK){
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $nome_arquivo = uniqid('img_') . '.' . $ext;
        $destino = $pasta . '/' . $nome_arquivo;

        if(move_uploaded_file($file['tmp_name'], $destino)){
            return $nome_arquivo;
        }
    }
    return null;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $enunciado = $_POST['enunciado'] ?? '';
    $id_disciplina = $_POST['id_disciplina'] ?? null;
    $dificuldade = $_POST['dificuldade'] ?? 'medio';
    $correta = $_POST['correta'] ?? null;

    $imagem_enunciado = salvarImagem($_FILES['imagem_enunciado']);

    // Inserir questão
    $stmt = $conexao->prepare("INSERT INTO questoes (enunciado, imagem_enunciado, id_disciplina, dificuldade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $enunciado, $imagem_enunciado, $id_disciplina, $dificuldade);
    $stmt->execute();
    $id_questao = $stmt->insert_id;
    $stmt->close();

    // Inserir alternativas
    if(isset($_POST['alternativas']) && is_array($_POST['alternativas'])){
        foreach($_POST['alternativas'] as $index => $alt){
            $texto = $alt['texto'] ?? '';
            $imagem_alt = null;

            if(isset($_FILES['alternativas']['name'][$index]['imagem']) &&
               $_FILES['alternativas']['error'][$index]['imagem'] === UPLOAD_ERR_OK){
                $fileAlt = [
                    'name' => $_FILES['alternativas']['name'][$index]['imagem'],
                    'tmp_name' => $_FILES['alternativas']['tmp_name'][$index]['imagem'],
                    'error' => $_FILES['alternativas']['error'][$index]['imagem']
                ];
                $imagem_alt = salvarImagem($fileAlt);
            }

            $eh_correta = ($index == $correta) ? 1 : 0;

            $stmtAlt = $conexao->prepare("INSERT INTO alternativas (id_questao, texto, imagem, correta) VALUES (?, ?, ?, ?)");
            $stmtAlt->bind_param("issi", $id_questao, $texto, $imagem_alt, $eh_correta);
            $stmtAlt->execute();
            $stmtAlt->close();
        }
    }

    echo "<script>alert('Questão salva com sucesso!'); window.location.href='cadastro_questao.php';</script>";
    exit;
} else {
    die("Acesso inválido.");
}
?>
