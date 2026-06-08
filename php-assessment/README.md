# PHP CodeIgniter 4 Restaurant POS & Cart System

This project provides a comprehensive, production-grade PHP backend and frontend solution for the **Restaurant POS Orders & Cart System** as specified in the Developer Assessment. It leverages **CodeIgniter 4** for the framework, **MySQL** for data storage, and **Vanilla CSS/JS** for the frontend user interface.

---

## 🛠️ Technical Stack & Architecture

The application implements a clean Model-View-Controller (MVC) pattern:
* **Controller Layer (`app/Controllers/`)**:
  * `ApiController`: Extends CodeIgniter’s `ResourceController`. Exposes a REST API delivering aggregated orders and split payments.
  * `CartController`: Directs the POS cart actions (add, remove, clear, quantity adjustments) using PHP Sessions.
* **Model Layer (`app/Models/`)**:
  * `OrderModel`: Connects to `order_history`. Formulates the grouped DISTINCT order searches and structural joins (`order_history` $\rightarrow$ `menu` $\rightarrow$ `categories` $\rightarrow$ `menu_names`).
  * `PaymentModel`: Connects to `payments` to pull split receipts for specific order IDs.
  * `ItemModel`: Directs core item lookups.
* **View Layer (`app/Views/`)**:
  * `cart_view.php`: A premium Checkout UI designed with Outfit Google Fonts, glassmorphism containers, hover effects, and CSS variables.
* **Client-Side Preview Layer (`index.html`)**:
  * A zero-dependency HTML file integrating mock datasets in Javascript. It serves as an instant GUI review tool showcasing both the interactive Cart POS and the API Database Explorer.

---

## 📂 Project Directory Structure & Files Breakdown

```text
php-assessment/
├── index.html                   <-- Client-side Interactive Cart POS & API Database Explorer (Zero dependencies)
├── findings.md                  <-- Task 1: Complete Data & Calculation Findings Report
├── README.md                    <-- Detailed setup, configuration, and running instructions (This file)
├── composer.json                <-- Composer dependency configurations
├── spark                        <-- CodeIgniter 4 CLI spark terminal executable
├── public/
│   └── index.php                <-- Web app entry front controller
├── database/
│   └── schema.sql               <-- Unified database table structures and pre-seeded data SQL
└── app/
    ├── Config/
    │   ├── Paths.php            <-- Core folder directories mappings
    │   ├── App.php              <-- Application URLs & locale settings
    │   ├── Autoload.php         <-- Namespaces configuration
    │   ├── Database.php         <-- Database configurations (MySQL & SQLite profiles)
    │   └── Routes.php           <-- Application URL routes mapping (/api/orders, /cart)
    ├── Models/
    │   ├── ItemModel.php        <-- Database model for menu items
    │   ├── OrderModel.php       <-- Relational joins query builder for orders
    │   └── PaymentModel.php     <-- Relational joins query builder for payments
    ├── Controllers/
    │   ├── ApiController.php    <-- Task 2: REST JSON API Controller (split payments & validations)
    │   └── CartController.php   <-- Task 3: Interactive Cart Controller (sessions & tax calculations)
    └── Views/
        └── cart_view.php        <-- Task 3: Premium checkout layout (Outfit font, Glassmorphism CSS)
```

### 1. `app/Config/Routes.php` (Routing Configuration)
Defines the URL routing mappings:
* `GET /api/orders` routes to `ApiController::index` to output the nested JSON data.
* `GET /cart` routes to `CartController::index` to render the shopping cart view.
* `GET /cart/add/(:num)` routes to `CartController::add/$1` to add items to the cart.
* `GET /cart/increase/(:num)` routes to `CartController::increase/$1` to increment item quantities.
* `GET /cart/decrease/(:num)` routes to `CartController::decrease/$1` to decrement item quantities.
* `GET /cart/remove/(:num)` routes to `CartController::remove/$1` to remove items.
* `GET /cart/clear` routes to `CartController::clear` to clear the cart session.

### 2. `ApiController.php` (REST API Endpoint)
Pulls unique order lines, compiles itemized lists and payments, and runs the audit validation formula:
* Expected Total Paid is calculated as:
  $$\text{Expected Total Paid} = \text{Amount Due} + \text{Tips} - \text{Discount}$$
* Emits a `discrepancy` balance and categorizes verification status.
* Adds optimization headers: CORS (`Access-Control-Allow-Origin: *`) and caching (`Cache-Control: public, max-age=60`) to maximize API performance under load.

### 3. `CartController.php` (Interactive Cart Controller)
* Implements checkout logic using CodeIgniter session variables.
* Computes inclusive 12.5% tax calculations dynamically:
  $$\text{Tax Amount} = \text{Total Including Tax} \times \left( \frac{12.5}{112.5} \right) = \text{Total Including Tax} \times \left( \frac{1}{9} \right)$$
  $$\text{Total Excluding Tax} = \text{Total Including Tax} - \text{Tax Amount}$$

### 4. `cart_view.php` (View Template)
* Designed as a premium checkout interface.
* Displays available items alongside the live cart.
* Displays subtotal (excluding tax), inclusive tax amount, and grand total.

---

## ⚡ Setup & Execution Guide

### Option A: Instant GUI Preview (No Setup Required)
You can test the functionality immediately without database installations or local PHP servers:
1. Open the `php-assessment/` folder in your browser.
2. Double-click the file **`index.html`** to load the GUI dashboard.
3. Toggle between tabs:
   * **Task 3: Interactive Cart POS** (Add items, adjust quantities, view tax).
   * **Task 2: API Database Explorer** (Inspect unique orders, nested items, split payments, and audit results).

---

### Option B: Local CodeIgniter 4 Server Setup
To boot up the live PHP backend, follow these steps:

#### 1. Import Database Schema
1. Open MySQL (e.g. phpMyAdmin / XAMPP) and create a database named **`restaurant_pos`**.
2. Import the file **`database/schema.sql`** to build and seed all tables.

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
1. Open a terminal inside the project directory:
   ```powershell
   cd "php-assessment"
   ```
2. Run composer to install the CodeIgniter 4 core dependencies:
   ```powershell
   composer install
   ```

#### 4. Run the Dev Server
1. Boot the server using CodeIgniter's Spark script:
   ```powershell
   php spark serve
   ```
   *The application will boot on `http://localhost:8080`.*

#### 5. Verify the Deliverables
* **Task 3 (Interactive Cart View)**: Navigate to `http://localhost:8080/cart`.
* **Task 2 (Orders JSON API)**: Query `GET http://localhost:8080/api/orders` to retrieve order and payment payloads.

---

## 🔒 Security, Performance & Code Optimizations

### 🛡️ Security Measures
1. **SQL Injection Prevention**: Uses CodeIgniter 4’s Query Builder, which automatically binds parameters to prevent SQL injection.
2. **CORS Middleware Security**: Explicit headers are attached to the API controller response to enable secure cross-origin resource requests.
3. **Session Security**: Session IDs are regenerated automatically upon checkout activities to prevent session fixation.

### ⚡ Performance Features
1. **HTTP Caching**: The API endpoint responds with `Cache-Control: public, max-age=60` headers, allowing browsers or proxies to cache requests for 60 seconds, reducing database load.
2. **Indexing**: Primary and foreign keys are explicitly mapped in the SQL schema for quick join query resolution.
