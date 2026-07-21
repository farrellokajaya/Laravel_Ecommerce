# Laravel Ecommerce

A full-stack ecommerce web application built with Laravel, Blade, and MariaDB. The application provides product discovery, shopping-cart management, Stripe test payments, order processing, PDF invoices, and role-based administration.

[![Live Demo](https://img.shields.io/badge/Live_Demo-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel-ecommerce-cqfh.onrender.com/)
[![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)

## Overview

This project demonstrates a complete ecommerce workflow for customers and administrators.

Customers can browse products, manage a cart, complete a Stripe test payment, view their orders, and download invoices. Administrators can manage categories, products, stock, and order statuses through a protected dashboard.

## Key Features

### Storefront

- Responsive ecommerce homepage
- Latest-product showcase
- Product catalog
- Search and category filtering
- Product detail pages
- Product quantity selection
- Stock availability validation

### Cart and Checkout

- Add products to cart
- Update quantities through the cart flow
- Remove cart items
- Calculate order totals
- Collect receiver information
- Process Stripe test payments
- Create orders only after successful payment
- Reduce stock based on purchased quantities
- Clear the cart after successful payment

### Orders and Invoices

- Customer order history
- Payment and delivery statuses
- Unique invoice numbers
- Customer PDF invoice download
- Administrator PDF invoice download
- Order ownership validation

### Administration

- Protected administrator dashboard
- Category CRUD
- Product CRUD
- Product-image upload
- Product search and pagination
- Order search and filtering
- Order-status management
- Deletion protection for related records

### Security

- Laravel Breeze authentication
- User and administrator role separation
- Protected checkout and order routes
- Authorization checks for invoices
- Server-side validation
- Product stock validation
- Environment-based credentials
- Production debug protection

## Tech Stack

### Backend

- PHP 8.2+
- Laravel 12
- Laravel Breeze
- Stripe PHP SDK
- DomPDF

### Frontend

- Blade
- Tailwind CSS
- Alpine.js
- Vite
- Custom responsive CSS

### Database and Infrastructure

- MySQL / MariaDB
- Docker
- Apache
- Render
- Git and GitHub

## Application Workflow

```text
Browse product
      ↓
Add to cart
      ↓
Enter receiver information
      ↓
Complete Stripe test payment
      ↓
Create order and reduce stock
      ↓
View order and download invoice
```

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── Storefront/
│   ├── Middleware/
│   └── Requests/
├── Models/
└── Services/

resources/views/
├── admin/
├── auth/
├── invoices/
├── partials/
└── storefront views

database/
├── factories/
├── migrations/
└── seeders/

public/
├── admin/
├── front_end/
└── products/
```

## Getting Started

### Prerequisites

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL or MariaDB
- Git

### 1. Clone the Repository

```bash
git clone https://github.com/farrellokajaya/Laravel_Ecommerce.git
cd Laravel_Ecommerce
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure the Environment

Copy `.env.example`:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Configure the database and Stripe test credentials:

```env
APP_NAME="Laravel Ecommerce"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

Never commit the `.env` file or real credentials.

### 4. Prepare the Application

```bash
php artisan key:generate
php artisan migrate
npm run build
```

If public storage is required:

```bash
php artisan storage:link
```

### 5. Start Development

```bash
composer run dev
```

Alternatively, run the services separately:

```bash
php artisan serve
npm run dev
```

Open:

```text
http://localhost:8000
```

## Creating an Administrator

Register a regular account, then open Laravel Tinker:

```bash
php artisan tinker
```

Update the intended account:

```php
$user = App\Models\User::where('email', 'your-email@example.com')->first();
$user->user_type = 'admin';
$user->email_verified_at = now();
$user->save();
```

Do not expose administrator credentials in public documentation.

## Stripe Test Mode

The project is intended to use Stripe test mode for portfolio demonstrations.

Example successful test card:

```text
Card number: 4242 4242 4242 4242
Expiry: any future date
CVC: any three digits
```

Never use live Stripe keys unless the application has been prepared for real transactions.

## Quality Checks

Run the automated test suite:

```bash
composer test
```

Check registered routes:

```bash
php artisan route:list
```

Check migration status:

```bash
php artisan migrate:status
```

Clear cached configuration:

```bash
php artisan optimize:clear
```

Create production assets:

```bash
npm run build
```

## Deployment

The application is deployed using a PHP 8.3 Apache Docker image and an external MariaDB database.

Live application:

[https://laravel-ecommerce-cqfh.onrender.com/](https://laravel-ecommerce-cqfh.onrender.com/)

The deployment uses Stripe test mode and is intended for portfolio demonstration only. Free hosting may require additional time to start after a period of inactivity.

## Production Checklist

- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure the correct production `APP_URL`
- Store credentials in the hosting dashboard
- Use a production database connection
- Use Stripe test keys for the portfolio demo
- Run migrations with `php artisan migrate --force`
- Cache optimized Laravel configuration
- Confirm `.env`, logs, database dumps, and credentials are not committed

## Project Status

The main customer and administrator ecommerce workflows are complete. Future improvements may include automated feature tests, external media storage, inventory alerts, and email notifications.

## Author

**Farrel Lokajaya**

- [Portofolio](https://farrel-portofolio-liard.vercel.app/)
- [LinkedIn](https://www.linkedin.com/in/farrel-lokajaya-a25944203/)
- [GitHub](https://github.com/farrellokajaya)
