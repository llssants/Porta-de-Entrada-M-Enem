document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("cronogramaForm");
  const resultado = document.getElementById("resultado");
  const listaResultado = document.getElementById("listaResultado");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const metodo = document.getElementById("metodo").value;
    const horasSemana = parseInt(document.getElementById("horas").value);
    const selects = document.querySelectorAll("#disciplinas select");

    // Limpa resultado anterior
    listaResultado.innerHTML = "";

    selects.forEach((sel) => {
      const materia = sel.dataset.materia;
      const dificuldade = parseInt(sel.value); // 1 a 5

      // Cálculo simples: horas proporcionais à dificuldade
      const horas = Math.round((dificuldade / 5) * horasSemana / selects.length);

      const li = document.createElement("li");
      li.textContent = `${materia}: ${horas}h por semana (Método: ${metodo})`;
      listaResultado.appendChild(li);
    });

    resultado.style.display = "block";
  });
});
