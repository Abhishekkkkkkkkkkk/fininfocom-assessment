from sqlalchemy.orm import Session
import models

def get_unique_orders(db: Session):
    """
    Retrieves all unique order IDs with dates and statuses from the order_history transaction table.
    """
    return db.query(
        models.OrderHistory.order_id,
        models.OrderHistory.order_date,
        models.OrderHistory.order_status
    ).distinct().all()

def get_order_details(db: Session, order_id: int, order_date: str, order_status: str):
    """
    Retrieves full itemized order lines and split payment transactions for a given order,
    along with payment verification checks.
    """
    # 1. Fetch itemized order details (joins order_history with menu, categories, and menu_names)
    items_query = db.query(
        models.OrderHistory.id,
        models.OrderHistory.item_id,
        models.OrderHistory.size,
        models.OrderHistory.price,
        models.OrderHistory.qty,
        models.OrderHistory.total,
        models.Menu.item_name,
        models.Category.category_name,
        models.MenuName.menu_name
    ).join(
        models.Menu, models.Menu.item_id == models.OrderHistory.item_id
    ).join(
        models.Category, models.Category.cat_id == models.Menu.cat_id
    ).join(
        models.MenuName, models.MenuName.menu_id == models.Menu.menu_id
    ).where(
        models.OrderHistory.order_id == order_id
    ).all()

    calculated_items_total = 0.0
    items_list = []
    for item in items_query:
        item_total = float(item.total)
        calculated_items_total += item_total
        items_list.append({
            "id": item.id,
            "item_id": item.item_id,
            "item_name": item.item_name,
            "category_name": item.category_name,
            "menu_name": item.menu_name,
            "size": item.size if item.size else None,
            "price": float(item.price),
            "qty": item.qty,
            "total": item_total
        })

    # 2. Fetch split payment details
    payments_query = db.query(models.Payment).where(models.Payment.order_id == order_id).all()
    
    sum_total_paid = 0.0
    sum_tips = 0.0
    sum_discount = 0.0
    recorded_amount_due = 0.0

    payments_list = []
    for pay in payments_query:
        total_paid_val = float(pay.total_paid)
        tips_val = float(pay.tips)
        discount_val = float(pay.discount)
        sum_total_paid += total_paid_val
        sum_tips += tips_val
        sum_discount += discount_val
        # Amount due is identical across split records for the same order ID
        recorded_amount_due = float(pay.amount_due)

        payments_list.append({
            "payment_id": pay.payment_id,
            "payment_date": pay.payment_date,
            "amount_due": float(pay.amount_due),
            "tips": tips_val,
            "discount": discount_val,
            "total_paid": total_paid_val,
            "payment_type": pay.payment_type,
            "payment_status": pay.payment_status
        })

    # 3. Mathematical Validation
    # Formula: Total Paid = Amount Due + Tips - Discount
    expected_total_paid = recorded_amount_due + sum_tips - sum_discount
    discrepancy = sum_total_paid - expected_total_paid
    
    # Rounded values for discrepancy matching
    rounded_discrepancy = round(sum_total_paid - round(expected_total_paid, 2), 2)
    validation_status = "Match" if abs(rounded_discrepancy) < 0.01 else "Discrepancy"

    return {
        "order_id": order_id,
        "order_date": order_date,
        "order_status": order_status,
        "calculated_items_total": round(calculated_items_total, 5),
        "recorded_amount_due": round(recorded_amount_due, 5),
        "payment_summary": {
            "total_paid": round(sum_total_paid, 5),
            "total_tips": round(sum_tips, 5),
            "total_discount": round(sum_discount, 5)
        },
        "verification": {
            "formula": "Sum(Total Paid) = Amount Due + Tips - Discount",
            "expected_total_paid": round(expected_total_paid, 5),
            "actual_total_paid": round(sum_total_paid, 5),
            "discrepancy": round(discrepancy, 5),
            "rounded_discrepancy": round(rounded_discrepancy, 2),
            "status": validation_status
        },
        "items": items_list,
        "payments": payments_list
    }
