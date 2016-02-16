<?php
namespace App\Test\TestCase\Controller;

use App\Test\Fixture\FixtureConstants;
use App\Test\Fixture\TradersFixture;
use Cake\ORM\TableRegistry;

class TradersControllerTest extends DMIntegrationTestCase {

    public $fixtures = [
        'app.traders'
    ];

    /** @var \App\Model\Table\TradersTable */
    private $traders;

    /** @var \App\Test\Fixture\TradersFixture */
    private $tradersFixture;

    public function setUp() {
        //parent::setUp();
        $this->traders = TableRegistry::get('Traders');
        $this->tradersFixture = new TradersFixture();
    }

    public function testGET_add() {

        // 1. GET the url and parse the response.
        $this->get('/traders/add');
        $this->assertResponseCode(200);
        $this->assertNoRedirect();
        $html = str_get_html($this->_response->body());

        // 2. Ensure that the correct form exists
        /* @var \simple_html_dom_node $form */
        $form = $html->find('form#TraderAddForm',0);
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

        // 3.3 Ensure that there's an input field for nickname, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#TraderNickname')) $unknownInputCnt--;

        // 3.4 Ensure that there's an input field for acctwerx_book_id, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#TraderAcctwerxBookId')) $unknownInputCnt--;

        // 4. Have all the input, select, and Atags been accounted for?
        $this->expectedInputsSelectsAtagsFound($unknownInputCnt, $unknownSelectCnt, $html, 'div#TradersAdd');
    }

    public function testPOST_add() {

        // 1. POST a suitable record to the url, observe redirection, and return the record just
        // posted, as read from the db.
        $fixtureRecord=$this->tradersFixture->newTraderRecord;
        $fromDbRecord=$this->genericPOSTAddProlog(
            null, // no login
            '/traders/add', $fixtureRecord,
            '/traders', $this->traders
        );

        // 2. Now validate that record.
        $this->assertEquals($fromDbRecord['nickname'],$fixtureRecord['nickname']);
        $this->assertEquals($fromDbRecord['acctwerx_book_id'],$fixtureRecord['acctwerx_book_id']);
    }

    //public function testDELETE() {
        //$this->deletePOST(
            //null, // no login
            //'/traders/delete/',
            //FixtureConstants::traderTypical, '/traders', $this->traders
        //);
    //}

    public function testGET_edit() {

        // 1. Obtain a record to edit, login, GET the url, and parse the response.
        $trader_id=FixtureConstants::traderTypical;
        $trader=$this->traders->get($trader_id);
        $url='/traders/edit/' . $trader_id;
        $html=$this->loginRequestResponse(null,$url);

        // 2. Ensure that the correct form exists
        /* @var \simple_html_dom_node $form */
        $form = $html->find('form#TraderEditForm',0);
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
        if($this->lookForHiddenInput($form,'_method','PUT')) $unknownInputCnt--;

        // 3.3 Ensure that there's an input field for nickname, of type text, that is correctly set
        if($this->inputCheckerA($form,'input#TraderNickname',
            $trader['nickname'])) $unknownInputCnt--;

        // 3.4 Ensure that there's an input field for acctwerx_book_id, of type text, that is correctly set
        if($this->inputCheckerA($form,'input#TraderAcctwerxBookId',
            $trader['acctwerx_book_id'])) $unknownInputCnt--;

        // 4. Have all the input, select, and Atags been accounted for?
        $this->expectedInputsSelectsAtagsFound($unknownInputCnt, $unknownSelectCnt, $html, 'div#TradersEdit');
    }

    public function testPOST_edit() {

        // 1. POST a suitable record to the url, observe the redirect, and return the record just
        // posted, as read from the db.
        $trader_id=FixtureConstants::traderTypical;
        $fixtureRecord=$this->tradersFixture->newTraderRecord;
        $fromDbRecord=$this->genericEditPutProlog(
            null, // no login
            '/traders/edit',
            $trader_id, $fixtureRecord,
            '/traders', $this->traders
        );

        // 2. Now validate that record.
        $this->assertEquals($fromDbRecord['nickname'],$fixtureRecord['nickname']);
        $this->assertEquals($fromDbRecord['acctwerx_book_id'],$fixtureRecord['acctwerx_book_id']);
    }

    public function testGET_index() {

        /* @var \simple_html_dom_node $content */
        /* @var \simple_html_dom_node $htmlRow */
        /* @var \simple_html_dom_node $table */
        /* @var \simple_html_dom_node $tbody */
        /* @var \simple_html_dom_node $td */
        /* @var \simple_html_dom_node $thead */

        // 1. Login, GET the url, observe the response, parse the response and send it back.
        $html=$this->loginRequestResponse(null,'/traders'); // no login

        // 2. Get the count of all <A> tags that are presently unaccounted for.
        $content = $html->find('div#TradersIndex',0);
        $this->assertNotNull($content);
        $unknownATag = count($content->find('a'));

        // 3. Look for the create new trader link
        $this->assertEquals(1, count($html->find('a#TraderAdd')));
        $unknownATag--;

        // 4. Ensure that there is a suitably named table to display the results.
        $table = $html->find('table#TradersTable',0);
        $this->assertNotNull($table);

        // 5. Ensure that said table's thead element contains the correct
        //    headings, in the correct trader, and nothing else.
        $thead = $table->find('thead',0);
        $thead_ths = $thead->find('tr th');

        $this->assertEquals($thead_ths[0]->id, 'nickname');
        $this->assertEquals($thead_ths[1]->id, 'acctwerx_book_id');
        $this->assertEquals($thead_ths[2]->id, 'actions');
        $column_count = count($thead_ths);
        $this->assertEquals($column_count,3); // no other columns

        // 6. Ensure that the tbody section has the same
        //    quantity of rows as the count of trader records in the fixture.
        $tbody = $table->find('tbody',0);
        $tbody_rows = $tbody->find('tr');
        $this->assertEquals(count($tbody_rows), count($this->tradersFixture->records));

        // 7. Ensure that the values displayed in each row, match the values from
        //    the fixture.  The values should be presented in a particular trader
        //    with nothing else thereafter.
        $iterator = new \MultipleIterator();
        $iterator->attachIterator(new \ArrayIterator($this->tradersFixture->records));
        $iterator->attachIterator(new \ArrayIterator($tbody_rows));

        foreach ($iterator as $values) {
            $fixtureRecord = $values[0];
            $htmlRow = $values[1];
            $htmlColumns = $htmlRow->find('td');

            // 7.0 nickname
            $this->assertEquals($fixtureRecord['nickname'],  $htmlColumns[0]->plaintext);

            // 7.1 acctwerx_book_id
            $this->assertEquals($fixtureRecord['acctwerx_book_id'],  $htmlColumns[1]->plaintext);

            // 7.2 Now examine the action links
            $td = $htmlColumns[2];
            $actionLinks = $td->find('a');
            $this->assertEquals('TraderView', $actionLinks[0]->name);
            $unknownATag--;
            $this->assertEquals('TraderEdit', $actionLinks[1]->name);
            $unknownATag--;
            //$this->assertEquals('TraderDelete', $actionLinks[2]->name);
            //$unknownATag--;

            // 7.9 No other columns
            $this->assertEquals(count($htmlColumns),$column_count);
        }

        // 8. Ensure that all the <A> tags have been accounted for
        $this->assertEquals(0, $unknownATag);
    }

    public function testGET_view() {

        /* @var \simple_html_dom_node $content */
        /* @var \simple_html_dom_node $field */
        /* @var \simple_html_dom_node $table */

        // 1. Obtain a record to view, login, GET the url, parse the response and send it back.
        $trader_id=FixtureConstants::traderTypical;
        $trader=$this->traders->get($trader_id);
        $url='/traders/' . $trader_id;
        $html=$this->loginRequestResponse(null, $url); // no login

        // 2. Verify the <A> tags
        // 2.1 Get the count of all <A> tags that are presently unaccounted for.
        $content = $html->find('div#TradersView',0);
        $this->assertNotNull($content);
        $unknownATag = count($content->find('a'));

        // 2.2 Look for specific tags
        $this->assertEquals(1, count($html->find('a#TraderDeposit')));
        $unknownATag--;

        $this->assertEquals(1, count($html->find('a#TraderBalanceSheet')));
        $unknownATag--;

        // 2.3. Ensure that all the <A> tags have been accounted for
        $this->assertEquals(0, $unknownATag);

        // 3.  Look for the table that contains the view fields.
        $table = $html->find('table#TraderViewTable',0);
        $this->assertNotNull($table);

        // 4. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values.
        //
        //  The actual trader that the fields are listed is hereby deemed unimportant.

        // This is the count of the table rows that are presently unaccounted for.
        $unknownRowCnt = count($table->find('tr'));

        // 4.1 nickname
        $field = $table->find('tr#nickname td',0);
        $this->assertEquals($trader['nickname'], $field->plaintext);
        $unknownRowCnt--;

        // 4.1 acctwerx_book_id
        $field = $table->find('tr#acctwerx_book_id td',0);
        $this->assertEquals($trader['acctwerx_book_id'], $field->plaintext);
        $unknownRowCnt--;

        // 4.9 Have all the rows been accounted for?  Are there any extras?
        $this->assertEquals(0, $unknownRowCnt);
    }
}
