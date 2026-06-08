<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * Return all orders with nested split payment details and itemized order details.
     * Accessible via GET /api/orders
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        try {
            $orderModel = new OrderModel();
            $paymentModel = new PaymentModel();

            // Retrieve all unique orders from grouped transaction logs
            $orders = $orderModel->getUniqueOrders();

            $response = [];

            foreach ($orders as $order) {
                $orderId = (int)$order['order_id'];

                // 1. Fetch itemized order details
                $items = $orderModel->getOrderItems($orderId);
                $calculatedItemsTotal = 0.0;

                $formattedItems = [];
                foreach ($items as $item) {
                    $itemTotal = (float)$item['total'];
                    $calculatedItemsTotal += $itemTotal;

                    $formattedItems[] = [
                        'id'            => (int)$item['id'],
                        'item_id'       => (int)$item['item_id'],
                        'item_name'     => $item['item_name'],
                        'category_name' => $item['category_name'],
                        'menu_name'     => $item['menu_name'],
                        'size'          => $item['size'],
                        'price'         => (float)$item['price'],
                        'qty'           => (int)$item['qty'],
                        'total'         => $itemTotal
                    ];
                }

                // 2. Fetch split payment details
                $payments = $paymentModel->getOrderPayments($orderId);
                
                $sumTotalPaid = 0.0;
                $sumTips = 0.0;
                $sumDiscount = 0.0;
                $recordedAmountDue = 0.0;

                $formattedPayments = [];
                foreach ($payments as $payment) {
                    $sumTotalPaid += (float)$payment['total_paid'];
                    $sumTips += (float)$payment['tips'];
                    $sumDiscount += (float)$payment['discount'];
                    // Amount due is the same across split payments for the same order
                    $recordedAmountDue = (float)$payment['amount_due'];

                    $formattedPayments[] = [
                        'payment_id'     => (int)$payment['payment_id'],
                        'payment_date'   => $payment['payment_date'],
                        'amount_due'     => (float)$payment['amount_due'],
                        'tips'           => (float)$payment['tips'],
                        'discount'       => (float)$payment['discount'],
                        'total_paid'     => (float)$payment['total_paid'],
                        'payment_type'   => $payment['payment_type'],
                        'payment_status' => $payment['payment_status']
                    ];
                }

                // 3. Mathematical Validation & Integrity Checks
                // Formula: Total Paid = Amount Due + Tips - Discount
                $expectedTotalPaid = $recordedAmountDue + $sumTips - $sumDiscount;
                $discrepancy = round($sumTotalPaid - $expectedTotalPaid, 5);
                
                // Rounded discrepancy for 2 decimal places comparison
                $roundedDiscrepancy = round($sumTotalPaid - round($expectedTotalPaid, 2), 2);
                $validationStatus = (abs($roundedDiscrepancy) < 0.01) ? 'Match' : 'Discrepancy';

                $response[] = [
                    'order_id'             => $orderId,
                    'order_date'           => $order['order_date'],
                    'order_status'         => $order['order_status'],
                    'calculated_items_total' => round($calculatedItemsTotal, 5),
                    'recorded_amount_due'   => round($recordedAmountDue, 5),
                    'payment_summary'      => [
                        'total_paid'     => round($sumTotalPaid, 5),
                        'total_tips'     => round($sumTips, 5),
                        'total_discount' => round($sumDiscount, 5),
                    ],
                    'verification'         => [
                        'formula'              => 'Sum(Total Paid) = Amount Due + Tips - Discount',
                        'expected_total_paid'  => round($expectedTotalPaid, 5),
                        'actual_total_paid'    => round($sumTotalPaid, 5),
                        'discrepancy'          => $discrepancy,
                        'rounded_discrepancy'  => $roundedDiscrepancy,
                        'status'               => $validationStatus
                    ],
                    'items'                => $formattedItems,
                    'payments'             => $formattedPayments
                ];
            }

            // Set security and performance caching headers (Cache for 60s to optimize performance)
            return $this->respond($response)
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->setHeader('Cache-Control', 'public, max-age=60');

        } catch (\Exception $e) {
            return $this->failServerError('An error occurred retrieving order details: ' . $e->getMessage());
        }
    }
}
