<?php
require "../cadastro/conexao.php";

// Buscar questões apenas de Matemática
$sql = "
    SELECT 
        q.id_questao, q.enunciado, q.imagem_enunciado, 
        q.origem, q.dificuldade, q.ano,
        d.nome AS disciplina_nome, 
        t.nome AS topico_nome,
        a.id_alternativa, a.texto AS alt_texto, a.correta
    FROM questoes q
    JOIN disciplinas d ON q.id_disciplina = d.id_disciplina
    JOIN topico t ON q.id_topico = t.id_topico
    JOIN alternativas a ON q.id_questao = a.id_questao
    WHERE d.nome = 'Matemática'
    ORDER BY q.id_questao ASC
";

$result = mysqli_query($conexao, $sql);

$questoes = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id_questao'];
        if (!isset($questoes[$id])) {
            $questoes[$id] = [
                'id' => $id,
                'disciplina' => $row['disciplina_nome'],
                'topico' => $row['topico_nome'],
                'enunciado' => $row['enunciado'],
                'imagem' => $row['imagem_enunciado'],
                'origem' => $row['origem'],
                'dificuldade' => $row['dificuldade'],
                'ano' => $row['ano'],
                'alternativas' => []
            ];
        }
        $questoes[$id]['alternativas'][] = [
            'texto' => $row['alt_texto'],
            'correta' => $row['correta']
        ];
    }
} else {
    echo "Erro na consulta: " . mysqli_error($conexao);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Plataforma - Matemática</title>
<style>
  :root {
    --green-dark: #006400;
    --green-dark-2: #004d00;
    --white: #ffffff;
    --muted: #f0fdf4;
    --card-shadow: rgba(0,100,0,0.14);
    --radius: 12px;
    font-size: 16px;
  }

html, body {
  height: 100%;
}

body {
  display: flex;
  flex-direction: column;
  margin: 0;
  font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
  background: linear-gradient(180deg, var(--muted), #fff);
  color: #222;
  padding: 0 20px 20px 20px;
}

.container {
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 18px;
  height: calc(100vh - 120px); /* altura da tela menos header */
}

.filters {
  display: flex;
  flex-direction: column;
  height: 100%;
  overflow-y: auto; /* se houver filtros demais, rola só ali */
}

.panel {
  height: 100%;
  overflow-y: auto; /* questões rolam dentro do painel */
}


  header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
  }

  .logo {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: var(--green-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-weight: 700;
    box-shadow: 0 6px 18px var(--card-shadow);
  }

  h1 {
    font-size: 1.25rem;
    margin: 0;
    color: var(--green-dark-2);
  }

  p.lead {
    margin: 0;
    color: #444;
    font-size: 0.95rem;
  }



  .filters, .panel {
    background: #fff;
    padding: 16px;
    border-radius: var(--radius);
    box-shadow: 0 8px 24px var(--card-shadow);
  }

  .form-row {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
  }

  label { font-size: 0.85rem; color: #444; }
  select, input[type="text"] {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e6e6e6;
    font-size: 0.95rem;
  }

  .btn {
    padding: 10px 14px;
    border-radius: 10px;
    background: var(--green-dark);
    color: var(--white);
    border: none;
    cursor: pointer;
    font-weight: 700;
  }

  .btn.ghost { background: #fff; border: 1px solid var(--green-dark); color: var(--green-dark); }

  .questao {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #f0f0f0;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }

  .questao h3 { margin:0 0 10px; color: var(--green-dark-2); }
  .enunciado { margin: 10px 0 20px; font-size:16px; }
  .alternativas { display: flex; flex-direction: column; gap:10px; }
  .alternativa { border:1px solid #ddd; padding:10px 15px; border-radius:6px; cursor:pointer; transition:0.2s; }
  .alternativa.selected { background: #e0ffe0; }
  .alternativa.correct { background:#d4edda; border-color:#28a745; }
  .alternativa.wrong { background:#f8d7da; border-color:#dc3545; }
  img.enunciado-img { max-width:100%; border-radius:6px; margin-bottom:10px; object-fit:contain; }
  .meta-line { color:#555; font-size:0.9rem; margin-top:8px; }
</style>
</head>
<body>

<header>
  <div class="logo">PF</div>
  <div>
    <h1>Porta de Entrada — Matemática</h1>
    <p class="lead">Questões de Matemática</p>
  </div>
</header>

<div class="container">
  <aside class="filters">
    <h3>Filtros</h3>
    <div class="form-row">
      <label for="topicSelect">Tópico</label>
      <select id="topicSelect">
        <option value="">— Todos os tópicos —</option>
      </select>
    </div>
    <div class="form-row">
      <label for="searchTxt">Buscar no enunciado</label>
      <input id="searchTxt" type="text" placeholder="palavra-chave..." />
    </div>
    <div style="display:flex;gap:8px;align-items:center;margin-top:6px">
      <button class="btn" id="filterBtn">Filtrar</button>
      <button class="btn ghost" id="clearBtn">Limpar</button>
    </div>
  </aside>

  <main class="panel">
    <div id="cardsGrid"><p style="color:#666">Nenhuma questão filtrada.</p></div>
  </main>
</div>

<script>
const QUESTOES = <?php echo json_encode(array_values($questoes), JSON_UNESCAPED_UNICODE); ?>;
const cardsGrid = document.getElementById('cardsGrid');
const topicSelect = document.getElementById('topicSelect');
const searchTxt = document.getElementById('searchTxt');
const filterBtn = document.getElementById('filterBtn');
const clearBtn = document.getElementById('clearBtn');

let QUESTOES_FILTRADAS = [];
let indexAtual = 0;

function renderQuestao(i){
  cardsGrid.innerHTML = '';
  if(QUESTOES_FILTRADAS.length === 0){
    cardsGrid.innerHTML = '<p style="color:#666">Nenhuma questão encontrada.</p>';
    return;
  }
  const q = QUESTOES_FILTRADAS[i];
  let html = `<div class="questao"><h3>${q.topico}</h3>`;
  if(q.imagem){ html += `<img src="../../IMG/Upload/${q.imagem}" class="enunciado-img">`; }
  html += `<p class="enunciado">${q.enunciado}</p><div class="alternativas">`;
  q.alternativas.forEach((a,idx)=>{
    html += `<div class="alternativa" onclick="selecionar(this, ${idx})">${String.fromCharCode(65+idx)}) ${a.texto}</div>`;
  });
  html += `</div>
    <button class="btn" onclick="verificar()">Verificar resposta</button>
    <div class="meta-line">Origem: <b>${q.origem || '-'}</b> | Dificuldade: <b>${q.dificuldade || '-'}</b> | Ano: <b>${q.ano || '-'}</b></div>
    <div style="display:flex;gap:8px;margin-top:14px;">
      <button class="btn ghost" id="prevBtn" ${i===0?'disabled':''}>Anterior</button>
      <button class="btn ghost" id="nextBtn" ${i===QUESTOES_FILTRADAS.length-1?'disabled':''}>Próxima</button>
    </div>
  </div>`;
  cardsGrid.innerHTML = html;

  document.getElementById('prevBtn').onclick = ()=>{ if(indexAtual>0){ indexAtual--; renderQuestao(indexAtual); } };
  document.getElementById('nextBtn').onclick = ()=>{ if(indexAtual<QUESTOES_FILTRADAS.length-1){ indexAtual++; renderQuestao(indexAtual); } };
}

function selecionar(el, ai){
  document.querySelectorAll('.alternativa').forEach(div=>div.classList.remove('selected'));
  el.classList.add('selected'); el.dataset.index = ai;
}

function verificar(){
  const q = QUESTOES_FILTRADAS[indexAtual];
  const alternativas = document.querySelectorAll('.alternativa');
  alternativas.forEach((div,idx)=>{
    const correta = q.alternativas[idx].correta == 1;
    if(correta){ div.classList.add('correct'); }
    else if(div.classList.contains('selected')){ div.classList.add('wrong'); }
  });
}

function populateTopicos(){
  topicSelect.innerHTML = '<option value="">— Todos os tópicos —</option>';
  const topicos = [...new Set(QUESTOES.map(q=>q.topico))].sort();
  topicos.forEach(t=>{ const o = document.createElement('option'); o.value=t;o.textContent=t;topicSelect.appendChild(o); });
}

function getFiltered(){
  const top = topicSelect.value;
  const search = searchTxt.value.trim().toLowerCase();
  return QUESTOES.filter(q=>{
    if(top && q.topico!==top) return false;
    if(search && !q.enunciado.toLowerCase().includes(search)) return false;
    return true;
  });
}

filterBtn.addEventListener('click', ()=>{
  QUESTOES_FILTRADAS = getFiltered(); indexAtual=0; renderQuestao(indexAtual);
});

clearBtn.addEventListener('click', ()=>{
  searchTxt.value=''; populateTopicos(); cardsGrid.innerHTML='<p style="color:#666">Nenhuma questão filtrada.</p>';
});

populateTopicos();
</script>

</body>
</html>
