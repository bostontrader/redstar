<?php
namespace App\Controller;

class TradeablesController extends AppController {

    const TRADEABLE_SAVED = "The tradeable has been saved.";
    const TRADEABLE_NOT_SAVED = "The tradeable could not be saved. Please, try again.";
    const TRADEABLE_DELETED = "The tradeable has been deleted.";
    const CANNOT_DELETE_TRADEABLE = "The tradeable could not be deleted. Please, try again.";

    public function add() {
        $this->request->allowMethod(['get','post']);
        $tradeable = $this->Tradeables->newEntity();
        if ($this->request->is('post')) {
            $tradeable = $this->Tradeables->patchEntity($tradeable, $this->request->data);
            if ($this->Tradeables->save($tradeable)) {
                $this->Flash->success(__(self::TRADEABLE_SAVED));
                return $this->redirect(['controller'=>'tradeables','action' => 'index','_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::TRADEABLE_NOT_SAVED));
            }
        }
        $this->set(compact('tradeable'));
        return null;
    }

    //public function delete($id = null) {
        //$this->request->allowMethod(['post', 'delete']);
        //$tradeable = $this->Tradeables->get($id);
        //if ($this->Tradeables->delete($tradeable)) {
            //$this->Flash->success(__(self::TRADEABLE_DELETED));
        //} else {
            //$this->Flash->error(__(self::CANNOT_DELETE_TRADEABLE));
        //}
        //return $this->redirect(['action' => 'index']);
    //}

    public function edit($id = null) {
        $this->request->allowMethod(['get', 'put']);
        $tradeable = $this->Tradeables->get($id);
        if ($this->request->is(['put'])) {
            $tradeable = $this->Tradeables->patchEntity($tradeable, $this->request->data);
            if ($this->Tradeables->save($tradeable)) {
                $this->Flash->success(__(self::TRADEABLE_SAVED));
                return $this->redirect(['controller'=>'tradeables','action' => 'index','_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::TRADEABLE_NOT_SAVED));
            }
        }
        $this->set(compact('tradeable'));
        return null;
    }

    public function index() {
        $this->request->allowMethod(['get']);
        $this->set('tradeables', $this->Tradeables->find());
    }

    public function view($id = null) {
        $this->request->allowMethod(['get']);
        $tradeable = $this->Tradeables->get($id);
        $this->set('tradeable', $tradeable);
    }
}
