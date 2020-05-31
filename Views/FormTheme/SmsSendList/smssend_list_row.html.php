<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */
?>

<div class="row">
    <div class="col-xs-8">
        <?php echo $view['form']->row($form['sms']); ?>
    </div>
    <div class="col-xs-4 mt-lg">
        <div class="mt-3">
            <?php echo $view['form']->row($form['newSmsButton']); ?>
        </div>
    </div>
</div>