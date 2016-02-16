<?php
/**
 * @var \Cake\ORM\Entity $trader
 * @var array $lineItems
 */
$this->Breadcrumb->makeTrail('Balance Sheet',$this->Html);
?>
<div id="TradersView">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="traders view large-9 medium-8 columns content">
        <h3><?= h(__('Balance Sheet for ').$trader->nickname) ?></h3>
        <table id="TraderBalanceTable" class="vertical-table">
            <thead>
            <tr>
                <th id="category" ><?= __('Category') ?></th>
                <th id="account" ><?= __('Account') ?></th>
                <th id="amount" ><?= __('Amount') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lineItems as $lineItem): ?>
                <tr>
                    <td><?= $lineItem['ct'] ?></td>
                    <td><?= $lineItem['at'] ?></td>
                    <td><?= $lineItem['amount'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
