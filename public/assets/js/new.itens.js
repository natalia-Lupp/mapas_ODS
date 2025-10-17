document.addEventListener("DOMContentLoaded", function () {
  const btnAddItens = document.getElementById("btnAddItens");
  const containerTabela = document.getElementById("containerTabelaItens");
  const mensagemErro = document.getElementById("mensagemErroItens");
  const mensagemSucesso = document.getElementById("mensagemSucesso");

  // 🔹 Recupera prédio e andares do localStorage
  let predio = null;
  try {
    predio = JSON.parse(localStorage.getItem("predioAtual"));
    let andares =
      JSON.parse(localStorage.getItem(`andaresPredio_${predio.id}`)) || [];

    // 🔹 Normaliza banheiros para array de objetos
    andares = andares.map((andar) => {
      const nBanheiros = Number(andar.banheiros) || 0;
      return {
        ...andar,
        numero: andar.numeroAndar || undefined,
        banheiros: Array.from({ length: nBanheiros }, (_, i) => ({
          numero: i + 1,
          itens: [],
        })),
      };
    });

    predio.andares = andares;
  } catch (e) {
    console.warn("Erro ao ler predioAtual ou andares do localStorage:", e);
  }

  // 🔹 Se não houver prédio ou andares
  if (!predio || !predio.andares || predio.andares.length === 0) {
    mensagemErro.textContent =
      "Nenhum prédio com andares encontrado. Cadastre andares primeiro.";
    mensagemErro.classList.remove("d-none");
    return;
  }

  // 🔹 Mostra ID do prédio
  const pPredioId = document.querySelector("#main-content .text-muted");
  if (predio && pPredioId) {
    pPredioId.textContent = `Prédio ID: ${predio.id}`;
  }

  // 🔹 Função para montar tabela
  function montarTabela() {
    if (!predio.andares || predio.andares.length === 0) {
      containerTabela.innerHTML =
        "<p class='text-muted'>Nenhum andar cadastrado.</p>";
      return;
    }

    containerTabela.innerHTML = `
      <table class="table table-striped mt-4">
        <thead>
          <tr>
            <th>Prédio</th>
            <th>Andar</th>
            <th>Banheiros</th>
            <th>Torneiras</th>
            <th>Vasos</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    `;

    const tbody = containerTabela.querySelector("tbody");

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

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${predio.nome}</td>
        <td>Andar ${andar.numero}</td>
        <td>${nBanheiros}</td>
        <td>${nTorneiras}</td>
        <td>${nVasos}</td>
      `;
      tbody.appendChild(tr);
    });
  }

  // 🔹 Botão “Adicionar Itens”
  btnAddItens.addEventListener("click", function () {
    const qtdTorneiras = Number(document.getElementById("qtdTorneiras").value);
    const qtdVasos = Number(document.getElementById("qtdVasos").value);

    if (qtdTorneiras < 3 || qtdVasos < 3) {
      mensagemErro.textContent = "Informe ao menos 3 torneiras e 3 vasos.";
      mensagemErro.classList.remove("d-none");
      mensagemSucesso.classList.add("d-none");
      return;
    }

    mensagemErro.classList.add("d-none");

    // 🔹 Distribui itens para cada banheiro de cada andar
    predio.andares.forEach((andar) => {
      andar.banheiros.forEach((banheiro) => {
        banheiro.itens = [
          ...Array(qtdTorneiras).fill("Torneira"),
          ...Array(qtdVasos).fill("Vaso"),
        ];
      });
    });

    // 🔹 Salva os andares atualizados no localStorage mantendo estrutura original
    localStorage.setItem(
      `andaresPredio_${predio.id}`,
      JSON.stringify(
        predio.andares.map((andar) => ({
          predioId: andar.predioId,
          predioNome: andar.predioNome,
          numeroAndar: andar.numero,
          banheiros: andar.banheiros.length,
        }))
      )
    );

    // 🔹 Monta tabela
    montarTabela();

    // 🔹 Mensagem de sucesso
    mensagemSucesso.classList.remove("d-none");
    setTimeout(() => mensagemSucesso.classList.add("d-none"), 3000);

    console.log("Itens cadastrados com sucesso:", predio.andares);
  });
  const btnSalvarPredio = document.getElementById("btnSalvarPredio");

  if (btnSalvarPredio) {
    btnSalvarPredio.addEventListener("click", function () {
      if (!predio) return;

      // Salva o objeto completo do prédio no localStorage
      localStorage.setItem("predioCompleto", JSON.stringify(predio));

      // Mensagem de sucesso
      mensagemSucesso.textContent = "Prédio salvo com sucesso!";
      mensagemSucesso.classList.remove("d-none");
      setTimeout(() => {
        mensagemSucesso.classList.add("d-none");
        mensagemSucesso.textContent = "Itens cadastrados com sucesso!"; // volta texto original
      }, 3000);

      console.log("Prédio completo salvo:", predio);
    });
  }
});
