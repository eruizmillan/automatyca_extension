<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

namespace MauticPlugin\MauticAutomatycaBundle\Event;

use Mautic\CoreBundle\Event\CommonEvent;
use MauticPlugin\MauticAutomatycaBundle\Entity\Sms;

/**
 * Class SmsEvent.
 */
class SmsEvent extends CommonEvent
{
    /**
     * @param Sms  $sms
     * @param bool $isNew
     */
    public function __construct(Sms $sms, $isNew = false)
    {
        $this->entity = $sms;
        $this->isNew  = $isNew;
    }

    /**
     * Returns the Sms entity.
     *
     * @return Sms
     */
    public function getSms()
    {
        return $this->entity;
    }

    /**
     * Sets the Sms entity.
     *
     * @param Sms $sms
     */
    public function setSms(Sms $sms)
    {
        $this->entity = $sms;
    }
}
