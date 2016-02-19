<?php
namespace App\Test\TestCase\Controller;

use App\Test\Fixture\FixtureConstants;
//use App\Test\Fixture\TradersFixture;
//use Cake\ORM\TableRegistry;

class TradersDepositTest extends DMIntegrationTestCase {

    public $fixtures = [
        'app.tradeables',
        'app.traders'
    ];

    /** @var \App\Model\Table\TradersTable */
    //private $traders;

    /** @var \App\Test\Fixture\TradersFixture */
    //private $tradersFixture;

    public function setUp() {
        //parent::setUp();
        //$this->traders = TableRegistry::get('Traders');
        //$this->tradersFixture = new TradersFixture();
    }

    public function testGET_deposit() {

        $trader_id=FixtureConstants::traderTypical;
        $url='/traders/deposit/' . $trader_id;
        $html=$this->loginRequestResponse(null,$url);

        // 2. Ensure that the correct form exists
        /* @var \simple_html_dom_node $form */
        $form = $html->find('form#TraderDepositForm',0);
        $this->assertNotNull($form);

        // 3. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values. This includes verifying that select
        //    lists contain options.
        //
        //  The actual trader that the fields are listed on the form is hereby deemed unimportant.

        // 3.1 These are counts of the select and input fields on the form.  They
        // are presently unaccounted for.
        $unknownSelectCnt = count($form->find('select'));
        $unknownInputCnt = count($form->find('input'));

        // 3.2 Look for the hidden POST input
        if($this->lookForHiddenInput($form)) $unknownInputCnt--;

        // 3.3 Ensure that there's an input field for amount, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#DepositAmount')) $unknownInputCnt--;

        // 3.4 Ensure that there's a select field for tradeable_id, that it has no selection,
        //    and that it has the correct quantity of available choices.
        if($this->selectCheckerA($form, 'DepositTradeableId', 'tradeables')) $unknownSelectCnt--;

        // 4. Have all the input, select, and Atags been accounted for?
        $this->expectedInputsSelectsAtagsFound($unknownInputCnt, $unknownSelectCnt, $html, 'div#TradersDeposit');
    }

    public function testPOST_deposit() {

        $trader_id=FixtureConstants::traderTypical;
        $tradeable_id=FixtureConstants::tradeableTypicalA;
        $newRecord=['amount'=>'500',''=>$tradeable_id];
        $this->post('/traders/deposit/'.$trader_id, $newRecord);
        $this->assertResponseCode(302);
        $this->assertRedirect( '/traders/'.$trader_id );

        // How can we test that the transaction was successfully sent to acctwerx?
    }

}
