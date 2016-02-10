<?php
namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\BadRequestException;

/**
 * Class OrderTransactionsController
 * @package App\Controller
 *
 * Orders and OrderTransactions are two logically distinct classes in this app, which unfortunately appear
 * glommed together to the ordinary user. So we have to tread very lightly around them.  Please see
 * the docs for OrdersController for the story.
 */
class OrderTransactionsController extends AppController {

    const DNC = "That does not compute";

    // GET /orders/:order_id/order_transactions
    public function index() {

        $this->request->allowMethod(['get']);

        // 1. First read the order_id and its record.
        $order_id=$this->get_order_id($this->request->params);
        /* @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        $query="SELECT
            tradeables_have.title as have_title,
            tradeables_want.title as want_title,
            traders.nickname
            from orders
            left join traders on orders.trader_id=traders.id
            left join tradeables as tradeables_have on orders.have_id=tradeables_have.id
            left join tradeables as tradeables_want on orders.want_id=tradeables_want.id
            where orders.id=$order_id";
        $order=$connection->execute($query)->fetchAll('assoc');

        // 2. Now read the OrderTransactions for this order. Yes, yes... I know...
        // we could combine these two queries. I'll do that later.
        $this->set(
            'order_transactions', $this->OrderTransactions->find()
            ->select(['mra','have_quantity','want_quantity'])
            ->where(['order_id'=>$order_id])
            ->order(['mra'])
        );
        $this->set(compact('order','order_id','order_transactions'));
    }

    // The actions in this controller should only be accessible in the context of an order,
    // as passed by appropriate routing.
    private function get_order_id($params) {
        if (array_key_exists('order_id', $params)) return $params['order_id'];
        throw new BadRequestException(self::DNC);
    }
}
