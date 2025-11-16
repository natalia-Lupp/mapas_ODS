document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("buildingRegistrationForm");
  const nameBuilding = document.getElementById("id_name");

  // Função para capitalizar usando regex
  function capitalizeWords(value) {
    return value.replace(/\b\w/g, function (match) {
      return match.toUpperCase();
    });
  }

  // Atualiza o input ao digitar (opcional)
  nameBuilding.addEventListener("input", function () {
    this.value = capitalizeWords(this.value);
  });

  form.addEventListener("submit", function (event) {
    // Apenas para evitar envio vazio
    if (!nameBuilding.value.trim()) {
    } else {
      // Garante todas as palavras capitalizadas antes de enviar
      nameBuilding.value = capitalizeWords(nameBuilding.value.trim());
    }
  });
});
