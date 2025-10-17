document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formAdicionarAndar");
  const btnCadastrarItens = document.getElementById("btnCadastrarItens");
  const listaAndares = document.getElementById("listaAndares");
  const mensagemErro = document.getElementById("mensagemErro");

  // 🔹 Recupera prédio salvo no localStorage
  let predio = null;
  try {
    predio = JSON.parse(localStorage.getItem("predioAtual"));
  } catch (e) {
    console.warn("Erro ao ler prédio do localStorage:", e);
  }

  if (!predio || !predio.id) {
    if (mensagemErro) {
      mensagemErro.textContent =
        "Nenhum prédio encontrado. Cadastre um prédio primeiro.";
      mensagemErro.style.display = "block";
    }
    return;
  }

  console.log("🏢 Prédio carregado:", predio);

  // 🔹 Recupera array de andares do localStorage ou cria vazio
  let andares = [];
  try {
    andares =
      JSON.parse(localStorage.getItem(`andaresPredio_${predio.id}`)) || [];
  } catch (e) {
    console.warn("Erro ao ler andares do localStorage:", e);
  }

  // 🔹 Atualiza predioAtual com os andares atuais
  predio.andares = andares;
  localStorage.setItem("predioAtual", JSON.stringify(predio));

  // 🔹 Cria a tabela se ainda não existir
  if (listaAndares && !listaAndares.querySelector("table")) {
    listaAndares.innerHTML = `
      <table id="tabelaAndares" class="table table-striped mt-4 align-middle">
        <thead>
          <tr>
            <th>Nome do Prédio</th>
            <th>Andar</th>
            <th>Banheiros</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    `;
  }

  const tabelaAndares = document.getElementById("tabelaAndares");
  if (!tabelaAndares) return; // previne erro se tabela não existir
  const tbody = tabelaAndares.querySelector("tbody");

  // 🔹 Renderiza andares já existentes no localStorage
  andares.forEach(function (andar) {
    const linha = document.createElement("tr");
    linha.innerHTML = `
      <td>${andar.predioNome}</td>
      <td>Andar ${andar.numeroAndar}</td>
      <td>${andar.banheiros} banheiro(s)</td>
      <td>
        <form class="d-inline">
          <button type="button" class="btn btn-link text-danger text-decoration-none p-0 btnExcluir">Excluir</button>
        </form>
      </td>
    `;
    tbody.appendChild(linha);

    // Evento de exclusão
    linha.querySelector(".btnExcluir").addEventListener("click", function () {
      const index = andares.indexOf(andar);
      if (index > -1) andares.splice(index, 1);
      localStorage.setItem(
        `andaresPredio_${predio.id}`,
        JSON.stringify(andares)
      );

      // Atualiza predioAtual também
      predio.andares = andares;
      localStorage.setItem("predioAtual", JSON.stringify(predio));

      linha.remove();
    });
  });

  // 🔹 Botão “Adicionar Andar”
  const btnAdicionarAndar = form.querySelector(".btn.btn-primary");

  btnAdicionarAndar.addEventListener("click", function () {
    const numeroAndar = document.getElementById("numeroAndar").value;
    const banheiros = document.getElementById("banheiros").value;

    if (!numeroAndar || !banheiros) return;

    if (mensagemErro) mensagemErro.style.display = "none";

    const novoAndar = {
      predioId: predio.id,
      predioNome: predio.nome,
      numeroAndar: numeroAndar,
      banheiros: banheiros,
    };

    andares.push(novoAndar);

    // 🔹 Atualiza localStorage
    localStorage.setItem(`andaresPredio_${predio.id}`, JSON.stringify(andares));
    predio.andares = andares;
    localStorage.setItem("predioAtual", JSON.stringify(predio));

    // 🔹 Cria linha na tabela
    const linha = document.createElement("tr");
    linha.innerHTML = `
      <td>${predio.nome}</td>
      <td>Andar ${numeroAndar}</td>
      <td>${banheiros} banheiro(s)</td>
      <td>
        <form class="d-inline">
          <button type="button" class="btn btn-link text-danger text-decoration-none p-0 btnExcluir">Excluir</button>
        </form>
      </td>
    `;
    tbody.appendChild(linha);

    // Evento de exclusão
    linha.querySelector(".btnExcluir").addEventListener("click", function () {
      const index = andares.indexOf(novoAndar);
      if (index > -1) andares.splice(index, 1);
      localStorage.setItem(
        `andaresPredio_${predio.id}`,
        JSON.stringify(andares)
      );
      predio.andares = andares;
      localStorage.setItem("predioAtual", JSON.stringify(predio));
      linha.remove();
    });

    document.getElementById("numeroAndar").value = "";
    document.getElementById("banheiros").value = "";
  });

  // 🔹 Botão “Cadastrar Itens”
  if (btnCadastrarItens) {
    btnCadastrarItens.addEventListener("click", function () {
      const linhas = tbody.querySelectorAll("tr");

      if (linhas.length === 0) {
        if (mensagemErro) {
          mensagemErro.textContent =
            "Você precisa cadastrar ao menos um andar antes de prosseguir.";
          mensagemErro.style.display = "block";
        }
        return;
      }

      if (mensagemErro) mensagemErro.style.display = "none";

      // Dados já estão no localStorage, pode prosseguir
      window.location.href = "/admin/new.itens";
    });
  }
});
