# Debugging & Testing Log

A record of real issues found and fixed while building this project, in the order they happened. Kept for learning purposes — most bugs here are common early-PHP/JS mistakes, and this is a reference for what they looked like and how they were diagnosed.

---

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