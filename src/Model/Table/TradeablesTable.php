<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TradeablesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->hasMany('Sides');
    }
}
