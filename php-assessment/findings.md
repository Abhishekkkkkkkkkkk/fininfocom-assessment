# Task 1: Detailed Data Findings Report
**Fullstack Developer Assessment - PHP**

This report documents a line-by-line and mathematical analysis of the datasets provided in the **Menu**, **Order History**, and **Payments** sheets of the assessment.

---

## 1. Menu Structure & Data Mapping

The restaurant menu is designed around a three-tier hierarchy:
$$\text{Menu Names (Types)} \longrightarrow \text{Category Names} \longrightarrow \text{Item Names}$$

### Menu Names (`menu_names` table)
* `Menu ID = 1`: **Food**
* `Menu ID = 2`: **Drinks**

### Category Names (`categories` table)
* `Cat ID = 1`: **Starters** (Mapped to `Menu ID = 1` - Food)
* `Cat ID = 2`: **Soft Drinks** (Mapped to `Menu ID = 2` - Drinks)
* `Cat ID = 3`: **Mains** (Mapped to `Menu ID = 1` - Food)
* `Cat ID = 4`: **Desserts** (Mapped to `Menu ID = 2` - Drinks)
* `Cat ID = 5`: **Hot Drinks** (Mapped to `Menu ID = 2` - Drinks)

### Item Mappings & Sizes (`items` and `item_prices` tables)
There are 10 unique menu items with their respective categories, menu associations, size availability, and base prices:
* **Item 1 (Item1)**: Mapped to category `Starters` (Food). Available in **Small** and **Large** sizes.
  * *Small Base Price*: £1.50
  * *Large Base Price*: £2.50
* **Item 2 (Item2)**: Mapped to category `Starters` (Food). Single size. Base Price: £3.00.
* **Item 3 (Item3)**: Mapped to category `Soft Drinks` (Drinks). Single size. Base Price: £2.50.
* **Item 4 (Item4)**: Mapped to category `Soft Drinks` (Drinks). Single size. Base Price: £1.50.
* **Item 5 (Item5)**: Mapped to category `Soft Drinks` (Drinks). Single size. Base Price: £1.00.
* **Item 6 (Item6)**: Mapped to category `Mains` (Food). Available in **Small** and **Large** sizes.
  * *Small Base Price*: £2.50
  * *Large Base Price*: £3.60
* **Item 7 (Item7)**: Mapped to category `Mains` (Food). Single size. Base Price: £2.50.
* **Item 8 (Item8)**: Mapped to category `Desserts` (Drinks). Available in **Small** and **Large** sizes.
  * *Small Base Price*: £3.75
  * *Large Base Price*: £6.50
* **Item 9 (Item9)**: Mapped to category `Desserts` (Drinks). Single size. Base Price: £1.50.
* **Item 10 (Item10)**: Mapped to category `Hot Drinks` (Drinks). Single size. Base Price: £2.00.

### Structural Schema Anomalies Detected:
* **Item 5 Menu Mapping Error**: `Item5` belongs to `Cat ID` = 2 (`Soft Drinks`), which is logically a **Drinks** category mapped to `Menu ID = 2`. However, `Item5`'s record contains `Menu ID = 1` (Food). This represents a database mapping inconsistency.
* **Size-Price Denormalization**: The raw menu data stores multiple sizes and prices as comma-separated lists within a single row (e.g., `Small, Large` and `1.50, 2.50`). For database implementation, these must be normalized into a separate `item_prices` or `item_variants` table.

---

## 2. Order History Sheet Analysis

The order history contains **53 transaction items** spanning **11 distinct orders** (Order IDs 10 through 20) between `01 Oct 2025` and `05 Oct 2025`.

### Key Findings & Structural Anomalies:
* **Skipped ID Anomaly**: The sequence of primary keys (`ID`) in the order log skips from **ID 39** directly to **ID 41**. **ID 40 is missing**, indicating a skipped record or deleted row.
* **Price Variance/Dynamic Pricing**: The actual prices charged in the transaction log differ significantly from the base menu prices:
  * *Item 1 (Small)*: Base price is £1.50, but transactions show sales at **£3.75** (Order 10), **£2.75** & **£2.50** (Order 13), and **£2.5698** (Order 18).
  * *Item 1 (Large)*: Base price is £2.50, but transactions show sales at **£3.50** (Order 12), and **£2.75655** & **£2.7556** (Order 14).
  * *Item 6 (Large)*: Base price is £3.60, but transactions show sales at **£5.50** (Order 12), **£2.75** & **£3.015** (Order 14), and **£6.586** (Order 16).
  * *Item 8 (Small)*: Base price is £3.75, but transactions show sales at **£3.50** (Order 13), **£5.23569** (Order 18), and **£7.2514** (Order 20).
  This indicates that either:
  1. Prices fluctuate dynamically based on time/demand.
  2. Manual price overrides were performed by the staff.
  3. The menu prices represent net cost while order prices represent gross prices including service fees, dynamic packaging, or tax adjustments.
* **High Decimal Precision**: Transaction records contain prices with up to 5 decimal places (e.g., `2.75636`, `5.63982`, `2.5698`), which is non-standard for menu pricing and suggests machine-generated calculations or currency conversions.

---

## 3. Payments Sheet & Mathematical Verification

The payments table contains **17 payment records** corresponding to the **11 orders**.

### Split Payments:
A single order can have multiple payment records, illustrating a split-bill feature (e.g. paying part cash and part card). 
* **Order 11**: Paid via Cash (£10.00) + Card (£11.25) = **£21.25**
* **Order 14**: Paid via Cash (£20.00) + Card (£22.82) = **£42.82** (Amount Due: £42.8193)
* **Order 16**: Paid via Cash (£10.00) + Card (£9.76) = **£19.76** (Amount Due: £19.758)
* **Order 18**: Paid via Cash (£25.00) + Card (£3.34) = **£28.34** (Amount Due: £26.33588, Tips: £2.00, Discount: £0.00)
* **Order 19**: Paid via Cash (£50.00) + Card (£22.13) = **£72.13** (Amount Due: £72.13188)
* **Order 20**: Paid via Cash (£25.00) + Card (£27.28) = **£52.28** (Amount Due: £52.2573)

### Payment Mathematical Formula:
The payments must satisfy the following calculation:
$$\sum (\text{Total Paid}) = \text{Amount Due} + \text{Tips} - \text{Discount}$$

* **Verification of Order 12 (Single Payment)**:
  * Items Sum (Amount Due) = £17.00
  * Tips = £3.00, Discount = £4.00
  * Expected Total Paid = $17.00 + 3.00 - 4.00 = \text{\bf £16.00}$
  * Recorded Total Paid = **£16.00** (Matches exactly).
* **Verification of Order 18 (Split Payment)**:
  * Items Sum (Amount Due) = £26.33588
  * Tips = £2.00, Discount = £0.00
  * Expected Total Paid = $26.33588 + 2.00 - 0.00 = \text{\bf £28.33588}$
  * Recorded Total Paid = £25.00 (Cash) + £3.34 (Card) = **£28.34** (Matches exactly when rounded to 2 decimal places).

### Mathematical & Data Anomalies Detected:
* **Overpayment in Order 20**:
  * The sum of items (Amount Due) is **£52.2573**.
  * Tips = £0, Discount = £0.
  * The expected total paid is £52.26 (rounded).
  * However, the payments sum to £25.00 (Cash) + £27.28 (Card) = **£52.28**.
  * This is a discrepancy of **+£0.02** (+2 cents) overpayment.
* **Missing Payment IDs**:
  * The `Payment ID` column generally increments from 100 to 121. However, IDs **112, 113, 114, 117, and 118** are missing from the export, indicating that either those transactions failed or they were omitted.
* **Refunded Orders**:
  * Order 15 is marked as `Refunded` with a payment of **£5.14** (Item total £5.136, rounded).
