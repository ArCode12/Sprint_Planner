# Changelog

All notable changes to this project, documented in the order they were built.

## Unreleased

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