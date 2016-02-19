<?php
namespace App\Controller;
use Cake\Datasource\ConnectionManager;

class MarketController extends AppController {


    public function market() {
        $this->request->allowMethod(['get']);

        /* @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        $query="select hq, wq, wq/hq as up from (SELECT
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
            where orders.have_id=1
            group by order_id) as base
            order by up desc";

        $results=$connection->execute($query)->fetchAll('assoc');

        // Examples to stuff the display
        /*$sellSides=[
            ['hq'=>25,'wq'=>78000],
            ['hq'=>5.5,'wq'=>16600],
            ['hq'=>10,'wq'=>30000]
        ];*/
        $sellSides=$results;

        $query="select hq, wq, hq/wq as up from (SELECT
            orders.id as order_id, orders.have_id, orders.want_id, traders.nickname,
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
            where orders.have_id=5
            group by order_id) as base
            order by up desc";
        $results=$connection->execute($query)->fetchAll('assoc');

        /*$buySides=[
            ['hq'=>29.5,'wq'=>0.01],
            ['hq'=>5850,'wq'=>2],
            ['hq'=>29000,'wq'=>10]
        ];*/
        $buySides=$results;

        $this->set(compact('buySides','sellSides'));
    }
}
