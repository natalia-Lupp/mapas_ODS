document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formCadastroPredio");
  const nomePredio = document.getElementById("id_name");

  form.addEventListener("submit", function (event) {
    // Apenas para evitar envio vazio
    if (!nomePredio.value.trim()) {
      event.preventDefault();
    }
  });
});
