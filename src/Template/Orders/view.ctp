<?php  /* @var \Cake\ORM\Entity $order */ ?>
<div id="OrdersView">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('View Sides'), ['order_id' => $order->id,'controller'=>'sides','action'=>'index','_method'=>'GET'],['id'=>'OrderViewSides']) ?></li>
        </ul>
    </nav>
    <div class="orders view large-9 medium-8 columns content">
        <h3><?= h($order->id) ?></h3>
        <table id="OrderViewTable" class="vertical-table">
            <tr id="datetime">
                <th><?= __('Datetime') ?></th>
                <td><?= $order->datetime ?></td>
            </tr>
            <tr id="trader">
                <th><?= __('Trader') ?></th>
                <td><?= $order->trader->nickname ?></td>
            </tr>
        </table>
    </div>
</div>
