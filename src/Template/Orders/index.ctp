<?php
/**
 * @var \Cake\ORM\Table $orders
 */
?>
<div id="OrdersIndex">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('New Order'), ['action' => 'add'], ['id'=>'OrderAdd']) ?></li>
        </ul>
    </nav>
    <div class="orders index large-9 medium-8 columns content">
        <h3><?= __('Orders') ?></h3>
        <table id="OrdersTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th id="order_id" ><?= __('Order ID') ?></th>
                    <th id="mra" ><?= __('Most Recent Activity') ?></th>
                    <th id="trader_nickname" ><?= __('Trader') ?></th>
                    <th id="has_title" ><?= __('Has') ?></th>
                    <th id="has_quantity" ><?= __('Has Quantity') ?></th>
                    <th id="wants_title" ><?= __('Wants') ?></th>
                    <th id="wants_quantity" ><?= __('Wants Quantity') ?></th>
                    <th id="actions" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= $order['mra'] ?></td>
                    <td><?= $order['nickname'] ?></td>
                    <td><?= $order['have_title'] ?></td>
                    <td><?= $order['hq'] ?></td>
                    <td><?= $order['want_title'] ?></td>
                    <td><?= $order['wq'] ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['controller'=>'OrderTransactions','order_id'=>$order['order_id'],'action'=>'index','_method'=>'GET'],['name'=>'OrderView']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
