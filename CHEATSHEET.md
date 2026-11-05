# Exam cheat sheet – where everything is

## HTML or PHP?

- **HTML pages** (`index.html`, `experiences.html`, `contact.html`) only show information, so they don't need PHP.
  They use HTML, CSS, Bootstrap and JavaScript. Their menu and footer are written into each page.
- **PHP pages** are used only where the exam asks for server-side work:
  - processing forms: `contact.php`, `subscribe.php`, `register.php`, `login.php`, `booking.php`, admin forms
  - reading from the database: `rooms.php`, `my_bookings.php`, admin pages
  - login, sessions and protected pages: `login.php`, `logout.php`, `auth_check.php`, `admin_check.php`
- Example of "HTML form processed using PHP": `contact.html` has `<form action="contact.php" method="POST">`.
  `contact.php` saves the message in the database and shows the success or error message
  (just like `student_form` → `process.php` in the notes).

Use **Ctrl + F** in VS Code to find the words in quotes.

## Task 1 – Bootstrap 5

| Requirement | File | Look for |
|---|---|---|
| Responsive navbar | `header.php` | `navbar navbar-expand-lg`, `navbar-toggler`, `collapse navbar-collapse` |
| Containers and grid | every page | `container`, `row`, `col-12 col-md-6 col-lg-4` |
| Cards | `index.html`, `rooms.php`, `my_bookings.php` | `card room-card`, `card booking-card` |
| Buttons | everywhere | `btn btn-lux`, `btn btn-lux-outline` |
| Forms | `contact.html`, `booking.php`, `register.php`, `login.php`, `admin_rooms.php`, footer newsletter | `form-control`, `form-select`, `form-label` |
| Alerts | every form page | `alert alert-success`, `alert alert-danger` |
| Modals | `rooms.php` | `modal fade`, `data-bs-toggle="modal"` (click a room card) |
| Progress bars | `booking.php`, `admin_dashboard.php` | `progress`, `progress-bar` |
| Image carousel | `index.html` | `carousel slide` ("Moments to share") |

## Task 2 – JavaScript (`js/script.js`)

**Functions** (more than 2): `calculateTotal()`, `countNights()`, `getMealPrice()` (uses a **switch**),
`showWelcome()` (uses **if / else if / else**), `validateBooking()`, `validateRegister()`, `validateContact()`,
`openMenu()`, `closeMenu()`, `changeMenuBackground()`, `resetMenuBackground()`, `togglePassword()`, `updateProgress()`.

**Events:**

| Event | Where | What happens |
|---|---|---|
| `onclick` | "Menu" button (every page), `login.php` "Show" button, `contact.php` "Go back" button | Opens the full-screen menu, shows the password, goes back with `history.back()` |
| `onchange` | `booking.php` room, dates, guests, meal plan; `rooms.php` sort | Updates the total price; reloads sorted rooms |
| `onmouseover` | Full-screen menu links (every page) | Changes the menu background photo and turns the link gold |
| `onmouseout` | Full-screen menu links (every page) | Puts the original background and colour back |
| `onsubmit` | `booking.php`, `register.php`, `contact.html` forms | Runs the validation function |
| `scroll`, `keydown` (addEventListener) | `script.js` bottom | Navbar turns solid when scrolling; Esc closes the menu |

**Validation (3+ fields):**
- `validateRegister()`: name not empty, email format, username 4–20 characters, password at least 6 characters, passwords match.
- `validateBooking()`: room chosen, check-in not in the past, check-out after check-in, guests within the room's limit, phone format.
- `validateContact()`: name, email, subject, message at least 10 characters.

## Task 3 – PHP

| Requirement | Where |
|---|---|
| Forms processed with PHP | `contact.html` → `contact.php`; footer newsletter → `subscribe.php`; booking, register, login and admin pages use `if ($_SERVER["REQUEST_METHOD"] == "POST")` |
| **GET** | `rooms.php` (`$_GET["type"]`, `$_GET["sort"]`), `booking.php?room_id=`, `admin_bookings.php?status=`, `admin_rooms.php?edit=`, messages like `login.php?message=registered` |
| **POST** | every form that saves data |
| include / require | `require "db_connect.php";`, `require "auth_check.php";`, `require "admin_check.php";`, `include "header.php";`, `include "footer.php";`, `include "admin_nav.php";` |
| Success and error messages | `$successMessage` / `$errorMessage` → `alert-success` / `alert-danger` |

## Task 4 – MySQL

- Database **`hotel_db`** with 5 tables: `users`, `rooms`, `bookings`, `messages`, `subscribers`.
- Connection: `db_connect.php` → `new mysqli($host, $username, $password, $database)` and `connect_error`.
- **Insert:** `INSERT INTO bookings` (booking.php), `INSERT INTO users` (register.php), `INSERT INTO messages` (contact.php), `INSERT INTO rooms` (admin_rooms.php), `INSERT INTO subscribers` (subscribe.php).
- **Retrieve and display:** `SELECT * FROM rooms` (rooms.php), bookings with `JOIN` (my_bookings, admin_bookings), users, messages.
- **Update / delete** (extra): confirm/cancel bookings, edit rooms, delete bookings, rooms and messages.
- Every insert/update/delete uses **prepare → bind_param → execute** like in the notes.
  - `bind_param` letters: `s` = string, `i` = integer, `d` = decimal number.
- `mysqli_report(MYSQLI_REPORT_OFF);` in `db_connect.php`: newer PHP stops the page with an error
  instead of returning false. This line makes the `if ($stmt->execute()) { ... } else { ... }` messages work like in the notes.

## Task 5 – Login

- `login.php`: finds the user with `SELECT ... WHERE username = ?`, then `password_verify()` checks the password.
- Passwords are saved with `password_hash()` (in `register.php`), so the database never stores the real password.
- After login: `$_SESSION["user_id"]`, `$_SESSION["full_name"]`, `$_SESSION["role"]`.
- **Protected pages:** `require "auth_check.php";` (booking, my bookings) – no session → sent to the login page.
- **Admin only:** `require "admin_check.php";` (all `admin_` pages) – a normal user is sent back with "only for the hotel admin".
- **Logout:** `logout.php` → `$_SESSION = []; session_destroy();`.
- `htmlspecialchars()` is used when **displaying** data, so the text is shown safely and isn't saved twice-encoded.

## Demo order (about 5 minutes)

1. Home (`index.html`): video, scroll (navbar turns solid), hover the **Menu** links, the carousel.
   Explain: HTML for pages that only show information, PHP only for server-side work.
2. Rooms: filter buttons (look at the **URL** – that's GET), sort, open a room modal, "Book this room".
3. Not logged in → sent to Log in (protected page).
4. Create an account with wrong data (JS errors), then correct data (success message).
5. Log in → welcome message → book a room: watch the total and progress bar change → success alert.
6. My bookings → cancel. Then try `admin_dashboard.php` as this user → blocked.
7. Log out, log in as **admin** → dashboard, confirm a booking, add / edit / delete a room, messages.
8. Show phpMyAdmin: the new rows in `users`, `bookings` and `messages`.

## Changes she might ask for

| She asks | What to do |
|---|---|
| Change a success message | Search the text, e.g. "Your message has been sent", and edit it |
| Add a field to the contact form (e.g. phone) | 1) phpMyAdmin: add column `phone VARCHAR(30)` to `messages`. 2) Add an `<input name="phone">` in `contact.html`. 3) In `contact.php`: `$phone = trim($_POST["phone"]);` and add `phone` and one more `?` to the INSERT, and one more `s` in `bind_param("sssss", ...)` |
| Change the breakfast price | `script.js` → `getMealPrice()` and `booking.php` → `$mealPrice = 22;` (both!) |
| Make the password at least 8 characters | `script.js` → `validateRegister()` `password.length < 6`, and `register.php` → `strlen($password) < 6` |
| Show rooms in a different order | `rooms.php`: change `ORDER BY price` (`$sort`) |
| Show only confirmed bookings | `admin_bookings.php?status=Confirmed` or add `WHERE status = 'Confirmed'` |
| Change a colour | `css/style.css` top: `--ocean-700`, `--sand-50`, `--accent` |
| Add a new menu link | `header.php` **and** the three `.html` pages: copy one `<li class="nav-item">` (navbar) and one `<li>` in `menu-links` |
| Count something new on the dashboard | `admin_dashboard.php`: copy a `SELECT COUNT(*) AS total ...` block and a `stat-card` |
