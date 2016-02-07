<?php  /* @var \Cake\ORM\Entity $order */ ?>
<div id="OrdersView">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('Sides'), ['controller' => 'sides', 'book_id' => $book->id, '_method'=>'GET'], ['id'=>'BookSides']) ?></li>
        </ul>
    </nav>
    <div class="orders view large-9 medium-8 columns content">
        <h3><?= h($order->id) ?></h3>
        <table id="OrderViewTable" class="vertical-table">
            <tr id="datetime">
                <th><?= __('Datetime') ?></th>
                <td><?= $order->datetime ?></td>
            </tr>
        </table>
    </div>
</div>
