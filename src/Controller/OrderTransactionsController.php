<?php
namespace App\Controller;

use Cake\Network\Exception\BadRequestException;

class OrderTransactionsController extends AppController {

    //const SIDE_SAVED = "The order_transaction has been saved.";
    //const SIDE_NOT_SAVED = "The order_transaction could not be saved. Please, try again.";
    //const DNC = "That does not compute";
    //const SIDE_DELETED = "The order_transaction has been deleted.";
    //const CANNOT_DELETE_SIDE = "The order_transaction could not be deleted. Please, try again.";

    // GET | POST /orders/:order_id/order_transactions/add
    /*public function add() {
        $this->request->allowMethod(['get', 'post']);

        $order_id=$this->get_order_id($this->request->params);

        $order_transaction = $this->OrderTransactions->newEntity(['contain'=>'Orders']);
        if ($this->request->is('post')) {
            $order_transaction = $this->OrderTransactions->patchEntity($order_transaction, $this->request->data);
            if ($this->OrderTransactions->save($order_transaction)) {
                $this->Flash->success(__(self::SIDE_SAVED));
                return $this->redirect(['action'=>'index','order_id'=>$order_id,'_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::SIDE_NOT_SAVED));
            }
        }
        $tradeables = $this->OrderTransactions->Tradeables->find('list');
        $this->set(compact('order_id','order_transaction','tradeables'));
        return null;
    }

    //public function delete($id = null) {
    //$this->request->allowMethod(['post', 'delete']);
    //$order_transaction = $this->OrderTransactions->get($id);
    //if ($this->OrderTransactions->delete($order_transaction)) {
    //$this->Flash->success(__(self::SIDE_DELETED));
    //} else {
    //$this->Flash->error(__(self::CANNOT_DELETE_SIDE));
    //}
    //return $this->redirect(['action' => 'index']);
    //}

    // GET | POST /orders/:order_id/order_transactions/edit/:id
    public function edit($id = null) {
        $this->request->allowMethod(['get', 'put']);

        $order_id=$this->get_order_id($this->request->params);

        $order_transaction = $this->OrderTransactions->get($id);
        if ($this->request->is(['put'])) {
            $order_transaction = $this->OrderTransactions->patchEntity($order_transaction, $this->request->data);
            if ($this->OrderTransactions->save($order_transaction)) {
                $this->Flash->success(__(self::SIDE_SAVED));
                return $this->redirect(['action'=>'index','order_id'=>$order_id,'_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::SIDE_NOT_SAVED));
            }
        }
        $tradeables = $this->OrderTransactions->Tradeables->find('list');
        $this->set(compact('order_id','order_transaction','tradeables'));
        return null;
    }*/

    // GET /orders/:order_id/order_transactions
    public function index() {

        $order_id=$this->get_order_id($this->request->params);

        $this->request->allowMethod(['get']);
        $this->set(
            'order_transactions', $this->OrderTransactions->find()
            ->select(['mra','have_quantity','want_quantity'])
            ->contain(['Orders'])
            ->where(['order_id'=>$order_id])
            ->order(['mra'])
        );
        $this->set(compact('order_id'));
    }

    // GET /orders/:order_id/order_transactions/:id
    public function view($id = null) {

        $this->request->allowMethod(['get']);

        $order_id=$this->get_order_id($this->request->params);

        $order_transaction = $this->OrderTransactions->get($id,['contain'=>'Orders']);
        $this->set('order_transaction', $order_transaction);
        $this->set('order_id',$order_id);
    }

    // The actions in this controller should only be accessible in the context of an order,
    // as passed by appropriate routing.
    private function get_order_id($params) {
        if (array_key_exists('order_id', $params)) return $params['order_id'];
        throw new BadRequestException(self::DNC);
    }
}
