document.addEventListener("DOMContentLoaded", function () {

  let predio = JSON.parse(localStorage.getItem("predioCompleto"));

  if (predio) {
    const form = document.getElementById("formEditarPredio");
    const nomePredio = document.getElementById("nomePredio");
    const areaConstruida = document.getElementById("areaConstruida");
    const qtdAndares = document.getElementById("qtdAndares");
    const listaAndares = document.getElementById("listaAndares");
    const mensagemSucesso = document.getElementById("mensagemSucesso");
    // Preenche dados básicos
    nomePredio.value = predio.nome || "";
    areaConstruida.value = predio.areaTotal || "";
    qtdAndares.value = predio.andares ? predio.andares.length : 0;

    // Função para renderizar andares e banheiros
    function renderizarAndares() {
      listaAndares.innerHTML = predio.andares
        .map(
          (andar, i) => `
        <div class="card mb-3 shadow-sm" data-andar-index="${i}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-semibold">Andar ${andar.numero}</h6>
              <button type="button" class="btn btn-outline-danger btn-sm btn-remover-andar">Remover Andar</button>
            </div>
            ${andar.banheiros
              .map(
                (banheiro, j) => `
              <div class="mb-3 d-flex align-items-start" data-banheiro-index="${j}">
                <div class="flex-grow-1">
                  <label class="form-label">Banheiro ${j + 1}</label>
                  <div class="row">
                    <div class="col-md-6">
                      <label class="form-label">Vasos</label>
                      <input type="number" class="form-control campo-vasos" data-andar="${i}" data-banheiro="${j}"
                        value="${
                          banheiro.itens
                            ? banheiro.itens.filter((i) => i === "Vaso").length
                            : 0
                        }" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Torneiras</label>
                      <input type="number" class="form-control campo-torneiras" data-andar="${i}" data-banheiro="${j}"
                        value="${
                          banheiro.itens
                            ? banheiro.itens.filter((i) => i === "Torneira")
                                .length
                            : 0
                        }" />
                    </div>
                  </div>
                </div>
                <div class="ms-3">
                  <button type="button" class="btn btn-outline-danger btn-sm btn-remover-banheiro">Remover Banheiro</button>
                </div>
              </div>
            `
              )
              .join("")}
          </div>
        </div>
      `
        )
        .join("");

      // Reaplica eventos para remover banheiros
      document.querySelectorAll(".btn-remover-banheiro").forEach((btn) => {
        btn.addEventListener("click", function () {
          const container = btn.closest("[data-banheiro-index]");
          const banheiroIndex = Number(container.dataset.banheiroIndex);
          const andarContainer = btn.closest("[data-andar-index]");
          const andarIndex = Number(andarContainer.dataset.andarIndex);

          predio.andares[andarIndex].banheiros.splice(banheiroIndex, 1);
          renderizarAndares();
        });
      });

      // Reaplica eventos para remover andares
      document.querySelectorAll(".btn-remover-andar").forEach((btn) => {
        btn.addEventListener("click", function () {
          const andarContainer = btn.closest("[data-andar-index]");
          const andarIndex = Number(andarContainer.dataset.andarIndex);

          predio.andares.splice(andarIndex, 1);
          qtdAndares.value = predio.andares.length; // atualiza input de quantidade
          renderizarAndares();
        });
      });
    }

    renderizarAndares();

    // Atualiza itens ao salvar
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      predio.nome = nomePredio.value.trim();
      predio.areaTotal = Number(areaConstruida.value);

      document.querySelectorAll(".campo-vasos").forEach((input) => {
        const andarIndex = Number(input.dataset.andar);
        const banheiroIndex = Number(input.dataset.banheiro);
        const qtdVasos = Number(input.value);

        const banheiro = predio.andares[andarIndex].banheiros[banheiroIndex];
        banheiro.itens = banheiro.itens || [];
        banheiro.itens = [
          ...banheiro.itens.filter((i) => i !== "Vaso"),
          ...Array(qtdVasos).fill("Vaso"),
          ...banheiro.itens.filter((i) => i !== "Torneira"),
        ];
      });

      document.querySelectorAll(".campo-torneiras").forEach((input) => {
        const andarIndex = Number(input.dataset.andar);
        const banheiroIndex = Number(input.dataset.banheiro);
        const qtdTorneiras = Number(input.value);

        const banheiro = predio.andares[andarIndex].banheiros[banheiroIndex];
        banheiro.itens = [
          ...banheiro.itens.filter((i) => i !== "Torneira"),
          ...Array(qtdTorneiras).fill("Torneira"),
          ...banheiro.itens.filter((i) => i !== "Vaso"),
        ];
      });

      localStorage.setItem("predioCompleto", JSON.stringify(predio));

      mensagemSucesso.classList.remove("d-none");
      setTimeout(() => {
        mensagemSucesso.classList.add("d-none");
        window.location.href = "/buildings/dashboard.admin";
      }, 1500);

      console.log("Prédio atualizado:", predio);
    });
    //window.location.href = "/buildings/dashboard.admin";
    //return;
  }

  alert("Nenhum prédio encontrado para edição!");
});
