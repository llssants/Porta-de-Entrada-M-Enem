document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("cronogramaForm");
  const resultado = document.getElementById("resultado");
  const listaResultado = document.getElementById("listaResultado");

  // Adicionar interatividade aos métodos de estudo
  const methodOptions = document.querySelectorAll('.method-option');
  methodOptions.forEach(option => {
    option.addEventListener('click', () => {
      const radio = option.querySelector('input[type="radio"]');
      radio.checked = true;
    });
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    // Pegar o método selecionado
    const metodoRadio = document.querySelector('input[name="metodo"]:checked');
    if (!metodoRadio) {
      alert('Por favor, selecione um método de estudo!');
      return;
    }
    const metodo = metodoRadio.value;
    const horasSemana = parseInt(document.getElementById("horas").value);
    const selects = document.querySelectorAll(".discipline-card select");

    // Limpa resultado anterior
    listaResultado.innerHTML = "";

    // Calcular total de pontos de dificuldade
    let totalDificuldade = 0;
    const disciplinas = [];
    
    selects.forEach((sel) => {
      const materia = sel.dataset.materia;
      const dificuldade = parseInt(sel.value);
      totalDificuldade += dificuldade;
      disciplinas.push({ materia, dificuldade });
    });

    // Distribuir horas proporcionalmente
    disciplinas.forEach(({ materia, dificuldade }) => {
      const horas = Math.round((dificuldade / totalDificuldade) * horasSemana * 10) / 10;
      
      const li = document.createElement("li");
      
      // Formatar o nome da matéria
      const materiaFormatada = materia.replace(/_/g, ' ');
      
      // Criar ícone baseado na dificuldade
      let icone = '';
      if (dificuldade >= 4) {
        icone = '🔥';
      } else if (dificuldade === 3) {
        icone = '📚';
      } else {
        icone = '✅';
      }
      
      li.innerHTML = `${icone} <strong>${materiaFormatada}</strong>: ${horas}h por semana (${getNomeDificuldade(dificuldade)})`;
      listaResultado.appendChild(li);
    });

    // Scroll suave até o resultado
    resultado.style.display = "block";
    setTimeout(() => {
      resultado.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
  });

  // Função auxiliar para obter nome da dificuldade
  function getNomeDificuldade(nivel) {
    switch(nivel) {
      case 5: return 'Muito difícil';
      case 4: return 'Difícil';
      case 3: return 'Médio';
      case 2: return 'Fácil';
      case 1: return 'Muito fácil';
      default: return 'Médio';
    }
  }
});

