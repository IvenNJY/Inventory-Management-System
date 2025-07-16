# Asset Management System (Laravel)

This is a Laravel-based Asset Management System for tracking assets, warranties, deployments, lifespan, and user/admin management. It is designed to run on XAMPP (Windows) with PHP, MySQL, and Node.js for frontend asset building.

## Features
- Asset CRUD (Create, Read, Update, Delete)
- Warranty and Lifespan tracking
- Asset deployment and history
- User and admin authentication/registration
- Toast notifications for status/errors
- Responsive UI with Tailwind CSS
- Filtering, sorting, and pagination for tables
- Modal dialogs for confirmation and asset assignment

## Prerequisites
- XAMPP (Apache, MySQL, PHP >= 8.1)
- Composer (PHP dependency manager)
- Node.js & npm (for frontend assets)

## Setup Instructions

### 1. Clone the Repository
```
git clone <your-repo-url>
```

### 2. Move Project to XAMPP Directory
Place the project folder (e.g., `RAD2Front`) inside your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\RAD2Front
```

### 3. Install PHP Dependencies
Open a terminal in the project root and run:
```
composer install
```

### 4. Install Node.js Dependencies & Build Frontend
```
npm install
npm run build
```

### 5. Configure Environment
Copy `.env.example` to `.env` and update database credentials:
```
cp .env.example .env
```
Edit `.env`:
```
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 6. Generate Application Key
```
php artisan key:generate
```

### 7. Run Migrations & Seeders
```
php artisan migrate --seed
```

### 8. Start XAMPP Services
- Start Apache and MySQL from XAMPP Control Panel.

### 9. Access the Application
Open your browser and go to:
```
http://localhost/RAD2Front/public
```

## Usage
- Register as a user or login as admin.
- Manage assets, warranties, deployments, and users via the dashboard.
- Use filters and modals for advanced management.

## Build Frontend Assets (Whenever UI changes)
```
npm run build
```

## Troubleshooting
- If you see blank pages, check `.env` settings and run `php artisan config:cache`.
- For asset errors, run `npm run build` again.
- For database errors, ensure MySQL is running and credentials are correct.
