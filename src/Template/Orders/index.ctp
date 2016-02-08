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
                    <th id="datetime" ><?= __('Datetime') ?></th>
                    <th id="trader_nickname" ><?= __('Trader') ?></th>
                    <th id="actions" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order->id ?></td>
                    <td><?= $order->datetime ?></td>
                    <td><?= $order->trader->nickname ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', 'id'=>$order->id, '_method'=>'GET'],['name'=>'OrderView']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit',$order->id],['name'=>'OrderEdit']) ?>
                        <?php // $this->Form->postLink(__('Delete'), ['action' => 'delete',  '_method'=>'DELETE', 'id'=>$order->id], ['name'=>'OrderDelete','confirm' => __('Are you sure you want to delete # {0}?', $order->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
