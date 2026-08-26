# Clients Manager

Simple customer & project-offer manager. Laravel 13 + MySQL + Blade + Tailwind CSS 4 + jQuery AJAX.

Workflow: **Customer → Projects/Offers → Status** (`new` / `confirmed` / `finished` / `cancelled`).

## Setup

1. Create the database and a user (adjust to taste):

```sql
CREATE DATABASE clients_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'clients'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON clients_manager.* TO 'clients'@'localhost';
FLUSH PRIVILEGES;
```

2. Set the credentials in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clients_manager
DB_USERNAME=clients
DB_PASSWORD=secret
```

3. Install, migrate, seed, build, serve:

```sh
composer install
npm install
php artisan migrate --seed
npm run build          # or: npm run dev
php artisan serve
```

Demo logins: `admin@example.com` / `password` and `sara@example.com` / `password` —
each account sees only its own data.

4. Run the test suite (requires the `pdo_sqlite` PHP extension, or point
   `phpunit.xml` at a MySQL test database):

```sh
php artisan test
```

## Structure

| Layer | Files |
| --- | --- |
| Models | `app/Models/Customer.php`, `app/Models/Project.php` |
| Status enum | `app/Enums/ProjectStatus.php` |
| Controllers | `app/Http/Controllers/{Dashboard,Customer,Project,Locale}Controller.php`, `Auth/` |
| Validation | `app/Http/Requests/` |
| Migrations | `database/migrations/2026_08_26_*` |
| Seeders / factories | `database/seeders/DatabaseSeeder.php`, `database/factories/` |
| Views | `resources/views/{dashboard,customers,projects,profile,auth}` |
| Components | `resources/views/components/` (layouts, modal, badge, field, buttons…) |
| jQuery AJAX | `resources/js/{ui,customers,projects}.js` |
| Translations | `lang/{en,fr,ar}/app.php` (Arabic switches the page to RTL) |

## Accounts & data ownership

Every customer and project stores a `user_id`. A newly registered account starts
empty and only ever sees its own records:

* Lists, search, filters, the customer picker and the dashboard counters are scoped
  with the `ownedBy()` model scope.
* `CustomerPolicy` / `ProjectPolicy` guard update, status change and delete (403 otherwise).
* `customer_id` on the project form is validated against the signed-in user's customers.
* Deleting a user cascades to their customers and projects.

## List behaviour

* Each project row is tinted by its status (blue = new, green = confirmed, grey = finished, red = cancelled).
* Finished and cancelled projects are **hidden by default** — only live work (new and confirmed) is listed.
  Tick *Show finished & cancelled*, or pick an explicit status filter, to see them.
  Changing a row's status to finished/cancelled removes it from the default list right away.
* Row actions are icon buttons (view projects / edit / delete) with tooltips and `aria-label`s.

## Profile

`/profile` — update name and email, and change the password (current password required).
Reachable from the person icon in the top bar.

## AJAX endpoints

All return JSON and require an authenticated session; CSRF is sent through
`$.ajaxSetup` from the `csrf-token` meta tag.

| Method | URI | Purpose |
| --- | --- | --- |
| GET | `/customers?search=` | Re-render the customers table |
| POST | `/customers` | Create customer |
| PUT | `/customers/{customer}` | Update customer |
| DELETE | `/customers/{customer}` | Delete customer (cascades to projects) |
| GET | `/customers/options?search=` | Customer picker data |
| GET | `/projects?search=&status=&customer=&show_archived=` | Re-render + filter the projects table |
| POST | `/projects` | Create project |
| PUT | `/projects/{project}` | Update project |
| PATCH | `/projects/{project}/status` | Change status inline |
| DELETE | `/projects/{project}` | Delete project |
