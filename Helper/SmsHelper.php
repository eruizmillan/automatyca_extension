<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

namespace MauticPlugin\MauticAutoamtycaBundle\Helper;

use Doctrine\ORM\EntityManager;
use libphonenumber\PhoneNumberFormat;
use Mautic\CoreBundle\Helper\PhoneNumberHelper;
use Mautic\LeadBundle\Entity\DoNotContact;
use Mautic\LeadBundle\Model\LeadModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticExotelSmsBundle\Model\SmsModel;

class SmsHelper
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var LeadModel
     */
    protected $leadModel;

    /**
     * @var PhoneNumberHelper
     */
    protected $phoneNumberHelper;

    /**
     * @var SmsModel
     */
    protected $smsModel;

    /**
     * @var IntegrationHelper
     */
    protected $integrationHelper;

    /**
     * SmsHelper constructor.
     *
     * @param EntityManager     $em
     * @param LeadModel         $leadModel
     * @param PhoneNumberHelper $phoneNumberHelper
     * @param SmsModel          $smsModel
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(EntityManager $em, LeadModel $leadModel, PhoneNumberHelper $phoneNumberHelper, SmsModel $smsModel, IntegrationHelper $integrationHelper)
    {
        $this->em                 = $em;
        $this->leadModel          = $leadModel;
        $this->phoneNumberHelper  = $phoneNumberHelper;
        $this->smsModel           = $smsModel;
        $this->integrationHelper  = $integrationHelper;
        $integration              = $integrationHelper->getIntegrationObject('Automatyca');
        $settings                 = $integration->getIntegrationSettings()->getFeatureSettings();
        $this->smsFrequencyNumber = $settings['frequency_number'];
    }

    public function unsubscribe($number)
    {
        $number = $this->phoneNumberHelper->format($number, PhoneNumberFormat::E164);

        /** @var \Mautic\LeadBundle\Entity\LeadRepository $repo */
        $repo = $this->em->getRepository('MauticLeadBundle:Lead');

        $args = [
            'filter' => [
                'force' => [
                    [
                        'column' => 'mobile',
                        'expr'   => 'eq',
                        'value'  => $number,
                    ],
                ],
            ],
        ];

        $leads = $repo->getEntities($args);

        if (!empty($leads)) {
            $lead = array_shift($leads);
        } else {
            // Try to find the lead based on the given phone number
            $args['filter']['force'][0]['column'] = 'phone';

            $leads = $repo->getEntities($args);

            if (!empty($leads)) {
                $lead = array_shift($leads);
            } else {
                return false;
            }
        }

        return $this->leadModel->addDncForLead($lead, 'sms', null, DoNotContact::UNSUBSCRIBED);
    }
}
