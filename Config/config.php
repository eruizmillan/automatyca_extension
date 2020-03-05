<?php
/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

return array(
    'name' => 'Automatyca',
    'description' => 'Enables the use of Automatyca text messages',
    'version' => '1.0',
    'author' => 'Mautic',

    'services' => [
        'events' => [
            'mautic.automatyca.sms.subscriber' => [
                'class'     => \MauticPlugin\MauticAutomatycaBundle\Event\AutomatycaSubscriber::class,
                'arguments' => [
                    'mautic.core.model.auditlog',
                    'mautic.page.model.trackable',
                    'mautic.page.helper.token',
                    'mautic.asset.helper.token',
                    'mautic.helper.sms',
                    'mautic.helper.core_parameters',
                    'mautic.helper.url',
                ],
            ],

        ],
        'forms' => [
        ],
        'helpers' => [],
        'other' => [
            'mautic.sms.transport.automatyca' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\Services\AutomatycaApi::class,
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.helper.phone_number',
                    'mautic.helper.integration',
                    'monolog.logger.mautic',
                    'mautic.http.connector',

                ],
                'alias' => 'mautic.sms.config.transport.automatyca',
                'tag' => 'mautic.sms_transport',
                'tagArguments' => [
                    'integrationAlias' => 'Automatyca',
                ],
            ],
        ],
        'models' => [],
        'integrations' => [
            'mautic.integration.automatyca' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\Integration\AutomatycaIntegration::class,
            ],
        ],
    ],
    'routes' => [],
    'menu' => [],
    'parameters' => [
      //  'link_shortener_url'        => null,
    ],
);
