<?php
namespace App\Test\Fixture;

class OrdersFixture extends DMFixture {
    public $import = ['table' => 'orders'];

    /**
     * This record will be added during a test.  We don't need or want to control the id here, so omit it.
     * Realize that this record will not really fit into the Orders table, and that the Controller and
     * testing ensure and expect that this info will actually result in a new record in the Orders table
     * as well as an associated record in OrderTransactions.
     */
    public $newOrderRecord = [
        'trader_id' => FixtureConstants::traderTypical,
        'have_id' => FixtureConstants::tradeableTypicalA,
        'have_quantity' => 100,
        'want_id' => FixtureConstants::tradeableTypicalB,
        'want_quantity' => 50,
    ];

    public function init() {
        $this->tableName='Orders';
        parent::init(); // This is where the records are loaded.
    }
}
