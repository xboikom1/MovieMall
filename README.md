# MovieMall

A fictional movie ticket and souvenir store built with Laravel and Tailwind CSS.

## Features

- Browse movies across 10 fictional franchises
- Interactive seat-selection cinema booking
- Souvenir shop with category and franchise filters
- Session-based cart for guests, database-backed cart for authenticated users (synced automatically on login)
- Checkout with courier / locker / pickup delivery options
- Order history for registered users
- Admin panel — manage movies, souvenirs, schedules, and orders

## Tech Stack

- **Backend** — Laravel 11, PostgreSQL
- **Frontend** — Alpine.js, Tailwind CSS, Vite
- **Auth** — Laravel Breeze (custom UI)

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- PostgreSQL

### Setup

```bash
cd laravel
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`, then:

```bash
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

The seeder creates two accounts:

| Role  | Email                  | Password        |
|-------|------------------------|-----------------|
| User  | test@example.com       | password        |
| Admin | admin@moviemall.com    | MM$ecure#2026!  |

The admin panel is available at `/xj7qr2/dashboard`.

## Project Structure

```
laravel/
  app/Http/Controllers/
  app/Http/Middleware/
  database/migrations/
  database/seeders/
  resources/views/
```
