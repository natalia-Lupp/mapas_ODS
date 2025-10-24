document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formEditarPredio");
  const nomePredio = document.getElementById("building_name");
  const numeroAndares = document.getElementById("building_floors");

  form.addEventListener("submit", function (event) {
    const nome = nomePredio.value.trim();
    const andares = Number(numeroAndares.value);

    // Validação do nome
    if (nome.length === 0) {
      event.preventDefault();
      alert("Informe o nome do prédio antes de salvar.");
      nomePredio.focus();
      return;
    }

    if (andares < 1 || andares > 10) {
      event.preventDefault();
      alert("Selecione um número de andares válido.");
      numeroAndares.focus();
      return;
    }

    console.log("Formulário válido. Enviando dados:", { nome, andares });
  });
});
