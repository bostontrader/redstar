<?php
namespace App\Controller;

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
            if ($this->Orders->save($order)) {
                $this->Flash->success(__(self::ORDER_SAVED));
                return $this->redirect(['controller'=>'orders','action' => 'index','_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::ORDER_NOT_SAVED));
            }
        }
        $this->set(compact('order'));
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

    public function edit($id = null) {
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
        $this->set(compact('order'));
        return null;
    }

    public function index() {
        $this->request->allowMethod(['get']);
        $this->set('orders', $this->Orders->find());
    }

    public function view($id = null) {
        $this->request->allowMethod(['get']);
        $order = $this->Orders->get($id);
        $this->set('order', $order);
    }
}
