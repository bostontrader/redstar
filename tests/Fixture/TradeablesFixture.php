<?php
namespace App\Test\Fixture;

class TradeablesFixture extends DMFixture {
    public $import = ['table' => 'tradeables'];

    // This record will be added during a test.  We don't need or want to control the id here, so omit it.
    public $newTradeableRecord = ['title' => 'Unibuck'];

    public function init() {
        $this->tableName='Tradeables';
        parent::init(); // This is where the records are loaded.
    }
}