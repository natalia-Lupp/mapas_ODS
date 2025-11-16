let formToSubmit = null;

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btn-delete").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      formToSubmit = document.querySelector(`.delete-form-${id}`);

      const modal = new bootstrap.Modal(
        document.getElementById("deleteConfirmModal")
      );
      modal.show();
    });
  });

  document
    .getElementById("confirmDeleteBtn")
    .addEventListener("click", function () {
      if (formToSubmit) {
        formToSubmit.submit();
      }
    });
});
