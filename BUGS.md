# Known Bugs & Issues

## Critical

### 1. Confirmation page JS syntax error
**File:** `laravel/resources/views/confirmation.blade.php:117–129`

The `const server = {` object is never properly closed. The `};` at line 129 terminates the `items_json` value but doesn't close the outer object, leaving the entire `confirmationInit()` function unparseable. The order items section is broken for every completed order.

---

### 2. Ticket insert fails when `schedule_slot_id` is null
**File:** `laravel/app/Http/Controllers/CheckoutController.php`

`tickets.schedule_slot_id` is a NOT NULL foreign key. CartController can store ticket items with only `date`/`time` strings and no `schedule_slot_id`. If such an item reaches checkout, the DB insert throws a constraint violation and the entire transaction silently rolls back.

---

## High

### 3. Race condition — souvenir overselling
**File:** `laravel/app/Http/Controllers/CheckoutController.php`

Stock is validated before the transaction begins. Two concurrent checkouts can both pass the `quantity >= N` check and both decrement, driving quantity negative. The decrement inside the transaction needs to assert affected rows: `->where('quantity', '>=', $qty)->decrement(...)` with a rowcount check.

### 4. Race condition — seat double-booking
**File:** `laravel/app/Http/Controllers/CheckoutController.php`

The seat availability check runs outside the transaction. Two simultaneous requests can both pass the check and both insert tickets for the same seat. The check must move inside the transaction, and a unique constraint on `(seat_id, schedule_slot_id)` in the `tickets` table would enforce it at the DB level.

### 5. Inner joins hide products with missing metadata
**Files:** `laravel/app/Http/Controllers/MovieController.php`, `laravel/app/Http/Controllers/SouvenirController.php`

Detail page queries use `JOIN` (not `LEFT JOIN`) on directors, studios, and languages. Any movie created via the admin panel without those fields assigned returns no rows and 404s, even though the record exists.

### 6. Guest `reference_id` not validated on checkout
**File:** `laravel/app/Http/Controllers/CheckoutController.php`

For guest checkout, `type` and `reference_id` come from the client. If a non-existent `reference_id` is passed, `value('price')` returns null and defaults to `9.99`. No check exists that the referenced product actually exists before the order is written.

---

## Medium

### 7. Avatar replacement leaks old files
**File:** `laravel/app/Http/Controllers/ProfileController.php`

Avatars are stored as `{user_id}.{ext}`. Uploading a `.jpg` after a `.png` writes a new file without deleting the old one. Old avatars accumulate in storage indefinitely.

### 8. Guest cart lost on login
**Files:** `laravel/app/Http/Controllers/CartController.php`, auth flow

When a guest logs in, their localStorage cart is not synced to the DB. The `/cart/sync` endpoint exists but nothing calls it after authentication. Users who add items as guests and then log in lose their cart.

### 9. No rate limiting on admin login
**File:** `laravel/routes/web.php`

The admin login route has no throttle middleware. Standard auth routes have `throttle:auth` but the admin group does not. The obfuscated URL is not a substitute for rate limiting.

---

## Low

### 10. "Confirmation email sent" message is false
**File:** `laravel/resources/views/confirmation.blade.php:44`

The page always displays *"A confirmation email has been sent to…"* but no email is ever sent.

### 11. Slug lookup loads entire table into PHP
**Files:** `laravel/app/Http/Controllers/MovieController.php`, `laravel/app/Http/Controllers/SouvenirController.php`

Every product detail page fetches all movies (or all souvenirs) into a PHP collection and filters by slug in PHP. Works fine at current scale, will degrade linearly as the catalog grows. Fix: add an indexed `slug` column and filter in SQL.

### 12. PostgreSQL-specific raw queries in CartController
**File:** `laravel/app/Http/Controllers/CartController.php`

`whereRaw("options::text = '[]'")` uses PostgreSQL cast syntax. Locks the project to Postgres and will break silently on any other driver.

### 13. `email_verified_at` column is unused
**File:** `laravel/database/migrations/0001_01_01_000000_create_users_table.php`

The column exists in the schema and is cast in the User model but is never set or checked anywhere. Should be dropped or implemented.
