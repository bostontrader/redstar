<?php
namespace App\Test\TestCase\Controller;

use App\Test\Fixture\FixtureConstants;
use App\Test\Fixture\SidesFixture;
use Cake\ORM\TableRegistry;

class SidesControllerTest extends DMIntegrationTestCase {

    public $fixtures = [
        'app.transactions',
        'app.orders'
    ];

    /** @var \Cake\ORM\Table */
    private $Sides;

    /** @var \Cake\ORM\Table */
    private $Books;

    /** @var \App\Test\Fixture\SidesFixture */
    private $transactionsFixture;

    public function setUp() {
        $this->Books = TableRegistry::get('Books');
        $this->Sides = TableRegistry::get('Sides');
        $this->transactionsFixture = new transactionsFixture();
    }

    public function testGET_add() {

        /* @var \simple_html_dom_node $form */
        /* @var \simple_html_dom_node $html */
        /* @var \simple_html_dom_node $legend */

        // 1. GET the url and parse the response.
        $order_id=FixtureConstants::orderTypical;
        $this->get('/orders/'.$order_id.'/transactions/add');
        $this->assertResponseCode(200);
        $this->assertNoRedirect();
        $html = str_get_html($this->_response->body());

        // 2. Ensure that the correct form exists
        $form = $html->find('form#SideAddForm',0);
        $this->assertNotNull($form);

        // 3. Now inspect the legend of the form.
        $legend = $form->find('legend',0);
        $order=$this->Books->get($order_id);
        $this->assertContains($order['title'],$legend->innertext());

        // 4. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values. This includes verifying that select
        //    lists contain options.
        //
        //  The actual order that the fields are listed on the form is hereby deemed unimportant.

        // 4.1 These are counts of the select and input fields on the form.  They
        // are presently untransactioned for.
        $unknownSelectCnt = count($form->find('select'));
        $unknownInputCnt = count($form->find('input'));

        // 4.2 Look for the hidden POST input.
        if($this->lookForHiddenInput($form)) $unknownInputCnt--;

        // 4.3 Look for the hidden order_id input, and validate its contents.
        if($this->lookForHiddenInput($form,'order_id',$order_id)) $unknownInputCnt--;

        // 4.4 Ensure that there's an input field for note, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#SideNote')) $unknownInputCnt--;

        // 4.5 Ensure that there's an input field for datetime, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#SideDatetime')) $unknownInputCnt--;

        // 5. Have all the input, select, and Atags been transactioned for?
        $this->expectedInputsSelectsAtagsFound($unknownInputCnt, $unknownSelectCnt, $html, 'div#SidesAdd');
    }

    public function testPOST_add() {

        // 1. POST a suitable record to the url, observe redirection, and return the record just
        // posted, as read from the db.
        $fixtureRecord=$this->transactionsFixture->newSideRecord;
        $fromDbRecord=$this->genericPOSTAddProlog(
            null, // no login
            '/orders/'.FixtureConstants::orderTypical.'/transactions/add', $fixtureRecord,
            '/orders/'.FixtureConstants::orderTypical.'/transactions', $this->Sides
        );

        // 2. Now validate that record.
        $this->assertEquals($fromDbRecord['order_id'],$fixtureRecord['order_id']);
        $this->assertEquals($fromDbRecord['note'],$fixtureRecord['note']);
        $this->assertEquals($fromDbRecord['datetime'],$fixtureRecord['datetime']);
    }

    //public function testDELETE() {
        //$this->deletePOST(
            //null, // no login
            //'/transactions/delete/',
            //FixtureConstants::transactionTypical, '/transactions', $this->Sides
        //);
    //}

    public function testGET_edit() {

        /* @var \simple_html_dom_node $form */
        /* @var \simple_html_dom_node $html */
        /* @var \simple_html_dom_node $legend */

        // 1. Obtain the relevant records and verify their referential integrity.
        $transaction_id=FixtureConstants::transactionTypical;
        $transaction=$this->Sides->get($transaction_id);
        $order_id=FixtureConstants::orderTypical;
        $order=$this->Books->get($order_id);
        $this->assertEquals($transaction['order_id'],$order['id']);

        // 2. GET the url and parse the response.
        $this->get('/orders/'.$order['id'].'/transactions/edit/' . $transaction_id);
        $this->assertResponseCode(200);
        $this->assertNoRedirect();
        $html = str_get_html($this->_response->body());

        // 3. Ensure that the correct form exists
        $form = $html->find('form#SideEditForm',0);
        $this->assertNotNull($form);

        // 4. Now inspect the legend of the form.
        $legend = $form->find('legend',0);
        $this->assertContains($order['title'],$legend->innertext());

        // 5. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values. This includes verifying that select
        //    lists contain options.
        //
        //  The actual order that the fields are listed on the form is hereby deemed unimportant.

        // 5.1 These are counts of the select and input fields on the form.  They
        // are presently untransactioned for.
        $unknownSelectCnt = count($form->find('select'));
        $unknownInputCnt = count($form->find('input'));

        // 5.2 Look for the hidden POST input
        if($this->lookForHiddenInput($form,'_method','PUT')) $unknownInputCnt--;

        // 5.3 Ensure that there's an input field for note, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#SideNote', $transaction['note'])) $unknownInputCnt--;

        // 5.4 Ensure that there's an input field for datetime, of type text, that is correctly set
        if($this->inputCheckerA($form,'input#SideDatetime', $transaction['datetime'])) $unknownInputCnt--;

        // 6. Have all the input, select, and Atags been transactioned for?
        $this->expectedInputsSelectsAtagsFound($unknownInputCnt, $unknownSelectCnt, $html, 'div#SidesEdit');
    }

    public function testPOST_edit() {

        // 1. Obtain the relevant records and verify their referential integrity.
        $transaction_id=FixtureConstants::transactionTypical;
        $transactionNew=$this->transactionsFixture->newSideRecord;
        $order_id=FixtureConstants::orderTypical;
        $order=$this->Books->get($order_id);
        $this->assertEquals($transactionNew['order_id'],$order['id']);

        // 2. POST a suitable record to the url, observe the redirect, and parse the response.
        $shortUrl='/orders/'.$order_id.'/transactions';
        $this->put($shortUrl.'/'.$transaction_id, $transactionNew);
        $this->assertResponseCode(302);
        $this->assertRedirect( $shortUrl );

        // 3. Now retrieve that 1 record and validate it.
        $fromDbRecord=$this->Sides->get($transaction_id);
        $this->assertEquals($fromDbRecord['order_id'],$transactionNew['order_id']);
        $this->assertEquals($fromDbRecord['note'],$transactionNew['note']);
        $this->assertEquals($fromDbRecord['datetime'],$transactionNew['datetime']);
    }

    public function testGET_index() {

        /* @var \simple_html_dom_node $content */
        /* @var \simple_html_dom_node $header */
        /* @var \simple_html_dom_node $htmlRow */
        /* @var \simple_html_dom_node $table */
        /* @var \simple_html_dom_node $tbody */
        /* @var \simple_html_dom_node $td */
        /* @var \simple_html_dom_node $thead */

        // 1. Submit submit request, examine response, observe no redirect, and parse the response.
        $order_id=FixtureConstants::orderTypical;
        $this->get('/orders/'.$order_id.'/transactions');
        $this->assertResponseCode(200);
        $this->assertNoRedirect();
        $html=str_get_html($this->_response->body());

        // 2. Now inspect the legend of the form.
        $header=$html->find('header',0);
        $order=$this->Books->get($order_id);
        $this->assertContains($order['title'],$header->innertext());

        // 3. Get a the count of all <A> tags that are presently untransactioned for.
        $content = $html->find('div#SidesIndex',0);
        $this->assertNotNull($content);
        $unknownATag = count($content->find('a'));

        // 4. Look for the create new transaction link
        $this->assertEquals(1, count($html->find('a#SideAdd')));
        $unknownATag--;

        // 5. Ensure that there is a suitably named table to display the results.
        $table = $html->find('table#SidesTable',0);
        $this->assertNotNull($table);

        // 6. Ensure that said table's thead element contains the correct
        //    headings, in the correct order, and nothing else.
        $thead = $table->find('thead',0);
        $thead_ths = $thead->find('tr th');
        $this->assertEquals($thead_ths[0]->id, 'note');
        $this->assertEquals($thead_ths[1]->id, 'datetime');
        $this->assertEquals($thead_ths[2]->id, 'actions');
        $column_count = count($thead_ths);
        $this->assertEquals($column_count,3); // no other columns

        // 7. Ensure that the tbody section has the correct quantity of rows.
        $dbRecords=$this->Sides->find()
            ->where(['order_id'=>$order_id])
            ->order(['datetime']);
        $tbody = $table->find('tbody',0);
        $tbody_rows = $tbody->find('tr');
        $this->assertEquals(count($tbody_rows), $dbRecords->count());

        // 8. Ensure that the values displayed in each row, match the values from
        //    the fixture.  The values should be presented in a particular order
        //    with nothing else thereafter.
        $iterator = new \MultipleIterator();
        $iterator->attachIterator(new \ArrayIterator($dbRecords->execute()->fetchAll('assoc')));
        $iterator->attachIterator(new \ArrayIterator($tbody_rows));

        foreach ($iterator as $values) {
            $fixtureRecord = $values[0];
            $htmlRow = $values[1];
            $htmlColumns = $htmlRow->find('td');

            // 9.0 datetime
            $this->assertEquals($fixtureRecord['Sides__note'],  $htmlColumns[0]->plaintext);
            $this->assertEquals($fixtureRecord['Sides__datetime'],  $htmlColumns[1]->plaintext);

            // 9.1 Now examine the action links
            $td = $htmlColumns[2];
            $actionLinks = $td->find('a');
            $this->assertEquals('SideView', $actionLinks[0]->name);
            $unknownATag--;
            $this->assertEquals('SideEdit', $actionLinks[1]->name);
            $unknownATag--;
            //$this->assertEquals('SideDelete', $actionLinks[2]->name);
            //$unknownATag--;

            // 9.9 No other columns
            $this->assertEquals(count($htmlColumns),$column_count);
        }

        // 10. Ensure that all the <A> tags have been transactioned for
        $this->assertEquals(0, $unknownATag);
    }

    public function testGET_view() {

        /* @var \simple_html_dom_node $content */
        /* @var \simple_html_dom_node $field */
        /* @var \simple_html_dom_node $table */

        // 1. Obtain the relevant records and verify their referential integrity.
        $transaction_id=FixtureConstants::transactionTypical;
        $transaction=$this->Sides->get($transaction_id);
        $order_id=FixtureConstants::orderTypical;
        $order=$this->Books->get($order_id);
        $this->assertEquals($transaction['order_id'],$order['id']);

        // 2. Submit request, examine response, observe no redirect, and parse the response.
        $this->get('/orders/'.$order_id.'/transactions/'.$transaction_id);
        $this->assertResponseCode(200);
        $this->assertNoRedirect();
        $html=str_get_html($this->_response->body());

        // 3.  Look for the table that contains the view fields.
        $table = $html->find('table#SideViewTable',0);
        $this->assertNotNull($table);

        // 4. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values.
        //
        //  The actual order that the fields are listed is hereby deemed unimportant.

        // This is the count of the table rows that are presently untransactioned for.
        $unknownRowCnt = count($table->find('tr'));

        // 4.1 order_title
        $field = $table->find('tr#order_title td',0);
        $this->assertEquals($order['title'], $field->plaintext);
        $unknownRowCnt--;

        // 4.2 note
        $field = $table->find('tr#note td',0);
        $this->assertEquals($transaction['note'], $field->plaintext);
        $unknownRowCnt--;

        // 4.3 datetime
        $field = $table->find('tr#datetime td',0);
        $this->assertEquals($transaction['datetime'], $field->plaintext);
        $unknownRowCnt--;

        // 4.9 Have all the rows been transactioned for?  Are there any extras?
        $this->assertEquals(0, $unknownRowCnt);

        // 5. Examine the <A> tags on this page.  There should be zero links.
        $content = $html->find('div#SidesView',0);
        $this->assertNotNull($content);
        $links = $content->find('a');
        $this->assertEquals(0,count($links));
    }
}
