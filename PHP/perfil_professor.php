<?php
session_start();

$nome = $_SESSION['usuario'];

?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Perfil do Professor</title>
  <link rel="stylesheet" href="../CSS/perfil.css">
  <style>
    /* Adicione estilos mínimos se necessário */
    .today { background-color: #ffeeba; border-radius: 50%; }
    .calendar td { text-align: center; padding: 5px; }
    .btn-cronograma a { text-decoration: none; color: white; background: #28a745; padding: 10px 15px; border-radius: 8px; display: inline-block; }
  </style>
</head>
<body>
  <div class="sidebar">
    <div>
      <h1>Perfil</h1>
      <nav class="menu">
        <a href="#" class="active">Menu principal</a>
        <a href="#">Minhas turmas</a>
        <a href="cadastro_questao.php">Registrar questão</a>
        <a href="#">Ajuda</a>
        <a href="#">Mensagens</a>
        <a href="regras.html">Regras do jogo</a>
      </nav>
    </div>
    <div>
      <button style="background:var(--primary);color:white;padding:10px;border:none;border-radius:8px;width:100%;cursor:pointer;">Download App</button>
    </div>
  </div>

  <div class="main">
    <header>
      <h2>Bem-vindo novamente <?php echo htmlspecialchars($nome); ?></h2>
      <input type="text" placeholder="Buscar conteúdos" style="padding:8px;border-radius:8px;border:1px solid #ccc;">
    </header>

    <!-- Cards principais -->
    <section class="cards">
      <div class="card"><h3>Minhas Turmas</h3><p>👥 5 turmas ativas</p></div>
      <div class="card"><h3>Regras do Jogo</h3><p>Clique aqui para visualizar</p></div>
      <div class="card"><h3>Ajuda</h3><p>Precisa de suporte? Clique aqui!</p></div>
    </section>

    <!-- Área principal com gráfico e cronograma -->
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-top:20px;">
      <div>
        <div class="card">
          <h3> Desempenho dos Alunos</h3>
          <canvas id="graficoDesempenho" width="400" height="200"></canvas>
        </div>
        <div class="card" style="margin-top:20px;">
         <button class="btn-cronograma"><a href="cronograma.html">Criar Cronograma da Turma</a></button>
        </div>
      </div>

      <!-- Widgets laterais -->
      <div class="side-widgets">
        <div class="calendar card">
          <h3 id="mesAnoTitulo"></h3>
          <table class="calendar" id="calendarTable">
            <thead>
              <tr>
                <th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th>
              </tr>
            </thead>
            <tbody id="calendarBody">
              <!-- Dias serão inseridos via JavaScript -->
            </tbody>
          </table>
        </div>

        <div class="card assignments" style="margin-top:20px;">
          <h3>Missões Diárias para os Alunos</h3>
          <p>✔ Enviar 1 atividade</p>
          <p>✔ Corrigir 5 questões</p>
          <p>⬜ Criar nova avaliação</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Gráfico com Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Gera o gráfico de desempenho
    const ctx = document.getElementById('graficoDesempenho').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex'],
        datasets: [{
          label: 'Média de Desempenho (%)',
          data: [75, 80, 70, 90, 85],
          borderColor: 'blue',
          backgroundColor: 'rgba(0, 123, 255, 0.2)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true } }
      }
    });
  </script>

  <!-- Calendário dinâmico -->
  <script>
    function gerarCalendario() {
      const hoje = new Date();
      const ano = hoje.getFullYear();
      const mes = hoje.getMonth();
      const diaHoje = hoje.getDate();

      const nomeMeses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                         'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
      
      document.getElementById('mesAnoTitulo').textContent = nomeMeses[mes] + ' ' + ano;

      const primeiroDia = new Date(ano, mes, 1).getDay();
      const ultimoDia = new Date(ano, mes + 1, 0).getDate();

      const corpoTabela = document.getElementById('calendarBody');
      corpoTabela.innerHTML = '';

      let linha = document.createElement('tr');
      for (let i = 0; i < primeiroDia; i++) {
        linha.appendChild(document.createElement('td'));
      }

      for (let dia = 1; dia <= ultimoDia; dia++) {
        if ((primeiroDia + dia - 1) % 7 === 0 && dia !== 1) {
          corpoTabela.appendChild(linha);
          linha = document.createElement('tr');
        }

        const celula = document.createElement('td');
        celula.textContent = dia;
        if (dia === diaHoje) {
          celula.classList.add('today');
        }
        linha.appendChild(celula);
      }

      corpoTabela.appendChild(linha);
    }

    window.onload = gerarCalendario;
  </script>
</body>
</html>
