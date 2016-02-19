<?php
/**
 * //var \Cake\ORM\Entity $order
 * //var \Cake\ORM\Table $sides
 */
?>
<div id="SidesIndex">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="sides index large-9 medium-8 columns content">
        <h4><header><?= __('The Market ') ?></header></h4>
        <table id="SidesTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th id="quantity" ><?= __('Have Quantity') ?></th>
                    <th id="unit_price" ><?= __('Unit Price') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sellSides as $order): ?>
                <tr>
                    <td><?= $this->Number->precision($order['hq'],2); ?></td>
                    <td><?= $this->Number->precision($order['up'],3);  ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table id="SidesTable" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th id="quantity" ><?= __('Want Quantity') ?></th>
                <th id="unit_price" ><?= __('Unit Price') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($buySides as $order): ?>
                <tr>
                    <td><?= $this->Number->precision($order['wq'],2); ?></td>
                    <td><?= $this->Number->precision($order['up'],3);  ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
