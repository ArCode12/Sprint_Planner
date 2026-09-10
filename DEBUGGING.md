# Debugging & Testing Log

A record of real issues found and fixed while building this project, in the order they happened. Kept for learning purposes — most bugs here are common early-PHP/JS mistakes, and this is a reference for what they looked like and how they were diagnosed.

---
### Back button styling wasn't applying despite correct-looking CSS
**Symptom:** The "Back to home" button's text went bold as expected, but the gray background never appeared, even after confirming the CSS rule existed and hard-refreshing.
**Cause:** CSS specificity conflict. An existing rule (`a.btn.ghost:link, a.btn.ghost:visited, a.btn.ghost:active { background: transparent; ... }`) was more specific than `.breadcrumb .btn.ghost` because it included the actual `a` element in the selector — so it won the "background" property even though it appeared earlier in the file. Written order doesn't decide the winner in CSS; specificity does.
**Fix:** Gave the button its own dedicated class (`.back-btn`) instead of reusing `.btn.ghost`, so it no longer competes with the general button rules at all. Used `!important` deliberately and narrowly, only on this one small, self-contained class.
**Lesson:** When a style seems to "partially" apply (some properties work, others don't), that's a strong sign of a specificity conflict between two rules, not a caching or typo issue — check for another selector targeting the same element with equal or higher specificity.

### `board.php` missing after the home page restructure
**Symptom:** Visiting `board.php` (or logging in, which redirects there) showed "webpage isn't available" — the file didn't exist at all.
**Cause:** When splitting the old `index.php` (the board) into a new `index.php` (home page) and `board.php` (the actual board), the instruction was to *rename* the original file — but the new home page content was pasted into `index.php` directly instead, without first saving a copy as `board.php`. The original board markup was never actually moved anywhere.
**Fix:** Recreated `board.php` from scratch using the known-correct board markup, pointing to the existing, untouched `script.js` (which still contained all the real add/edit/delete/drag-and-drop logic).
**Lesson:** When restructuring by "renaming" a file into two purposes, do the rename/copy *first*, then edit the new copy — editing in place risks losing the original content if a copy was never actually made.

### Home page showing unstyled with a missing button
**Symptom:** After confirming `index.php`'s code was correct (including the "Try it now" button and full CSS classes), the live page still showed no styling and was missing that button entirely.
**Cause:** Browser was displaying a cached version of the page from before the demo button and new CSS rules were added — the same caching issue seen earlier in this project.
**Fix:** Hard refresh (Ctrl/Cmd+Shift+R).
**Lesson:** Still the most common false alarm in this project — if the code looks correct but the browser doesn't reflect it, hard-refresh before investigating further.


### Cards appeared to swap between users when testing with two tabs
**Symptom:** Logged in as one user in one browser tab, then a different user in a second tab — refreshing the first tab showed the second user's cards instead of the first user's.
**Cause:** Not an actual bug. PHP sessions are tied to the browser as a whole, not to individual tabs — logging in as a second user in one tab overwrites the session cookie for the *entire* browser, silently switching every open tab to that same logged-in user.
**Fix:** No code change needed. Confirmed by testing with two properly separate sessions instead — one normal window plus one incognito/private window (each gets its own separate cookies).
**Lesson:** To test multi-user behavior correctly, always use two separate browser sessions (incognito, or two different browsers) — never two tabs in the same browser, since they always share one login.


### Empty `index.php` after editing
**Symptom:** Page showed nothing at all.
**Cause:** File content was accidentally deleted while editing.
**Fix:** Restored the full page structure from a known-working copy.
**Lesson:** Keep a backup/reference copy of key files while learning — easy to lose content while pasting or editing.

### Empty `style.css`
**Symptom:** Board displayed as plain, unstyled text; the add-card form was visible instead of hidden.
**Cause:** The stylesheet had never actually been filled in — file existed but was blank.
**Fix:** Added the full CSS.
**Lesson:** "The file exists" isn't the same as "the file has the right content" — always check file contents directly when something isn't styled.

### Duplicated HTML block in `index.php`
**Symptom:** "Sprint Planner" title appeared twice; add-card form sat open on the page instead of hidden; two elements shared `id="modalBackdrop"`.
**Cause:** A chunk of markup got pasted twice, once inside the wrong container.
**Fix:** Rewrote the file cleanly, removing the duplicate block and the duplicate ID.
**Lesson:** HTML `id` attributes must be unique on a page — duplicates cause unpredictable behavior.

### `style.css` not loading despite being correct
**Symptom:** No styling applied even after the CSS was filled in.
**Cause:** Browser was serving a cached copy of `index.php` from before the `<link>` tag existed.
**Fix:** Hard refresh (Ctrl/Cmd+Shift+R).
**Lesson:** When a change doesn't seem to take effect, hard-refresh before assuming the code is wrong — this came up multiple times in this project.

### `script.js` not running at all
**Symptom:** Board title showed, but no columns or cards appeared.
**Cause:** `<script src="script.js"></script>` was missing from `index.php` entirely.
**Fix:** Added the script tag before `</body>`.
**Lesson:** A JS file can be perfectly correct and still do nothing if the page never loads it.

### MySQL crashing in XAMPP
**Symptom:** phpMyAdmin and the site both failed to connect to the database ("actively refused" connection error).
**Cause:** Leftover MySQL replication files from a previous crash.
**Fix:** Cleared the conflicting files; MySQL started normally afterward.
**Lesson:** A red error in the XAMPP log isn't always visible in the most recent lines — check timestamps carefully, and don't assume the newest entry is the cause.

### `script.js` reverted to a tiny test snippet
**Symptom:** Board stopped rendering columns; only the intro box showed.
**Cause:** An earlier temporary test snippet (`fetch(...).then(...)`) had fully replaced the real file contents instead of being added alongside them.
**Fix:** Rewrote `script.js` in full, merging the fetch-based data loading with the original rendering code.
**Lesson:** When adding a temporary test snippet, add it *alongside* existing code, not in place of it.

### Stray character breaking `script.js`
**Symptom:** Board stopped rendering past the intro column; Console showed `ReferenceError: c is not defined`.
**Cause:** A stray `c` character was accidentally left at the end of a line during editing.
**Fix:** Removed the extra character.
**Lesson:** JavaScript errors halt the entire script at the point of failure — everything after the broken line silently never runs. Always check the Console when something stops working partway through.

### Drag-and-drop not responding
**Symptom:** Cards wouldn't drag at all; no console errors.
**Cause:** Browser was running a cached, pre-drag-and-drop version of `script.js`.
**Fix:** Hard refresh.
**Lesson:** Same caching issue as before — worth trying first whenever new code seems to have "no effect."

### Signup crashing instead of showing an error
**Symptom:** Fatal error page (`Uncaught mysqli_sql_exception: Duplicate entry`) instead of the friendly "email already in use" message.
**Cause:** Modern PHP/mysqli throws an exception on a database error by default, rather than returning `false` the way the original `if/else` check assumed.
**Fix:** Wrapped the insert query in `try { } catch (mysqli_sql_exception $e) { }`.
**Lesson:** PHP's error-handling behavior can change between versions — code that "should" work by older conventions may need updating.

### Login form field mismatch
**Symptom:** `Warning: Undefined array key "identifier"` shown on submitting the login form.
**Cause:** The PHP code was updated to read `$_POST["identifier"]`, but the HTML `<input>` still had `name="email"` — the two didn't match.
**Fix:** Updated the input's `name` attribute to `identifier`.
**Lesson:** `$_POST["fieldname"]` only works if it exactly matches the form input's `name` attribute — a very common source of "undefined array key" warnings.

### Name field visible when it should be hidden
**Symptom:** The signup form's Name field showed even on the login page.
**Cause:** A CSS rule (`.field { display: block; }`) was overriding the HTML `hidden` attribute.
**Fix:** Added `.field[hidden] { display: none; }` to make the hidden state take priority. (Later made moot by splitting login and signup into fully separate pages.)
**Lesson:** CSS `display` rules can silently override HTML's built-in `hidden` attribute if not accounted for.

---

## General lessons that came up repeatedly

- **Hard refresh early.** Several "bugs" in this project were actually the browser showing an old cached file. This was the single most common false alarm.
- **Check the Console before guessing.** JavaScript errors point directly at the broken line and the reason — always check before assuming.
- **A file existing isn't the same as a file having the right content.** More than once, a blank or reverted file looked like a mysterious bug but was simply empty.
- **Match names exactly.** Several bugs came down to a mismatch between an HTML `name`/`id` attribute and the PHP/JS code reading it. These are easy to introduce while editing and easy to miss while reading.