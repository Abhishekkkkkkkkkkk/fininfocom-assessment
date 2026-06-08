import sqlite3
import os

DB_FILE = "restaurant_pos.db"
SQL_FILE = os.path.join("database", "schema.sql")

def seed():
    """
    Reads database/schema.sql and seeds the SQLite database (restaurant_pos.db) locally.
    """
    if not os.path.exists(SQL_FILE):
        print(f"Error: SQL file not found at {SQL_FILE}!")
        return

    # If the SQLite database file exists, delete it to ensure a clean seed
    if os.path.exists(DB_FILE):
        print(f"Found existing database {DB_FILE}. Re-creating for a clean seed...")
        try:
            os.remove(DB_FILE)
        except Exception as e:
            print(f"Warning: Could not remove old database: {e}")

    print(f"Creating and connecting to SQLite database: {DB_FILE}...")
    conn = sqlite3.connect(DB_FILE)
    cursor = conn.cursor()

    print("Reading schema SQL file...")
    with open(SQL_FILE, "r", encoding="utf-8") as f:
        sql_content = f.read()

    # Split SQL by semicolon to isolate statements
    statements = sql_content.split(";")
    
    print("Seeding tables and pre-loaded transactions...")
    success_count = 0
    for stmt in statements:
        stmt = stmt.strip()
        if not stmt:
            continue
        
        # Strip comments
        lines = [line for line in stmt.split("\n") if not line.strip().startswith("--")]
        clean_stmt = " ".join(lines).strip()
        if not clean_stmt:
            continue
            
        try:
            cursor.execute(clean_stmt)
            success_count += 1
        except Exception as e:
            print(f"Error executing statement: {e}")
            print(f"Statement: {clean_stmt[:100]}...")

    conn.commit()
    conn.close()
    print(f"Database seeding completed successfully! {success_count} SQL statements executed.")

if __name__ == "__main__":
    seed()
