<?php
/**
 * @var int $order_id
 * @var \Cake\ORM\Table $side
 * @var \Cake\ORM\Table $tradeables
 */
?>

<div id="SidesAdd">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="sides form large-9 medium-8 columns content">
        <?= $this->Form->create($side,['id'=>'SideAddForm']) ?>
        <fieldset>
            <legend><?= __('Add Side for Order: '.$order_id) ?></legend>
            <?php
                echo $this->Form->input('order_id',['value'=>$order_id,'type'=>'hidden']);
                echo $this->Form->radio('havewant', [['value'=>1,'text'=>'have'],['value'=>-1,'text'=>'want']]);
                echo $this->Form->input('tradeable_id', ['id'=>'SideTradeableId', 'options' => $tradeables, 'empty' => '(none selected)']);
                echo $this->Form->input('quantity',['id'=>'SideQuantity','type'=>'text']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
