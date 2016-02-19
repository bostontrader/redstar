<?php
/* //var \Cake\ORM\Entity $trader */
$this->Breadcrumb->makeTrail('Deposit',$this->Html);
?>

<div id="TradersDeposit">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="traders form large-9 medium-8 columns content">
        <?= $this->Form->create(null,['url'=>['action'=>'deposit',$trader_id],'id'=>'TraderDepositForm']) ?>
        <fieldset>
            <legend><?= __('Make Deposit') ?></legend>
            <?php
                echo $this->Form->input('amount',['id'=>'DepositAmount']);
                echo $this->Form->input('tradeable_id', ['id'=>'DepositTradeableId', 'options' => $tradeables, 'empty' => '(none selected)']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
