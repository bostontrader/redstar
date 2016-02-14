<?php  /* @var \Cake\ORM\Entity $tradeable */ ?>
<div id="TradeablesView">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="tradeables view large-9 medium-8 columns content">
        <h3><?= h($tradeable->id) ?></h3>
        <table id="TradeableViewTable" class="vertical-table">
            <tr id="symbol">
                <th><?= __('Symbol') ?></th>
                <td><?= $tradeable->symbol ?></td>
            </tr>
            <tr id="title">
                <th><?= __('Title') ?></th>
                <td><?= $tradeable->title ?></td>
            </tr>
        </table>
    </div>
</div>
