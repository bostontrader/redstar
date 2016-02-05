<?php  /* @var \Cake\ORM\Entity $trader */ ?>

<div id="TradersAdd">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="traders form large-9 medium-8 columns content">
        <?= $this->Form->create($trader,['action'=>'add','id'=>'TraderAddForm']) ?>
        <fieldset>
            <legend><?= __('Add Trader') ?></legend>
            <?php
                echo $this->Form->input('nickname',['id'=>'TraderNickname']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
