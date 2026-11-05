# Côte d'Or Retreat – Hotel Reservation System

A luxury redesign of the Côte d'Or Retreat website (Anse Volbert, Praslin, Seychelles) with a working
booking system. Built with HTML, CSS, JavaScript, Bootstrap 5.3.8, PHP and MySQL (MySQLi).

## 1. Run it with WampServer

1. Start **WampServer** and wait until its icon turns **green**.
2. Copy the whole `cotedor` folder into `C:\wamp64\www\`, so you have `C:\wamp64\www\cotedor\index.html`.
3. Open phpMyAdmin (left-click the Wamp icon → **phpMyAdmin**, or the address from class, e.g.
   `http://localhost/phpmyadmin5.2.3/`), log in with user `root` and an empty password.
4. Click **Import**, choose `hotel_db.sql` from the project folder, and click **Import** (or **Go**) at the bottom.
   This creates the `hotel_db` database with all tables and sample data.
5. Open **http://localhost/cotedor/** in your browser (this opens `index.html`).

> Open the site through `http://localhost/...`. Double-clicking a `.php` file will not work.

## 2. Test accounts

| Role  | Username | Password   | Opens                      |
|-------|----------|------------|----------------------------|
| Admin | `admin`  | `admin123` | Admin dashboard            |
| User  | `guest`  | `guest123` | My bookings, can book rooms |

You can also create new User accounts on the **Create account** page.

## 3. Photos and video

The site works without photos (it shows sand-coloured frames), but it looks its best with them.

**Quick way:** double-click **`download-photos.bat`** in the project folder (internet needed). It downloads
22 free Pexels photos and the beach video into `images` and `video` with the right names. Photos that are
already in the folder are skipped, so it never replaces your own. The list of photos is in `CREDITS.md`.

**Photos from the official Côte d'Or Retreat website:** open cotedorretreat.com, right-click a photo →
**Save image as…**, and save it into the `images` folder using one of the names below (for example the real
pool as `pool.jpg`, the real rooms as `room-double.jpg`, `room-selfcatering.jpg`, `room-apartment.jpg`).
Do this **before** running `download-photos.bat`, or save over the downloaded file afterwards.
If a photo saves as `.webp`, just rename it to the `.jpg` name; it will still show.

To change any photo later, save a new one into the `images` folder with **exactly** the same name:

| File name | What to look for | Where it shows |
|---|---|---|
| `hero.jpg` | Wide shot of a tropical beach or bay | Top of home page, menu background |
| `intro-1.jpg` | Tall photo, person relaxing among palms / terrace | Home – "Since 2025" collage (big) |
| `intro-2.jpg` | Aerial shot of waves on a beach | Home collage (middle) |
| `intro-3.jpg` | Palm trees by the sea | Home collage (small) |
| `strip-1.jpg` | Granite rocks on a Seychelles beach | Home – "An island escape" strip |
| `strip-2.jpg` | Tropical breakfast with fruit | Home strip |
| `strip-3.jpg` | Turquoise bay with white sand | Home strip |
| `strip-4.jpg` | Pool with palm trees | Home strip |
| `room-double.jpg` | Bright hotel bedroom, double bed | Double Room, menu "Rooms" |
| `room-selfcatering.jpg` | Hotel room with small kitchen | Self-Catering room |
| `room-apartment.jpg` | Apartment living room | Two-Bedroom Apartment |
| `spa.jpg` | Spa / massage room | Spa banner, Experiences |
| `restaurant.jpg` | Restaurant table by the sea | Dining section |
| `story-1.jpg` | Cocktail at a bar | Dining section (small) |
| `story-2.jpg` | Spa details – flowers, stones, oils | Experiences – spa |
| `pool.jpg` | Hotel swimming pool | Carousel, Booking page, Log in |
| `beach.jpg` | Calm beach with soft sand | Location, Contact, Register |
| `moment-1.jpg` | Person swimming in clear water | Carousel slide 1 |
| `moment-2.jpg` | Palm forest (search "Vallée de Mai" or "palm forest") | Carousel slide 3 |
| `moment-3.jpg` | Sunset over the ocean | Carousel slide 4 |
| `vallee-de-mai.jpg` | Palm forest / coco de mer | Experiences – Explore |
| `anse-lazio.jpg` | Beach with granite boulders (search "Anse Lazio" or "Praslin") | Experiences – Explore |

Useful searches: "Seychelles", "Praslin", "La Digue", "tropical beach", "luxury hotel room", "spa".

**Top-of-page video:** download a free beach or ocean video from **pexels.com/videos** (search "tropical beach
drone"), choose the HD (1280 or 1920 wide) size, and save it as `video/hero.mp4`. Keep it under about 15 MB.
If there is no video, `hero.jpg` shows instead.

## 4. Things to update

- **Phone and email** (`+248 4 000 000`, `stay@cotedorretreat.com`) are placeholders. Replace them with the
  hotel's real details in `header.php` (menu) and `footer.php`, and in the three `.html` pages
  (they have their own copy of the menu and footer). Use VS Code's **Find in Files** (Ctrl + Shift + F) to change them all at once.
- **Room prices** come from the database. Change them on the admin **Rooms** page.

## 5. Before you submit

1. Test everything on localhost.
2. In phpMyAdmin, select `hotel_db` → **Export** → **Go**, and save the file as `hotel_db.sql`
   (this is the `.sql` file you submit, with your latest data).
3. Upload the folder to GitHub.

## Files

**HTML** is used for pages that only show information. **PHP** is used only where the exam needs
server-side work: processing forms, the database, login, sessions and protected pages.


| File | What it does |
|---|---|
| `db_connect.php` | Connects to MySQL with MySQLi (required by every page that uses the database) |
| `header.php`, `footer.php` | Shared navbar, full-screen menu and footer (included on every PHP page) |
| `auth_check.php` | Protects pages that need a logged-in user |
| `admin_check.php` | Protects admin pages (Admin role only) |
| `admin_nav.php` | The tab bar on admin pages |
| `index.html` | Home page (plain HTML – no server-side work needed) |
| `experiences.html` | Spa, dining, pool and places to explore (plain HTML) |
| `contact.html` | Contact form (HTML form, sent to `contact.php`) |
| `contact.php` | Processes the contact form: saves it in the `messages` table and shows a success or error message |
| `subscribe.php` | Processes the footer newsletter form: saves it in the `subscribers` table and shows a message |
| `rooms.php` | Rooms read from the database, GET filter and sort, details modals |
| `register.php`, `login.php`, `logout.php` | Accounts, login with sessions, logout |
| `booking.php` | Booking form (login required, POST → `bookings` table) |
| `my_bookings.php` | A guest's own bookings, cancel a pending booking |
| `admin_dashboard.php` | Counts, progress bars, latest bookings |
| `admin_bookings.php` | All bookings: filter, confirm, cancel, delete |
| `admin_rooms.php` | Add, edit and delete rooms |
| `admin_users.php` | All registered accounts |
| `admin_messages.php` | Contact messages and newsletter subscribers |
| `css/style.css` | All custom styles |
| `js/script.js` | All JavaScript functions |
| `css/bootstrap.min.css`, `js/bootstrap.bundle.min.js` | Bootstrap 5.3.8 (local copy, so it works without internet) |
| `hotel_db.sql` | Database: tables and sample data |
