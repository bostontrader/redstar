<?php
namespace App\Controller;
use Cake\Datasource\ConnectionManager;

class OrdersController extends AppController {

    const ORDER_SAVED = "The order has been saved.";
    const ORDER_NOT_SAVED = "The order could not be saved. Please, try again.";
    const ORDER_DELETED = "The order has been deleted.";
    const CANNOT_DELETE_ORDER = "The order could not be deleted. Please, try again.";

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

    //public function delete($id = null) {
        //$this->request->allowMethod(['post', 'delete']);
        //$order = $this->Orders->get($id);
        //if ($this->Orders->delete($order)) {
            //$this->Flash->success(__(self::ORDER_DELETED));
        //} else {
            //$this->Flash->error(__(self::CANNOT_DELETE_ORDER));
        //}
        //return $this->redirect(['action' => 'index']);
    //}

    /*public function edit($id = null) {
        $this->request->allowMethod(['get', 'put']);
        $order = $this->Orders->get($id);
        if ($this->request->is(['put'])) {
            $order = $this->Orders->patchEntity($order, $this->request->data);
            if ($this->Orders->save($order)) {
                $this->Flash->success(__(self::ORDER_SAVED));
                return $this->redirect(['controller'=>'orders','action' => 'index','_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::ORDER_NOT_SAVED));
            }
        }
        //$traders = $this->Orders->Traders->find('list');
        //$tradeables = $this->Orders->Tradeables->find('list');
        //$this->set(compact('order','traders','tradeables'));
        //return null;


        / @var \Cake\Database\Connection $connection /
        $connection = ConnectionManager::get('default');
        $query="SELECT
            orders.id as order_id, traders.nickname,".
            //max(order_transactions.dt) as mr,

            "tradeables_have.title as have_title,
            sum(order_transactions.have_quantity) as hq,

            tradeables_want.title as want_title,
            sum(order_transactions.want_quantity) as wq

            from orders
            left join order_transactions on orders.id=order_transactions.order_id
            left join traders on orders.trader_id=traders.id
            left join tradeables as tradeables_have on orders.have_id=tradeables_have.id
            left join tradeables as tradeables_want on orders.want_id=tradeables_want.id
            where order_id=$id
            group by order_id";
        $order=$connection->execute($query)->fetchAll('assoc'); // should only be 1

        $this->set('order', $order);
    }*/

    public function index() {
        $this->request->allowMethod(['get']);

        //$query=$this->Orders->find()->contain(['OrderTransactions','Traders']);
        //$query->select([
            //'Orders.id',
            //'Traders.nickname',
            //'Tradeables.title',
            //'mr'=>$query->func()->max('dt'),
            //'wq'=>$query->func()->sum('want_quantity'),
            //'hq'=>$query->func()->sum('have_quantity')
        //]);
        //$query->group('Orders.id');
        //$n=$query->toArray();

        //SELECT
        //orders.id as order_id,
        //sum(order_transactions.have_quantity) as hq,
        //sum(order_transactions.want_quantity) as wq
        //from orders left join order_transactions on orders.id=order_transactions.order_id
        //group by order_id

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

    public function market() {
        $this->request->allowMethod(['get']);

        $sellSides=$this->Orders->find()
            ->where(['have_id'=>2])
            ->andWhere(['want_id'=>1]);

        $buySides=$this->Orders->find()
            ->where(['have_id'=>1])
            ->andWhere(['want_id'=>2]);

        $this->set(compact('buySides','sellSides'));
    }

    public function view($id = null) {
        $this->request->allowMethod(['get']);
        $order = $this->Orders->get($id,['contain'=>'Traders']);
        $this->set('order', $order);
    }
}
