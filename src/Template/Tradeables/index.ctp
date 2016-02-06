<?php
/**
 * @var \Cake\ORM\Table $tradeables
 */
?>
<div id="TradeablesIndex">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('New Tradeable'), ['action' => 'add'], ['id'=>'TradeableAdd']) ?></li>
        </ul>
    </nav>
    <div class="tradeables index large-9 medium-8 columns content">
        <h3><?= __('Tradeables') ?></h3>
        <table id="TradeablesTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th id="title" ><?= __('Title') ?></th>
                    <th id="actions" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tradeables as $tradeable): ?>
                <tr>
                    <td><?= $tradeable->title ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', 'id'=>$tradeable->id, '_method'=>'GET'],['name'=>'TradeableView']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit',$tradeable->id],['name'=>'TradeableEdit']) ?>
                        <?php // $this->Form->postLink(__('Delete'), ['action' => 'delete',  '_method'=>'DELETE', 'id'=>$tradeable->id], ['name'=>'TradeableDelete','confirm' => __('Are you sure you want to delete # {0}?', $tradeable->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
