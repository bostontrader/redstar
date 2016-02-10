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
        <h4><header><?= __('Sides for Order : '.$order_id) ?></header></h4>
        <table id="SidesTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th id="havewant" ><?= __('Note') ?></th>
                    <th id="tradeable" ><?= __('Tradeable') ?></th>
                    <th id="quantity" ><?= __('Quantity') ?></th>
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
