<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SidesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->belongsTo('Orders');
        $this->belongsTo('Tradeables');
    }
}
