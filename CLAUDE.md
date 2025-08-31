# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Lunar PHP** Laravel e-commerce starter kit with Livewire integration. Lunar is a headless e-commerce platform for Laravel. The project is a reference implementation showing how to build a complete e-commerce store.

⚠️ **Warning**: This is not production-ready code - it's a learning/reference implementation.

## Development Commands

### Primary Development
```bash
# Frontend development server
npm run dev

# Build frontend assets for production
npm run build

# Laravel artisan commands
php artisan serve
php artisan migrate
php artisan db:seed
```

### Testing
```bash
# Run PHPUnit tests
php artisan test
# or
vendor/bin/phpunit
```

### Lunar-Specific Setup
```bash
# Install Lunar after composer install
php artisan lunar:install

# Link storage for media files
php artisan storage:link
```

### Development Environment Options

**Docker**: 
```bash
cp .env.docker.example .env
docker-compose up
```

**Lando** (if available):
```bash
lando start
lando composer install
cp .env.lando.example .env
lando artisan migrate && lando artisan lunar:install && lando artisan db:seed
```

## Architecture

### Core Technologies
- **Laravel 10/11** - PHP framework
- **Lunar PHP** - Headless e-commerce package 
- **Tailwind CSS** - Styling framework
- **Alpine.js** - Lightweight JS framework
- **Vite** - Frontend build tool

### Key Models & Extensions
- `App\Models\Product` extends `Lunar\Models\Product` - Custom product model with discount calculations, ratings, and owner scoping
- `App\Models\User` - Extends Laravel User with roles and owner relationships
- `App\Traits\OwnerScope` - Provides multi-tenant functionality by owner_id

### Directory Structure
- `app/Http/Controllers/` - Standard Laravel controllers, separated into Admin and frontend
- `app/Models/` - Eloquent models extending Lunar models
- `app/Policies/` - Authorization policies for ownership-based access
- `app/Services/` - Business logic services (e.g., DiscountService)
- `app/Traits/` - Reusable traits like OwnerScope
- `resources/views/` - Blade templates for frontend and admin
- `routes/web.php` - All web routes including admin panel and API endpoints

### Authentication & Authorization
- Multi-role authentication: visitors, partners, admin users
- Owner-based scoping for multi-tenant functionality
- Admin panel protected by 'auth' and 'owner' middleware at `/admin`
- Social login support (Facebook, Google)

### E-commerce Features
- Product management with variants and media
- Shopping cart functionality
- Checkout with Stripe and PayPal integration
- Product reviews and Q&A system
- Wishlist functionality
- Admin order management with PDF generation
- Discount system with percentage and fixed amount support

### Frontend Patterns
- Blade components and layouts
- Alpine.js for interactivity
- Tailwind for responsive design
- AJAX search and cart operations
- Product filtering and sorting

## Important Notes

### Testing
- Uses SQLite in-memory database for testing
- Test environment configured in `phpunit.xml`
- Separate Unit and Feature test suites

### Media Management
- Uses Laravel Media Library via Lunar
- Thumbnails stored with fallback.jpg as default
- Media uploads handled through admin interface

### Multi-tenancy
- Owner-based scoping throughout models
- All products, orders, and entities tied to owner_id
- Admin panel shows only owner-specific data

### Custom Lunar Extensions
- Enhanced Product model with computed attributes for pricing, ratings
- Custom discount calculation service
- Extended user authentication and role management