from fastapi import FastAPI, Depends, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import RedirectResponse
from sqlalchemy.orm import Session
from typing import List

import models
import schemas
import crud
from database import engine, get_db

# Create database tables if they do not exist
models.Base.metadata.create_all(bind=engine)

# Initialize FastAPI application
app = FastAPI(
    title="Restaurant POS Orders API",
    description="A secure and performant Python API built with FastAPI, listing nested orders and payments details.",
    version="1.0.0"
)

# Set up CORS middleware to allow cross-origin requests (essential for security and frontend consumption)
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["GET", "POST", "OPTIONS"],
    allow_headers=["*"],
)

@app.get("/", include_in_schema=False)
def index_redirect():
    """
    Redirects root index request to interactive Swagger Documentation UI.
    """
    return RedirectResponse(url="/docs")


@app.get("/api/orders", response_model=List[schemas.OrderResponse], summary="Retrieve all orders details")
def read_orders(db: Session = Depends(get_db)):
    """
    Exposes an API endpoint that lists all orders along with:
    - Nested itemized ordered items details.
    - Nested split payments transaction receipts.
    - Mathematical verification calculations of receipts integrity.
    """
    try:
        # 1. Fetch unique orders
        unique_orders = crud.get_unique_orders(db)
        
        response_data = []
        for order in unique_orders:
            # 2. Query and aggregate nested details for each order
            details = crud.get_order_details(
                db, 
                order_id=order.order_id, 
                order_date=order.order_date, 
                order_status=order.order_status
            )
            response_data.append(details)
            
        return response_data
    except Exception as e:
        raise HTTPException(
            status_code=500, 
            detail=f"An error occurred retrieving database transactions: {str(e)}"
        )


if __name__ == "__main__":
    import uvicorn
    # Local dev runner setup
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)
