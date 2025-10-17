document.addEventListener("DOMContentLoaded", function () {
  const tabela = document
    .getElementById("tabelaPrediosDashboard")
    .querySelector("tbody");
  const totalPrediosEl = document.getElementById("totalPredios");
  const mediaAndaresEl = document.getElementById("mediaAndares");

  try {
    const predio = JSON.parse(localStorage.getItem("predioCompleto"));

    if (!predio || !predio.andares || predio.andares.length === 0) {
      tabela.innerHTML = `<tr><td colspan="6" class="text-muted">Nenhum dado encontrado.</td></tr>`;
      totalPrediosEl.textContent = "0";
      mediaAndaresEl.textContent = "0";
      return;
    }

    let html = "";
    let totalAndares = 0;

    predio.andares.forEach((andar) => {
      const nBanheiros = andar.banheiros.length;
      const nTorneiras = andar.banheiros.reduce(
        (acc, b) =>
          acc + (b.itens ? b.itens.filter((i) => i === "Torneira").length : 0),
        0
      );
      const nVasos = andar.banheiros.reduce(
        (acc, b) =>
          acc + (b.itens ? b.itens.filter((i) => i === "Vaso").length : 0),
        0
      );

      totalAndares += 1;

      html += `
                <tr>
                    <td class="text-secondary">${predio.nome}</td>
                    <td class="text-secondary">Andar ${andar.numero}</td>
                    <td class="text-secondary">${nBanheiros}</td>
                    <td class="text-secondary">${nTorneiras}</td>
                    <td class="text-secondary">${nVasos}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-4">
                            <button class="btn btn-link text-primary p-0 editarPredio" data-id="${predio.id}">
                                Editar
                            </button>
                            <form action="/admin/delete/${predio.id}" method="POST" class="d-inline">
                                <input type="hidden" name="_method" value="DELETE" />
                                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            `;
    });

    tabela.innerHTML = html;

    // Atualiza cards
    totalPrediosEl.textContent = "1"; // temporário, apenas um prédio
    mediaAndaresEl.textContent = (totalAndares / 1).toFixed(1);

    // Evento para todos os botões editar
    document.querySelectorAll(".editarPredio").forEach((btn) => {
      btn.addEventListener("click", () => {
        // Salva o prédio no localStorage para edição
        localStorage.setItem("predioEdicao", JSON.stringify(predio));
        // Redireciona para a página de edição
        window.location.href = "/admin/edit";
      });
    });
  } catch (e) {
    tabela.innerHTML = `<tr><td colspan="6" class="text-danger">Erro ao ler os dados: ${e.message}</td></tr>`;
    console.error(e);
  }

  // Botão de excluir tabela (limpa localStorage temporariamente)
  const btnExcluir = document.getElementById("btnExcluirTabela");
  btnExcluir.addEventListener("click", () => {
    localStorage.removeItem("predioCompleto");
    tabela.innerHTML = `<tr><td colspan="6" class="text-muted">Nenhum dado encontrado.</td></tr>`;
    totalPrediosEl.textContent = "0";
    mediaAndaresEl.textContent = "0";
  });
});
