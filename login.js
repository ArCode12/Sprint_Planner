const emailInput = document.getElementById("emailInput");
const passwordInput = document.getElementById("passwordInput");
const authError = document.getElementById("authError");
const submitBtn = document.getElementById("submitBtn");

submitBtn.addEventListener("click", () => {
  const identifier = emailInput.value.trim();
  const password = passwordInput.value;

  if (!identifier || !password) return;

  fetch("auth.php", {
    method: "POST",
    body: JSON.stringify({ action: "login", identifier, password })
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        window.location.href = "index.php";
      } else {
        authError.textContent = result.error;
        authError.hidden = false;
      }
    });
});