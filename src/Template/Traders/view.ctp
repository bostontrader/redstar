<?php
/* @var \Cake\ORM\Entity $trader */
$this->Breadcrumb->makeTrail($trader->nickname,$this->Html);
?>
<div id="TradersView">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
        </ul>
    </nav>
    <div class="traders view large-9 medium-8 columns content">
        <h3><?= h($trader->id) ?></h3>
        <table id="TraderViewTable" class="vertical-table">
            <tr id="nickname">
                <th><?= __('Nickname') ?></th>
                <td><?= $trader->nickname ?></td>
            </tr>
        </table>
    </div>
</div>
