# Python FastAPI Restaurant POS Orders API

This project provides a comprehensive, production-grade Python API solution for the **Restaurant POS Orders System** as specified in the Developer Assessment. It leverages **FastAPI** for the web framework, **SQLite** for the local data storage, and **SQLAlchemy ORM** for query compilation and database mapping.

---

## 🛠️ Technical Stack & Architecture

The application is structured using a standard clean MVC/Layered Architecture:
* **Web/Routing Layer (`main.py`)**: Defines FastAPI routes, handles CORS middleware policies, redirects index request, and implements exception boundaries.
* **Service/Query Layer (`crud.py`)**: Directs database joins, aggregates data structures, and evaluates mathematical balance verification formulas.
* **Validation/Serialization Layer (`schemas.py`)**: Utilizes **Pydantic v2** models to serialize, validate, and parse SQL objects into JSON format with precise type enforcement.
* **ORM Mapping Layer (`models.py`)**: Uses **SQLAlchemy Declarative Base** to map SQLite tables and relationships.
* **Database Connection Layer (`database.py`)**: Manages the local SQLite database creation and SessionLocal dependency lifecycle.
* **Database Seeder (`seed_db.py`)**: A database creator script that parses schema files transactionally.

---

## 📂 Project Directory Structure & Files Breakdown

```text
python-api/
├── main.py                      <-- FastAPI application router entrypoint
├── database.py                  <-- SQLAlchemy database configurations & SessionLocal generator
├── models.py                    <-- SQLAlchemy ORM models matching database tables
├── schemas.py                   <-- Pydantic models for request/response serialization
├── crud.py                      <-- Database queries (joins, split payments & validations)
├── seed_db.py                   <-- SQLite database creator and auto-seeder utility
├── findings.md                  <-- Task 1: Complete Data & Calculations Findings Report
├── README.md                    <-- Detailed setup, API endpoints, and running instructions (This file)
├── requirements.txt             <-- Python package dependencies
└── database/
    └── schema.sql               <-- Unified database table structures and pre-seeded data SQL
```

### 1. `database.py` (Database Engine & Session)
* Instantiates the database engine using `sqlite:///./restaurant_pos.db`.
* Implements `check_same_thread=False` to support concurrent, multi-threaded requests inside FastAPI.
* Exposes `get_db()` as a context manager/dependency that yields a database session and safely closes it upon request completion.

### 2. `models.py` (SQLAlchemy Relational Mapping)
Defines 5 relational database models:
* `MenuName`: Matches the `menu_names` table (Menu ID -> Menu Name like Food/Drinks).
* `Category`: Matches the `categories` table (Cat ID -> Category Name, mapped to Menu Name via ForeignKey).
* `Menu`: Matches the `menu` table. Stores item sizes and prices as denormalized VARCHAR lists matching raw schema layout.
* `OrderHistory`: Matches the `order_history` table containing 53 order lines. Stores item sales details (price charged, qty, order status, total cost).
* `Payment`: Matches the `payments` table containing 17 split payments (amount due, tips, discounts, paid amount, payment type/status).

### 3. `schemas.py` (Pydantic Serialization & Types)
* `ItemResponse`: Serializes nested order item lines (casts database string fields like price/total to float, maps joined parent category and menu names).
* `PaymentResponse`: Formats nested payment entries (casts database string fields like tips/discounts/paid amount to float).
* `VerificationResponse`: Formats audit calculations, providing values for `expected_total_paid`, `actual_total_paid`, `discrepancy`, `rounded_discrepancy`, and `status`.
* `OrderResponse`: The parent serialization model wrapper returning aggregated order stats and arrays of items/payments.

### 4. `crud.py` (Business Logic & Aggregation joins)
* `get_unique_orders(db)`: Retrieves unique Order IDs from the transaction table.
* `get_order_details(db, order_id, order_date, order_status)`:
  * Pulls itemized lines by joining `order_history` $\rightarrow$ `menu` $\rightarrow$ `categories` $\rightarrow$ `menu_names` to fetch item metadata.
  * Fetches all split receipts for the order ID.
  * Runs mathematical validation verifying:
    $$\sum (\text{Total Paid}) = \text{Amount Due} + \text{Tips} - \text{Discount}$$
  * Emits discrepancy balances and classifies status as `Match` or `Discrepancy` (discrepancy threshold is set to $< \text{£}0.01$ based on a 2-decimal rounded comparison).

---

## ⚡ Setup & Execution Guide

### 1. Prerequisite Installations
Open a terminal inside this project directory (`python-api/`) and install the python packages:
```powershell
pip install -r requirements.txt
```

### 2. Run Database Seeding
Create the local SQLite database file `restaurant_pos.db` and seed the tables:
```powershell
python seed_db.py
```
*(Prints: "Database seeding completed successfully! 10 SQL statements executed.")*

### 3. Run the Backend API
Launch the FastAPI application:
```powershell
python -m uvicorn main:app --reload
```
*(The server will boot and run on `http://127.0.0.1:8000`.)*

---

## 🔍 REST API Specification & Endpoint Details

### GET `/api/orders`
Returns the complete list of unique orders with their nested details.

#### **Response format (JSON)**:
```json
[
  {
    "order_id": 10,
    "order_date": "01 Oct 2025",
    "order_status": "Completed",
    "calculated_items_total": 9.25,
    "recorded_amount_due": 9.25,
    "payment_summary": {
      "total_paid": 9.25,
      "total_tips": 0.0,
      "total_discount": 0.0
    },
    "verification": {
      "formula": "Sum(Total Paid) = Amount Due + Tips - Discount",
      "expected_total_paid": 9.25,
      "actual_total_paid": 9.25,
      "discrepancy": 0.0,
      "rounded_discrepancy": 0.0,
      "status": "Match"
    },
    "items": [
      {
        "id": 1,
        "item_id": 2,
        "item_name": "Item2",
        "category_name": "Starters",
        "menu_name": "Food",
        "size": null,
        "price": 2.5,
        "qty": 1,
        "total": 2.5
      },
      {
        "id": 2,
        "item_id": 3,
        "item_name": "Item3",
        "category_name": "Soft Drinks",
        "menu_name": "Drinks",
        "size": null,
        "price": 1.5,
        "qty": 2,
        "total": 3.0
      },
      {
        "id": 3,
        "item_id": 1,
        "item_name": "Item1",
        "category_name": "Starters",
        "menu_name": "Food",
        "size": "Small",
        "price": 3.75,
        "qty": 1,
        "total": 3.75
      }
    ],
    "payments": [
      {
        "payment_id": 100,
        "payment_date": "01 Oct 2025",
        "amount_due": 9.25,
        "tips": 0.0,
        "discount": 0.0,
        "total_paid": 9.25,
        "payment_type": "Card",
        "payment_status": "Completed"
      }
    ]
  }
]
```

---

## 🔒 Security, Performance & Code Optimizations

### 🛡️ Security Measures
1. **Parameterized Queries**: All queries compiled by SQLAlchemy ORM generate parameterized SQL queries, completely eliminating SQL Injection risks.
2. **CORS Middleware Security**: Pre-configured using FastAPI's `CORSMiddleware` restricting headers and routing permissions.
3. **Input Validation**: Pydantic strictly checks data types during deserialization, preventing buffer or type injection vectors.

### ⚡ Performance Features
1. **SQLite Connection Optimization**: Multi-threaded database transactions are permitted via `check_same_thread=False` combined with SQLAlchemy Session Local dependency injection.
2. **Indexing Strategy**: Indexes are created on critical search fields (`order_id`, `item_id`, `payment_id`) to maximize query execution speeds on joins.
3. **Round Off Logic**: Precise floating-point calculations using Python's decimal routing functions to protect currency conversions.
