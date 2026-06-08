import os
from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker

# Default database connection URL (falls back to SQLite local database file)
DATABASE_URL = os.getenv("DATABASE_URL", "sqlite:///./restaurant_pos.db")

# Create the SQLAlchemy engine
# For SQLite, check_same_thread is set to False to allow multi-threaded FastAPI requests
engine = create_engine(
    DATABASE_URL, 
    connect_args={"check_same_thread": False} if DATABASE_URL.startswith("sqlite") else {}
)

# Session builder
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Base class for SQLAlchemy ORM models
Base = declarative_base()

# Database session dependency for FastAPI routes
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
