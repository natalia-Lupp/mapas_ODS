document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("formCadastroPredio");
  var nomePredio = document.getElementById("nomePredio");

  if (!form || !nomePredio) return;

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    var nome = nomePredio.value.trim();

    if (nome.length < 2) {
      alert("O nome do prédio deve ter pelo menos 2 letras.");
      nomePredio.focus();
      return;
    }

    // Cria objeto temporário do prédio
    const predio = {
      id: Date.now(), // ID temporário
      nome: nome,
      andares: [], // vai receber depois
    };

    // Salva no localStorage
    localStorage.setItem("predioAtual", JSON.stringify(predio));

    // Redireciona para a página de andares
    window.location.href = "/admin/new.floor";
  });
});
