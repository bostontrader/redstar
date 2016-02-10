<?php
namespace App\Test\TestCase\Controller;

use App\Test\Fixture\FixtureConstants;
use App\Test\Fixture\OrdersFixture;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * Class OrdersControllerTest
 * @package App\Test\TestCase\Controller
 *
 * This test is significantly different than the ordinary CRUD testing of
 * most other Controllers.  It's basically a mix of testing the Orders and OrderTransactions
 * controller.  Please see the Test Plan for rationale.
 */
class OrdersControllerTest extends DMIntegrationTestCase {

    public $fixtures = [
        'app.orders',
        'app.order_transactions',
        'app.traders',
        'app.tradeables'
    ];

    /** @var \App\Model\Table\OrdersTable */
    private $Orders;

    /** @var \App\Test\Fixture\OrdersFixture */
    private $ordersFixture;

    public function setUp() {
        //parent::setUp();
        $this->Orders = TableRegistry::get('Orders');
        $this->ordersFixture = new OrdersFixture();
    }

    public function testGET_add() {

        /* @var \simple_html_dom_node $form */
        /* @var \simple_html_dom_node $content */

        // 1. GET the url and parse the response.
        $this->get('/orders/add');
        $this->assertResponseCode(200);
        $this->assertNoRedirect();
        $html = str_get_html($this->_response->body());

        // 2. Ensure that the correct form exists
        $form = $html->find('form#OrderAddForm',0);
        $this->assertNotNull($form);

        // 3. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values. This includes verifying that select
        //    lists contain options.
        //
        //  The actual order that the fields are listed on the form is hereby deemed unimportant.

        // 3.1 These are counts of the select and input fields on the form.  They
        // are presently unaccounted for.
        $unknownSelectCnt = count($form->find('select'));
        $unknownInputCnt = count($form->find('input'));

        // 3.2 Look for the hidden POST input
        if($this->lookForHiddenInput($form)) $unknownInputCnt--;

        // 3.3 Ensure that there's a select field for trader_id, that it has no selection,
        //    and that it has the correct quantity of available choices.
        if($this->selectCheckerA($form, 'OrderTraderId', 'traders')) $unknownSelectCnt--;

        // 3.4 Ensure that there's a select field for have_id (tradeables), that it has no selection,
        //    and that it has the correct quantity of available choices.
        if($this->selectCheckerA($form, 'OrderHaveId', 'tradeables')) $unknownSelectCnt--;

        // 3.5 Ensure that there's an input field for have_quantity, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#OrderHaveQuantity')) $unknownInputCnt--;

        // 3.6 Ensure that there's a select field for want_id (tradeables), that it has no selection,
        //    and that it has the correct quantity of available choices.
        if($this->selectCheckerA($form, 'OrderWantId', 'tradeables')) $unknownSelectCnt--;

        // 3.7 Ensure that there's an input field for want_quantity, of type text, and that it is empty
        if($this->inputCheckerA($form,'input#OrderWantQuantity')) $unknownInputCnt--;

        // 4. Have all the input, select, and Atags been accounted for?
        $this->assertEquals(0, $unknownInputCnt);
        $this->assertEquals(0, $unknownSelectCnt);

        // 4.1 Examine the <A> tags on this page.  There should be zero links.
        $content = $html->find('div#OrdersAdd',0);
        $this->assertNotNull($content);
        $links = $content->find('a');
        $this->assertEquals(0,count($links));
    }

    /**
     * The POST to /orders/add will result in a new record created in the Orders table
     * as well as an associated record in OrderTransactions.
     */
    public function testPOST_add() {

        // 1. POST a suitable record to the url, observe redirection, and return the record just
        // posted, as read from the db.
        $fixtureRecord=$this->ordersFixture->newOrderRecord;

        //$this->fakeLogin($user_id);
        $this->post('/orders/add', $fixtureRecord);
        $this->assertResponseCode(302);
        $this->assertRedirect('/orders');

        // Now retrieve the newly written record. Recall that this new Order record will also have
        // a new associated OrderTransactions record. We must also determine _which_ record is
        // the new record.  It would be rather tedious to capture the new id, and arrange to
        // deliver it back to here, so let's just read the highest id, via the 'order' clause,
        // and hope that works well enough.
        $query=new Query(ConnectionManager::get('test'),$this->Orders);
        $fromDbRecord=$query->find('all')
            ->contain('OrderTransactions')
            ->order(['id' => 'DESC'])
            ->first();

        // 2. Now validate these records.
        $this->assertEquals($fromDbRecord['trader_id'],$fixtureRecord['trader_id']);
        $this->assertEquals($fromDbRecord['have_id'],$fixtureRecord['have_id']);
        $this->assertEquals($fromDbRecord['want_id'],$fixtureRecord['want_id']);
        $this->assertEquals($fromDbRecord->order_transactions[0]['have_quantity'],$fixtureRecord['have_quantity']);
        $this->assertEquals($fromDbRecord->order_transactions[0]['want_quantity'],$fixtureRecord['want_quantity']);
    }

    // We don't want to delete an order
    //public function testDELETE() {}

    // We don't want to edit an order. We can only cancel it.
    //public function testGET_edit() {}
    //public function testPOST_edit() {}

    public function testGET_index() {

        /* @var \simple_html_dom_node $content */
        /* @var \simple_html_dom_node $htmlRow */
        /* @var \simple_html_dom_node $table */
        /* @var \simple_html_dom_node $tbody */
        /* @var \simple_html_dom_node $td */
        /* @var \simple_html_dom_node $thead */
        /* @var \Cake\Database\Connection $connection */

        // 1. Login, GET the url, observe the response, parse the response and send it back.
        $html=$this->loginRequestResponse(null,'/orders'); // no login

        // 2. Get the count of all <A> tags that are presently unaccounted for.
        $content = $html->find('div#OrdersIndex',0);
        $this->assertNotNull($content);
        $unknownATag = count($content->find('a'));

        // 3. Look for the create new order link
        $this->assertEquals(1, count($html->find('a#OrderAdd')));
        $unknownATag--;

        // 4. Ensure that there is a suitably named table to display the results.
        $table = $html->find('table#OrdersTable',0);
        $this->assertNotNull($table);

        // 5. Ensure that said table's thead element contains the correct
        //    headings, in the correct order, and nothing else.
        $thead = $table->find('thead',0);
        $thead_ths = $thead->find('tr th');

        $this->assertEquals($thead_ths[0]->id, 'order_id');
        $this->assertEquals($thead_ths[1]->id, 'mra');
        $this->assertEquals($thead_ths[2]->id, 'trader_nickname');
        $this->assertEquals($thead_ths[3]->id, 'has_title');
        $this->assertEquals($thead_ths[4]->id, 'has_quantity');
        $this->assertEquals($thead_ths[5]->id, 'wants_title');
        $this->assertEquals($thead_ths[6]->id, 'wants_quantity');
        $this->assertEquals($thead_ths[7]->id, 'actions');
        $column_count = count($thead_ths);
        $this->assertEquals($column_count,8); // no other columns

        // 6. Ensure that the tbody section has the same
        //    quantity of rows as the count of order records in the fixture.
        $tbody = $table->find('tbody',0);
        $tbody_rows = $tbody->find('tr');

        // This is essentially the same query as OrderController.index
        $connection = ConnectionManager::get('default');
        $query="SELECT
            orders.id as order_id, traders.nickname,
            max(order_transactions.mra) as mra,

            tradeables_have.title as have_title,
            sum(order_transactions.have_quantity) as hq,

            tradeables_want.title as want_title,
            sum(order_transactions.want_quantity) as wq

            from orders
            left join order_transactions on orders.id=order_transactions.order_id
            left join traders on orders.trader_id=traders.id
            left join tradeables as tradeables_have on orders.have_id=tradeables_have.id
            left join tradeables as tradeables_want on orders.want_id=tradeables_want.id
            group by order_id";
        $results=$connection->execute($query)->fetchAll('assoc');
        $this->assertEquals(count($tbody_rows), count($results));

        // 7. Ensure that the values displayed in each row, match the values from
        //    the fixture.  The values should be presented in a particular order
        //    with nothing else thereafter.
        $iterator = new \MultipleIterator();
        $iterator->attachIterator(new \ArrayIterator($results));
        $iterator->attachIterator(new \ArrayIterator($tbody_rows));

        foreach ($iterator as $values) {
            $fixtureRecord = $values[0];
            $htmlRow = $values[1];
            $htmlColumns = $htmlRow->find('td');

            $this->assertEquals($fixtureRecord['order_id'],  $htmlColumns[0]->plaintext);
            $this->assertEquals($fixtureRecord['mra'],  $htmlColumns[1]->plaintext);
            $this->assertEquals($fixtureRecord['nickname'],  $htmlColumns[2]->plaintext);
            $this->assertEquals($fixtureRecord['have_title'],  $htmlColumns[3]->plaintext);
            $this->assertEquals($fixtureRecord['hq'],  $htmlColumns[4]->plaintext);
            $this->assertEquals($fixtureRecord['want_title'],  $htmlColumns[5]->plaintext);
            $this->assertEquals($fixtureRecord['wq'],  $htmlColumns[6]->plaintext);

            // 7.1 Now examine the action links
            $td = $htmlColumns[7];
            $actionLinks = $td->find('a');
            $this->assertEquals('OrderView', $actionLinks[0]->name);
            $unknownATag--;

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
        $order_id=FixtureConstants::orderTypical;
        $order=$this->Orders->get($order_id);
        $url='/orders/' . $order_id;
        $html=$this->loginRequestResponse(null, $url); // no login

        // 2. Verify the <A> tags
        // 2.1 Get the count of all <A> tags that are presently unaccounted for.
        $content = $html->find('div#OrdersView',0);
        $this->assertNotNull($content);
        $unknownATag = count($content->find('a'));

        // 2.2 Look for specific tags
        //$this->assertEquals(1, count($html->find('a#OrderAccounts')));
        //$unknownATag--;
        //$this->assertEquals(1, count($html->find('a#OrderTransactions')));
        //$unknownATag--;

        // 2.3. Ensure that all the <A> tags have been accounted for
        $this->assertEquals(0, $unknownATag);

        // 3.  Look for the table that contains the view fields.
        $table = $html->find('table#OrderViewTable',0);
        $this->assertNotNull($table);

        // 4. Now inspect the fields on the form.  We want to know that:
        // A. The correct fields are there and no other fields.
        // B. The fields have correct values.
        //
        //  The actual order that the fields are listed is hereby deemed unimportant.

        // This is the count of the table rows that are presently unaccounted for.
        $unknownRowCnt = count($table->find('tr'));

        // 4.1 datetime
        $field = $table->find('tr#datetime td',0);
        $this->assertEquals($order['datetime'], $field->plaintext);
        $unknownRowCnt--;

        // 4.9 Have all the rows been accounted for?  Are there any extras?
        $this->assertEquals(0, $unknownRowCnt);
    }
}
