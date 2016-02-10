<?php
namespace App\Controller;
use Cake\Datasource\ConnectionManager;

/**
 * Class OrdersController
 * @package App\Controller
 *
 * Orders and OrderTransactions are two logically distinct classes in this app, which unfortunately appear
 * glommed together to the ordinary user. So we have to tread very lightly around them.
 *
 * An Order is a declaration of 'haves' and 'wants' by a particular trader.  For example, the trader
 * may _have_ dilithium crystals and _want_ quatloos. But it's the job of an OrderTransaction to record
 * the date and quantities. At anytime, the actual quantities of tradeables that are had and wanted,
 * are the sums of said amounts as recorded in the OrderTransactions.
 *
 * This may seem like a lot of trouble, but please realize that an Order may be partially fulfilled,
 * so we need some means of recording any number of 'OrderTransactions' that apply.  So we declare that
 * Orders haveMany OrderTransactions and that OrderTransactions belongTo Orders. We can nest the routing
 * as well, so that we can have urls such as /orders/3/order_transactions (to list all OrderTransactions
 * for a particular Order.
 */
class OrdersController extends AppController {

    const ORDER_SAVED = "The order has been saved.";
    const ORDER_NOT_SAVED = "The order could not be saved. Please, try again.";
    //const ORDER_DELETED = "The order has been deleted.";
    //const CANNOT_DELETE_ORDER = "The order could not be deleted. Please, try again.";

    public function add() {
        $this->request->allowMethod(['get','post']);
        $order = $this->Orders->newEntity();
        if ($this->request->is('post')) {
            $order = $this->Orders->patchEntity($order, $this->request->data);
            $orderTransaction=$this->Orders->OrderTransactions->newEntity();
            $orderTransaction=$this->Orders->OrderTransactions->patchEntity($orderTransaction, $this->request->data);
            $order->order_transactions=[$orderTransaction];
            if ($this->Orders->save($order)) {
                $this->Flash->success(__(self::ORDER_SAVED));
                return $this->redirect(['controller'=>'orders','action' => 'index','_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::ORDER_NOT_SAVED));
            }
        }
        $traders = $this->Orders->Traders->find('list');
        $tradeables = $this->Orders->Tradeables->find('list');
        $this->set(compact('order','traders','tradeables'));
        return null;
    }

    // We don't want to delete orders. We can close them and not see them,
    // but we don't want to remove any record of activity.
    //public function delete($id = null) {}

    // We don't want to edit orders.  We cannot change the trader or the have and want
    // tradeables, so what's the point?  We can close an order and make another one
    // if we care.
    //public function edit($id = null) {}

    // This method lists the Orders, but it also includes aggregate info from the
    // OrderTransactions.
    public function index() {
        $this->request->allowMethod(['get']);

        /* @var \Cake\Database\Connection $connection */
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
        $this->set('orders', $results);
    }

    // We don't want to 'view' an Order here.  An Order, all by itself is too uninteresting to look
    // at, but if we want to see all the OrderTransactions, that's a job for OrderTransactions.index.
    //public function view($id = null) {}
}
