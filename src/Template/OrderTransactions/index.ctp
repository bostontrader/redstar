<?php
/**
 * //var \Cake\ORM\Entity $order
 * //var \Cake\ORM\Table $sides
 */
?>
<div id="SidesIndex">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?php // $this->Html->link(__('New Side'), ['order_id'=>$order_id,'action'=>'add','_method'=>'GET'],['id'=>'SideAdd']) ?></li>
        </ul>
    </nav>
    <div class="sides index large-9 medium-8 columns content">
        <h4><header><?= __('Transactions for Order : '.$order_id) ?></header></h4>

        <table id="OrderViewTable" class="vertical-table">
            <tr id="trader_nickname">
                <th><?= __('Trader') ?></th>
                <td><?= $order[0]['nickname'] ?></td>
            </tr>
            <tr id="have_title">
                <th><?= __('Have') ?></th>
                <td><?= $order[0]['have_title'] ?></td>
            </tr>
            <tr id="want_title">
                <th><?= __('Want') ?></th>
                <td><?= $order[0]['want_title'] ?></td>
            </tr>
        </table>

        <table id="OrderTransactionsTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th id="mra" ><?= __('Most Recent Activity') ?></th>
                    <th id="have_quantity" ><?= __('Have Quantity') ?></th>
                    <th id="want_quantity" ><?= __('Want Quantity') ?></th>
                    <th id="actions" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_transactions as $order_transaction): ?>
                <tr>
                    <td><?= $order_transaction->mra ?></td>
                    <td><?= $order_transaction->have_quantity ?></td>
                    <td><?= $order_transaction->want_quantity ?></td>
                    <td class="actions">
                        <?php // $this->Html->link(__('View'), ['order_id'=>$order_id,'action'=>'view','id'=>$side->id,'_method'=>'GET'],['name'=>'SideView']) ?>
                        <?php // $this->Html->link(__('Edit'), ['order_id'=>$order_id,'action'=>'edit', $side->id, '_method'=>'GET'],['name'=>'SideEdit']) ?>
                        <?php //$this->Form->postLink(__('Delete'), ['action' => 'delete', $side->id], ['name'=>'SideDelete','confirm' => __('Are you sure you want to delete # {0}?', $side->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
