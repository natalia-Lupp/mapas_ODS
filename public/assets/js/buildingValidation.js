document.addEventListener("DOMContentLoaded", function () {
  const btnAddPredios = document.getElementById("btnAddPredios");
  const nomePredio = document.getElementById("nomePredio");
  const numeroAndares = document.getElementById("numeroAndares");
  const containerTabelaItens = document.getElementById("containerTabelaItens");

  // Array para armazenar os prédios adicionados temporariamente
  let predios = [];

  // Função para montar a tabela de prédios
  function montarTabelaPredios() {
    if (predios.length === 0) {
      containerTabelaItens.innerHTML =
        "<p class='text-muted'>Nenhum prédio adicionado.</p>";
      return;
    }

    containerTabelaItens.innerHTML = `
      <table class="table table-striped mt-4">
        <thead>
          <tr>
            <th>Nome do Prédio</th>
            <th>Número de Andares</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    `;

    const tbody = containerTabelaItens.querySelector("tbody");

    predios.forEach((predio, index) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${predio.nome}</td>
        <td>${predio.andares}</td>
        <td>
          <button type="button" class="btn btn-sm btn-danger btn-excluir" data-index="${index}">
            Excluir
          </button>
        </td>
      `;
      tbody.appendChild(tr);
    });

    // Adicionar evento para excluir prédios da lista
    containerTabelaItens.querySelectorAll(".btn-excluir").forEach((btn) => {
      btn.addEventListener("click", function () {
        const index = Number(this.dataset.index);
        predios.splice(index, 1);
        montarTabelaPredios();
      });
    });
  }

  // Botão "Adicionar Prédio"
  btnAddPredios.addEventListener("click", function () {
    const nome = nomePredio.value.trim();
    const andares = Number(numeroAndares.value);

    if (nome.length < 2) {
      alert("O nome do prédio deve ter pelo menos 2 letras.");
      nomePredio.focus();
      return;
    }

    predios.push({ nome: nome, andares: andares });

    // Limpar campos do formulário
    nomePredio.value = "";
    numeroAndares.value = 1;

    montarTabelaPredios();
  });

  // Inicializa tabela vazia
  montarTabelaPredios();
});
