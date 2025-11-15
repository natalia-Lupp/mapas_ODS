document.addEventListener("DOMContentLoaded", () => {
  const showFormButton = document.getElementById("showFormButton");
  const formContainer = document.getElementById("formContainer");

  // --- LÓGICA DO MODAL DE EXCLUSÃO ---
  let imageFormToDelete = null;

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
