<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'sms');
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.sms.smses'));

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'templateButtons' => [
                'new' => $permissions['sms:smses:create'],
            ],
            'routeBase' => 'sms',
        ]
    )
);

?>

<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <?php echo $view->render(
        'MauticCoreBundle:Helper:list_toolbar.html.php',
        [
            'searchValue' => $searchValue,
            'searchHelp'  => 'mautic.sms.help.searchcommands',
            'searchId'    => 'sms-search',
            'action'      => $currentRoute,
            // 'filters'     => $filters // @todo
        ]
    ); ?>

    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>

