# Fullstack Developer Assessment Solutions: Python API & PHP (CodeIgniter 4)

This repository contains the complete, production-grade solutions for both the **Python API** and the **PHP (CodeIgniter 4)** components of the Fullstack Developer Assessment.

Both projects have been designed to meet top industry standards, showcasing strict database design principles, relational joins, robust data serialization, secure CORS controls, and detailed data audit verification models.

---

## 📂 Project Directory Structure

The repository organizes the Python and PHP components into separate, self-contained directories. A unified `.gitignore` prevents virtual environments and framework cache directories from cluttering commits:

```text
D:\fininfocom task\
├── README.md                          <-- Parent overview and startup guide (This file)
├── .gitignore                         <-- Git tracking rules (excludes caches, local DBs, vendor directories)
├── PHP Task.pdf                       <-- PHP Assessment Specification
├── Fullstack Developer Assessment - Python API.pdf <-- Python Assessment Specification
│
├── python-api/                        <-- Python FastAPI Solution
│   ├── main.py                        <-- FastAPI application and routes entrypoint
│   ├── database.py                    <-- SQLite Database connection configurations
│   ├── models.py                      <-- SQLAlchemy ORM database models
│   ├── schemas.py                     <-- Pydantic serialization & request/response schemas
│   ├── crud.py                        <-- Query builder joins, split payments & logic checks
│   ├── seed_db.py                     <-- SQLite auto-seeding script
│   ├── findings.md                    <-- Python data analysis & anomaly report
│   ├── README.md                      <-- Detailed Python setup & endpoints guide
│   ├── requirements.txt               <-- Python package dependencies
│   └── database/
│       └── schema.sql                 <-- SQL script to build and seed Python tables
│
└── php-assessment/                    <-- PHP CodeIgniter 4 Solution
    ├── index.html                     <-- Client-side interactive cart & mock API explorer
    ├── composer.json                  <-- PHP composer dependencies
    ├── spark                          <-- CodeIgniter 4 CLI executable utility
    ├── findings.md                    <-- PHP data analysis & anomaly report
    ├── README.md                      <-- Detailed PHP setup & configuration guide
    ├── public/
    │   └── index.php                  <-- Web app entry front controller
    ├── database/
    │   └── schema.sql                 <-- SQL script to build and seed MySQL tables
    ├── app/
    │   ├── Config/                    <-- Configuration (Database, Routes, App, etc.)
    │   ├── Controllers/               <-- API and Cart view controllers
    │   ├── Models/                    <-- Relational joins models (Item, Order, Payment)
    │   └── Views/                     <-- Interactive checkout view template (Outfit, Glassmorphism)
    └── writable/                      <-- Framework cache & logs folder
```

---

## 🔍 Task 1: Joint Data Analysis & Findings

Both solutions are backed by an in-depth mathematical audit of the provided datasets (**Menu**, **Order History**, and **Payments**). The analysis uncovered several critical real-world database design flaws:

### 1. Menu Relationship Mapping Anomaly
* **Item 5 (`Item5`)** belongs to Category `Soft Drinks` (which has a `Menu ID = 2` - Drinks). However, the menu table maps it to `Menu ID = 1` (Food). This violates standard category-menu hierarchies.

### 2. Missing Database Record IDs
* **Order History Sequence**: The transaction records jump from ID 39 directly to ID 41. **ID 40 is missing** in the sequence.
* **Payments Sequence**: Several transaction records are missing: IDs **112, 113, 114, 117, and 118** are absent from the payment log.

### 3. Dynamic Unit Pricing & Decimal Precision
* Unit prices charged in the transaction history vary dynamically compared to their base menu prices (e.g., `Item 1 (Small)` has a base price of £1.50 but is sold at £3.75, £2.75, £2.50, and £2.5698).
* Transaction prices are stored with up to 5 decimal places (e.g., `2.75636`), reflecting dynamic pricing engines, automated fees, or currency conversions.

### 4. Split-Bill Calculations & Overpayment
* We verified that split-payment transactions across Cash and Card receipts satisfy the verification formula:
  $$\sum (\text{Total Paid}) = \text{Amount Due} + \text{Tips} - \text{Discount}$$
* While Order 12 and Order 18 match exactly, **Order 20 has a $+£0.02$ overpayment discrepancy** (Items total £52.2573, tips/discounts are zero, but split payment records sum to £52.28).

---

## 🐍 Python FastAPI REST API Solution

The Python backend is designed to run asynchronously on **FastAPI** with **SQLite** as the database, using **SQLAlchemy ORM** for query compilation.

### **Core Features:**
* **ORM Modeling**: Leverages SQLAlchemy Declarative Base to define relational mappings, foreign keys, and lazy-loading relationships.
* **Input Validation**: Uses **Pydantic v2** models to enforce type constraints, round numeric parameters, and structure JSON responses.
* **Database Seeder**: An automated python seeder (`seed_db.py`) parses the schema script transactionally and creates the local database file instantly.
* **Mathematical Auditor**: Validates each order’s split payments against its items sum and records verification metrics inside the API response.

### **Execution Instructions:**
1. Navigate to the Python project:
   ```powershell
   cd python-api
   ```
2. Install the dependencies:
   ```powershell
   pip install -r requirements.txt
   ```
3. Initialize and seed the database:
   ```powershell
   python seed_db.py
   ```
4. Start the server:
   ```powershell
   python -m uvicorn main:app --reload
   ```
5. **API Endpoints**:
   * Interactive Documentation: 👉 **`http://127.0.0.1:8000/docs`**
   * GET Orders Nested JSON Payload: 👉 **`http://127.0.0.1:8000/api/orders`**

---

## 🐘 PHP CodeIgniter 4 Web Solution

The PHP application is structured under the MVC design pattern, utilizing **CodeIgniter 4** and **MySQL**.

### **Core Features:**
* **REST API Controller**: Extends `ResourceController` to output nested JSON, incorporating performance-optimizing `Cache-Control` headers and secure CORS headers.
* **Interactive Cart View**: Features a premium checkout layout styled with Google Font typography and glassmorphism styling. Calculates inclusive 12.5% tax:
  $$\text{Tax Amount} = \text{Total Including Tax} \times \left( \frac{12.5}{112.5} \right) = \text{Total Including Tax} \times \left( \frac{1}{9} \right)$$
* **Zero-Dependency GUI Preview (`index.html`)**: Includes a client-side mockup combining mock datasets in vanilla JS. Allows the recruiter to test both the Interactive Cart and API explorer instantly by double-clicking the file in their browser.

### **Execution Instructions:**

#### Option A: Instant Browser Preview (No Server Setup Required)
1. Open the `php-assessment/` folder.
2. Double-click **`index.html`** to load the visual GUI in your web browser.

#### Option B: Run Live PHP Server
1. Create a MySQL database named `restaurant_pos` and import the script: **`database/schema.sql`**.
2. Edit `app/Config/Database.php` and configure your local MySQL credentials.
3. Install composer dependencies:
   ```powershell
   composer install
   ```
4. Run the local development server:
   ```powershell
   php spark serve
   ```
   *The server runs on `http://localhost:8080`.*
5. **API Endpoints**:
   * GET Orders Nested JSON Payload: 👉 **`http://localhost:8080/api/orders`**
   * Interactive Checkout Cart: 👉 **`http://localhost:8080/cart`**

---

## 🛡️ Security, Performance & Code Optimizations

* **SQL Injection Prevention**: Enforced via CodeIgniter Query Builder and SQLAlchemy ORM parameter binding.
* **CORS Settings**: Fully enabled across both projects to permit safe cross-origin API calls.
* **HTTP Caching**: The PHP API sets `Cache-Control: public, max-age=60` to optimize backend database load.
* **Index Configurations**: Primary and foreign keys are explicitly mapped in database schemas to optimize join queries.
