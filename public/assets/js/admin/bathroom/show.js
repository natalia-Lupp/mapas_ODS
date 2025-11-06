document.addEventListener("DOMContentLoaded", () => {
  const showFormButton = document.getElementById("showFormButton");
  const formContainer = document.getElementById("formContainer");

  if (!showFormButton || !formContainer) return;

  showFormButton.addEventListener("click", () => {
    const isVisible = formContainer.style.display === "block";
    formContainer.style.display = isVisible ? "none" : "block";
    showFormButton.textContent = isVisible ? "Adicionar imagem" : "Cancelar";
  });
});
