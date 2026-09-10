# Changelog

All notable changes to this project, documented in the order they were built.

## Unreleased

## Navigation and Accessibility fixes 

- Fixed a CSS specificity conflict where the "Back to home" button's background wasn't applying - gave it a dedicated '.back-btn' class instead of reusing '.btn.ghost', which was losing a specificity fight against another rule 

## Sprint planning guide page
- Added `guide.php` — a dedicated page explaining what sprint planning is (an article-style section) and a 5-step breakdown matching the board's actual columns (Backlog, To Do, Coding in progress, Testing in progress, Done)
- Linked to it from the home page nav as "How it works"
- Added reusable `.panel-section`, `.article`, `.process`, and `.section-eyebrow` styles, meant to scale to future content/template pages rather than being one-off styles for this page alone
- Added a "Back to home" button to `login.php` and `signup.php` as well, using the same `.back-btn` styling as the guide page

## Public home page and demo board
- Split the old `index.php` (which was the board) into `board.php` (the real, saving board) and a new `index.php` (a logged-out marketing home page)
- `index.php` redirects logged-in users straight to `board.php`; `login.php` and `signup.php` now redirect to `board.php` on success instead of the old `index.php`
- Added a hero section, feature highlights, and a yellow accent badge to the home page
- Added `demo.php` + `demo.js` — a fully interactive board (add, edit, delete, drag-and-drop, due dates, priority) for logged-out visitors to try, with all data kept in a JavaScript array only — no `fetch`, no database, resets on every refresh
- Fixed a gap where `board.php` was never actually created during the home page restructure — `index.php` and `login.php` were already pointing to it, but the file itself didn't exist yet. Recreated it with the original board markup (header, user badge, logout, add/edit modal) pointing to the existing `script.js`.

## Per-user data scoping
- Added a `user_id` column to the `cards` table, linking every card to the account that created it
- Assigned existing pre-login cards to a specific test account so nothing was lost
- `get_cards.php` now only fetches cards belonging to the logged-in user (`WHERE user_id = ...`), instead of every card in the table
- `add_card.php` now saves the logged-in user's `id` on every new card
- `update_card.php`'s update, move, and delete actions all now require `AND user_id = ...` matching the logged-in user — so a card's `id` alone isn't enough to edit or delete it; it must actually belong to that user
- Verified correct behavior by testing with two separate accounts in genuinely separate sessions (a normal window + a guest window)

## Due dates and priority labels
- Added `due_date` (optional `DATE`) and `priority` (`low`/`medium`/`high`, defaults to `medium`) columns to the `cards` table
- Added a date picker and priority dropdown to the Add/Edit card modal
- `add_card.php` and `update_card.php` both handle an empty due date the same way email was handled at signup — stored as SQL `NULL` rather than an empty string
- Cards now display a colored priority badge (green/amber/red for low/medium/high) and a formatted due date (e.g. "Mar 15") when one is set

## Layout and card refinements
- Switched the board from a scrolling flex row to a CSS grid (`repeat(6, minmax(0, 1fr))`), so all 6 columns always fit the available width with no horizontal scrolling
- Removed the tilted rotation on cards — straightened into plain rectangles, with more padding and a minimum height so they read as proper sticky notes rather than thin strips
- Redesigned the page header: larger title, a bottom border separating it from the board, and better spacing between the user badge and Log out button
- Fixed the Log out button styling — the `a.btn.ghost` rule for turning the `<a>` tag into a proper-looking button hadn't actually been saved in an earlier step

## Project documentation
- Added `DEBUGGING.md` — a log of every real bug encountered during development, with symptoms, causes, and fixes, for future reference
- Added `PLANNING.md` — a short project plan covering purpose, current features, planned features, tech decisions, and what's explicitly out of scope for now

## Removed Javascript files
- Removed `login.js` and `signup.js` — no longer used after switching signup/login to plain PHP form submissions instead of JavaScript/fetch


## Login and session gating
- `index.php` now checks `$_SESSION["user_id"]` at the very top and redirects to `login.php` if nobody is logged in, before any board HTML is sent
- Added a header row showing the logged-in user's name and a Log out link
- Added `logout.php` — destroys the session and redirects to `login.php`
- Fixed `<a>` tag styling so the Log out link visually matches the app's other buttons
- Removed `login.js` and `signup.js` — no longer used after switching signup/login to plain PHP form submissions instead of JavaScript/fetch

## User accounts 

- Signup now logs the user in immediately instead of redirecting to a separate login step — uses `mysqli_insert_id()` to get the new user's `id` right after inserting, then sets `$_SESSION` directly in `signup.php`
- Made `email` optional at signup: an empty field is stored as SQL `NULL` rather than an empty string, so the `UNIQUE` constraint doesn't block multiple users from all leaving it blank
- Fixed a mismatched form field name (`email` vs `identifier`) that broke login after switching to username-or-email login
- Wrapped the signup database insert in `try/catch (mysqli_sql_exception)`, since newer PHP throws an exception on a duplicate entry instead of just returning `false`

## Visual polish
- Restyled cards to look like sticky notes: no border, offset drop shadow, alternating slight rotation per card (`nth-child(odd)`/`nth-child(even)`), and a folded-corner detail via a `::after` pseudo-element
- Added a hover state: cards straighten, lift, and scale up slightly, with `z-index` raised so they sit above neighboring tilted cards
- Added a colored top accent bar per column via a `--accent` CSS variable, set per-column in JavaScript and read by `style.css` — makes each stage visually distinct at a glance
- Added a generated SVG favicon (no image file needed) matching the board's blue
## Drag and drop between columns
- Cards are now draggable (`draggable = true`), storing the card's `id` on `dragstart` via `e.dataTransfer`
- Each column's card area listens for `dragover` (with `preventDefault()`, required to allow dropping at all) and `drop`, which reads back the card's `id` and calls `moveCard()`
- Extended `update_card.php` with a third `"move"` action, which updates only `column_name`, leaving the card's title and color untouched


## delete cards
- Reused the same `openModal` form for both adding and editing — the Save button checks whether an existing card was passed in (`editingCardId`) and calls `add_card.php` or `update_card.php` accordingly
- Added `update_card.php` to handle both editing and deleting a card in a single file, based on an `action` field sent from the browser (`"delete"` vs. anything else defaults to an update)
- Used `(int)` type casting on the incoming card `id` before using it in SQL, since it's inserted directly into the query without `mysqli_real_escape_string` — casting to an integer prevents anything except a plain number reaching the database
- `DELETE` removes a single row by `id`; `UPDATE` changes only `title` and `color`, leaving `column_name` untouched so the card stays in place
- Delete button only appears when editing an existing card, not when adding a new one
- Added the "+ Add card" feature — clicking "+ Add card" on any column opens a form, and saving it sends the new card to `add_card.php`, which stores it in the database; the board then reloads to show it
- Fixed a JavaScript syntax error (a stray character left in `script.js`) that was breaking the whole board after the add-card code was introduced


## Add & edit cards
- Added the "+ Add card" feature — clicking "+ Add card" on any column opens a form, and saving it sends the new card to `add_card.php`, which stores it in the database; the board then reloads to show it
- Fixed a JavaScript syntax error (a stray character left in `script.js`) that was breaking the whole board after the add-card code was introduced

## Cleanup
- Removed one-off setup scripts (`create_database.php`, `create_table.php`, `add_test_card.php`, `test_connection.php`) now that their jobs were done, keeping the project folder to only the files actually used by the running site

## PHP/MySQL backend
- Connected the site to MySQL for the first time via `db_connect.php`
- Created the `sprint_planner` database and the `cards` table (`id`, `title`, `column_name`, `color`)
- Built `get_cards.php` to read all cards from the database and return them as JSON
- Rewrote `script.js` to fetch real cards from the database instead of using hardcoded starter data
- Fixed `index.php` after it was found completely empty — restored the page structure (title, board container, add-card modal)
- Fixed a duplicated block inside `index.php` where the title and board container had accidentally been pasted a second time inside the modal, and two elements shared the same ID
- Fixed a broken Google Fonts link in `index.php` (stray spaces inside the URL were breaking it)
- Fixed `style.css` after it was found completely empty — added the full stylesheet so the board displays styled instead of as plain unstyled text
- Fixed a missing `<script src="script.js"></script>` tag in `index.php`, which meant none of the board's JavaScript was ever running
- Diagnosed and resolved a local MySQL crash in XAMPP (leftover replication files) that was preventing phpMyAdmin and the site from connecting to the database

## Static board (v0.1)
- Initial board layout matching the sprint planning design: one row of six equal-width columns
- Sprint Planning column (blue) with an explanation of what sprint planning is and why it helps
- Backlog, To Do, Coding in progress, Testing in progress, and Done columns
- Cards default to a yellow background, with an add-card form to change the color
- Board built with plain HTML, CSS, and JavaScript, styled with Google Fonts (Space Grotesk / Inter)

## Project setup
- Repository created on GitHub (`ArCode12/Sprint_Planner`) and made public
- `README.md`, `LICENSE` (MIT), and `.gitignore` added