<?php 
include '../cadastro/conexao.php'; 

function salvarImagem($file) {
    // Pasta destino correta (Upload dentro de IMG)
    $pasta = realpath(__DIR__ . '/../../IMG/Upload');
    if (!$pasta) {
        $pasta = __DIR__ . '/../../IMG/Upload';
        mkdir($pasta, 0777, true);
    }

    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $tmpName = $file['tmp_name'];
        $originalName = basename($file['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $extPermitidas)) {
            return null; 
        }

        $novoNome = uniqid('img_') . '.' . $ext;
        $destino = $pasta . '/' . $novoNome;

        if (move_uploaded_file($tmpName, $destino)) {
            return $novoNome; // salva só o nome do arquivo
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enunciado = $_POST['enunciado'] ?? '';
    $id_disciplina = $_POST['id_disciplina'] ?? null;
    $id_topico = $_POST['id_topico'] ?? null;
    $dificuldade = $_POST['dificuldade'] ?? 'medio';
    $origem = $_POST['origem'] ?? null;
    $ano = $_POST['ano'] ?? null;

    $nomeImagemEnunciado = salvarImagem($_FILES['imagem_enunciado']);

    $conexao->begin_transaction();

    try {
        $sqlQuestao = "INSERT INTO questoes (enunciado, imagem_enunciado, id_disciplina, dificuldade, origem, ano, id_topico)
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sqlQuestao);
        $stmt->bind_param('ssisssi', $enunciado, $nomeImagemEnunciado, $id_disciplina, $dificuldade, $origem, $ano, $id_topico);
        $stmt->execute();

        $id_questao = $stmt->insert_id;
        $stmt->close();

        // SALVAR ALTERNATIVAS
        $alternativas = $_POST['alternativas'] ?? [];
        $correta = $_POST['correta'] ?? null;

        foreach ($alternativas as $key => $alt) {
            $texto = $alt['texto'] ?? '';
            $imagemAlt = null;

            if (isset($_FILES['alternativas']['name'][$key]['imagem']) &&
                $_FILES['alternativas']['error'][$key]['imagem'] === UPLOAD_ERR_OK) {

                $fileAlt = [
                    'name' => $_FILES['alternativas']['name'][$key]['imagem'],
                    'type' => $_FILES['alternativas']['type'][$key]['imagem'],
                    'tmp_name' => $_FILES['alternativas']['tmp_name'][$key]['imagem'],
                    'error' => $_FILES['alternativas']['error'][$key]['imagem'],
                    'size' => $_FILES['alternativas']['size'][$key]['imagem']
                ];

                $imagemAlt = salvarImagem($fileAlt);
            }

            $ehCorreta = ($correta == $key) ? 1 : 0;

            $sqlAlt = "INSERT INTO alternativas (id_questao, texto, imagem, correta) VALUES (?, ?, ?, ?)";
            $stmtAlt = $conexao->prepare($sqlAlt);
            $stmtAlt->bind_param('issi', $id_questao, $texto, $imagemAlt, $ehCorreta);
            $stmtAlt->execute();
            $stmtAlt->close();
        }

        $conexao->commit();
        header('Location: cadastro_questao.php?msg=Questão cadastrada com sucesso');
        exit;

    } catch (Exception $e) {
        $conexao->rollback();
        echo "Erro: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cadastrar Questão</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/estilos.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h1 class="mb-4">Cadastrar Questão</h1>

  <!-- IMPORTANTE: o action agora aponta pro próprio arquivo -->
  <form id="form-questao" method="post" action="" enctype="multipart/form-data">
    
    <!-- Enunciado -->
    <div class="mb-3">
      <label class="form-label">Enunciado</label>
      <textarea class="form-control" name="enunciado" required></textarea>
    </div>

    <!-- Imagem do Enunciado -->
    <div class="mb-3">
      <label class="form-label">Imagem do enunciado (opcional)</label>
      <input type="file" class="form-control" name="imagem_enunciado" accept="image/*">
    </div>

    <!-- Origem e Ano -->
    <div class="row">
      <div class="col-md-8 mb-3">
        <label class="form-label">Origem da questão</label>
        <input type="text" class="form-control" name="origem" placeholder="Ex: ENEM, UNICAMP, Fuvest...">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Ano</label>
        <input type="number" class="form-control" name="ano" placeholder="Ex: 2023" min="1900" max="2099">
      </div>
    </div>

    <!-- Disciplina -->
    <div class="mb-3">
      <label class="form-label">Disciplina</label>
      <select class="form-select" name="id_disciplina" id="disciplina" required>
        <option value="">Selecione</option>
        <?php
            $result = $conexao->query("SELECT id_disciplina, nome FROM disciplinas ORDER BY nome");
            if ($result && $result->num_rows > 0) {
              while ($d = $result->fetch_assoc()) {
                  echo "<option value='{$d['id_disciplina']}'>{$d['nome']}</option>";
              }
            }
        ?>
      </select>
    </div>

    <!-- Tópico -->
    <div class="mb-3" id="topico-container" style="display:none;">
      <label class="form-label">Tópico</label>
      <select class="form-select" name="id_topico" id="topico">
        <option value="">Selecione um tópico</option>
      </select>
    </div>

    <!-- Dificuldade -->
    <div class="mb-3">
      <label class="form-label">Dificuldade</label>
      <select class="form-select" name="dificuldade">
        <option value="facil">Fácil</option>
        <option value="medio" selected>Médio</option>
        <option value="dificil">Difícil</option>
      </select>
    </div>

    <!-- Alternativas -->
    <h4 class="mt-4">Alternativas</h4>
    <div id="alternativas"></div>

    <button type="button" class="btn btn-secondary mb-3" onclick="adicionarAlternativa()">Adicionar alternativa</button>
    <button type="submit" class="btn btn-primary w-100">Salvar Questão</button>
  </form>
</div>

<script>
let contadorAlt = 0;

function adicionarAlternativa() {
  contadorAlt++;
  const container = document.getElementById('alternativas');
  const card = document.createElement('div');
  card.className = 'alternativa-card border rounded p-3 mb-3 bg-light';
  card.innerHTML = `
    <div class="mb-2">
      <label>Texto da alternativa</label>
      <input type="text" name="alternativas[${contadorAlt}][texto]" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Imagem da alternativa (opcional)</label>
      <input type="file" name="alternativas[${contadorAlt}][imagem]" class="form-control" accept="image/*">
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="correta" value="${contadorAlt}" required>
      <label class="form-check-label">Alternativa correta</label>
    </div>
  `;
  container.appendChild(card);
}

for (let i = 0; i < 4; i++) adicionarAlternativa();

document.getElementById('disciplina').addEventListener('change', function() {
  const idDisciplina = this.value;
  const topicoContainer = document.getElementById('topico-container');
  const selectTopico = document.getElementById('topico');

  if (idDisciplina) {
    fetch(`buscar_topicos.php?id_disciplina=${idDisciplina}`)
      .then(res => res.json())
      .then(data => {
        selectTopico.innerHTML = '<option value="">Selecione um tópico</option>';
        data.forEach(t => {
          const opt = document.createElement('option');
          opt.value = t.id_topico;
          opt.textContent = t.nome;
          selectTopico.appendChild(opt);
        });
        topicoContainer.style.display = 'block';
      });
  } else {
    topicoContainer.style.display = 'none';
    selectTopico.innerHTML = '<option value="">Selecione um tópico</option>';
  }
});
</script>
</body>
</html>
