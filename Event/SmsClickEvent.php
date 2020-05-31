<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

namespace MauticPlugin\MauticAutomatycaBundle\Event;

use Mautic\CoreBundle\Event\CommonEvent;
use MauticPlugin\MauticAutomatycaBundle\Entity\Sms;
use MauticPlugin\MauticAutomatycaBundle\Entity\Stat;

/**
 * Class SmsClickEvent.
 */
class SmsClickEvent extends CommonEvent
{
    private $request;

    private $sms;

    /**
     * @param Stat $stat
     * @param $request
     */
    public function __construct(Stat $stat, $request)
    {
        $this->entity  = $stat;
        $this->sms     = $stat->getSms();
        $this->request = $request;
    }

    /**
     * Returns the Sms entity.
     *
     * @return Sms
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * Get sms request.
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Stat
     */
    public function getStat()
    {
        return $this->entity;
    }
}
