<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id', 
        'payment_date', 
        'payment_id', 
        'order_id', 
        'amount_due', 
        'tips', 
        'discount', 
        'total_paid', 
        'payment_type', 
        'payment_status'
    ];

    /**
     * Get all payment records for a specific order.
     * Useful for orders with split payments.
     *
     * @param int $orderId
     * @return array
     */
    public function getOrderPayments(int $orderId): array
    {
        return $this->where('order_id', $orderId)->findAll();
    }
}
