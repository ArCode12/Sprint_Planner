# Project Plan

## Purpose

A sprint planning board, similar in spirit to Trello or Mural, built as a learning project to get real, hands-on experience with full-stack web development — specifically PHP and MySQL, without relying on JavaScript frameworks. The long-term goal is for it to support small teams planning and tracking sprint work together.

## Core features (built so far)

- Six-column board layout: Sprint Planning (info), Backlog, To Do, Coding in progress, Testing in progress, Done
- Cards can be added, edited, deleted, and dragged between columns
- Cards default to a yellow "sticky note" appearance, with a selectable color
- Data is stored in MySQL and persists across visits (not just local browser storage)
- User accounts: signup and login (username or email + password), with sessions
- The board is gated behind login — visiting it without being logged in redirects to the login page
- Visual design: sticky-note card styling, colored column accents, hover interactions

## Planned features (not yet built)

Roughly in the order they're expected to be tackled:

- **Due dates and priority labels** on cards (paused to prioritize login first; database columns for this may already partially exist — check before rebuilding)
- **Per-user data scoping** — currently, all cards are visible to everyone regardless of login; cards need a `user_id` (or `board_id`) so each user/team sees their own board
- **Team collaboration** — inviting others to a shared board, multiple people editing the same board
- **Restructuring `index.php`** into a proper landing/marketing page for logged-out visitors, with the board itself becoming a separate logged-in view/template

## Tech decisions

- **PHP + MySQL**, not a JavaScript framework — matches the developer's existing skill set and learning goals
- **The board (add/edit/delete/drag-and-drop)** uses JavaScript + `fetch()` to talk to PHP endpoints as JSON, so it updates instantly without a full page reload — worth the added complexity here since the interaction is frequent and benefits from feeling instant
- **Login and signup** use traditional PHP form submissions (`$_POST`, full page reloads) instead of JavaScript/fetch — a page reload on login/signup is unnoticeable in practice, and this approach stays fully within PHP, matching the developer's background
- **Passwords** are stored using `password_hash()` / `password_verify()` — never stored as plain text
- **XAMPP** is used for local development (Apache + MySQL + PHP); GitHub Pages (used for earlier static versions of this project) cannot run PHP, so this app requires PHP-capable hosting once deployed publicly

## Out of scope for now

Explicitly not being built yet, to avoid scope creep:

- Real-time collaboration (multiple people editing simultaneously with live updates)
- Password reset / email verification flows
- Mobile app or offline support
- Automated testing (unit/integration tests) — manual testing only at this stage, logged in `DEBUGGING.md`
- Anything requiring paid infrastructure (this remains a free/local learning project for now)