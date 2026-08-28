/* Sprint Planner — fetches cards from the database via get_cards.php */

const STAGE_COLUMNS = [
  { id: "backlog", name: "Backlog", accent: "#94A3B8" },
  { id: "todo", name: "To Do", accent: "#0B5FA5" },
  { id: "coding", name: "Coding in progress", accent: "#D97706" },
  { id: "testing", name: "Testing in progress", accent: "#7C3AED" },
  { id: "done", name: "Done", accent: "#16A34A" },
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
    colEl.style.setProperty("--accent", col.accent);
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

    bodyEl.addEventListener("dragover", (e) => {
      e.preventDefault();
    });

    bodyEl.addEventListener("drop", (e) => {
      e.preventDefault();
      const cardId = e.dataTransfer.getData("text/plain");
      moveCard(cardId, col.id);
    });

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
  el.draggable = true;
  el.innerHTML = `<div class="card-title"></div>`;
  el.querySelector(".card-title").textContent = card.title;
  el.addEventListener("click", () => openModal(card.column_name, card));
  el.addEventListener("dragstart", (e) => {
    e.dataTransfer.setData("text/plain", card.id);
  });
  return el;
}

function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

function moveCard(cardId, newColumnId) {
  fetch("update_card.php", {
    method: "POST",
    body: JSON.stringify({ action: "move", id: cardId, column_name: newColumnId })
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        loadCards();
      }
    });
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
const deleteCardBtn = document.getElementById("deleteCardBtn");

deleteCardBtn.addEventListener("click", () => {
  fetch("update_card.php", {
    method: "POST",
    body: JSON.stringify({ action: "delete", id: editingCardId })
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        closeModal();
        loadCards();
      }
    });
});

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

let editingCardId = null;

function openModal(columnId, existingCard) {
  selectedColumnId = columnId;
  editingCardId = existingCard ? existingCard.id : null;
  selectedColor = existingCard ? existingCard.color : DEFAULT_CARD_COLOR;
  titleInput.value = existingCard ? existingCard.title : "";
  detailInput.value = "";
  deleteCardBtn.hidden = !existingCard;
  backdrop.classList.add("open");
}

function closeModal() {
  backdrop.classList.remove("open");
}

cancelModalBtn.addEventListener("click", closeModal);

saveCardBtn.addEventListener("click", () => {
  const title = titleInput.value.trim();
  if (!title) return;

  const isEditing = editingCardId !== null;
  const url = isEditing ? "update_card.php" : "add_card.php";
  const body = isEditing
    ? { action: "update", id: editingCardId, title: title, color: selectedColor }
    : { title: title, column_name: selectedColumnId, color: selectedColor };

  fetch(url, {
    method: "POST",
    body: JSON.stringify(body)
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