// Script sederhana untuk validasi form
document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const inputs = form.querySelectorAll("input[required], select[required]");
      let valid = true;
      inputs.forEach((input) => {
        if (!input.value.trim()) {
          valid = false;
          input.style.border = "2px solid red";
        } else {
          input.style.border = "1px solid #ddd";
        }
      });
      if (!valid) {
        e.preventDefault();
        alert("Harap isi semua field yang wajib!");
      }
    });
  });
});
