<?php  /* @var \Cake\ORM\Entity $tradeable $tradeable */ ?>

<div id="TradeablesEdit">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="tradeables form large-9 medium-8 columns content">
        <?= $this->Form->create($tradeable,['id'=>'TradeableEditForm']) ?>
        <fieldset>
            <legend><?= __('Edit Tradeable') ?></legend>
            <?php
                echo $this->Form->input('symbol',['id'=>'TradeableSymbol']);
                echo $this->Form->input('title',['id'=>'TradeableTitle']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
