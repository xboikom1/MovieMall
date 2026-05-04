# MovieMall

A Laravel-based e-commerce platform for cinema tickets and movie merchandise.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (default) or MySQL/PostgreSQL

## Setup

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
npm run build
php artisan serve
```

## Admin Access

| Field    | Value                       |
|----------|-----------------------------|
| URL      | `/xj7qr2/login`             |
| Email    | `admin@moviemall.com`       |
| Password | `MM$ecure#2026!`            |

> Re-seed the database (`php artisan migrate:fresh --seed`) to apply the default credentials if you have an existing database.

## Features

- Movie listings and ticket browsing
- Souvenir/merchandise shop
- Shopping cart and checkout
- User accounts and address book
- Admin dashboard — product and image management
