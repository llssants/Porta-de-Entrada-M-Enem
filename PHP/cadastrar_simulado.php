<?php include 'conexao.php'; ?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cadastrar Questão</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .container { max-width: 800px; }
    .alternativa-card {
        border: 1px solid #ddd; 
        border-radius: 10px; 
        padding: 15px; 
        background: #fff;
        margin-bottom: 10px;
    }
  </style>
</head>
<body class="p-4">
<div class="container">
  <h1 class="mb-4">Cadastrar Questão Simulado</h1>

  <form id="form-questao" method="post" action="salvar_questao.php" enctype="multipart/form-data">
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

    <!-- Disciplina -->
    <div class="mb-3">
      <label class="form-label">Disciplina</label>
      <select class="form-select" name="id_disciplina" required>
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

    <div id="alternativas">
      <!-- Gerado pelo JS -->
    </div>

    <button type="button" class="btn btn-secondary mb-3" onclick="adicionarAlternativa()">Adicionar alternativa</button>

    <button type="submit" class="btn btn-primary w-100">Salvar Questão</button>
  </form>
</div>

<script>
const input = document.getElementById('imagemEnunciado');

// Captura imagem do clipboard e adiciona ao input
document.addEventListener('paste', function(e) {
  const items = e.clipboardData?.items;
  if (!items) return;

  for (let i = 0; i < items.length; i++) {
    if (items[i].type.indexOf('image') !== -1) {
      const blob = items[i].getAsFile();

      // Cria um DataTransfer para simular arquivo no input
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(blob);
      input.files = dataTransfer.files;

      // Mostrar preview da imagem
      const reader = new FileReader();
      reader.onload = function(event) {
        let imgPreview = document.getElementById('previewImagem');
        if (!imgPreview) {
          imgPreview = document.createElement('img');
          imgPreview.id = 'previewImagem';
          imgPreview.style.maxWidth = '200px';
          imgPreview.style.display = 'block';
          input.parentNode.appendChild(imgPreview);
        }
        imgPreview.src = event.target.result;
      }
      reader.readAsDataURL(blob);
    }
  }
});

let contadorAlt = 0;

// Função para adicionar alternativa
function adicionarAlternativa() {
  const container = document.getElementById('alternativas');
  contadorAlt++;

  const card = document.createElement('div');
  card.className = 'alternativa-card mb-3';

  card.innerHTML = `
    <div class="mb-2">
      <label>Texto da alternativa</label>
      <input type="text" name="alternativas[${contadorAlt}][texto]" class="form-control">
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

// Adiciona 4 alternativas por padrão
for (let i = 0; i < 4; i++) adicionarAlternativa();
</script>

</body>
</html>
