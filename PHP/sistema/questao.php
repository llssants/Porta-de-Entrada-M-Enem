<?php
require "conexao.php";

// Buscar todas as questões + alternativas
$sql = "SELECT q.id_questao, q.enunciado, q.imagem_enunciado, 
               a.id_alternativa, a.texto, a.imagem, a.correta
        FROM questoes q
        JOIN alternativas a ON q.id_questao = a.id_questao
        ORDER BY q.id_questao ASC, a.id_alternativa ASC";

$result = $conexao->query($sql);

// Organizar em array por questão
$questoes = [];
if ($result && $result->num_rows > 0) {
    while ($linha = $result->fetch_assoc()) {
        $id = $linha['id_questao'];
        if (!isset($questoes[$id])) {
            $questoes[$id] = [
                'id' => $id,
                'enunciado' => $linha['enunciado'],
                'imagem' => $linha['imagem_enunciado'],
                'alternativas' => []
            ];
        }
        $questoes[$id]['alternativas'][] = [
            'id' => $linha['id_alternativa'],
            'texto' => $linha['texto'],
            'imagem' => $linha['imagem'],
            'correta' => (bool)$linha['correta']
        ];
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Simulado</title>
<style>
body{font-family:Arial,sans-serif;background:#f6f6f6;margin:0;padding:20px}
.container{max-width:900px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1)}
.questao{margin-bottom:20px}
h3{margin:0 0 10px}
.enunciado{margin-top:10px;margin-bottom:20px}
.alternativas{display:flex;flex-direction:column;gap:10px}
.alternativa{border:1px solid #ddd;padding:10px 15px;border-radius:6px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;transition:0.2s;position:relative}
.alternativa.selected{background:#e0f7ff}
.alternativa.correct{background:#d4edda;border-color:#28a745}
.alternativa.wrong{background:#f8d7da;border-color:#dc3545}
.alternativa.subline{text-decoration:line-through;color:#555}
.xMark{cursor:pointer;font-weight:bold;color:#007bff;margin-left:10px;user-select:none}
button{margin-top:15px;padding:8px 16px;border:none;border-radius:6px;background:#007bff;color:#fff;cursor:pointer}
button:disabled{background:#aaa}
.nav{display:flex;justify-content:space-between;margin-top:20px}
img{max-width:100%;border-radius:6px;margin-bottom:10px}
</style>
</head>
<body>
<div class="container">
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
  if(q.imagem){ html += `<img src="${q.imagem}" alt="Imagem da questão">`; }
  html += `<p class="enunciado">${q.enunciado}</p>`;

  html += `<div class="alternativas">`;
  q.alternativas.forEach((a, idx)=>{
    html += `<div class="alternativa" onclick="selecionar(this)">
               <span>${String.fromCharCode(65+idx)}) ${a.texto}</span>
               <span class="xMark" onclick="toggleX(event,this)">X</span>
             </div>`;
  });
  html += `</div>
           <button onclick="verificarResposta(${i})">Verificar resposta</button>
           </div>`;
  area.innerHTML = html;

  prevBtn.disabled = (i===0);
  nextBtn.disabled = (i===questoes.length-1);
}

// Seleciona alternativa
function selecionar(el){
  document.querySelectorAll(".alternativa").forEach(div=>{
    div.classList.remove("selected");
  });
  el.classList.add("selected");
}

// Marca ou desmarca X para risco
function toggleX(event, el){
  event.stopPropagation();
  el.parentElement.classList.toggle("subline");
}

// Verifica resposta
function verificarResposta(i){
  const q = questoes[i];
  const divs = document.querySelectorAll(".alternativa");
  divs.forEach((div, idx)=>{
    if(q.alternativas[idx].correta){
      div.classList.add("correct");
    } else if(div.classList.contains("selected")){
      div.classList.add("wrong");
    }
  });
}

prevBtn.onclick = ()=>{ if(index>0){ index--; renderQuestao(index); } }
nextBtn.onclick = ()=>{ if(index<questoes.length-1){ index++; renderQuestao(index); } }

if(questoes.length>0){
  renderQuestao(index);
} else {
  area.innerHTML = "<p>Nenhuma questão encontrada.</p>";
}
</script>
</body>
</html>
