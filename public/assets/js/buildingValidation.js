document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formCadastroPredio");
  const nomePredio = document.getElementById("nomePredio");

  if (!form) return;

  form.addEventListener("submit", function (event) {
    const nome = nomePredio.value.trim();

    if (nome.length < 2) {
      event.preventDefault();
      alert("O nome do prédio deve ter pelo menos 2 letras.");
      nomePredio.focus();
      return;
    }
  });
});
