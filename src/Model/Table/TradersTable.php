<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TradersTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->hasMany('Orders');
        $this->displayField('nickname');
    }
}
