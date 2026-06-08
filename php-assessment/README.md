# Fullstack Developer Assessment - PHP
This project provides a comprehensive, production-ready implementation of the Fullstack Restaurant POS assessment in PHP using the **CodeIgniter 4** framework.

---

## 📂 Project Directory Structure

```text
php-assessment/
├── index.html                   <-- Client-side Interactive Cart POS & API Database Explorer (Zero dependencies)
├── findings.md                  <-- Task 1: Complete Data & Calculation Findings Report
├── README.md                    <-- Project guide and setup instructions (This file)
├── composer.json                <-- Composer dependency configuration
├── spark                        <-- CodeIgniter 4 CLI spark terminal executable
├── public/
│   └── index.php                <-- Web app entry front controller
├── database/
│   └── schema.sql               <-- Unified database table structures and pre-seeded data SQL
└── app/
    ├── Config/
    │   ├── Paths.php            <-- Core folder directories mapping
    │   ├── App.php              <-- Application URLs & locale settings
    │   ├── Autoload.php         <-- Namespaces configuration
    │   ├── Services.php         <-- Boot service loader
    │   ├── Constants.php        <-- Global configuration constants
    │   ├── Security.php         <-- CSRF and cookie protection setup
    │   ├── Filters.php          <-- HTTP middleware filters
    │   ├── Logger.php           <-- Error logs threshold config
    │   ├── Exceptions.php       <-- Framework exceptions logger
    │   ├── Database.php         <-- Database configurations (MySQL & SQLite profiles)
    │   └── Routes.php           <-- Application URL routes mapping (/api/orders, /cart)
    ├── Models/
    │   ├── ItemModel.php        <-- Relational joins query builder for items
    │   ├── OrderModel.php       <-- Relational joins query builder for orders
    │   └── PaymentModel.php     <-- Relational joins query builder for payments
    ├── Controllers/
    │   ├── ApiController.php    <-- Task 2: REST JSON API Controller (split payments & verification checks)
    │   └── CartController.php   <-- Task 3: Interactive Cart Controller (sessions)
    └── Views/
        └── cart_view.php        <-- Task 3: Premium checkout layout (Outfit font, Glassmorphism CSS)
```

---

## ⚡ Quick Start Options

### Option A: Instant GUI Preview (No Setup Required)
You can view the fully completed tasks without database imports or local servers:
1. Navigate to: `D:\fininfocom task\php-assessment\`
2. Double-click **`index.html`** to load it inside your web browser.
3. Switch tabs:
   * **Task 3: Interactive Cart**: Add items, increase/decrease quantities, filter categories, and observe tax calculations.
   * **Task 2: API Database Explorer**: Expand rows to view order items, split payments, mathematical checks, and copy the raw nested JSON output.

### Option B: Run the PHP CodeIgniter 4 Server
To boot up the live PHP backend, follow these steps:

#### 1. Import Database Schema
1. Create a MySQL database named **`restaurant_pos`** on your local database system (e.g. phpMyAdmin, XAMPP, or Laragon).
2. Open your database command tool or GUI panel and import the file: **`database/schema.sql`** to create and seed the tables.

#### 2. Configure Credentials
1. Open the file: **`app/Config/Database.php`**.
2. Update the `$default` connection group with your local MySQL credentials:
   ```php
   'hostname' => 'localhost',
   'username' => 'root',       // Your MySQL username
   'password' => '',           // Your MySQL password
   'database' => 'restaurant_pos',
   ```

#### 3. Install Vendor Dependencies
1. Open a Command Prompt or PowerShell terminal inside the project directory:
   ```cmd
   cd "D:\fininfocom task\php-assessment"
   ```
2. Run composer to install the CodeIgniter 4 system directory:
   ```cmd
   composer install
   ```

#### 4. Run the Dev Server
1. Boot the server using CodeIgniter's Spark script:
   ```cmd
   php spark serve
   ```
   *The application will boot on `http://localhost:8080`.*

#### 5. Verify the Deliverables
* **Task 3 (Interactive Cart View)**: Navigate to `http://localhost:8080/cart` to add items to the cart, adjust quantities, view totals, and check the 12.5% inclusive tax calculations.
* **Task 2 (Orders JSON API)**: Request GET `http://localhost:8080/api/orders` in Postman to retrieve all orders with nested items, split payments, and calculations verification logs.

---

## 🔍 Task 1: Top Findings Summary (from findings.md)

1. **Menu Relationship Mapping Anomaly**: Item 5 (Item5) belongs to `Cat ID = 2` (Soft Drinks) which is a Drinks menu type (`Menu ID = 2`), but its database record is mapped to `Menu ID = 1` (Food).
2. **Missing Auto-Increment ID**: The Order History transaction records jump from ID 39 to ID 41. **ID 40 is missing** in the transaction sequence.
3. **High Decimal Precision & Price Variations**: Transaction log prices are stored with up to 5 decimal places (e.g., `2.75636`). Actual transaction unit prices charged differ significantly from the base menu pricing, reflecting dynamic pricing adjustments.
4. **Split Bill Payment Verification**: We mathematically validated that the sum of split payments across Cash/Card checks matches the formula:
   $$\sum (\text{Total Paid}) = \text{Amount Due} + \text{Tips} - \text{Discount}$$
5. **Overpayment Discrepancy**: Order 20's items sum to **£52.2573** (Amount Due), with no tips or discounts. However, its split payment receipts sum to **£52.28**, creating a **+£0.02** (~2 cents) discrepancy.
6. **Payment ID Gap**: In the payment log, IDs **112, 113, 114, 117, and 118** are missing from the sequential transaction record.
