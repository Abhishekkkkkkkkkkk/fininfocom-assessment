<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'order_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id', 
        'order_date', 
        'order_id', 
        'item_id', 
        'size', 
        'price', 
        'qty', 
        'order_status', 
        'total'
    ];

    /**
     * Get all unique orders by grouping the transaction logs.
     *
     * @return array
     */
    public function getUniqueOrders(): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('order_id, order_date, order_status');
        $builder->groupBy('order_id, order_date, order_status');
        return $builder->get()->getResultArray();
    }

    /**
     * Get all ordered items for a specific order.
     * Joins order_history with menu, categories, and menu_names.
     *
     * @param int $orderId
     * @return array
     */
    public function getOrderItems(int $orderId): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('order_history.*, menu.item_name, categories.category_name, menu_names.menu_name');
        $builder->join('menu', 'menu.item_id = order_history.item_id', 'left');
        $builder->join('categories', 'categories.cat_id = menu.cat_id', 'left');
        $builder->join('menu_names', 'menu_names.menu_id = menu.menu_id', 'left');
        $builder->where('order_history.order_id', $orderId);
        
        return $builder->get()->getResultArray();
    }
}
