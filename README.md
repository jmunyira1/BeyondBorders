# Beyond Borders Adventures

The Beyond Borders Adventures site, built on CodeIgniter 4.7.4 with htmx and
CodeIgniter Shield.

The original static design is preserved untouched in [`_design/`](_design/) for
reference. `public/assets/css/theme.css` is that design's stylesheet, copied
across unchanged — all additions live in `public/assets/css/app.css` so the
design system stays clean.

---

## Running it

The site is served by WAMP at:

- **Public site** — <http://localhost/BeyondBordersSite/public/>
- **Admin** — <http://localhost/BeyondBordersSite/public/admin>

### First sign-in

| | |
|---|---|
| Email | `admin@beyondbordersadventures.co.ke` |
| Password | `Karibu#Mara2026` |

**Change this password** on first login via *Admin → My account*. Public
registration is disabled — new admins are created from the CLI:

```bash
php spark shield:user create
php spark shield:user addgroup -n <username> -g admin
```

### Database

MariaDB 11.4.9 on **port 3306** (WAMP's default instance — the same server your
other projects use), database `beyond_borders`, user `root`, no password.

> Note: this machine also runs MySQL 8.4.7 on port 3308. The app is pointed at
> 3306. `.env` uses `127.0.0.1` rather than `localhost` so the port is
> unambiguous.

Rebuild from scratch:

```bash
php spark migrate:refresh
php spark db:seed DatabaseSeeder
```

The seeders load every piece of content from the original design — 12 packages,
5 categories, 10 destinations, 6 tour types, 6 blog posts, 12 gallery images,
5 testimonials and 7 FAQs — plus the admin account.

---

## What the admin controls

Everything that was hard-coded in the static HTML:

| Screen | Controls |
|---|---|
| **Enquiries** | Inbox for all three forms. Filter by type/status, read detail, set status, keep internal notes, reply by email or WhatsApp in one click. |
| **Packages** | Full CRUD with image upload, repeatable inclusion/exclusion rows, featured flag, publish toggle. |
| **Blog posts** | Full CRUD, scheduled publishing, auto read-time estimate. |
| **Gallery** | Upload, caption, reorder, show/hide. |
| **Testimonials / FAQs** | Inline add, edit, reorder, show/hide. |
| **Taxonomies** | Categories, destinations, tour types, post categories — the three that drive the public filter, with a usage count each. |
| **Settings** | Phone, email, address, socials, hero copy, vision/mission, legal pages, and the whole WhatsApp widget. |

Images can be uploaded (stored in `public/uploads/`, auto-resized to 1600px) or
given as a URL. The seeded rows use picsum placeholders until real photos are
uploaded — both work side by side via the `media_url()` helper.

---

## The package filter

`/packages` filters on **category, destination, tour type, price band, duration
band and keyword**, plus sorting — modelled on the Stejos Tours filter.

- htmx swaps only the results grid (`#packages-results`); no page reload.
- The server sends `HX-Push-Url` so the address bar shows a clean, shareable
  `/packages?category=safari&duration=day-trip`, not the internal endpoint.
- Loading that URL directly renders the full page, so links stay crawlable.
- Active filters appear as chips; each removes just itself.
- **Works without JavaScript** — the form is a plain GET to `/packages` and the
  Filter button submits it normally.

Filter logic lives in `PackageModel::filtered()`; the bands are declared as
`PRICE_RANGES` / `DURATION_RANGES` constants so the dropdowns and the query can
never drift apart.

---

## The WhatsApp widget

A floating button opens a branded chat card: greeting bubble, message box,
Enter-to-send. On submit it opens `wa.me` with the configured prefill plus
whatever the visitor typed. Number, name, role, greeting and prefill are all
editable in *Admin → Settings*, and the whole widget can be switched off.

Any element with `data-wa-open="some message"` opens the card pre-filled — used
by the "Chat with us" buttons on the package and contact pages.

---

## Notable implementation details

- **`url_to()`, not `route_to()`.** The app lives in a subdirectory; `route_to()`
  returns a root-relative path that would resolve to `http://localhost/packages`.
- **The `{id}` placeholder needs its own rule.** Models using
  `is_unique[table.slug,id,{id}]` also declare `'id' => 'permit_empty|…'`,
  and `AdminController::saveRow()` merges the id into the payload — CodeIgniter
  resolves the placeholder from the data array, not the primary-key argument.
  Without both, a row cannot be saved without changing its own slug, and the
  failure is silent.
- **CSRF is enforced globally** (`csrf` filter in `Config\Filters::$globals`).
  `security.regenerate` is off so one token stays valid for the session, which
  is what lets htmx reuse it across swaps.
- **Forms degrade gracefully.** Every htmx form has a real `action`/`method` and
  works as a normal POST if JavaScript fails.
- **Spam**: every public form carries an off-screen honeypot; a filled one gets
  the success screen but is never stored.

---

## Layout

```
app/
  Config/Site.php            defaults for every editable setting
  Controllers/               public site
  Controllers/Admin/         admin, all extending AdminController
  Database/Migrations/       schema
  Database/Seeds/            content ported from the static design
  Helpers/site_helper.php    media_url(), money(), site(), whatsapp_link()…
  Models/                    PackageModel holds the filter query
  Views/
    layouts/public.php       public shell
    admin/layout.php         admin shell
    partials/                nav, footer, WhatsApp card, package card
    packages/_results.php    the htmx-swapped results fragment
public/
  assets/css/theme.css       the original design — unmodified
  assets/css/app.css         additions
  assets/js/htmx.min.js      vendored, no CDN dependency
  uploads/                   admin image uploads
_design/                     the original static HTML, for reference
```

## Still to do

- Point `notifyEmail` at a real inbox and configure SMTP in `app/Config/Email.php`
  if you want email alerts on new enquiries (`notifyOnEnquiry` in Settings).
- Replace the picsum placeholder images with real photography.
- Paste real terms and privacy copy into *Admin → Settings*.
