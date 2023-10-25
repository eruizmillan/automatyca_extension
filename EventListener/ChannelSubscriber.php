<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

namespace MauticPlugin\MauticAutomatycaBundle\EventListener;

use Mautic\ChannelBundle\ChannelEvents;
use Mautic\ChannelBundle\Event\ChannelEvent;
use Mautic\ChannelBundle\Model\MessageModel;
use Mautic\LeadBundle\Model\LeadModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\ReportBundle\Model\ReportModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ChannelSubscriber.
 */
class ChannelSubscriber implements EventSubscriberInterface
{
    /**
     * @var IntegrationHelper
     */
    protected $integrationHelper;

    /**
     * ChannelSubscriber constructor.
     *
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(IntegrationHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ChannelEvents::ADD_CHANNEL => ['onAddChannel', 90],
        ];
    }

    /**
     * @param ChannelEvent $event
     */
    public function onAddChannel(ChannelEvent $event)
    {
        $integration = $this->integrationHelper->getIntegrationObject('Automatyca');

        if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {
            $event->addChannel(
                'sms',
                [
                    MessageModel::CHANNEL_FEATURE => [
                        'campaignAction'             => 'sms.automatyca_send_text_sms',
                        'campaignDecisionsSupported' => [
                            'page.pagehit',
                            'asset.download',
                            'form.submit',
                        ],
                        'lookupFormType' => 'automatycasms_list',
                        'repository'     => 'MauticAutomatycaBundle:Sms',
                    ],
                    LeadModel::CHANNEL_FEATURE   => [],
                    ReportModel::CHANNEL_FEATURE => [
                        'table' => 'sms_messages',
                    ],
                ]
            );
        }
    }
}
