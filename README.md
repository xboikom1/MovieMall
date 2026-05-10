# MovieMall

A full-stack e-commerce platform for movie tickets and souvenirs, built with **Laravel 13** and **Tailwind CSS**. MovieMall enables users to browse cinema schedules, select specific seats via an interactive map, and purchase movie-related merchandise.

## Features

### Client Section
* **Dynamic Catalog**: Browse movies and souvenirs with advanced filtering (genre, year, rating, price) and sorting (most popular, highest rated, newest).
* **Interactive Cinema Booking**: Real-time seat selection for movie screenings using a live seat map.
* **Full-Text Search**: ILIKE-based parallel search across movie titles, directors, souvenir names, and categories.
* **Hybrid Shopping Cart**: 
    * **Guests**: Items stored in a PHP session.
    * **Authenticated Users**: Items persisted in the database (`cart_items` table).
    * **Cart Portability**: Automatic merging of guest session items into the user's database cart upon login.
* **Secure Checkout**: Server-side recalculation of all totals (subtotal, shipping, tax) to prevent price-manipulation attacks.
* **User Profiles**: Manage saved delivery addresses and view historical order data.

### Admin Panel (`/xj7qr2/login`)
* **Obfuscated Access**: Admin area is mounted on a non-conventional prefix to prevent discovery.
* **Product Management**: Create, edit, and delete movies or souvenirs, including multi-image uploads and setting primary images.
* **Schedule Management**: Create screening slots with automated end-time calculation based on movie duration and a 15-minute cleaning buffer.
* **Order Oversight**: Complete overview of all customer orders with status tracking and search functionality.

## Tech Stack

* **Backend**: Laravel 13, PHP 8.3
* **Database**: PostgreSQL 16
* **Frontend**: Alpine.js (interactive components), Tailwind CSS v3, Vite 6
* **Auth**: Laravel Breeze (customized business logic)
* **Date/Time**: Carbon

## Implementation Details

* **Concurrency Protection**: Uses **pessimistic locking** for souvenir stock and database-level **unique constraints** on `tickets(seat_id, schedule_slot_id)` to prevent double-booking during simultaneous checkouts.
* **Data Integrity**: Souvenir prices are copied to the `order_souvenirs` pivot table at purchase to ensure historical orders reflect the price paid, even if product prices change later.
* **Architecture**:
    * **Query Builder**: To keep the codebase lean and performant, the Laravel Query Builder is used for most entities. Eloquent models are reserved for `User`, `CartItem`, and `DeliveryAddress`.
    * **Security**: Admin login is rate-limited to 5 attempts per minute.

## Getting Started

### Requirements
* PHP 8.3+
* Composer
* Node.js 22+
* PostgreSQL 16

### Setup
1.  **Clone and Install**:
    ```bash
    cd laravel
    composer install
    npm install
    ```
2.  **Environment**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
3.  **Database & Build**:
    Configure your PostgreSQL credentials in `.env`, then:
    ```bash
    php artisan migrate:fresh --seed
    npm run build
    php artisan serve
    ```

### Default Credentials
| Role | Email | Password |
| :--- | :--- | :--- |
| **User** | test@example.com | password |
| **Admin** | admin@moviemall.com | MM$ecure#2026! |

*The admin panel is available at `/xj7qr2/login`.*

## Project Structure
* `app/Http/Controllers/`: specialized controllers for `Cart`, `Checkout`, `AdminDashboard`, and `Search`.
* `database/migrations/`: Includes constraints for seat uniqueness and schema for the hybrid cart system.
* `resources/views/`: Blade templates for the client catalog and admin dashboard.
