<?php
namespace App\Test\Fixture;

class OrderTransactionsFixture extends DMFixture {
    public $import = ['table' => 'order_transactions'];

    // This record will be added during a test.  We don't need or want to control the id here, so omit it.
    public $newOrderTransactionsRecord = [
    ];

    public function init() {
        $this->tableName='OrderTransactions';
        parent::init(); // This is where the records are loaded.
    }
}