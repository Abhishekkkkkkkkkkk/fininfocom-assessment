# Fullstack Developer Assessment - PHP & Python API Solutions

This repository contains the complete and production-ready solutions for both the **Python API** and the **PHP (CodeIgniter 4)** components of the Fullstack Developer Assessment.

Both projects are structured to demonstrate high standards in clean code architecture, database relational joins, data validation, and mathematical audit verification.

---

## 📂 Project Structure

```text
D:\fininfocom task\
├── README.md                          <-- Parent overview and startup guide (This file)
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

## 🚀 Installation & Running Guide

### 🐍 Python FastAPI Setup
The Python backend uses **FastAPI**, **SQLite** (local file-based db), and **SQLAlchemy ORM**.

1. Navigate to the Python project directory:
   ```powershell
   cd "python-api"
   ```
2. Install the required modules:
   ```powershell
   pip install -r requirements.txt
   ```
3. Initialize and seed the SQLite database file (`restaurant_pos.db`):
   ```powershell
   python seed_db.py
   ```
4. Start the live local API server:
   ```powershell
   python -m uvicorn main:app --reload
   ```
5. **Access Endpoint**:
   * Interactive Documentation: 👉 **`http://127.0.0.1:8000/docs`**
   * GET Orders JSON Payload: 👉 **`http://127.0.0.1:8000/api/orders`**

---

### 🐘 PHP CodeIgniter 4 Setup
The PHP backend uses **CodeIgniter 4**, **MySQL**, and **Composer**.

#### Option 1: Instant Client-Side GUI Preview (No Setup Required)
For convenience, you can load the completed tasks instantly without importing databases or running local servers:
1. Open the `php-assessment/` folder in your explorer.
2. Double-click the file **`index.html`** to load it inside your web browser.
3. Toggle between tabs:
   * **Task 3: Interactive Cart POS** (Add items, adjust quantities, view 12.5% inclusive tax).
   * **Task 2: API Database Explorer** (Inspect hierarchical orders, nested items, split payments, and mathematical checks).

#### Option 2: Local Spark Server Setup
1. Open MySQL (e.g. phpMyAdmin / XAMPP) and create a database named `restaurant_pos`.
2. Import **`database/schema.sql`** into the database to build and seed all tables.
3. Open `app/Config/Database.php` and configure your local MySQL credentials:
   ```php
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => '', // your password
   'database' => 'restaurant_pos',
   ```
4. Install composer dependencies:
   ```powershell
   composer install
   ```
5. Run the dev server using the Spark tool:
   ```powershell
   php spark serve
   ```
6. **Access Endpoint**:
   * GET Orders JSON API: 👉 **`http://localhost:8080/api/orders`**
   * Interactive POS Cart Page: 👉 **`http://localhost:8080/cart`**

---

## 🔒 Security, Performance & API Design

* **SQL Injection Prevention**: Standard parameterized query binding via ORM and query builders in both frameworks.
* **CORS Middleware**: Pre-configured headers in both applications to allow cross-origin requests.
* **Input and Type Validation**: Strict typing using Pydantic models in Python and PSR-4 compliant Models in PHP.
* **Database Indexing**: Relational tables include explicit primary/foreign keys and index mappings for fast query resolution.
