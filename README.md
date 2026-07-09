# Laravel Ecommerce

A portfolio-ready ecommerce web application built with Laravel, Blade, MySQL/MariaDB, Stripe test payment, product management, cart, order history, and PDF invoice generation.

This project is designed as a full-stack Laravel ecommerce case study with separate storefront and admin workflows.

---

## Features

### Storefront

- Public homepage with latest available products
- Product listing with search and category filter
- Product detail page
- Add to cart with quantity support
- Cart management
- Checkout form with receiver name, address, and phone number
- Stripe test payment flow
- Payment success page
- User order history
- User invoice download as PDF

### Admin Panel

- Admin dashboard summary
- Category management
  - Create category
  - View categories
  - Update category
  - Delete category only when it is not used by products
- Product management
  - Create product
  - Upload product image
  - View products with pagination
  - Search products
  - Update product
  - Delete product only when it is not linked to an order
- Order management
  - View orders
  - Search and filter orders
  - Update order status
  - Download admin invoice PDF

### Security and Validation

- Laravel authentication using Breeze
- User and admin role separation with `user_type`
- Authenticated checkout route
- Invoice access restricted to the order owner
- Product image validation by type and size
- Product stock validation before checkout
- Order creation only after successful Stripe payment
- Cart is cleared after successful payment
- Stock is reduced after successful payment

---

## Tech Stack

- Laravel 12
- PHP 8.2+
- Blade template engine
- MySQL / MariaDB
- Laravel Breeze authentication
- Stripe PHP SDK
- Stripe test mode
- DomPDF for invoice PDF generation
- Vite
- Tailwind CSS / custom CSS
- Docker Compose / Laravel Sail runtime

---

## Main Packages

```json
{
  "php": "^8.2",
  "laravel/framework": "^12.0",
  "barryvdh/laravel-dompdf": "^3.1",
  "stripe/stripe-php": "^20.2",
  "laravel/breeze": "^2.4",
  "laravel/sail": "^1.62"
}
```

---

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── CategoryController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── OrderController.php
│   │   │   └── ProductController.php
│   │   └── Storefront/
│   │       ├── CartController.php
│   │       ├── CheckoutController.php
│   │       ├── DashboardController.php
│   │       ├── HomeController.php
│   │       ├── InvoiceController.php
│   │       ├── OrderController.php
│   │       └── ProductController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/
├── Models/
│   ├── Category.php
│   ├── Order.php
│   ├── Product.php
│   ├── ProductCart.php
│   └── User.php
└── Services/
    └── ProductImageService.php

resources/views/
├── admin/
├── auth/
├── invoices/
├── partials/
├── index.blade.php
├── allproducts.blade.php
├── product_details.blade.php
├── stripe.blade.php
├── payment-success.blade.php
├── viewcartproducts.blade.php
└── viewmyorders.blade.php

public/
├── admin/
├── front_end/
├── build/
└── products/
```

---

## Main Routes

### Public Routes

| Method | URL | Description |
|---|---|---|
| GET | `/` | Homepage |
| GET | `/allproducts` | Product listing, search, and category filter |
| GET | `/product_details/{id}` | Product detail page |

### Authenticated User Routes

| Method | URL | Description |
|---|---|---|
| GET | `/dashboard` | User dashboard redirect/page |
| GET | `/myorders` | User order history |
| POST | `/addtocart/{id}` | Add product to cart |
| GET | `/cartproduct` | View cart |
| DELETE | `/removecartproducts/{id}` | Remove cart item |
| GET | `/checkout` | Checkout page |
| POST | `/checkout/payment` | Process Stripe payment |
| GET | `/payment/success/{invoiceNumber}` | Payment success page |
| GET | `/invoice/{invoiceNumber}/download` | Download user invoice PDF |

### Admin Routes

| Method | URL | Description |
|---|---|---|
| GET | `/admin/dashboard` | Admin dashboard |
| GET / POST | `/add_category` | Create category |
| GET | `/view_category` | View categories |
| GET / POST | `/update_category/{id}` | Update category |
| DELETE | `/delete_category/{id}` | Delete category |
| GET / POST | `/add_product` | Create product |
| GET | `/view_product` | View products |
| GET / POST | `/updateproduct/{id}` | Update product |
| DELETE | `/deleteproduct/{id}` | Delete product |
| GET | `/search` | Search product in admin panel |
| GET | `/vieworder` | View orders |
| POST | `/change_status/{id}` | Update order status |
| GET | `/downloadpdf/{id}` | Download admin invoice PDF |

---

## Database Tables

The application uses Laravel migrations for the main ecommerce entities:

- `users`
- `categories`
- `products`
- `product_carts`
- `orders`
- `sessions`
- `cache`
- `jobs`

Important ecommerce fields include:

- `users.user_type`
- `products.product_title`
- `products.product_description`
- `products.product_quantity`
- `products.product_prices`
- `products.product_image`
- `products.product_category`
- `product_carts.quantity`
- `orders.receiver_name`
- `orders.receiver_address`
- `orders.receiver_phone`
- `orders.quantity`
- `orders.unit_price`
- `orders.total_price`
- `orders.payment_status`
- `orders.status`
- `orders.stripe_payment_id`
- `orders.invoice_number`

---

## Requirements

For local development with Docker:

- Windows with PowerShell
- Docker Desktop
- WSL enabled
- Git
- Composer dependencies installed in the project or available through the Docker container
- Node dependencies installed in the project or available through the Docker container

For production deployment:

- PHP 8.2+
- Composer
- MySQL or MariaDB database
- Node.js and npm for Vite build
- PHP extensions commonly required by Laravel, including:
  - `pdo_mysql`
  - `mbstring`
  - `openssl`
  - `fileinfo`
  - `xml`
  - `dom`
  - `ctype`
  - `json`
  - `tokenizer`

---

## Local Installation with Docker Compose

> These commands use `docker compose exec laravel.test` because this project is developed with Docker/Sail services.

### 1. Clone the repository

```powershell
git clone https://github.com/your-username/your-repository-name.git
cd your-repository-name
```

### 2. Copy environment file

```powershell
copy .env.example .env
```

### 3. Configure local `.env`

Use MariaDB for Docker local development:

```env
APP_NAME="Laravel Ecommerce"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=sail
DB_PASSWORD=password

STRIPE_KEY=pk_test_your_stripe_publishable_key
STRIPE_SECRET=sk_test_your_stripe_secret_key
```

Do not commit your real `.env` file to GitHub.

### 4. Start Docker containers

```powershell
docker compose up -d
```

### 5. Install PHP dependencies

```powershell
docker compose exec laravel.test composer install
```

### 6. Install JavaScript dependencies

```powershell
docker compose exec laravel.test npm install
```

### 7. Generate application key

```powershell
docker compose exec laravel.test php artisan key:generate
```

### 8. Run database migrations

```powershell
docker compose exec laravel.test php artisan migrate
```

### 9. Build frontend assets

```powershell
docker compose exec laravel.test npm run build
```

### 10. Clear Laravel cache

```powershell
docker compose exec laravel.test php artisan optimize:clear
```

### 11. Open the application

```text
http://localhost
```

---

## Creating an Admin User Locally

The project uses the `user_type` column to separate regular users and admin users.

Register a normal user first from the browser, then update the user role to admin.

Example using Laravel Tinker:

```powershell
docker compose exec laravel.test php artisan tinker
```

Inside Tinker:

```php
$user = App\Models\User::where('email', 'your-email@example.com')->first();
$user->user_type = 'admin';
$user->email_verified_at = now();
$user->save();
```

Exit Tinker:

```php
exit
```

---

## Stripe Test Mode Setup

This project is intended to use Stripe test mode for portfolio usage.

Add your Stripe test keys to `.env`:

```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

Use Stripe test card for successful payment testing:

```text
Card number: 4242 4242 4242 4242
Expiry: any future date
CVC: any 3 digits
```

Do not use live Stripe keys for a public portfolio demo unless the project is prepared for real transactions.

---

## Product Images

Product images are stored locally in:

```text
public/products
```

This is acceptable for a portfolio deployment if the product images are fixed demo assets and no new images will be uploaded after deployment.

If the deployed application needs dynamic product image uploads, use persistent external storage such as Cloudinary, Supabase Storage, or S3-compatible storage.

---

## Invoice PDF

The application uses DomPDF to generate invoices.

User invoice route:

```text
/invoice/{invoiceNumber}/download
```

User invoice access is restricted by:

- authenticated user ID
- invoice number
- paid payment status

Admin invoice route:

```text
/downloadpdf/{id}
```

---

## Common Development Commands

### Check routes

```powershell
docker compose exec laravel.test php artisan route:list
```

### Check migration status

```powershell
docker compose exec laravel.test php artisan migrate:status
```

### Run migrations

```powershell
docker compose exec laravel.test php artisan migrate
```

### Clear cache

```powershell
docker compose exec laravel.test php artisan optimize:clear
```

### Build assets

```powershell
docker compose exec laravel.test npm run build
```

### Run Vite development server

```powershell
docker compose exec laravel.test npm run dev
```

### Run tests

```powershell
docker compose exec laravel.test php artisan test
```

### Check PHP syntax

```powershell
docker compose exec laravel.test sh -c "find app routes database config -name '*.php' -print0 | xargs -0 -n1 php -l"
```

---

## Production Environment Checklist

For production deployment, configure environment variables in the hosting dashboard, not in GitHub.

Required production values:

```env
APP_NAME="Laravel Ecommerce"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-deployed-domain.com

DB_CONNECTION=mysql
DB_HOST=your-production-db-host
DB_PORT=3306
DB_DATABASE=your-production-db-name
DB_USERNAME=your-production-db-username
DB_PASSWORD=your-production-db-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

STRIPE_KEY=pk_test_or_live_key
STRIPE_SECRET=sk_test_or_live_secret
```

For portfolio usage, use Stripe test keys:

```text
pk_test_...
sk_test_...
```

---

## Deployment Notes

Before deploying publicly:

- Make sure `.env` is not committed
- Make sure database dump files such as `.sql` are not committed
- Make sure `vendor/` and `node_modules/` are not committed
- Make sure Laravel logs are not committed
- Make sure `APP_DEBUG=false` in production
- Set the production document root to `public/`
- Run `composer install --no-dev --optimize-autoloader`
- Run `npm install` or `npm ci`
- Run `npm run build`
- Run `php artisan migrate --force`
- Run `php artisan optimize`

---

## Suggested Free or Low-Cost Deployment Options

Recommended options for this project:

1. Laravel Cloud
   - Best Laravel-specific deployment experience
   - Simple setup for Laravel projects
   - Good option if a small monthly cost is acceptable

2. Koyeb or Render with external MySQL/MariaDB
   - Can be used for portfolio deployment
   - Needs careful setup for environment variables, build commands, and persistent storage
   - Product images should be committed as demo static assets or moved to external storage

3. Aiven MySQL Free
   - Useful as an external MySQL database option
   - Suitable for portfolio or demo deployments with limited usage

---

## Testing Checklist

Before pushing or deploying, test these flows:

### User

- Register user
- Login user
- Logout user
- View homepage
- View all products
- Search products
- Filter by category
- View product detail
- Add product to cart
- Update quantity through add-to-cart flow
- Remove product from cart

### Checkout

- Open checkout with cart items
- Submit receiver name, address, and phone number
- Pay using Stripe test card
- Confirm successful redirect
- Confirm cart is empty after payment
- Confirm stock decreases after payment
- Confirm order appears in user order history
- Confirm invoice PDF can be downloaded

### Admin

- Login as admin
- Access admin dashboard
- Add category
- Update category
- Delete unused category
- Add product with image
- Update product
- Delete unused product
- View orders
- Filter/search orders
- Update order status
- Download admin invoice PDF

### Security

- Access admin page as guest
- Access admin page as regular user
- Access checkout as guest
- Access another user's invoice URL
- Confirm `APP_DEBUG=false` in production
- Confirm `.env` is not accessible publicly

---

## Notes for Portfolio Reviewers

This project demonstrates:

- Laravel MVC structure
- Authentication and role-based authorization
- Ecommerce cart and checkout logic
- Stripe test payment integration
- Stock and order transaction handling
- Invoice PDF generation
- Admin CRUD management
- Blade-based UI implementation
- Docker-based local development workflow

---

## License

This project is created for portfolio and educational purposes.
