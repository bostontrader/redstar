<!DOCTYPE html>
<html>
<head></head>
<body>
    <header>
        <div class="header-image">
        </div>
    </header>

    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>

                <?php if(is_null($currentUser)) { ?>
                <?php } else if($isAdmin) { ?>
                    <li><?= $this->Html->link(__('Orders'),['controller'=>'orders','_method'=>'GET']) ?></li>
                <?php } ?>

        </ul>
    </nav>

    <footer>
    </footer>
</body>
</html>
