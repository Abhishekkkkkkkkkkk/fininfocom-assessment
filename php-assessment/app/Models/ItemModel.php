<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table            = 'menu';
    protected $primaryKey       = 'item_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['item_id', 'item_name', 'cat_id', 'menu_id', 'size', 'price'];

    /**
     * Get item details with category name and menu type name.
     * Maps to the 'menu' table and 'menu_names' table.
     *
     * @param int|null $itemId
     * @return array|null
     */
    public function getItemDetails(?int $itemId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('menu.*, categories.category_name, menu_names.menu_name');
        $builder->join('categories', 'categories.cat_id = menu.cat_id', 'left');
        $builder->join('menu_names', 'menu_names.menu_id = menu.menu_id', 'left');

        if ($itemId !== null) {
            $builder->where('menu.item_id', $itemId);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
}
