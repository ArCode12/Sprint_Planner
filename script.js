/* Sprint Planner — fetches cards from the database via get_cards.php */

const STAGE_COLUMNS = [
  { id: "backlog", name: "Backlog" },
  { id: "todo", name: "To Do" },
  { id: "coding", name: "Coding in progress" },
  { id: "testing", name: "Testing in progress" },
  { id: "done", name: "Done" },
];

const DEFAULT_CARD_COLOR = "#F6E58D";
const CARD_COLORS = ["#F6E58D", "#A7D8F0", "#C9E8B8", "#F2B8C6", "#D6C6F2", "#FFFFFF"];

let cards = [];

const boardTable = document.getElementById("boardTable");

/* ---------- Load cards from the database ---------- */

function loadCards() {
  fetch("get_cards.php")
    .then(response => response.json())
    .then(data => {
      cards = data;
      render();
    });
}

/* ---------- Rendering ---------- */

function render() {
  boardTable.innerHTML = "";

  const introEl = document.createElement("div");
  introEl.className = "col intro";
  introEl.innerHTML = `
    <div class="col-head">Sprint Planning</div>
    <div class="col-body">
      <p><strong>What it is:</strong> a short, focused meeting at the start of each sprint where the team decides what work to take on and how to get it done.</p>
      <p><strong>Why it helps:</strong> it aligns everyone on priorities, breaks big goals into doable tasks, surfaces risks early, and sets a realistic pace &mdash; so the whole team knows exactly what "done" looks like before the sprint starts.</p>
    </div>
  `;
  boardTable.appendChild(introEl);

  STAGE_COLUMNS.forEach(col => {
    const colCards = cards.filter(c => c.column_name === col.id);

    const colEl = document.createElement("div");
    colEl.className = "col stage";
    colEl.innerHTML = `
      <div class="col-head">
        <span>${escapeHtml(col.name)}</span>
        <span class="col-count">${colCards.length}</span>
      </div>
      <div class="col-body" data-column-id="${col.id}"></div>
      <button class="column-add">+ Add card</button>
    `;
      colEl.querySelector(".column-add").addEventListener("click", () => openModal(col.id));
    const bodyEl = colEl.querySelector(".col-body");

    if (colCards.length === 0) {
      const hint = document.createElement("div");
      hint.className = "empty-hint";
      hint.textContent = "No cards yet";
      bodyEl.appendChild(hint);
    }
    colCards.forEach(card => bodyEl.appendChild(renderCard(card)));

    boardTable.appendChild(colEl);
  });
}

function renderCard(card) {
  const el = document.createElement("article");
  el.className = "card";
  el.style.background = card.color;
  el.innerHTML = `<div class="card-title"></div>`;
  el.querySelector(".card-title").textContent = card.title;
  return el;
}

function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

/* ---------- Init ---------- */

loadCards();


/* ---------- Add card modal ---------- */

const backdrop = document.getElementById("modalBackdrop");
const titleInput = document.getElementById("cardTitleInput");
const detailInput = document.getElementById("cardDetailInput");
const swatchesEl = document.getElementById("swatches");
const saveCardBtn = document.getElementById("saveCardBtn");
const cancelModalBtn = document.getElementById("cancelModalBtn");

let selectedColumnId = null;
let selectedColor = DEFAULT_CARD_COLOR;

// Build the color swatch buttons once
CARD_COLORS.forEach(color => {
  const sw = document.createElement("div");
  sw.className = "swatch";
  sw.style.background = color;
  sw.addEventListener("click", () => {
    selectedColor = color;
    [...swatchesEl.children].forEach(s => s.classList.remove("selected"));
    sw.classList.add("selected");
  });
  swatchesEl.appendChild(sw);
});

function openModal(columnId) {
  selectedColumnId = columnId;
  selectedColor = DEFAULT_CARD_COLOR;
  titleInput.value = "";
  detailInput.value = "";
  backdrop.classList.add("open");
}

function closeModal() {
  backdrop.classList.remove("open");
}

cancelModalBtn.addEventListener("click", closeModal);

saveCardBtn.addEventListener("click", () => {
  const title = titleInput.value.trim();
  if (!title) return;

  fetch("add_card.php", {
    method: "POST",
    body: JSON.stringify({
      title: title,
      column_name: selectedColumnId,
      color: selectedColor
    })
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        closeModal();
        loadCards();
      } else {
        alert("Could not save card: " + result.error);
      }
    });
});