# Mart24h

**Stock Management System**

Mart24h helps retail and wholesale teams keep inventory, supplier, and purchasing data consistent and actionable. The platform streamlines the full lifecycle of stock management — from cataloging brands and SKUs to reconciling supplier invoices and generating printable PDFs for every transaction.

## Features

- Brand & SKU cataloging
- Inventory tracking across products
- Supplier and purchasing management
- Supplier invoice reconciliation
- Printable PDF generation for transactions

## Tech Stack

- **Language:** PHP 8
- **Framework:** Laravel
- **Database:** MySQL

## Getting Started

When you clone this project from GitHub, it won't include the `.env` file or the `vendor` directory (these are excluded from version control), so you'll need to set them up yourself.

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Install Composer Dependencies

```bash
composer update
```

### 3. Create the `.env` File

Copy the example environment file and rename it to `.env`:

```bash
cp .env.example .env
```

### 4. Generate an Application Key

Laravel requires an application key:

```bash
php artisan key:generate
```

### 5. Set Up the Database

Create a new database for the project, then update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run Migrations

Set up your database tables:

```bash
php artisan migrate
```

### 7. Start the Development Server

```bash
php artisan serve
```

## Troubleshooting

### Fixing a `storage:link` Error

If you run into a `storage:link` error, delete the `public/storage` directory first, then re-run the link command.

**Windows (Command Prompt):**

```bash
cd /path/to/laravel/project
rmdir /s /q "C:\path\to\laravel\storage\logs"
```

**Re-create the storage link:**

```bash
php artisan storage:link
```

---

Following these steps should get the project up and running. If you run into any other issues, feel free to open an issue in this repository.

## Old Version

> _Add a link or note here about the previous/legacy version of this project, if applicable._

## License

Specify your license here (e.g., MIT). If none has been chosen yet, consider adding one so others know how they can use your code.
