// public/assets/js/dashboard.js
document.addEventListener("DOMContentLoaded", async function () {
  const tabela = document.querySelector("#tabelaPrediosDashboard tbody");
  const totalPrediosEl = document.getElementById("totalPredios");
  const mediaAndaresEl = document.getElementById("mediaAndares");
  const btnExcluirTabela = document.getElementById("btnExcluirTabela");

  async function carregarPredios() {
    try {
      const resposta = await fetch("/buildings", {
        headers: { Accept: "application/json" },
      });

      if (!resposta.ok) throw new Error("Falha ao carregar prédios.");

      const dados = await resposta.json();
      const buildings = dados.buildings || [];

      if (!buildings.length) {
        tabela.innerHTML = `<tr><td colspan="3" class="text-muted">Nenhum prédio cadastrado.</td></tr>`;
        totalPrediosEl.textContent = "0";
        mediaAndaresEl.textContent = "0";
        return;
      }

      let html = "";
      let totalPredios = buildings.length;
      let totalAndares = 0;

      for (const predio of buildings) {
        const nAndares = predio.n_floors || 0;
        totalAndares += nAndares;

        html += `
          <tr>
            <td class="text-secondary">${predio.name}</td>
            <td class="text-secondary">${nAndares}</td>
            <td>
              <div class="d-flex justify-content-center gap-4">
                <a href="/buildings/${predio.id}/edit" class="btn btn-link text-primary p-0">
                  Editar
                </a>
                <button class="btn btn-link text-danger text-decoration-none p-0" 
                        data-id="${predio.id}" id="btnExcluir-${predio.id}">
                  Excluir
                </button>
              </div>
            </td>
          </tr>
        `;
      }

      tabela.innerHTML = html;
      totalPrediosEl.textContent = totalPredios;
      mediaAndaresEl.textContent = (totalAndares / totalPredios).toFixed(1);

      // Eventos de exclusão individual
      buildings.forEach((predio) => {
        const botao = document.getElementById(`btnExcluir-${predio.id}`);
        if (botao) {
          botao.addEventListener("click", () => excluirPredio(predio.id));
        }
      });
    } catch (erro) {
      console.error(erro);
      tabela.innerHTML = `<tr><td colspan="3" class="text-danger">Erro ao carregar: ${erro.message}</td></tr>`;
    }
  }

  async function excluirPredio(id) {
    if (!confirm("Deseja excluir este prédio?")) return;

    try {
      const resposta = await fetch(`/buildings/${id}`, { method: "DELETE" });
      if (resposta.ok) {
        alert("Prédio excluído com sucesso!");
        carregarPredios();
      } else {
        alert("Erro ao excluir prédio.");
      }
    } catch (erro) {
      console.error(erro);
      alert("Erro na exclusão: " + erro.message);
    }
  }

  // Excluir todos os prédios
  btnExcluirTabela.addEventListener("click", async () => {
    if (!confirm("Deseja realmente excluir todos os prédios?")) return;

    try {
      const resposta = await fetch("/buildings/deleteAll", {
        method: "DELETE",
      });
      if (resposta.ok) {
        alert("Todos os prédios foram removidos.");
        carregarPredios();
      } else {
        alert("Erro ao excluir todos os prédios.");
      }
    } catch (erro) {
      alert("Erro: " + erro.message);
    }
  });

  // Inicializa o carregamento ao abrir o painel
  carregarPredios();
});
