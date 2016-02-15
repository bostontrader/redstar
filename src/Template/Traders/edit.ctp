<?php  /* @var \Cake\ORM\Entity $trader $trader */ ?>

<div id="TradersEdit">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="traders form large-9 medium-8 columns content">
        <?= $this->Form->create($trader,['id'=>'TraderEditForm']) ?>
        <fieldset>
            <legend><?= __('Edit Trader') ?></legend>
            <?php
                echo $this->Form->input('nickname',['id'=>'TraderNickname']);
                echo $this->Form->input('acctwerx_book_id',['id'=>'TraderAcctwerxBookId','type'=>'text']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
