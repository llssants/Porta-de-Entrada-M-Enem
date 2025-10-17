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
<link rel="stylesheet" href="../CSS/simulado.css">
</head>
<body>

<!-- Header com tempo -->
<div class="header-timer">
  <div class="tempo" id="timer">Tempo restante: 90:00</div>
  <div class="menu">
    <span></span><span></span><span></span>
  </div>
</div>

<!-- Container da questão -->
<div class="container">
  <div id="areaQuestao"></div>

  <!-- Navegação -->
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

// Armazenar escolhas e dúvidas
const respostas = Array(questoes.length).fill(null);
const duvidas = Array(questoes.length).fill(false);

// Timer (90 minutos)
let totalSegundos = 90*60;
const timerEl = document.getElementById("timer");
const timerInterval = setInterval(()=>{
    const h = Math.floor(totalSegundos/3600).toString().padStart(2,'0');
    const min = Math.floor((totalSegundos%3600)/60).toString().padStart(2,'0');
    const seg = (totalSegundos%60).toString().padStart(2,'0');
    timerEl.textContent = `Tempo restante: ${h}:${min}:${seg}`;
    totalSegundos--;
    if(totalSegundos<0){ clearInterval(timerInterval); alert("Tempo esgotado!"); }
},1000);

function renderQuestao(i){
  const q = questoes[i];
  let html = `<div class="questao">
                <h3>Questão ${i+1} de ${questoes.length}</h3>
                <div class="badges">
                  <span>Enem 2024</span>
                  <span>Prova Amarela</span>
                </div>`;
  if(q.imagem){ html += `<img src="${q.imagem}" alt="Imagem da questão" style="max-width:100%;margin-bottom:10px;border-radius:6px">`; }
  html += `<p class="enunciado">${q.enunciado}</p>`;
  html += `<div class="alternativas">`;
  q.alternativas.forEach((a, idx)=>{
    const sel = respostas[i]===idx ? "selected" : "";
    html += `<div class="alternativa ${sel}" onclick="selecionar(this, ${idx})">
               <span>${String.fromCharCode(65+idx)}) ${a.texto}</span>
               ${a.imagem ? `<img src="${a.imagem}" alt="Imagem alternativa" style="max-width:50px;margin-left:10px;border-radius:4px">` : ''}
               <span class="xMark" onclick="toggleX(event,this)">X</span>
             </div>`;
  });
  area.innerHTML = html;

  prevBtn.disabled = (i===0);
  nextBtn.disabled = (i===questoes.length-1);
}

// Seleciona alternativa
function selecionar(el, idx){
  document.querySelectorAll(".alternativa").forEach(div=>{
    div.classList.remove("selected");
  });
  el.classList.add("selected");
  respostas[index] = idx;
}

// Marca ou desmarca X para risco
function toggleX(event, el){
  event.stopPropagation();
  el.parentElement.classList.toggle("subline");
}

// Marca ou desmarca dúvida
function toggleDuvida(el){
  duvidas[index] = !duvidas[index];
  el.classList.toggle("active");
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
