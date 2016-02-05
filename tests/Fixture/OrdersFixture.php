<?php
namespace App\Test\Fixture;

class OrdersFixture extends DMFixture {
    public $import = ['table' => 'orders'];

    // This record will be added during a test.  We don't need or want to control the id here, so omit it.
    public $newOrderRecord = ['datetime' => '2016-01-15'];

    public function init() {
        $this->tableName='Orders';
        parent::init(); // This is where the records are loaded.
    }
}