<?php
require "../cadastro/conexao.php";

// Verifica se veio o nome do tópico na URL
if (!isset($_GET['topico'])) {
    die("Tópico não especificado.");
}

// Recebe o nome do tópico (ex: Arte na Idade Média)
$nome_topico = $_GET['topico'];

// Busca o id_topico correspondente a esse nome
$sql_topico = "SELECT id_topico, nome FROM topicos WHERE nome = ?";
$stmt_topico = $conexao->prepare($sql_topico);
$stmt_topico->bind_param("s", $nome_topico);
$stmt_topico->execute();
$result_topico = $stmt_topico->get_result();

if ($result_topico->num_rows === 0) {
    die("Tópico não encontrado no banco de dados.");
}

$dados_topico = $result_topico->fetch_assoc();
$id_topico = $dados_topico['id_topico'];
$nome_topico = $dados_topico['nome'];

// Agora busca as questões relacionadas a esse tópico
$sql = "SELECT 
            q.id_questao,
            q.enunciado,
            q.imagem_enunciado,
            a.id_alternativa,
            a.texto,
            a.correta
        FROM questoes q
        JOIN alternativas a ON q.id_questao = a.id_questao
        WHERE q.id_topico = ?
        ORDER BY q.id_questao";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_topico);
$stmt->execute();
$result = $stmt->get_result();

// Agrupar alternativas por questão
$questoes = [];
while ($row = $result->fetch_assoc()) {
    $id = $row['id_questao'];
    if (!isset($questoes[$id])) {
        $questoes[$id] = [
            'enunciado' => $row['enunciado'],
            'imagem_enunciado' => $row['imagem_enunciado'],
            'alternativas' => []
        ];
    }
    $questoes[$id]['alternativas'][] = [
        'id' => $row['id_alternativa'],
        'texto' => $row['texto'],
        'correta' => $row['correta']
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Questões – <?= htmlspecialchars($nome_topico) ?></title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f6f6f6;
      margin: 0;
      padding: 20px;
    }
    h1 {
      color: #8B0000;
      margin-bottom: 20px;
      border-left: 5px solid #CD5C5C;
      padding-left: 10px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .questao {
      margin-bottom: 20px;
      padding: 15px;
      background: #fff;
      border: 1px solid #f0f0f0;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .questao h3 {
      margin: 0 0 10px;
    }
    .enunciado {
      margin-top: 10px;
      margin-bottom: 20px;
      font-size: 16px;
    }
    .alternativas {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .alternativa {
      border: 1px solid #ddd;
      padding: 10px 15px;
      border-radius: 6px;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      cursor: pointer;
      transition: 0.2s;
      gap: 10px;
    }
    .alternativa.selected {
      background: #e0f7ff;
    }
    .alternativa.correct {
      background: #d4edda;
      border-color: #28a745;
    }
    .alternativa.wrong {
      background: #f8d7da;
      border-color: #dc3545;
    }
    .alternativa.subline {
      text-decoration: line-through;
      color: #555;
    }
    .xMark {
      cursor: pointer;
      font-weight: bold;
      color: #007bff;
      margin-left: auto;
      user-select: none;
    }
    button {
      margin-top: 15px;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      background: #007bff;
      color: #fff;
      cursor: pointer;
    }
    button:disabled {
      background: #aaa;
    }
    .nav {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    img.enunciado-img {
      max-width: 100%;
      border-radius: 6px;
      margin-bottom: 10px;
      object-fit: contain;
    }
    img.alt-img {
      max-width: 120px;
      max-height: 80px;
      border-radius: 6px;
      object-fit: contain;
    }
    span.alt-text {
      flex: 1;
    }
  </style>
</head>
<body>

<div class="container">
  <h1><?= htmlspecialchars($nome_topico) ?></h1>
  <div id="areaQuestao"></div>
  <div class="nav">
    <button id="prevBtn">Anterior</button>
    <button id="nextBtn">Próxima</button>
  </div>
</div>

<script>
const questoes = <?php echo json_encode(array_values($questoes), JSON_UNESCAPED_UNICODE); ?>;
let index = 0;
const area = document.getElementById("areaQuestao");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

function renderQuestao(i){
  const q = questoes[i];
  let html = `<div class="questao">
                <h3>Questão ${i+1} de ${questoes.length}</h3>`;
  if(q.imagem_enunciado){
    html += `<img src="../../IMG/Upload/${q.imagem_enunciado}" class="enunciado-img" alt="Imagem da questão">`;
  }
  html += `<p class="enunciado">${q.enunciado}</p>`;

  html += `<div class="alternativas">`;
  q.alternativas.forEach((a, idx)=>{
    html += `<div class="alternativa" onclick="selecionar(this)">
               <span class="alt-text">${String.fromCharCode(65+idx)}) ${a.texto}</span>`;
    html += `<span class="xMark" onclick="toggleX(event,this)">X</span>
             </div>`;
  });
  html += `</div>
           <button onclick="verificarResposta(event, ${i})">Verificar resposta</button>
           </div>`;
  area.innerHTML = html;

  prevBtn.disabled = (i === 0);
  nextBtn.disabled = (i === questoes.length - 1);
}

function selecionar(el){
  document.querySelectorAll(".alternativa").forEach(div=>{
    div.classList.remove("selected");
  });
  el.classList.add("selected");
}

function toggleX(event, el){
  event.stopPropagation();
  el.parentElement.classList.toggle("subline");
}

function verificarResposta(event, i){
  const q = questoes[i];
  const divs = document.querySelectorAll(".alternativa");
  divs.forEach((div, idx)=>{
    if(q.alternativas[idx].correta){
      div.classList.add("correct");
    } else if(div.classList.contains("selected")){
      div.classList.add("wrong");
    }
  });
  event.target.disabled = true;
}

prevBtn.onclick = () => { if(index > 0){ index--; renderQuestao(index); } };
nextBtn.onclick = () => { if(index < questoes.length - 1){ index++; renderQuestao(index); } };

if(questoes.length > 0) {
  renderQuestao(index);
} else {
  area.innerHTML = "<p>Nenhuma questão encontrada.</p>";
}
</script>

</body>
</html>
