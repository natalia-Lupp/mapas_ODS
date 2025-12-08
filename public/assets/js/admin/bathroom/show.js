function capitalizeFirstLetter(str) {
  if (!str) {
    return ""; // Handle empty or null strings
  }
  return str.charAt(0).toUpperCase() + str.slice(1);
}
document.addEventListener("DOMContentLoaded", async () => {
  const showFormButton = document.getElementById("showFormButton");
  const formContainer = document.getElementById("formContainer");
  const consumptions = document.getElementById('consumptions');
  // --- LÓGICA DO MODAL DE EXCLUSÃO ---
  let imageFormToDelete = null;

  let email = sessionStorage.getItem('email');
  let password = sessionStorage.getItem('password');

  if (email && password) {
    fetch(
      `/api/buildings/${consumptions.getAttribute('building')}/bathrooms/${consumptions.getAttribute('bathroom')}/consumptions`,
      {
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Basic ${btoa(email+':'+password)}`
        }
      }
    ).then( async (response) => {
      data = JSON.parse(await response.text());
      if (data.length > 0) {

        data.forEach((o) =>{
          consumptions.innerHTML += `
            <tr>
              <td>${capitalizeFirstLetter(o.name)}</td>
              <td>${o.quantity}L</td>
              <td>${o.date}</td>
            </tr>
          `
        });
      } else {
        consumptions.innerHTML += `<tr>Nenhum registro de comsumo.</tr>`;
      }
    }).catch((e) => consumptions.innerHTML += e  ?? `<tr>Nenhum registro de comsumo.</tr>`)
  }

  document.querySelectorAll(".btn-delete-image").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      imageFormToDelete = document.querySelector(`.delete-image-form-${id}`);

      const modal = new bootstrap.Modal(
        document.getElementById("deleteImageConfirmModal")
      );
      modal.show();
    });
  });

  document
    .getElementById("confirmDeleteImageBtn")
    .addEventListener("click", () => {
      if (imageFormToDelete) {
        imageFormToDelete.submit();
      }
    });

  // --- FORM VISUAL DE UPLOAD (mantido) ---
  if (showFormButton && formContainer) {
    if (formContainer.dataset.open === "1") {
      formContainer.style.display = "block";
      showFormButton.textContent = "Cancelar";
    }

    showFormButton.addEventListener("click", () => {
      const isVisible = formContainer.style.display === "block";
      formContainer.style.display = isVisible ? "none" : "block";
      showFormButton.textContent = isVisible ? "Adicionar imagem" : "Cancelar";
    });
  }
});
