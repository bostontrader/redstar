<?php
/**
 * @var \Cake\ORM\Table $traders
 */
$this->Breadcrumb->makeTrail('Traders',$this->Html);
?>
<div id="TradersIndex">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('New Trader'), ['action' => 'add'], ['id'=>'TraderAdd']) ?></li>
        </ul>
    </nav>
    <div class="traders index large-9 medium-8 columns content">
        <h3><?= __('Traders') ?></h3>
        <table id="TradersTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th id="nickname" ><?= __('Nickname') ?></th>
                    <th id="acctwerx_book_id" ><?= __('Acctwerx Book Id') ?></th>
                    <th id="actions" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($traders as $trader): ?>
                <tr>
                    <td><?= $trader->nickname ?></td>
                    <td><?= $trader->acctwerx_book_id ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', 'id'=>$trader->id, '_method'=>'GET'],['name'=>'TraderView']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit',$trader->id],['name'=>'TraderEdit']) ?>
                        <?php // $this->Form->postLink(__('Delete'), ['action' => 'delete',  '_method'=>'DELETE', 'id'=>$trader->id], ['name'=>'TraderDelete','confirm' => __('Are you sure you want to delete # {0}?', $trader->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
