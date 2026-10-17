# Negah Brochure Platform

**Event brochure & digital invitation platform** — a hybrid self-service + concierge system
built with **Laravel 11 (PHP 8.2+)** and optimised for deployment on **Microsoft IIS**.

Clients submit an event through a dynamic public form, instantly receive a live preview on a
pre-built responsive template, and after a concierge/telephone review the admin team activates a
permanent branded link: **`negahm.ir/B/{slug}`**.

---

## Table of contents

- [The hybrid model](#the-hybrid-model)
- [Features](#features)
- [Tech stack](#tech-stack)
- [Requirements](#requirements)
- [Quick start (local, SQLite)](#quick-start-local-sqlite)
- [MySQL / MariaDB setup](#mysql--mariadb-setup)
- [Deployment on Microsoft IIS](#deployment-on-microsoft-iis)
- [Admin panel](#admin-panel)
- [Environment variables](#environment-variables)
- [Database schema](#database-schema)
- [Project structure](#project-structure)
- [Workflow reference](#workflow-reference)

---

## The hybrid model

| Step | Who | What happens |
|------|-----|--------------|
| **1 · Submission** | Client | Fills the dynamic form (`/create`): event details, logo, brand colours, custom slug, output type. |
| **2 · Instant preview** | System | The slug is validated live, the submission is stored with status `pending_review`, and a **temporary signed "Preview Ready" link** renders the full brochure from the selected template. |
| **3 · Concierge review** | Admin | The submission appears in `/admin/events`. The team reviews it, calls the client (`contact_phone`), and applies adjustments in the admin edit form. |
| **4 · Activation & slug lock** | Admin | After payment/confirmation the status flips to `active` — the public link becomes permanent and the **slug is locked** (it can never be changed again). |

Output types the client can order:

- 📖 **Digital Brochure** — responsive event page.
- ✉️ **RSVP Form** — brochure with a built-in RSVP form (responses tracked in the admin panel).
- 🎨 **Graphic Invitation Card** — elegant card-style digital invitation.

Pre-built responsive templates (auto-suggested by event type, overridable in the form):

| Template key | Style | Suggested for |
|--------------|-------|---------------|
| `default-concert` | Dark Stage — bold dark theme | Concerts, festivals, theatre |
| `minimal-corporate` | Clean Minimal — airy professional | Seminars, conferences, webinars |
| `vibrant-exhibition` | Vibrant Showcase — colourful energy | Exhibitions, fairs |
| `elegant-invitation` | Elegant Invitation — refined card | Formal invitations, galas |

---

## Features

- **Public submission form** with Alpine.js **live in-page preview** (mirrors colours, logo, layout as you type).
- **Real-time slug availability** (`GET /slug-check`) with automatic slug suggestions (`my-event-2`, …) and a reserved-word blacklist.
- **Temporary signed preview links** (`/preview-ready/{id}` and the brochure itself before activation) that expire automatically.
- **Four server-rendered brochure templates**, fully branded with the client's colours/logo (Tailwind CSS CDN — no build step needed on the server).
- **RSVP collection** with per-event response lists in the admin panel.
- **Admin / concierge dashboard**: stats, searchable + filterable queue, submission review, consultation notes, adjustments, one-click **Approve & activate** (locks the slug), expire / draft / delete actions.
- **IIS-ready**: `web.config` URL Rewrite rules for clean URLs (`/B/{slug}`, `/admin/...`), static-file passthrough, and hard blocking of sensitive source folders.
- MySQL / MariaDB / SQLite support out of the box.

---

## Tech stack

- **Backend:** Laravel 11.x (PHP 8.2+), Eloquent ORM, Form Requests, signed URLs.
- **Frontend:** Blade templates + Tailwind CSS (CDN) + Alpine.js (CDN).
- **Storage:** Laravel `public` disk → `storage/app/public` (symlinked to `public/storage`) for logos.
- **Database:** MySQL / MariaDB (production) or SQLite (local/lightweight).
- **Server:** Microsoft IIS + URL Rewrite Module 2.0 + PHP via FastCGI.

---

## Requirements

- PHP **8.2+** with extensions: `pdo_mysql` (or `pdo_sqlite`), `mbstring`, `openssl`, `fileinfo`, `ctype`, `tokenizer`, `xml`, `curl`, `zip`
- Composer 2
- MySQL 8 / MariaDB 10.6+ (or SQLite)
- For IIS: IIS 10+, **URL Rewrite Module 2.0**, PHP registered as a FastCGI handler

---

## Quick start (local, SQLite)

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite          # (already created by composer on fresh installs)
php artisan migrate --seed              # migrations + demo events
php artisan storage:link                # public/storage -> storage/app/public
php artisan serve                       # http://127.0.0.1:8000
```

- Public form: `http://127.0.0.1:8000/create`
- Admin panel: `http://127.0.0.1:8000/admin/login` (default `admin` / `negah-admin` — **change in `.env`**)

---

## MySQL / MariaDB setup

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=negah
DB_USERNAME=negah_user
DB_PASSWORD=secret
```

```bash
php artisan migrate --seed
```

---

## Deployment on Microsoft IIS

### 1. Install the prerequisites

1. Install **PHP 8.2+ (x64, Non-Thread-Safe / VC17)** for Windows and note the path, e.g. `C:\PHP\php-cgi.exe`.
2. Enable (or install) **CGI** under *Turn Windows features on or off → Web Server → Application Development*.
3. Install the **[URL Rewrite Module 2.0](https://www.iis.net/downloads/microsoft/url-rewrite)**.
4. Register PHP with IIS (or use *PHP Manager for IIS*):

```bat
%windir%\system32\inetsrv\appcmd set config /section:system.webServer/fastCGI /+[fullPath='C:\PHP\php-cgi.exe']

%windir%\system32\inetsrv\appcmd set config /section:system.webServer/handlers ^
 /+[name='PHP_via_FastCGI',path='*.php',verb='*',modules='FastCgiModule',^
 scriptProcessor='C:\PHP\php-cgi.exe',resourceType='Either',requireAccess='Script']
```

Verify `php-cgi.exe` has `extension=pdo_mysql`, `extension=mbstring`, `extension=openssl`,
`extension=fileinfo` enabled in `php.ini`, and set `date.timezone` (e.g. `Asia/Tehran`).

### 2. Deploy the code

```bat
cd C:\inetpub\negah
composer install --no-dev --optimize-autoloader
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan config:cache & php artisan route:cache & php artisan view:cache
```

### 3. IIS site configuration — two options

| Option | Site **Physical Path** | `web.config` used | When to use |
|--------|----------------------|-------------------|-------------|
| **A (recommended)** | `C:\inetpub\negah\public` | `public\web.config` | Standard servers where you control the site's physical path. |
| **B** | `C:\inetpub\negah` (project root) | root `web.config` | Shared hosting / panels that force the project root as the site root. |

Both files are provided and fully commented. They:

- Route **every clean URL** (`/B/{slug}`, `/admin/events`, `/slug-check`, …) to Laravel's front controller (`public/index.php`).
- Serve real static files (CSS/JS/favicon, uploaded logos under `/storage/...`) directly.
- Block access to sensitive folders and files (`.git`, `app`, `config`, `database`, `routes`, `vendor`, `storage` internals, `composer.json`, `.env`, …).

> **Tip:** if you use Option A you may delete the root `web.config`; if you use Option B keep
> `public\web.config` anyway — it is harmless.

### 4. Windows folder permissions

Grant **Modify** (read + write + delete) on these folders to the IIS application-pool identity
(usually `IIS AppPool\<YourAppPool>` or `IUSR`):

```
storage\
bootstrap\cache\
```

Everything else only needs **Read**.

### 5. Storage symlink on Windows

`php artisan storage:link` works on modern Windows (creates a junction). If it fails (e.g. missing
privileges), create it manually from an elevated prompt:

```bat
mklink /J C:\inetpub\negah\public\storage C:\inetpub\negah\storage\app\public
```

### 6. PHP session / temp directories

Ensure `upload_tmp_dir` and `session.save_path` in `php.ini` point to writable folders
(e.g. `C:\Windows\Temp` or `C:\PHP\tmp`).

### 7. Production checklist

- [ ] `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://negahm.ir`
- [ ] `NEGAH_ADMIN_PASSWORD` changed (or set to a bcrypt hash `$2y$...`)
- [ ] HTTPS binding + redirect (IIS URL Rewrite or the "Require SSL" feature)
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- [ ] Database backups scheduled (SQL Server Maintenance Plans / Windows Task Scheduler + `mysqldump`)

---

## Admin panel

1. Go to `/admin/login` and sign in (credentials in `.env`, see below).
2. **Dashboard** (`/admin`) — pipeline stats (pending / active / draft / expired), RSVP totals, latest submissions.
3. **Submissions** (`/admin/events`) — filter by status, free-text search, RSVP counts.
4. **Review page** — full details, uploaded logo, brand colours, consultation notes, RSVP table,
   preview/live links, and the **status workflow**:
   - **Approve & activate** → status `active`, `activated_at` timestamp set, **slug locked permanently**.
   - Move back to pending, mark expired, or set as draft.
   - Active events cannot be deleted (expire them instead).
5. **Make adjustments** — edit everything after the phone call. On active/expired events the slug
   field is **read-only** (secure slug locking).

---

## Environment variables

| Variable | Default | Purpose |
|----------|---------|---------|
| `APP_NAME` | `Negah Brochure Platform` | Application name. |
| `APP_URL` | `http://localhost` | Canonical base URL (use `https://negahm.ir` in production). |
| `DB_CONNECTION` | `sqlite` | `sqlite`, `mysql` or `mariadb`. |
| `NEGAH_BASE_URL` | `negahm.ir/B/` | Fixed branded prefix shown to users: `{prefix}{slug}`. |
| `NEGAH_PREVIEW_TTL_HOURS` | `48` | Lifetime of temporary signed preview links. |
| `NEGAH_ADMIN_USERNAME` | `admin` | Admin panel username. |
| `NEGAH_ADMIN_PASSWORD` | `negah-admin` | Admin password (plaintext or bcrypt `$2y$...` hash). |

---

## Database schema

### `events`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `user_id` | FK, nullable | Reserved for future customer accounts. |
| `title` | string | |
| `slug` | string, **unique** | Public link `negahm.ir/B/{slug}`. |
| `event_type` | string | `concert`, `seminar`, `exhibition`, … |
| `event_date` | datetime | |
| `venue` | text | |
| `description` | longtext | |
| `logo_path` | string, nullable | Path on the `public` disk. |
| `brand_color` | string, default `#000000` | Primary HEX colour. |
| `secondary_color` | string, nullable | Secondary HEX colour. |
| `template_name` | string, default `default-concert` | Pre-built template key. |
| `output_type` | string, default `brochure` | `brochure` \| `rsvp` \| `invitation`. |
| `status` | enum | `draft` \| `pending_review` (default) \| `active` \| `expired`. |
| `contact_phone` | string | For the consultation call. |
| `admin_notes` | text, nullable | Internal, never public. |
| `activated_at` | timestamp, nullable | Set at activation. |
| `created_at` / `updated_at` | timestamps | |

### `rsvps`

`id`, `event_id` (FK, cascade delete), `name`, `email`, `phone`, `guests`, `message`, timestamps.

---

## Project structure

```
├── web.config                         ← IIS URL Rewrite (Option B: project root as site root)
├── public/
│   └── web.config                     ← IIS URL Rewrite (Option A: public as site root)
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── EventController.php          Public form, slug check, store, preview, brochure, RSVP
│   │   │   ├── HomeController.php           Landing page
│   │   │   └── Admin/
│   │   │       ├── AuthController.php       Login / logout
│   │   │       ├── DashboardController.php  Stats overview
│   │   │       └── EventController.php      Review / edit / activate / expire
│   │   ├── Middleware/AdminAuth.php         Session guard for /admin
│   │   └── Requests/StoreEventRequest.php   Public submission validation
│   ├── Models/
│   │   ├── Event.php                        Statuses, templates, slug rules, URL helpers
│   │   └── Rsvp.php
│   └── ...
├── config/negah.php                 Base URL prefix, slug limits, preview TTL, admin creds
├── database/
│   ├── migrations/…create_events_table.php
│   ├── migrations/…create_rsvps_table.php
│   └── seeders/EventSeeder.php      Demo submissions (all statuses & templates)
└── resources/views/
    ├── home.blade.php               Landing page
    ├── events/
    │   ├── create.blade.php         Submission form + Alpine.js live preview
    │   ├── preview-ready.blade.php  "Preview Ready" screen + temporary link
    │   ├── show.blade.php           Dynamic brochure shell (status banners, RSVP include)
    │   ├── templates/               default-concert, minimal-corporate,
    │   │                            vibrant-exhibition, elegant-invitation
    │   └── partials/rsvp-form.blade.php
    ├── admin/                       login, dashboard, events index/show/edit
    └── layouts/                     app.blade.php (public), admin.blade.php
```

## Workflow reference

```
Client                          System                            Admin
──────                          ──────                            ─────
/create form  ──────────────►  validate + store (pending_review)
              ◄──────────────  "Preview Ready" + temp signed link
        │                                               ──────►  /admin/events (queue)
        │                                               ◄──────  phone consultation
        │                                               ──────►  adjustments saved
        │                                               ──────►  Approve & activate
        │                             slug locked, status=active
        ▼
negahm.ir/B/{slug}  ◄────── permanent public brochure / RSVP / invitation
```

---

© 2026 Negah. Built with Laravel, ready for IIS.
