<?php

/**
 * @package     Mautic
 * @copyright   2020 Mautic Contributors. All rights reserved.
 * @author      Fernando Rivas
 */

namespace MauticPlugin\MauticAutomatycaBundle\Controller;

use Mautic\CoreBundle\Controller\AjaxController as CommonAjaxController;
use Mautic\CoreBundle\Controller\AjaxLookupControllerTrait;

class AjaxController extends CommonAjaxController
{
    use AjaxLookupControllerTrait;
}
