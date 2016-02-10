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
                    <th id="quantity" ><?= __('Quantity') ?></th>
                    <th id="unit_price" ><?= __('Unit Price') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sellSides as $order): ?>
                <tr>
                    <td><?= $order->have_quantity ?></td>
                    <td><?= $order->want_quantity/$order->have_quantity  ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table id="SidesTable" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th id="quantity" ><?= __('Quantity') ?></th>
                <th id="unit_price" ><?= __('Unit Price') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($buySides as $order): ?>
                <tr>
                    <td><?= $order->want_quantity ?></td>
                    <td><?= $order->have_quantity/$order->want_quantity  ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
