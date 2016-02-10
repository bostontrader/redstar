<?php  /* @var \Cake\ORM\Entity $order */ ?>

<div id="OrdersAdd">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="orders form large-9 medium-8 columns content">
        <?= $this->Form->create(null,['action'=>'add','id'=>'OrderAddForm']) ?>
        <fieldset>
            <legend><?= __('Add Order') ?></legend>
            <?php
                echo $this->Form->input('trader_id', ['id'=>'OrderTraderId', 'options' => $traders, 'empty' => '(none selected)']);
                echo $this->Form->input('have_id', ['id'=>'OrderHaveId', 'options' => $tradeables, 'empty' => '(none selected)']);
                echo $this->Form->input('have_quantity',['id'=>'OrderHaveQuantity','type'=>'text']);
                echo $this->Form->input('want_id', ['id'=>'OrderWantId', 'options' => $tradeables, 'empty' => '(none selected)']);
                echo $this->Form->input('want_quantity',['id'=>'OrderWantQuantity','type'=>'text']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
