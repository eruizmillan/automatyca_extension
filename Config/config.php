<?php
/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

return array(
    'name' => 'Automatyca',
    'description' => 'Enables the use of Automatyca text messages',
    'version' => '2.0',
    'author' => 'Mautic',

    'services' => [
        'events' => [
            'mautic.automatyca.sms.campaignbundle.subscriber' => [
                'class' => 'MauticPlugin\MauticAutomatycaBundle\EventListener\CampaignSubscriber',
                'arguments' => [
                    'mautic.helper.integration',
                    'mautic.automatyca.sms.model.sms',
                ],
            ],
            'mautic.automatyca.sms.subscriber' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\Event\AutomatycaSubscriber::class,
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
            'mautic.automatyca.sms.channel.subscriber' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\EventListener\ChannelSubscriber::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
            'mautic.automatyca.sms.message_queue.subscriber' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\EventListener\MessageQueueSubscriber::class,
                'arguments' => [
                    'mautic.automatyca.sms.model.sms',
                ],
            ],
            'mautic.automatyca.sms.stats.subscriber' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\EventListener\StatsSubscriber::class,
                'arguments' => [
                    'doctrine.orm.entity_manager',
                ],
            ],

        ],
        'forms' => [
            'mautic.automatyca.form.type.sms' => [
                'class' => 'MauticPlugin\MauticAutomatycaBundle\Form\Type\SmsType',
                'arguments' => 'mautic.factory',
                'alias' => 'automatyca',
            ],
            'mautic.automatyca.form.type.automatycaconfig' => [
                'class' => 'MauticPlugin\MauticAutomatycaBundle\Form\Type\ConfigType',
                'alias' => 'automatycasmsconfig',
            ],
            'mautic.automatyca.form.type.automatycasend_list' => [
                'class' => 'MauticPlugin\MauticAutomatycaBundle\Form\Type\SmsSendType',
                'arguments' => 'router',
                'alias' => 'automatycasend_list',
            ],
            'mautic.automatyca.form.type.automatycasms_list' => [
                'class' => 'MauticPlugin\MauticAutomatycaBundle\Form\Type\SmsListType',
                'alias' => 'automatycasms_list',
            ],
        ],
        'helpers' => [
            'mautic.automatyca.helper.sms' => [
                'class' => 'MauticPlugin\MauticAutoamtycaBundle\Helper\SmsHelper',
                'arguments' => [
                    'doctrine.orm.entity_manager',
                    'mautic.lead.model.lead',
                    'mautic.helper.phone_number',
                    'mautic.automatyca.sms.model.sms',
                    'mautic.helper.integration',
                ],
                'alias' => 'automatyca_helper',
            ],
        ],
        'other' => [
            'mautic.sms.transport.automatyca' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\Services\AutomatycaApi::class,
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.helper.phone_number',
                    'mautic.helper.integration',
                    'monolog.logger.mautic',
                    'mautic.http.client',

                ],
                'alias' => 'mautic.sms.config.transport.automatyca',
                'tag' => 'mautic.sms_transport',
                'tagArguments' => [
                    'integrationAlias' => 'Automatyca',
                ],
            ],
            'mautic.automatyca.sms.api' => [
                'class' => 'MauticPlugin\MauticAutomatycaBundle\Services\AutomatycaApi',
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.helper.phone_number',
                    'mautic.helper.integration',
                    'monolog.logger.mautic',
                ],
                'alias' => 'automatyca_api',
            ],
        ],
        'models' => [
            'mautic.automatyca.sms.model.sms' => [
                'class' => 'MauticPlugin\MauticAutomatycaBundle\Model\SmsModel',
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.lead.model.lead',
                    'mautic.channel.model.queue',
                    'mautic.automatyca.sms.api',
                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.automatyca' => [
                'class' => \MauticPlugin\MauticAutomatycaBundle\Integration\AutomatycaIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.user.model.user',
                ],
            ],
        ],
    ],
    'routes' => [
        'main' => [
            'mautic_sms_index' => [
                'path' => '/automatyca/{page}',
                'controller' => 'MauticAutomatycaBundle:Sms:index',
            ],
            'mautic_sms_action' => [
                'path' => '/automatyca/{objectAction}/{objectId}',
                'controller' => 'MauticAutomatycaBundle:Sms:execute',
            ],
            'mautic_sms_contacts' => [
                'path' => '/automatyca/view/{objectId}/contact/{page}',
                'controller' => 'MauticAutomatycaBundle:Sms:contacts',
            ],
        ],
        'public' => [
            'mautic_receive_sms' => [
                'path' => '/automatyca/receive',
                'controller' => 'MauticAutomatycaBundle:Api\SmsApi:receive',
            ],
        ],
        'api' => [
            'mautic_api_smsesstandard' => [
                'standard_entity' => true,
                'name' => 'smses',
                'path' => '/automatycasmses',
                'controller' => 'MauticAutomatycaBundle:Api\SmsApi',
            ],
        ],
    ],
    'menu' => [


        'main' => [
            'items' => [
                'mautic.sms.smses' => [
                    'route' => 'mautic_sms_index',
                    'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
                    'parent' => 'mautic.core.channels',
                    'checks' => [
                        'integration' => [
                            'Automatyca' => [
                                'enabled' => true,
                            ],
                        ],
                    ],
                    'priority' => 70,
                ],
            ],
        ],
    ],
    'parameters' => [
        'sms_enabled' => false,
        'sms_username' => null,
        'sms_password' => null,
        'sms_sending_phone_number' => null,
        'sms_frequency_number' => null,
        'sms_frequency_time' => null,
    ],
);
