const nameInput = document.getElementById("nameInput");
const emailInput = document.getElementById("emailInput");
const passwordInput = document.getElementById("passwordInput");
const authError = document.getElementById("authError");
const submitBtn = document.getElementById("submitBtn");

submitBtn.addEventListener("click", () => {
  const name = nameInput.value.trim();
  const email = emailInput.value.trim();
  const password = passwordInput.value;

  if (!name || !email || !password) return;

  fetch("auth.php", {
    method: "POST",
    body: JSON.stringify({ action: "signup", name, email, password })
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