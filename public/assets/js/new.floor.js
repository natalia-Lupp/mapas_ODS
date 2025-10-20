document.addEventListener("DOMContentLoaded", function () {
  const btnAddPredios = document.getElementById("btnAddPredios");
  const form = document.getElementById("formCadastroPredio");
  const nomePredio = document.getElementById("nomePredio");
  const numeroAndares = document.getElementById("numeroAndares");
  const containerTabelaPredios = document.getElementById(
    "containerTabelaPredios"
  );

  // Array para armazenar prédios temporariamente
  let predios = [];

  // Função para montar a tabela de prédios
  function montarTabelaPredios() {
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
        <tbody></tbody>
      </table>
    `;

    const tbody = containerTabelaPredios.querySelector("tbody");

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

    // Evento para excluir prédio
    containerTabelaPredios.querySelectorAll(".btn-excluir").forEach((btn) => {
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

    predios.push({ nome, andares });

    // Limpa os campos
    nomePredio.value = "";
    numeroAndares.value = 1;

    montarTabelaPredios();
  });

  // Submeter múltiplos prédios
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    if (predios.length === 0) {
      alert("Adicione pelo menos um prédio antes de salvar.");
      return;
    }

    // Envia prédio por prédio para o backend
    (async () => {
      try {
        for (const p of predios) {
          const data = new FormData();
          data.append("building[name]", p.nome);
          data.append("building[n_floors]", p.andares);

          const response = await fetch(form.action, {
            method: "POST",
            body: data,
          });

          if (!response.ok) {
            throw new Error("Erro ao salvar prédio: " + p.nome);
          }
        }

        alert("Todos os prédios foram cadastrados com sucesso!");
        window.location.href = "<?= route('buildings.index') ?>"; // redireciona
      } catch (error) {
        console.error(error);
        alert("Ocorreu um erro ao cadastrar os prédios.");
      }
    })();
  });

  // Inicializa tabela vazia
  montarTabelaPredios();
});
