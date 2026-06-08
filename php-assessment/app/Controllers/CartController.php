<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CartController extends Controller
{
    // Define the 5 static items for Task 3 (Inclusive of 12.5% Tax)
    private $items = [
        1 => ['id' => 1, 'name' => 'Item 1', 'price' => 10.00, 'category' => 'Starters'],
        2 => ['id' => 2, 'name' => 'Item 2', 'price' => 7.50, 'category' => 'Starters'],
        3 => ['id' => 3, 'name' => 'Item 3', 'price' => 5.00, 'category' => 'Soft Drinks'],
        4 => ['id' => 4, 'name' => 'Item 4', 'price' => 2.50, 'category' => 'Soft Drinks'],
        5 => ['id' => 5, 'name' => 'Item 5', 'price' => 3.00, 'category' => 'Mains']
    ];

    /**
     * Show the interactive shopping cart.
     * Accessible via GET /cart
     *
     * @return string
     */
    public function index()
    {
        $session = session();
        $cart = $session->get('cart') ?? [];

        // Calculate Totals
        $totalInclTax = 0.0;
        foreach ($cart as $cartItemId => $details) {
            $totalInclTax += (float)$details['price'] * (int)$details['qty'];
        }

        // Tax Calculation (12.5% inclusive: Tax Amount = Total * (12.5 / 112.5) = Total * (1 / 9))
        $taxAmount = $totalInclTax * (12.5 / 112.5);
        $totalExclTax = $totalInclTax - $taxAmount;

        $data = [
            'available_items' => $this->items,
            'cart'            => $cart,
            'total_incl_tax'  => round($totalInclTax, 2),
            'tax_amount'      => round($taxAmount, 2),
            'total_excl_tax'  => round($totalExclTax, 2),
            'cart_count'      => array_sum(array_column($cart, 'qty'))
        ];

        return view('cart_view', $data);
    }

    /**
     * Add an item to the cart.
     * Accessible via POST/GET /cart/add/(:num)
     *
     * @param int $itemId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function add(int $itemId)
    {
        if (!isset($this->items[$itemId])) {
            return redirect()->to('/cart')->with('error', 'Invalid Item.');
        }

        $session = session();
        $cart = $session->get('cart') ?? [];

        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] += 1;
        } else {
            $cart[$itemId] = [
                'id'       => $this->items[$itemId]['id'],
                'name'     => $this->items[$itemId]['name'],
                'price'    => $this->items[$itemId]['price'],
                'category' => $this->items[$itemId]['category'],
                'qty'      => 1
            ];
        }

        $session->set('cart', $cart);
        return redirect()->to('/cart')->with('success', $this->items[$itemId]['name'] . ' added to cart.');
    }

    /**
     * Increase item quantity.
     * Accessible via GET /cart/increase/(:num)
     *
     * @param int $itemId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function increase(int $itemId)
    {
        $session = session();
        $cart = $session->get('cart') ?? [];

        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] += 1;
            $session->set('cart', $cart);
        }

        return redirect()->to('/cart');
    }

    /**
     * Decrease item quantity.
     * Accessible via GET /cart/decrease/(:num)
     *
     * @param int $itemId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function decrease(int $itemId)
    {
        $session = session();
        $cart = $session->get('cart') ?? [];

        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] -= 1;
            if ($cart[$itemId]['qty'] <= 0) {
                unset($cart[$itemId]);
            }
            $session->set('cart', $cart);
        }

        return redirect()->to('/cart');
    }

    /**
     * Remove item from cart.
     * Accessible via GET /cart/remove/(:num)
     *
     * @param int $itemId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function remove(int $itemId)
    {
        $session = session();
        $cart = $session->get('cart') ?? [];

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            $session->set('cart', $cart);
        }

        return redirect()->to('/cart')->with('success', 'Item removed from cart.');
    }

    /**
     * Clear the cart.
     * Accessible via GET /cart/clear
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function clear()
    {
        $session = session();
        $session->remove('cart');
        return redirect()->to('/cart')->with('success', 'Cart cleared.');
    }
}
