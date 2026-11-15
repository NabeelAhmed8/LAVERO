# LAVÉRO — Haute Couture & Editorial Atelier

A luxury couture e-commerce platform and bespoke salon management system built with Laravel 12, Tailwind CSS v4, and Alpine.js.

---

## Features

- **Exclusive Editorial Storefront:** Curated runway collections, seasonal drops, dynamic stock badge indicators, and high-resolution garment showcases.
- **Private Client Circle (Auth):** Secure registration, email verification codes, password recovery, and client profile management.
- **Private Fitting & Bespoke Salon Inquiries:** Salon request handling with direct atelier routing.
- **Shopping Bag & Checkout:** Interactive cart drawer, persistent session management, address collection, Stripe payment gateway integration, and Cash on Delivery fallback.
- **Couture CMS & Admin Suite:** 
  - Product catalog management with real-time stock status (In Stock, Low Stock, 2 Items Left, Out of Stock).
  - Editorial badge customization, pricing architecture, size matrix selection, and dynamic image gallery management.
  - Client orders and shipment tracking dispatch (DHL, FedEx, UPS).
  - Newsletter subscriber registry.

---

## Tech Stack

- **Backend:** PHP 8.3 / Laravel 12
- **Database:** SQLite
- **Frontend:** Blade Templates, Tailwind CSS v4, Alpine.js, Motion.js
- **Testing:** Pest PHP Test Suite (100% green feature test coverage)
- **Code Style:** Laravel Pint

---

## Getting Started

### Prerequisites
- PHP >= 8.3
- Composer
- Node.js >= 20 & NPM
- SQLite3

### Local Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/NabeelAhmed8/LAVERO.git
   cd LAVERO
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database & Migrations:**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Build Frontend Assets:**
   ```bash
   npm run build
   # or for live HMR development:
   npm run dev
   ```

6. **Start Local Development Server:**
   ```bash
   php artisan serve
   ```
   Open `http://localhost:8000` in your browser.

---

## Running Tests

Execute the Pest test suite:
```bash
php artisan test
```

---

## License

Private / Proprietary. All rights reserved.
