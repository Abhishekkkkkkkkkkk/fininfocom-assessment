<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Restaurant Cart | POS Dashboard</title>
    <meta name="description" content="A premium POS checkout cart built with CodeIgniter 4, matching the exact styling of the assessment mockup.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f7f7f9;
            --panel-bg: #ffffff;
            --sidebar-bg: #f3f3f5;
            --border-color: #e4e4e7;
            --text-primary: #1e1e24;
            --text-secondary: #71717a;
            --primary: #d32f2f; /* Red button style from PDF */
            --primary-hover: #b71c1c;
            --accent-purple: #8e24aa; /* Purple checkout style from PDF */
            --accent-purple-hover: #7b1fa2;
            --success: #388e3c;
            --danger: #d32f2f;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --radius-lg: 12px;
            --radius-md: 8px;
            --radius-sm: 4px;
            --transition: all 0.2s ease-in-out;
        }

        /* Light/Dark harmony */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #121214;
                --panel-bg: #1e1e24;
                --sidebar-bg: #18181c;
                --border-color: #2e2e34;
                --text-primary: #f4f4f5;
                --text-secondary: #a1a1aa;
                --primary: #e53935;
                --primary-hover: #c62828;
                --accent-purple: #ab47bc;
                --accent-purple-hover: #9c27b0;
                --shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        header {
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            background: var(--panel-bg);
            box-shadow: var(--shadow);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 18px;
        }

        h1 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .api-link {
            padding: 8px 16px;
            background-color: transparent;
            color: var(--accent-purple);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 13px;
            transition: var(--transition);
            border: 1px solid var(--accent-purple);
        }

        .api-link:hover {
            background-color: var(--accent-purple);
            color: white;
        }

        /* Workspace Grid */
        .workspace {
            display: grid;
            grid-template-columns: 240px 1fr 380px;
            flex-grow: 1;
            width: 100%;
            min-height: calc(100vh - 70px);
        }

        @media (max-width: 1024px) {
            .workspace {
                grid-template-columns: 1fr;
            }
        }

        /* Category Sidebar navigation */
        .sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-secondary);
            margin-bottom: 12px;
            padding-left: 12px;
        }

        .category-tab {
            width: 100%;
            padding: 12px 16px;
            background: none;
            border: none;
            border-radius: var(--radius-md);
            text-align: left;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 3px solid transparent;
        }

        .category-tab:hover {
            background: rgba(0, 0, 0, 0.03);
            color: var(--accent-purple);
        }

        .category-tab.active {
            background: var(--panel-bg);
            color: var(--accent-purple);
            font-weight: 600;
            border-left: 3px solid var(--accent-purple);
            box-shadow: var(--shadow);
        }

        .category-count {
            background: rgba(0, 0, 0, 0.05);
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
        }

        /* Center Menu Grid */
        .menu-section {
            padding: 24px;
            background-color: var(--bg-color);
            overflow-y: auto;
            max-height: calc(100vh - 70px);
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }

        .menu-card {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .menu-card:hover {
            border-color: var(--accent-purple);
        }

        .item-category {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .item-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .item-tax-tag {
            font-size: 11px;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .item-price {
            font-size: 16px;
            font-weight: 700;
        }

        .add-btn {
            padding: 8px 16px;
            background-color: var(--primary); /* Red style button */
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            text-align: center;
        }

        .add-btn:hover {
            background-color: var(--primary-hover);
        }

        /* Order Summary column styling */
        .summary-panel {
            background: var(--panel-bg);
            border-left: 1px solid var(--border-color);
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 70px);
            overflow-y: auto;
        }

        .summary-title-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 700;
        }

        .clear-cart-link {
            font-size: 12px;
            color: var(--danger);
            text-decoration: none;
            font-weight: 500;
        }

        .clear-cart-link:hover {
            text-decoration: underline;
        }

        .cart-items-list {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .empty-cart-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px 0;
            color: var(--text-secondary);
            gap: 8px;
            margin: auto 0;
        }

        .cart-item-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .cart-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-item-name {
            font-weight: 600;
            font-size: 14px;
        }

        .cart-item-remove {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .cart-item-remove:hover {
            color: var(--danger);
        }

        .cart-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            background: #f1f1f3;
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .qty-btn:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .qty-val {
            width: 28px;
            text-align: center;
            font-weight: 600;
            font-size: 13px;
        }

        .cart-item-subtotal {
            font-weight: 600;
            font-size: 14px;
        }

        /* Calculations section at the bottom of Summary Panel */
        .bill-calculations {
            border-top: 1px solid var(--border-color);
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .bill-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .bill-row.total-row {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            border-top: 1px dashed var(--border-color);
            padding-top: 10px;
        }

        .checkout-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--accent-purple); /* Purple checkout button style from mockup */
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 12px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(142, 36, 170, 0.2);
        }

        .checkout-btn:hover {
            background-color: var(--accent-purple-hover);
        }

        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--panel-bg);
            border: 1px solid var(--success);
            padding: 12px 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo-area">
            <div class="logo-icon">POS</div>
            <div>
                <h1>Restaurant POS</h1>
                <p style="font-size: 11px; color: var(--text-secondary);">Task 3 - Shopping Cart</p>
            </div>
        </div>
        <a href="<?= site_url('api/orders') ?>" class="api-link" id="view-api-btn">View Orders API</a>
    </header>

    <main class="workspace">
        <!-- Sidebar categories -->
        <aside class="sidebar">
            <div class="sidebar-title">Categories</div>
            <button class="category-tab active" onclick="filterCategory('All')" id="cat-tab-all">
                <span>All Categories</span>
                <span class="category-count">5</span>
            </button>
            <button class="category-tab" onclick="filterCategory('Starters')" id="cat-tab-starters">
                <span>Starters</span>
                <span class="category-count">2</span>
            </button>
            <button class="category-tab" onclick="filterCategory('Soft Drinks')" id="cat-tab-drinks">
                <span>Soft Drinks</span>
                <span class="category-count">2</span>
            </button>
            <button class="category-tab" onclick="filterCategory('Mains')" id="cat-tab-mains">
                <span>Mains</span>
                <span class="category-count">1</span>
            </button>
        </aside>

        <!-- Menu Section -->
        <section class="menu-section">
            <h2 style="font-size: 20px; font-weight: 700; letter-spacing: -0.3px;">Menu Items</h2>
            <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">Click add to customize your cart. All prices include 12.5% Tax.</p>

            <div class="menu-grid">
                <?php foreach ($available_items as $id => $item): ?>
                    <div class="menu-card" data-category="<?= esc($item['category']) ?>" id="menu-item-<?= $id ?>">
                        <div>
                            <div class="item-category"><?= esc($item['category']) ?></div>
                            <div class="item-title"><?= esc($item['name']) ?></div>
                            <div class="item-tax-tag">Incl. 12.5% Tax</div>
                        </div>
                        <div class="card-footer">
                            <span class="item-price">£<?= number_format($item['price'], 2) ?></span>
                            <a href="<?= site_url('cart/add/' . $id) ?>" class="add-btn" id="add-btn-<?= $id ?>">Add</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Order Summary Side Panel -->
        <section class="summary-panel">
            <div class="summary-title-area">
                <span class="summary-title">Order Summary</span>
                <?php if (!empty($cart)): ?>
                    <a href="<?= site_url('cart/clear') ?>" class="clear-cart-link" id="clear-cart-btn">Clear All</a>
                <?php endif; ?>
            </div>

            <div class="cart-items-list">
                <?php if (empty($cart)): ?>
                    <div class="empty-cart-state">
                        <div style="font-size: 36px; opacity: 0.3;">🛒</div>
                        <p style="font-weight: 600; font-size: 14px;">Your cart is empty</p>
                        <p style="font-size: 12px; color: var(--text-secondary);">Click "Add" on menu items to order.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($cart as $id => $details): ?>
                        <div class="cart-item-row" id="cart-row-<?= $id ?>">
                            <div class="cart-item-header">
                                <span class="cart-item-name"><?= esc($details['name']) ?></span>
                                <a href="<?= site_url('cart/remove/' . $id) ?>" class="cart-item-remove" id="remove-btn-<?= $id ?>">&times;</a>
                            </div>
                            <div class="cart-item-footer">
                                <div class="qty-controls">
                                    <a href="<?= site_url('cart/decrease/' . $id) ?>" class="qty-btn" id="dec-btn-<?= $id ?>">-</a>
                                    <span class="qty-val" id="qty-val-<?= $id ?>"><?= $details['qty'] ?></span>
                                    <a href="<?= site_url('cart/increase/' . $id) ?>" class="qty-btn" id="inc-btn-<?= $id ?>">+</a>
                                </div>
                                <span class="cart-item-subtotal">£<?= number_format($details['price'] * $details['qty'], 2) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Totals / Calculation Section -->
            <div class="bill-calculations">
                <div class="bill-row">
                    <span>Subtotal (Excl. Tax)</span>
                    <span id="subtotal-val">£<?= number_format($total_excl_tax, 2) ?></span>
                </div>
                <div class="bill-row">
                    <span>Tax (12.5%)</span>
                    <span id="tax-val">£<?= number_format($tax_amount, 2) ?></span>
                </div>
                <div class="bill-row total-row">
                    <span>Total (Incl. Tax)</span>
                    <span id="total-val">£<?= number_format($total_incl_tax, 2) ?></span>
                </div>

                <button class="checkout-btn" onclick="alert('Order Processed successfully!')" id="checkout-submit-btn" <?= empty($cart) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' ?>>
                    Checkout &rarr; £<?= number_format($total_incl_tax, 2) ?>
                </button>
            </div>
        </section>
    </main>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="toast-notification" id="toast-alert">
            <span style="color: var(--success); font-weight: bold;">✓</span>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-alert');
                if (toast) toast.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>

    <script>
        function filterCategory(category) {
            const tabs = document.querySelectorAll('.category-tab');
            tabs.forEach(tab => tab.classList.remove('active'));

            if (category === 'All') {
                document.getElementById('cat-tab-all').classList.add('active');
            } else if (category === 'Starters') {
                document.getElementById('cat-tab-starters').classList.add('active');
            } else if (category === 'Soft Drinks') {
                document.getElementById('cat-tab-drinks').classList.add('active');
            } else if (category === 'Mains') {
                document.getElementById('cat-tab-mains').classList.add('active');
            }

            const cards = document.querySelectorAll('.menu-card');
            cards.forEach(card => {
                if (category === 'All' || card.getAttribute('data-category') === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
