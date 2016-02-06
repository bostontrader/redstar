<?php
namespace App\Test\TestCase\Controller;

use App\Test\Fixture\FixtureConstants;
use App\Test\Fixture\TradeablesFixture;
use Cake\ORM\TableRegistry;

class TradeablesControllerTest extends DMIntegrationTestCase {

    public $fixtures = [
        'app.tradeables'
    ];

    /** @var \App\Model\Table\TradeablesTable */
    private $tradeables;

    /** @var \App\Test\Fixture\TradeablesFixture */
    private $tradeablesFixture;

    public function setUp() {
        //parent::setUp();
        $this->tradeables = TableRegistry::get('Tradeables');
        $this->tradeablesFixture = new TradeablesFixture();
    }

    public function testGET_add() {

        // 1. GET the url and parse the response.
        $this->get('/tradeables/add');
        $this->assertResponseCode(200);
        $this->assertNoRedirect();
        $html = str_get_html($this->_response->body());

        // 2. Ensure that the correct form exists
        /* @var \simple_html_dom_node $form */
        $form = $html->find('form#TradeableAddForm',0);
        $this->assertNotNull($form);

        // 3. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values. This includes verifying that select
        //    lists contain options.
        //
        //  The actual tradeable that the fields are listed on the form is hereby deemed unimportant.

        // 3.1 These are counts of the select and input fields on the form.  They
        // are presently unaccounted for.
        $unknownSelectCnt = count($form->find('select'));
        $unknownInputCnt = count($form->find('input'));

        // 3.2 Look for the hidden POST input
        if($this->lookForHiddenInput($form)) $unknownInputCnt--;

        // 3.3 Ensure that there's an input field for title, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#TradeableTitle')) $unknownInputCnt--;

        // 4. Have all the input, select, and Atags been accounted for?
        $this->expectedInputsSelectsAtagsFound($unknownInputCnt, $unknownSelectCnt, $html, 'div#TradeablesAdd');
    }

    public function testPOST_add() {

        // 1. POST a suitable record to the url, observe redirection, and return the record just
        // posted, as read from the db.
        $fixtureRecord=$this->tradeablesFixture->newTradeableRecord;
        $fromDbRecord=$this->genericPOSTAddProlog(
            null, // no login
            '/tradeables/add', $fixtureRecord,
            '/tradeables', $this->tradeables
        );

        // 2. Now validate that record.
        $this->assertEquals($fromDbRecord['title'],$fixtureRecord['title']);
    }

    //public function testDELETE() {
        //$this->deletePOST(
            //null, // no login
            //'/tradeables/delete/',
            //FixtureConstants::tradeableTypical, '/tradeables', $this->tradeables
        //);
    //}

    public function testGET_edit() {

        // 1. Obtain a record to edit, login, GET the url, and parse the response.
        $tradeable_id=FixtureConstants::tradeableTypical;
        $tradeable=$this->tradeables->get($tradeable_id);
        $url='/tradeables/edit/' . $tradeable_id;
        $html=$this->loginRequestResponse(null,$url);

        // 2. Ensure that the correct form exists
        /* @var \simple_html_dom_node $form */
        $form = $html->find('form#TradeableEditForm',0);
        $this->assertNotNull($form);

        // 3. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values. This includes verifying that select
        //    lists contain options.
        //
        //  The actual tradeable that the fields are listed on the form is hereby deemed unimportant.

        // 3.1 These are counts of the select and input fields on the form.  They
        // are presently unaccounted for.
        $unknownSelectCnt = count($form->find('select'));
        $unknownInputCnt = count($form->find('input'));

        // 3.2 Look for the hidden POST input
        if($this->lookForHiddenInput($form,'_method','PUT')) $unknownInputCnt--;

        // 3.3 Ensure that there's an input field for title, of type text, that is correctly set
        if($this->inputCheckerA($form,'input#TradeableTitle',
            $tradeable['title'])) $unknownInputCnt--;

        // 4. Have all the input, select, and Atags been accounted for?
        $this->expectedInputsSelectsAtagsFound($unknownInputCnt, $unknownSelectCnt, $html, 'div#TradeablesEdit');
    }

    public function testPOST_edit() {

        // 1. POST a suitable record to the url, observe the redirect, and return the record just
        // posted, as read from the db.
        $tradeable_id=FixtureConstants::tradeableTypical;
        $fixtureRecord=$this->tradeablesFixture->newTradeableRecord;
        $fromDbRecord=$this->genericEditPutProlog(
            null, // no login
            '/tradeables/edit',
            $tradeable_id, $fixtureRecord,
            '/tradeables', $this->tradeables
        );

        // 2. Now validate that record.
        $this->assertEquals($fromDbRecord['title'],$fixtureRecord['title']);
    }

    public function testGET_index() {

        /* @var \simple_html_dom_node $content */
        /* @var \simple_html_dom_node $htmlRow */
        /* @var \simple_html_dom_node $table */
        /* @var \simple_html_dom_node $tbody */
        /* @var \simple_html_dom_node $td */
        /* @var \simple_html_dom_node $thead */

        // 1. Login, GET the url, observe the response, parse the response and send it back.
        $html=$this->loginRequestResponse(null,'/tradeables'); // no login

        // 2. Get the count of all <A> tags that are presently unaccounted for.
        $content = $html->find('div#TradeablesIndex',0);
        $this->assertNotNull($content);
        $unknownATag = count($content->find('a'));

        // 3. Look for the create new tradeable link
        $this->assertEquals(1, count($html->find('a#TradeableAdd')));
        $unknownATag--;

        // 4. Ensure that there is a suitably named table to display the results.
        $table = $html->find('table#TradeablesTable',0);
        $this->assertNotNull($table);

        // 5. Ensure that said table's thead element contains the correct
        //    headings, in the correct tradeable, and nothing else.
        $thead = $table->find('thead',0);
        $thead_ths = $thead->find('tr th');

        $this->assertEquals($thead_ths[0]->id, 'title');
        $this->assertEquals($thead_ths[1]->id, 'actions');
        $column_count = count($thead_ths);
        $this->assertEquals($column_count,2); // no other columns

        // 6. Ensure that the tbody section has the same
        //    quantity of rows as the count of tradeable records in the fixture.
        $tbody = $table->find('tbody',0);
        $tbody_rows = $tbody->find('tr');
        $this->assertEquals(count($tbody_rows), count($this->tradeablesFixture->records));

        // 7. Ensure that the values displayed in each row, match the values from
        //    the fixture.  The values should be presented in a particular tradeable
        //    with nothing else thereafter.
        $iterator = new \MultipleIterator();
        $iterator->attachIterator(new \ArrayIterator($this->tradeablesFixture->records));
        $iterator->attachIterator(new \ArrayIterator($tbody_rows));

        foreach ($iterator as $values) {
            $fixtureRecord = $values[0];
            $htmlRow = $values[1];
            $htmlColumns = $htmlRow->find('td');

            // 7.0 title
            $this->assertEquals($fixtureRecord['title'],  $htmlColumns[0]->plaintext);

            // 7.1 Now examine the action links
            $td = $htmlColumns[1];
            $actionLinks = $td->find('a');
            $this->assertEquals('TradeableView', $actionLinks[0]->name);
            $unknownATag--;
            $this->assertEquals('TradeableEdit', $actionLinks[1]->name);
            $unknownATag--;
            //$this->assertEquals('TradeableDelete', $actionLinks[2]->name);
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
        $tradeable_id=FixtureConstants::tradeableTypical;
        $tradeable=$this->tradeables->get($tradeable_id);
        $url='/tradeables/' . $tradeable_id;
        $html=$this->loginRequestResponse(null, $url); // no login

        // 2. Verify the <A> tags
        // 2.1 Get the count of all <A> tags that are presently unaccounted for.
        $content = $html->find('div#TradeablesView',0);
        $this->assertNotNull($content);
        $unknownATag = count($content->find('a'));

        // 2.2 Look for specific tags
        //$this->assertEquals(1, count($html->find('a#TradeableAccounts')));
        //$unknownATag--;
        //$this->assertEquals(1, count($html->find('a#TradeableTransactions')));
        //$unknownATag--;

        // 2.3. Ensure that all the <A> tags have been accounted for
        $this->assertEquals(0, $unknownATag);

        // 3.  Look for the table that contains the view fields.
        $table = $html->find('table#TradeableViewTable',0);
        $this->assertNotNull($table);

        // 4. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values.
        //
        //  The actual tradeable that the fields are listed is hereby deemed unimportant.

        // This is the count of the table rows that are presently unaccounted for.
        $unknownRowCnt = count($table->find('tr'));

        // 4.1 title
        $field = $table->find('tr#title td',0);
        $this->assertEquals($tradeable['title'], $field->plaintext);
        $unknownRowCnt--;

        // 4.9 Have all the rows been accounted for?  Are there any extras?
        $this->assertEquals(0, $unknownRowCnt);
    }
}
