<?php
namespace App\Controller;

class TradersController extends AppController {

    const TRADER_SAVED = "The trader has been saved.";
    const TRADER_NOT_SAVED = "The trader could not be saved. Please, try again.";
    const TRADER_DELETED = "The trader has been deleted.";
    const CANNOT_DELETE_TRADER = "The trader could not be deleted. Please, try again.";

    public function add() {
        $this->request->allowMethod(['get','post']);
        $trader = $this->Traders->newEntity();
        if ($this->request->is('post')) {
            $trader = $this->Traders->patchEntity($trader, $this->request->data);
            if ($this->Traders->save($trader)) {
                $this->Flash->success(__(self::TRADER_SAVED));
                return $this->redirect(['controller'=>'traders','action' => 'index','_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::TRADER_NOT_SAVED));
            }
        }
        $this->set(compact('trader'));
        return null;
    }

    //public function delete($id = null) {
        //$this->request->allowMethod(['post', 'delete']);
        //$trader = $this->Traders->get($id);
        //if ($this->Traders->delete($trader)) {
            //$this->Flash->success(__(self::TRADER_DELETED));
        //} else {
            //$this->Flash->error(__(self::CANNOT_DELETE_TRADER));
        //}
        //return $this->redirect(['action' => 'index']);
    //}

    public function edit($id = null) {
        $this->request->allowMethod(['get', 'put']);
        $trader = $this->Traders->get($id);
        if ($this->request->is(['put'])) {
            $trader = $this->Traders->patchEntity($trader, $this->request->data);
            if ($this->Traders->save($trader)) {
                $this->Flash->success(__(self::TRADER_SAVED));
                return $this->redirect(['controller'=>'traders','action' => 'index','_method'=>'GET']);
            } else {
                $this->Flash->error(__(self::TRADER_NOT_SAVED));
            }
        }
        $this->set(compact('trader'));
        return null;
    }

    public function index() {
        $this->request->allowMethod(['get']);
        $this->set('traders', $this->Traders->find());
    }

    public function view($id = null) {
        $this->request->allowMethod(['get']);
        $trader = $this->Traders->get($id);
        $this->set('trader', $trader);
    }
}
