from pydantic import BaseModel
from typing import List, Optional

class ItemResponse(BaseModel):
    id: int
    item_id: int
    item_name: str
    category_name: str
    menu_name: str
    size: Optional[str] = None
    price: float
    qty: int
    total: float

    class Config:
        from_attributes = True


class PaymentResponse(BaseModel):
    payment_id: int
    payment_date: str
    amount_due: float
    tips: float
    discount: float
    total_paid: float
    payment_type: str
    payment_status: str

    class Config:
        from_attributes = True


class VerificationResponse(BaseModel):
    formula: str
    expected_total_paid: float
    actual_total_paid: float
    discrepancy: float
    rounded_discrepancy: float
    status: str


class PaymentSummary(BaseModel):
    total_paid: float
    total_tips: float
    total_discount: float


class OrderResponse(BaseModel):
    order_id: int
    order_date: str
    order_status: str
    calculated_items_total: float
    recorded_amount_due: float
    payment_summary: PaymentSummary
    verification: VerificationResponse
    items: List[ItemResponse]
    payments: List[PaymentResponse]

    class Config:
        from_attributes = True
