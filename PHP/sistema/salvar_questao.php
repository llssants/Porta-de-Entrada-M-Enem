<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Receber dados do formulário
    $enunciado = $_POST['enunciado'];
    $id_disciplina = $_POST['id_disciplina'];
    $dificuldade = $_POST['dificuldade'];
    $correta = $_POST['correta']; // índice da alternativa correta

    // Tratamento da imagem do enunciado
    $imagem_enunciado = null;
    if (isset($_FILES['imagem_enunciado']) && $_FILES['imagem_enunciado']['error'] == 0) {
        $ext = pathinfo($_FILES['imagem_enunciado']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = 'enunciado_'.time().'.'.$ext;
        move_uploaded_file($_FILES['imagem_enunciado']['tmp_name'], 'uploads/'.$nome_arquivo);
        $imagem_enunciado = $nome_arquivo;
    }

    // Inserir questão
    $stmt = $conexao->prepare("INSERT INTO questoes (enunciado, imagem_enunciado, id_disciplina, dificuldade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $enunciado, $imagem_enunciado, $id_disciplina, $dificuldade);
    if ($stmt->execute()) {
        $id_questao = $stmt->insert_id; // pega o ID da questão inserida
    } else {
        die("Erro ao salvar questão: " . $stmt->error);
    }
    $stmt->close();

    // Inserir alternativas
    if (isset($_POST['alternativas'])) {
        foreach ($_POST['alternativas'] as $index => $alt) {
            $texto = $alt['texto'];
            $imagem_alt = null;

            // Verifica se há imagem para a alternativa
            if (isset($_FILES['alternativas']['name'][$index]['imagem']) &&
                $_FILES['alternativas']['error'][$index]['imagem'] == 0) {

                $ext = pathinfo($_FILES['alternativas']['name'][$index]['imagem'], PATHINFO_EXTENSION);
                $nome_arquivo = 'alt_'.time().'_'.$index.'.'.$ext;
                move_uploaded_file($_FILES['alternativas']['tmp_name'][$index]['imagem'], 'uploads/'.$nome_arquivo);
                $imagem_alt = $nome_arquivo;
            }

            $eh_correta = ($index == $correta) ? 1 : 0;

            $stmt = $conexao->prepare("INSERT INTO alternativas (id_questao, texto, imagem, correta) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $id_questao, $texto, $imagem_alt, $eh_correta);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo "<script>alert('Questão salva com sucesso!'); window.location.href='cadastro_questao.php';</script>";

} else {
    die("Acesso inválido.");
}
?>
