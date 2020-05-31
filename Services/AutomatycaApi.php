<?php
/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author      Jan Kozak <galvani78@gmail.com>
 */

namespace MauticPlugin\MauticAutomatycaBundle\Services;

use Guzzle\Http\Client;
use Joomla\Http\Http;
use Mautic\CoreBundle\Exception\BadConfigurationException;
use Mautic\CoreBundle\Helper\PhoneNumberHelper;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\PageBundle\Model\TrackableModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\SmsBundle\Sms\TransportInterface;
use MauticPlugin\MauticAutomatycaBundle\Api\AbstractSmsApi;
use Monolog\Logger;
use Plivo\Exceptions\PlivoRestException;
use Plivo\RestClient;

class AutomatycaApi extends AbstractSmsApi implements TransportInterface
{
    /**
     * @var Client
     */
    //protected $client;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $originator;

    /**
     * @var IntegrationHelper
     */
    private $integrationHelper;

    /**
     * @var Http
     */
   // private $http;

    /**
     * MessageBirdApi constructor.
     *
     * @param TrackableModel $pageTrackableModel
     * @param PhoneNumberHelper $phoneNumberHelper
     * @param IntegrationHelper $integrationHelper
     * @param Logger $logger
     *
     * @param Http $http
     */
    public function __construct(TrackableModel $pageTrackableModel, PhoneNumberHelper $phoneNumberHelper, IntegrationHelper $integrationHelper, Logger $logger/*, Http $http*/)
    {
        $this->logger = $logger;
        $this->integrationHelper = $integrationHelper;
      //  $this->http = $http;
      //  $this->client = $http;
        parent::__construct($pageTrackableModel);
    }

    /**
     * @param Lead $contact
     * @param string $content
     *
     * @return bool
     */

    public function sendSms(Lead $contact, $content)
    {
     //   echo 'send sms!';
        if (!$contact->getMobile()) {
            return false;
        }

        $integration = $this->integrationHelper->getIntegrationObject('Automatyca');
        $data = $integration->getDecryptedApiKeys();
        //  $client = new RestClient($data['AFILNET_USER'], $data['AFILNET_PASSWORD']);

        $url = "www.pasarelasms.com/api/http/";
        $fields = array(
            "class" => urlencode("sms"),
            "method" => urlencode("sendsms"),
            "user" => urlencode(trim($data['AUTOMATYCA_USER'])),
            "password" => urlencode(trim($data['AUTOMATYCA_PASSWORD'])),
            "from" => urlencode($data['AUTOMATYCA_USER']),
            "to" => urlencode(str_replace(["+", " "], "", $contact->getMobile())),
            "sms" => $content,
            "scheduledatetime" => "",
            "output" => "",

        );
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://" . $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
      //  curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        if (!$result) {
            //try again, with http
            curl_setopt($ch, CURLOPT_URL, "http://" . $url);
            $result = curl_exec($ch);
        }
        $curl_error = curl_error($ch);
        curl_close($ch);
        /*var_dump($result);
        var_dump($curl_error);*/
        if ($curl_error)
            echo $curl_error;
        if (!$result)
            return false;
        return true;

    }
}