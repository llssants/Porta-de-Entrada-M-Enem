<?php
require "../cadastro/conexao.php";

// Buscar questões com tópicos e disciplinas de Linguagens
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
    WHERE d.nome IN ('Português','Literatura','Inglês','Espanhol','Artes','Educação Física')
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
<title>Plataforma - Linguagens</title>
<style>
  :root {
    --purple-dark: #4B2E83;
    --purple-dark-2: #3A1E6A;
    --white: #ffffff;
    --muted: #f5f3ff;
    --card-shadow: rgba(75,46,131,0.14);
    --radius: 12px;
    font-size: 16px;
  }

  * { box-sizing: border-box }
  body {
    margin: 0;
    font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
    background: linear-gradient(180deg, var(--muted), #fff);
    color: #222;
    padding: 20px;
  }

  header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 18px;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .logo {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: var(--purple-dark);
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
    color: var(--purple-dark-2)
  }

  p.lead {
    margin: 0;
    color: #444;
    font-size: 0.95rem
  }

  .tabs {
    display: flex;
    gap: 8px;
    margin: 0 0 18px 0;
    flex-wrap: wrap;
  }

  .tab {
    padding: 10px 14px;
    border-radius: 999px;
    background: #fff;
    border: 2px solid transparent;
    cursor: pointer;
    font-weight: 600;
    color: var(--purple-dark);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.03);
  }

  .tab.active {
    background: linear-gradient(180deg, var(--purple-dark), var(--purple-dark-2));
    color: var(--white);
    transform: translateY(-2px);
  }

  .container {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 18px;
  }

  .filters {
    background: #fff;
    padding: 16px;
    border-radius: var(--radius);
    box-shadow: 0 8px 24px var(--card-shadow);
    border: 1px solid rgba(0, 0, 0, 0.04);
  }

  .filters h3 {
    margin: 0 0 10px 0;
    color: var(--purple-dark)
  }

  .form-row {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px
  }

  label {
    font-size: 0.85rem;
    color: #444
  }

  select, input[type="text"] {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e6e6e6;
    background: #fff;
    outline: none;
    font-size: 0.95rem;
  }

  .btn {
    display: inline-block;
    padding: 10px 14px;
    border-radius: 10px;
    background: var(--purple-dark);
    color: var(--white);
    border: none;
    cursor: pointer;
    font-weight: 700;
  }

  .btn.ghost {
    background: #fff;
    border: 1px solid var(--purple-dark);
    color: var(--purple-dark)
  }

  .panel {
    background: #fff;
    padding: 16px;
    border-radius: var(--radius);
    box-shadow: 0 8px 24px var(--card-shadow);
    min-height: 320px;
  }

  .questao {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #f0f0f0;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }

  .questao h3 { margin: 0 0 10px; color: var(--purple-dark-2); }
  .enunciado { margin-top: 10px; margin-bottom: 20px; font-size: 16px; }
  .alternativas { display: flex; flex-direction: column; gap: 10px; }
  .alternativa {
    border: 1px solid #ddd;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
  }
  .alternativa.selected { background: #f0e8ff; }
  .alternativa.correct { background: #d4edda; border-color: #28a745; }
  .alternativa.wrong { background: #f8d7da; border-color: #dc3545; }
  img.enunciado-img {
    max-width: 100%;
    border-radius: 6px;
    margin-bottom: 10px;
    object-fit: contain;
  }

  .meta-line { color: #555; font-size: 0.9rem; margin-top: 8px; }
</style>
</head>
<body>

<header>
  <div class="brand">
    <div class="logo">PF</div>
    <div>
      <h1>Porta de Entrada — Linguagens</h1>
      <p class="lead">Questões de Português, Literatura, Inglês, Espanhol, Artes e Educação Física</p>
    </div>
  </div>
</header>

<div class="tabs" role="tablist" aria-label="Disciplinas de Linguagens">
  <button class="tab" data-disciplina="Português">Português</button>
  <button class="tab" data-disciplina="Literatura">Literatura</button>
  <button class="tab" data-disciplina="Inglês">Inglês</button>
  <button class="tab" data-disciplina="Espanhol">Espanhol</button>
  <button class="tab" data-disciplina="Artes">Artes</button>
  <button class="tab" data-disciplina="Educação Física">Educação Física</button>
</div>

<div class="container">
  <aside class="filters">
    <h3>Filtros</h3>
    <div class="form-row">
      <label for="discSelect">Disciplina</label>
      <select id="discSelect">
        <option value="">— Todas as disciplinas —</option>
        <option>Português</option>
        <option>Literatura</option>
        <option>Inglês</option>
        <option>Espanhol</option>
        <option>Artes</option>
        <option>Educação Física</option>
      </select>
    </div>
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
    <div id="cardsGrid"><p style="color:#666">Nenhuma matéria filtrada.</p></div>
  </main>
</div>

<script>
const QUESTOES = <?php echo json_encode(array_values($questoes), JSON_UNESCAPED_UNICODE); ?>;
const cardsGrid = document.getElementById('cardsGrid');
const discSelect = document.getElementById('discSelect');
const topicSelect = document.getElementById('topicSelect');
const searchTxt = document.getElementById('searchTxt');
const filterBtn = document.getElementById('filterBtn');
const clearBtn = document.getElementById('clearBtn');
const tabs = document.querySelectorAll('.tab');

let QUESTOES_FILTRADAS = [];
let indexAtual = 0;

function renderQuestao(i){
  cardsGrid.innerHTML = '';
  if(QUESTOES_FILTRADAS.length === 0){
    cardsGrid.innerHTML = '<p style="color:#666">Nenhuma questão encontrada.</p>';
    return;
  }

  const q = QUESTOES_FILTRADAS[i];
  let html = `<div class="questao">
      <h3>${q.topico}</h3>`;
  if(q.imagem){
    html += `<img src="../../IMG/Upload/${q.imagem}" class="enunciado-img">`;
  }
  html += `<p class="enunciado">${q.enunciado}</p>`;
  html += `<div class="alternativas">`;
  q.alternativas.forEach((a,idx)=>{
    html += `<div class="alternativa" onclick="selecionar(this, ${idx})">
      ${String.fromCharCode(65+idx)}) ${a.texto}
    </div>`;
  });
  html += `</div>
      <button class="btn" onclick="verificar()">Verificar resposta</button>
      <div class="meta-line">Origem: <b>${q.origem || '-'}</b> | Dificuldade: <b>${q.dificuldade || '-'}</b> | Ano: <b>${q.ano || '-'}</b></div>
      <div style="display:flex;gap:8px;margin-top:14px;">
        <button class="btn ghost" id="prevBtn" ${i===0?'disabled':''}>Anterior</button>
        <button class="btn" id="nextBtn" ${i===QUESTOES_FILTRADAS.length-1?'disabled':''}>Próxima</button>
      </div>
    </div>`;
  cardsGrid.innerHTML = html;

  document.getElementById('prevBtn').onclick = ()=>{
    if(indexAtual > 0){ indexAtual--; renderQuestao(indexAtual); }
  };
  document.getElementById('nextBtn').onclick = ()=>{
    if(indexAtual < QUESTOES_FILTRADAS.length - 1){ indexAtual++; renderQuestao(indexAtual); }
  };
}

function selecionar(el, ai){
  document.querySelectorAll('.alternativa').forEach(div=>div.classList.remove('selected'));
  el.classList.add('selected');
  el.dataset.index = ai;
}

function verificar(){
  const q = QUESTOES_FILTRADAS[indexAtual];
  const alternativas = document.querySelectorAll('.alternativa');
  alternativas.forEach((div,idx)=>{
    const correta = q.alternativas[idx].correta == 1;
    if(correta){
      div.classList.add('correct');
    } else if(div.classList.contains('selected')){
      div.classList.add('wrong');
    }
  });
}

function populateTopicos(disciplina){
  topicSelect.innerHTML = '<option value="">— Todos os tópicos —</option>';
  const filtered = QUESTOES.filter(q => !disciplina || q.disciplina === disciplina);
  const topicos = [...new Set(filtered.map(q=>q.topico))].sort();
  topicos.forEach(t=>{
    const o = document.createElement('option');
    o.value = t; o.textContent = t;
    topicSelect.appendChild(o);
  });
}

function getFiltered(){
  const disc = discSelect.value;
  const top = topicSelect.value;
  const search = searchTxt.value.trim().toLowerCase();
  return QUESTOES.filter(q=>{
    if(disc && q.disciplina !== disc) return false;
    if(top && q.topico !== top) return false;
    if(search && !q.enunciado.toLowerCase().includes(search)) return false;
    return true;
  });
}

filterBtn.addEventListener('click', ()=>{
  QUESTOES_FILTRADAS = getFiltered();
  indexAtual = 0;
  renderQuestao(indexAtual);
});

clearBtn.addEventListener('click', ()=>{
  discSelect.value = '';
  topicSelect.innerHTML = '<option value="">— Todos os tópicos —</option>';
  searchTxt.value = '';
  cardsGrid.innerHTML = '<p style="color:#666">Nenhuma matéria filtrada.</p>';
});

discSelect.addEventListener('change', e=>{
  populateTopicos(e.target.value);
});

tabs.forEach(t=>{
  t.addEventListener('click', ()=>{
    tabs.forEach(x=>x.classList.remove('active'));
    t.classList.add('active');
    discSelect.value = t.dataset.disciplina;
    populateTopicos(t.dataset.disciplina);
    QUESTOES_FILTRADAS = QUESTOES.filter(q=>q.disciplina === t.dataset.disciplina);
    indexAtual = 0;
    renderQuestao(indexAtual);
  });
});
</script>

</body>
</html>
