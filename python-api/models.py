from sqlalchemy import Column, Integer, String, ForeignKey
from sqlalchemy.orm import relationship
from database import Base

class MenuName(Base):
    __tablename__ = "menu_names"

    menu_id = Column(Integer, primary_key=True, index=True)
    menu_name = Column(String(50), nullable=False)

    # Relationships
    categories = relationship("Category", back_populates="menu_name")
    items = relationship("Menu", back_populates="menu_name")


class Category(Base):
    __tablename__ = "categories"

    cat_id = Column(Integer, primary_key=True, index=True)
    category_name = Column(String(100), nullable=False)
    menu_id = Column(Integer, ForeignKey("menu_names.menu_id"))

    # Relationships
    menu_name = relationship("MenuName", back_populates="categories")
    items = relationship("Menu", back_populates="category")


class Menu(Base):
    __tablename__ = "menu"

    item_id = Column(Integer, primary_key=True, index=True)
    item_name = Column(String(100), nullable=False)
    cat_id = Column(Integer, ForeignKey("categories.cat_id"))
    menu_id = Column(Integer, ForeignKey("menu_names.menu_id"))
    size = Column(String(50), nullable=True)
    price = Column(String(50), nullable=False)

    # Relationships
    category = relationship("Category", back_populates="items")
    menu_name = relationship("MenuName", back_populates="items")
    order_items = relationship("OrderHistory", back_populates="menu_item")


class OrderHistory(Base):
    __tablename__ = "order_history"

    id = Column(Integer, primary_key=True, index=True)
    order_date = Column(String(50), nullable=False)
    order_id = Column(Integer, nullable=False, index=True)
    item_id = Column(Integer, ForeignKey("menu.item_id"))
    size = Column(String(50), nullable=True)
    price = Column(String(50), nullable=False)
    qty = Column(Integer, nullable=False)
    order_status = Column(String(50), nullable=False)
    total = Column(String(50), nullable=False)

    # Relationships
    menu_item = relationship("Menu", back_populates="order_items")


class Payment(Base):
    __tablename__ = "payments"

    id = Column(Integer, primary_key=True, index=True)
    payment_date = Column(String(50), nullable=False)
    payment_id = Column(Integer, nullable=False, index=True)
    order_id = Column(Integer, nullable=False, index=True)
    amount_due = Column(String(50), nullable=False)
    tips = Column(String(50), nullable=False)
    discount = Column(String(50), nullable=False)
    total_paid = Column(String(50), nullable=False)
    payment_type = Column(String(50), nullable=False)
    payment_status = Column(String(50), nullable=False)
