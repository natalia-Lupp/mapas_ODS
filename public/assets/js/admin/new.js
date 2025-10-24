document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("buildingRegistrationForm");
  const nameBuilding = document.getElementById("id_name");

  form.addEventListener("submit", function (event) {
    // Apenas para evitar envio vazio
    if (!nameBuilding.value.trim()) {
      event.preventDefault();
    }
  });
});
