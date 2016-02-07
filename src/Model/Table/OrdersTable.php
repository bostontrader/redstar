<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class OrdersTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->hasMany('Sides');
    }
}
