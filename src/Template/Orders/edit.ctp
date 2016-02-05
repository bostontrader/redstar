<?php  /* @var \Cake\ORM\Entity $order $order */ ?>

<div id="OrdersEdit">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="orders form large-9 medium-8 columns content">
        <?= $this->Form->create($order,['id'=>'OrderEditForm']) ?>
        <fieldset>
            <legend><?= __('Edit Order') ?></legend>
            <?php
                echo $this->Form->input('datetime',['id'=>'OrderDatetime']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
