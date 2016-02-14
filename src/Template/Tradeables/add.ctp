<?php  /* @var \Cake\ORM\Entity $tradeable */ ?>

<div id="TradeablesAdd">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="tradeables form large-9 medium-8 columns content">
        <?= $this->Form->create($tradeable,['action'=>'add','id'=>'TradeableAddForm']) ?>
        <fieldset>
            <legend><?= __('Add Tradeable') ?></legend>
            <?php
                echo $this->Form->input('symbol',['id'=>'TradeableSymbol']);
                echo $this->Form->input('title',['id'=>'TradeableTitle']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
