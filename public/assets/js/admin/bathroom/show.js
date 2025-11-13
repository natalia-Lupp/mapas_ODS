document.addEventListener("DOMContentLoaded", () => {
  const showFormButton = document.getElementById("showFormButton");
  const formContainer = document.getElementById("formContainer");

  if (!showFormButton || !formContainer) return;

  // --- 1) Abrir automaticamente se houver erro ---
  if (formContainer.dataset.open === "1") {
    formContainer.style.display = "block";
    showFormButton.textContent = "Cancelar";
  }

  // --- 2) Abrir / fechar no clique ---
  showFormButton.addEventListener("click", () => {
    const isVisible = formContainer.style.display === "block";

    formContainer.style.display = isVisible ? "none" : "block";
    showFormButton.textContent = isVisible ? "Adicionar imagem" : "Cancelar";
  });
});
