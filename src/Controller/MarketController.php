<?php
namespace App\Controller;
use Cake\Datasource\ConnectionManager;

class MarketController extends AppController {


    public function market() {
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


        $sellSides=[
            ['have_quantity'=>25,'want_quantity'=>78000],
            ['have_quantity'=>5.5,'want_quantity'=>16600],
            ['have_quantity'=>10,'want_quantity'=>30000]
        ];

        $buySides=[
            ['have_quantity'=>29.5,'want_quantity'=>0.01],
            ['have_quantity'=>5850,'want_quantity'=>2],
            ['have_quantity'=>29000,'want_quantity'=>10]
        ];

        $this->set(compact('buySides','sellSides'));
    }
}
