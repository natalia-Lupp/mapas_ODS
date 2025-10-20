document.addEventListener("DOMContentLoaded", function () {
  const btnAddPredios = document.getElementById("btnAddPredios");
  const form = document.getElementById("formCadastroPredio");
  const nomePredio = document.getElementById("nomePredio");
  const numeroAndares = document.getElementById("numeroAndares");
  const containerTabelaPredios = document.getElementById(
    "containerTabelaPredios"
  );

  // Array temporário para prédios adicionados
  let predios = [];

  // Função para montar a tabela de prédios na tela
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

    // Evento para excluir prédios da tabela temporária
    containerTabelaPredios.querySelectorAll(".btn-excluir").forEach((btn) => {
      btn.addEventListener("click", function () {
        predios.splice(Number(this.dataset.index), 1);
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

    // Limpar campos
    nomePredio.value = "";
    numeroAndares.value = 1;

    montarTabelaPredios();
  });

  // Evento de submit do formulário para salvar todos os prédios
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    if (predios.length === 0) {
      alert("Adicione pelo menos um prédio antes de salvar.");
      return;
    }

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

          if (!response.ok) throw new Error("Erro ao salvar prédio: " + p.nome);
        }

        // Redireciona para lista de prédios após salvar todos
        window.location.href = "<?= route('buildings.index') ?>";
      } catch (error) {
        console.error(error);
        alert("Ocorreu um erro ao cadastrar os prédios.");
      }
    })();
  });

  // Inicializa tabela vazia
  montarTabelaPredios();
});
