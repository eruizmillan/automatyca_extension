<?php


/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Fernando Rivas
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticAutomatycaBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AutomatycaIntegration.
 */
class AutomatycaIntegration extends AbstractIntegration
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'Automatyca';
    }

    public function getIcon()
    {
        return 'plugins/MauticAutomatycaBundle/Assets/img/logo-automatyca.jpg';
    }

    public function getSecretKeys()
    {
        return ['AUTOMATYCA_PASSWORD'];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRequiredKeyFields()
    {
        return [
            'AUTOMATYCA_USER'  => 'mautic.plugin.automatyca.automatyca_user',
            'AUTOMATYCA_PASSWORD' => 'mautic.plugin.automatyca.automatyca_password',
          /*  'sender_phone_number' => 'mautic.plugin.afilnet.sender.phone_number',*/
        ];
    }

    /**
     * @param \Mautic\PluginBundle\Integration\Form|FormBuilder $builder
     * @param array                                             $data
     * @param string                                            $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea == 'keys') {


        }
    }

    /**
     * @return array
     */
    public function getFormSettings()
    {
        return [
            'requires_callback'      => false,
            'requires_authorization' => false,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }
}

