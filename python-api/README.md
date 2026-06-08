# Fullstack Developer Assessment - Python API
This project provides a comprehensive, production-ready implementation of the Fullstack Restaurant Orders API in Python using **FastAPI** and **SQLAlchemy ORM**.

---

## 📂 Project Directory Structure

```text
python-api/
├── main.py                      <-- FastAPI application router entrypoint
├── database.py                  <-- SQLAlchemy database configuration & SessionLocal generator
├── models.py                    <-- SQLAlchemy ORM models matching database tables
├── schemas.py                   <-- Pydantic models for request/response serialization
├── crud.py                      <-- Database queries (joins, split payments & validations)
├── seed_db.py                   <-- SQLite database creator and auto-seeder utility
├── findings.md                  <-- Task 1: Complete Data & Calculations Findings Report
├── README.md                    <-- Setup guide, API endpoints, and running instructions (This file)
├── requirements.txt             <-- Python package dependencies
└── database/
    └── schema.sql               <-- Unified database table structures and pre-seeded data SQL
```

---

## ⚡ Setup & Execution Guide

To set up the database and run the FastAPI backend server on your PC:

### 1. Install Dependencies
Open a terminal (Command Prompt or PowerShell) inside this project directory and install the packages:
```cmd
pip install -r requirements.txt
```

### 2. Auto-Seed the Local SQLite Database
Run the pre-configured database seeder. This will automatically read `database/schema.sql`, create a local SQLite database file named **`restaurant_pos.db`**, and seed all categories, menu options, 53 orders, and 17 payments instantly:
```cmd
python seed_db.py
```

### 3. Launch the API Server
Start the Uvicorn dev server:
```cmd
uvicorn main:app --reload
```
*The server will boot and run on `http://127.0.0.1:8000`.*

---

## 🔍 API Testing & Verification

Once the server is running, you can verify the results via:

### A. Auto-Generated Swagger Documentation
Open your web browser and go to:
👉 **`http://127.0.0.1:8000/docs`** (or `http://127.0.0.1:8000/redoc`)
* FastAPI provides an interactive UI where you can inspect the request/response models and test the endpoints directly from the browser.

### B. Postman or Curl
Import and query the GET endpoint to retrieve the nested JSON structure:
👉 GET **`http://127.0.0.1:8000/api/orders`**

#### Nested JSON API Response Format:
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
      ...
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

## 🔍 Task 1: Top Findings Summary (from findings.md)

1. **Item 5 Menu Mapping Error**: `Item5` belongs to Category `2` (Soft Drinks), which should map to `Menu ID = 2` (Drinks). However, its menu record is mapped to `Menu ID = 1` (Food).
2. **Missing Auto-Increment ID**: The Order History jumps from ID 39 to ID 41. **ID 40 is missing** in the transaction sequence.
3. **High Decimal Precision & Price Variations**: Transaction prices are recorded with up to 5 decimal places (e.g., `2.75636`). Actual charged prices differ significantly from base menu pricing, reflecting dynamic pricing or staff overrides.
4. **Split Bill Payment Verification**: We mathematically validated that the sum of split payments across Cash/Card checks matches the formula:
   $$\sum (\text{Total Paid}) = \text{Amount Due} + \text{Tips} - \text{Discount}$$
5. **Overpayment Discrepancy**: Order 20's items sum to **£52.2573** (Amount Due), with no tips or discounts. However, its split payment receipts sum to **£52.28**, creating a **+£0.02** (~2 cents) discrepancy.
6. **Payment ID Gap**: In the payment log, IDs **112, 113, 114, 117, and 118** are missing from the sequential transaction record.
