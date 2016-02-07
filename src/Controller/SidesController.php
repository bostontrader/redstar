<?php
namespace App\Controller;

use Cake\Network\Exception\BadRequestException;

class SidesController extends AppController {

    const SIDE_SAVED = "The side has been saved.";
    const SIDE_NOT_SAVED = "The side could not be saved. Please, try again.";
    const DNC = "That does not compute";
    const SIDE_DELETED = "The side has been deleted.";
    const CANNOT_DELETE_SIDE = "The side could not be deleted. Please, try again.";

    // GET | POST /orders/:order_id/sides/add
    public function add() {
        $this->request->allowMethod(['get', 'post']);

        $order_id=$this->get_order_id($this->request->params);

        $side = $this->Sides->newEntity(['contain'=>'Orders']);
        if ($this->request->is('post')) {
            $side = $this->Sides->patchEntity($side, $this->request->data);
            if ($this->Sides->save($side)) {
                $this->Flash->success(__(self::SIDE_SAVED));
                return $this->redirect(['action'=>'index','order_id'=>$order_id,'_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::SIDE_NOT_SAVED));
            }
        }
        $tradeables = $this->Sides->Tradeables->find('list');
        $this->set(compact('order_id','side','tradeables'));
        return null;
    }

    //public function delete($id = null) {
    //$this->request->allowMethod(['post', 'delete']);
    //$side = $this->Sides->get($id);
    //if ($this->Sides->delete($side)) {
    //$this->Flash->success(__(self::SIDE_DELETED));
    //} else {
    //$this->Flash->error(__(self::CANNOT_DELETE_SIDE));
    //}
    //return $this->redirect(['action' => 'index']);
    //}

    // GET | POST /orders/:order_id/sides/edit/:id
    public function edit($id = null) {
        $this->request->allowMethod(['get', 'put']);

        $order_id=$this->get_order_id($this->request->params);

        $side = $this->Sides->get($id);
        if ($this->request->is(['put'])) {
            $side = $this->Sides->patchEntity($side, $this->request->data);
            if ($this->Sides->save($side)) {
                $this->Flash->success(__(self::SIDE_SAVED));
                return $this->redirect(['action'=>'index','order_id'=>$order_id,'_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::SIDE_NOT_SAVED));
            }
        }
        $tradeables = $this->Sides->Tradeables->find('list');
        $this->set(compact('order_id','side','tradeables'));
        return null;
    }

    // GET /orders/:order_id/sides
    public function index() {

        $order_id=$this->get_order_id($this->request->params);

        $this->request->allowMethod(['get']);
        $this->set(
            'sides', $this->Sides->find()
            ->contain(['Orders','Tradeables'])
            ->where(['order_id'=>$order_id]));
        $this->set(compact('order_id'));
    }

    // GET /orders/:order_id/sides/:id
    public function view($id = null) {

        $this->request->allowMethod(['get']);

        $order_id=$this->get_order_id($this->request->params);

        $side = $this->Sides->get($id,['contain'=>'Orders']);
        $this->set('side', $side);
        $this->set('order_id',$order_id);
    }

    // The actions in this controller should only be accessible in the context of an order,
    // as passed by appropriate routing.
    private function get_order_id($params) {
        if (array_key_exists('order_id', $params)) return $params['order_id'];
        throw new BadRequestException(self::DNC);
    }
}
