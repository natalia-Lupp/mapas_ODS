document.addEventListener("DOMContentLoaded", function () {
  const btnAddPredios = document.getElementById("btnAddPredios");
  const form = document.getElementById("formCadastroPredio");
  const nomePredio = document.getElementById("nomePredio");
  const numeroAndares = document.getElementById("numeroAndares");
  const containerTabelaPredios = document.getElementById(
    "containerTabelaPredios"
  );

  // Lista temporária de prédios adicionados
  let predios = [];

  // Monta a tabela temporária
  function montarTabelaPredios() {
    if (!containerTabelaPredios) return;

    if (predios.length === 0) {
      containerTabelaPredios.innerHTML =
        "<p class='text-muted'>Nenhum prédio adicionado.</p>";
      return;
    }

    containerTabelaPredios.innerHTML = `
      <table class="table table-striped mt-4">
        <thead>
          <tr>
            <th>Nome do Prédio</th>
            <th>Número de Andares</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          ${predios
            .map(
              (p, i) => `
            <tr>
              <td>${p.nome}</td>
              <td>${p.andares}</td>
              <td>
                <button type="button" class="btn btn-sm btn-danger btn-excluir" data-index="${i}">
                  Excluir
                </button>
              </td>
            </tr>
          `
            )
            .join("")}
        </tbody>
      </table>
    `;
  }

  // Event delegation para excluir prédios
  containerTabelaPredios.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-excluir")) {
      const index = Number(e.target.dataset.index);
      predios.splice(index, 1);
      montarTabelaPredios();
    }
  });

  // Botão “Adicionar Prédio”
  btnAddPredios.addEventListener("click", function () {
    const nome = nomePredio.value.trim();
    const andares = Number(numeroAndares.value);

    if (nome.length === 0) {
      alert("Informe o nome do prédio antes de adicionar.");
      return;
    }

    predios.push({ nome, andares });

    // Limpar campos
    nomePredio.value = "";
    numeroAndares.value = 1;

    montarTabelaPredios();
  });

  // Submissão do formulário
  form.addEventListener("submit", function (event) {
    if (predios.length === 0) {
      event.preventDefault();
      alert("Adicione pelo menos um prédio antes de salvar.");
      return;
    }

    // Remove campos ocultos antigos
    form.querySelectorAll(".predio-hidden").forEach((el) => el.remove());

    // Pega apenas o **primeiro prédio** da lista
    const p = predios[0];

    // Cria campos ocultos para enviar para a controller
    const inputNome = document.createElement("input");
    inputNome.type = "hidden";
    inputNome.name = "building[name]";
    inputNome.value = p.nome;
    inputNome.classList.add("predio-hidden");

    const inputAndares = document.createElement("input");
    inputAndares.type = "hidden";
    inputAndares.name = "building[n_floors]";
    inputAndares.value = p.andares;
    inputAndares.classList.add("predio-hidden");

    form.appendChild(inputNome);
    form.appendChild(inputAndares);

    console.log("Prédio que será enviado:", p);
  });

  // Inicializa a tabela vazia
  montarTabelaPredios();
});
