# Barangay Community Health Check Details System

A production-ready PHP + PostgreSQL web application with secure authentication, role-based access, patient/health record CRUD, dashboard analytics, and printable reports.

## Tech Stack

- HTML5
- CSS3 (responsive glassmorphism + nature-inspired theme)
- Vanilla JavaScript
- PHP 8+
- PostgreSQL
- Chart.js

## Project Structure

- `css/` stylesheets
- `js/` frontend scripts
- `php/` backend action handlers
- `includes/` shared PHP config/auth/layout utilities
- `assets/` static assets
- `database/` SQL schema

## Setup (Local)

1. Copy `env.example` values into your environment (Apache config, shell env, or hosting vars).
2. Create PostgreSQL DB and import:
   - `database/schema.sql`
3. Serve the project with Apache/PHP.
4. Open `http://localhost/<project-folder>/`

Default seeded users:
- Admin: `admin@barangay.local` / `password123`
- Health Worker: `worker@barangay.local` / `password123`

## Security Notes

- Passwords use `password_hash()` / `password_verify()`
- SQL queries use prepared statements
- CSRF token verification on form submissions
- Session-based authentication with regeneration on login
- Basic input sanitization

## GitHub Upload

1. Initialize git:
   - `git init`
2. Commit:
   - `git add .`
   - `git commit -m "Initial Barangay Health System"`
3. Create a repository on GitHub.
4. Push:
   - `git remote add origin <your_repo_url>`
   - `git branch -M main`
   - `git push -u origin main`

## Render Deployment (PostgreSQL)

This project includes:
- `Dockerfile`
- `render.yaml`

Steps:
1. Push this codebase to GitHub.
2. In Render, create a **Web Service** from the repo.
3. Render auto-detects Docker and builds the service.
4. Set environment variables in Render dashboard:
   - `APP_ENV=production`
   - `APP_NAME=Barangay Community Health Check Details System`
   - `DATABASE_URL` (recommended; full postgres URL)
   - Optional fallback: `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD`
5. Import `database/schema.sql` into your Render Postgres DB.

### Troubleshooting “Connection refused”
- If you see “Connection refused” on Render, it usually means the service is trying to reach PostgreSQL at localhost. Set `DATABASE_URL` or `PGHOST` correctly.
- The app now shows a friendly error screen with tips if the DB is unreachable.

## Feature Highlights

- Secure login + logout + remember me + forgot password UI
- Role support: `Admin`, `Health Worker`
- Patient management with duplicate prevention
- Health record management with patient history
- Dashboard metrics and illness chart
- Fast patient table search + pagination
- Printable reports (patient list + summaries)
- Responsive modern UI with animations, toasts, loader, sticky top bar, collapsible sidebar
