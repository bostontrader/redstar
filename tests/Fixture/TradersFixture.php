<?php
namespace App\Test\Fixture;

class TradersFixture extends DMFixture {
    public $import = ['table' => 'traders'];

    // This record will be added during a test.  We don't need or want to control the id here, so omit it.
    public $newTraderRecord = ['nickname' => 'batman'];

    public function init() {
        $this->tableName='Traders';
        parent::init(); // This is where the records are loaded.
    }
}