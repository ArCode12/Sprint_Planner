/* Sprint Planner demo — everything lives in memory only, resets on refresh */

const STAGE_COLUMNS = [
  { id: "backlog", name: "Backlog", accent: "#94A3B8" },
  { id: "todo", name: "To Do", accent: "#0B5FA5" },
  { id: "coding", name: "Coding in progress", accent: "#D97706" },
  { id: "testing", name: "Testing in progress", accent: "#7C3AED" },
  { id: "done", name: "Done", accent: "#16A34A" },
];

const DEFAULT_CARD_COLOR = "#F6E58D";
const CARD_COLORS = ["#F6E58D", "#A7D8F0", "#C9E8B8", "#F2B8C6", "#D6C6F2", "#FFFFFF"];

function uid() {
  return Math.random().toString(36).slice(2, 10) + Date.now().toString(36);
}

let cards = [
  { id: uid(), column_name: "backlog", title: "Draft the sprint goal", color: DEFAULT_CARD_COLOR, priority: "medium", due_date: null },
  { id: uid(), column_name: "backlog", title: "Try dragging this card →", color: "#A7D8F0", priority: "low", due_date: null },
];

const boardTable = document.getElementById("boardTable");

/* ---------- Rendering ---------- */

function render() {
  boardTable.innerHTML = "";

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

    bodyEl.addEventListener("dragover", (e) => e.preventDefault());
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

  const priorityLabel = card.priority.charAt(0).toUpperCase() + card.priority.slice(1);

  el.innerHTML = `
    <div class="card-title"></div>
    <div class="card-meta">
      <span class="priority-badge priority-${card.priority}">${priorityLabel}</span>
      ${card.due_date ? `<span class="due-date">${formatDate(card.due_date)}</span>` : ""}
    </div>
  `;
  el.querySelector(".card-title").textContent = card.title;

  el.addEventListener("click", () => openModal(card.column_name, card));
  el.addEventListener("dragstart", (e) => e.dataTransfer.setData("text/plain", card.id));
  return el;
}

function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString(undefined, { month: "short", day: "numeric" });
}

function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

function moveCard(cardId, newColumnId) {
  const card = cards.find(c => c.id === cardId);
  if (card) {
    card.column_name = newColumnId;
    render();
  }
}

/* ---------- Add/edit modal ---------- */

const backdrop = document.getElementById("modalBackdrop");
const titleInput = document.getElementById("cardTitleInput");
const detailInput = document.getElementById("cardDetailInput");
const dueDateInput = document.getElementById("cardDueDateInput");
const priorityInput = document.getElementById("cardPriorityInput");
const swatchesEl = document.getElementById("swatches");
const saveCardBtn = document.getElementById("saveCardBtn");
const cancelModalBtn = document.getElementById("cancelModalBtn");
const deleteCardBtn = document.getElementById("deleteCardBtn");

let selectedColumnId = null;
let selectedColor = DEFAULT_CARD_COLOR;
let editingCardId = null;

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

function openModal(columnId, existingCard) {
  selectedColumnId = columnId;
  editingCardId = existingCard ? existingCard.id : null;
  selectedColor = existingCard ? existingCard.color : DEFAULT_CARD_COLOR;
  titleInput.value = existingCard ? existingCard.title : "";
  detailInput.value = "";
  dueDateInput.value = existingCard && existingCard.due_date ? existingCard.due_date : "";
  priorityInput.value = existingCard ? existingCard.priority : "medium";
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

  const dueDate = dueDateInput.value || null;
  const priority = priorityInput.value;

  if (editingCardId) {
    const card = cards.find(c => c.id === editingCardId);
    card.title = title;
    card.color = selectedColor;
    card.due_date = dueDate;
    card.priority = priority;
  } else {
    cards.push({
      id: uid(),
      column_name: selectedColumnId,
      title,
      color: selectedColor,
      due_date: dueDate,
      priority,
    });
  }
  closeModal();
  render();
});

deleteCardBtn.addEventListener("click", () => {
  cards = cards.filter(c => c.id !== editingCardId);
  closeModal();
  render();
});

/* ---------- Init ---------- */

render();