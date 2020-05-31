<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

namespace MauticPlugin\MauticAutomatycaBundle\Exception;

class MissingUsernameException extends \Exception
{
    protected $message = 'Missing SMS Username';
}
