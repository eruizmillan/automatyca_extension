<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticAutomatycaBundle\Event;


use Mautic\AssetBundle\Helper\TokenHelper as AssetTokenHelper;
use Mautic\CoreBundle\Event\TokenReplacementEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\CoreBundle\Helper\UrlHelper;
use Mautic\CoreBundle\Model\AuditLogModel;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Helper\TokenHelper;
use Mautic\PageBundle\Entity\Trackable;
use Mautic\PageBundle\Helper\TokenHelper as PageTokenHelper;
use Mautic\PageBundle\Model\TrackableModel;
use Mautic\SmsBundle\Helper\SmsHelper;
use Mautic\SmsBundle\SmsEvents;
use Mautic\SmsBundle\Event\SmsEvent;


/**
 * Class CampaignSubscriber.
 */
class AutomatycaSubscriber extends CommonSubscriber
{
    /**
     * @var AuditLogModel
     */
    protected $auditLogModel;

    /**
     * @var TrackableModel
     */
    protected $trackableModel;

    /**
     * @var PageTokenHelper
     */
    protected $pageTokenHelper;

    /**
     * @var AssetTokenHelper
     */
    protected $assetTokenHelper;
    /**
     * @var SmsHelper
     */
    protected $smsHelper;
    /**
     * @var CoreParametersHelper
     */
    protected $coreParametersHelper;
    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * DynamicContentSubscriber constructor.
     *
     * @param AuditLogModel $auditLogModel
     * @param TrackableModel $trackableModel
     * @param PageTokenHelper $pageTokenHelper
     * @param AssetTokenHelper $assetTokenHelper
     * @param SmsHelper $smsHelper
     * @param CoreParametersHelper $coreParametersHelper
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        AuditLogModel $auditLogModel,
        TrackableModel $trackableModel,
        PageTokenHelper $pageTokenHelper,
        AssetTokenHelper $assetTokenHelper,
        SmsHelper $smsHelper,
        CoreParametersHelper $coreParametersHelper,
        UrlHelper $urlHelper
    )
    {
        $this->auditLogModel = $auditLogModel;
        $this->trackableModel = $trackableModel;
        $this->pageTokenHelper = $pageTokenHelper;
        $this->assetTokenHelper = $assetTokenHelper;
        $this->smsHelper = $smsHelper;
        $this->coreParametersHelper = $coreParametersHelper;
        $this->urlHelper = $urlHelper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            /*   SmsEvents::SMS_POST_SAVE => ['onPostSave', 0],*/
            /*SmsEvents::SMS_POST_DELETE => ['onDelete', 0],*/
            SmsEvents::TOKEN_REPLACEMENT => ['onTokenReplacement', 0],
        ];
    }

    /**
     * Add an entry to the audit log.
     *
     * @param SmsEvent $event
     */
    /* public function onPostSave(SmsEvent $event)
     {
         $entity = $event->getSms();
         if ($details = $event->getChanges()) {
             $log = [
                 'bundle' => 'automatyca',
                 'object' => 'sms',
                 'objectId' => $entity->getId(),
                 'action' => ($event->isNew()) ? 'create' : 'update',
                 'details' => $details,
             ];
             $this->auditLogModel->writeToLog($log);
         }
     }*/

    /**
     * @param TokenReplacementEvent $event
     */
    public
    function onTokenReplacement(TokenReplacementEvent $event)
    {
        /** @var Lead $lead */

        $urlShortenerLink = $this->coreParametersHelper->getParameter('link_shortener_url');

        $lead = $event->getLead();
        $content = $event->getContent();
        $clickthrough = $event->getClickthrough();

        if ($content) {
            $tokens = array_merge(
                TokenHelper::findLeadTokens($content, $lead->getProfileFields()),
                $this->pageTokenHelper->findPageTokens($content, $clickthrough),
                $this->assetTokenHelper->findAssetTokens($content, $clickthrough)
            );
            // Disable trackable urls
            if (!$this->smsHelper->getDisableTrackableUrls()) {
                list($content, $trackables) = $this->trackableModel->parseContentForTrackables(
                    $content,
                    $tokens,
                    'sms',
                    $clickthrough['channel'][1]
                );

                /**
                 * @var string
                 * @var Trackable $trackable
                 */
                foreach ($trackables as $token => $trackable) {
                    $tokens[$token] = $this->trackableModel->generateTrackableUrl($trackable, $clickthrough, true);
                }
            }

            $content = str_replace(array_keys($tokens), array_values($tokens), $content);

            if ($urlShortenerLink) {
                preg_match_all('!https?://\S+!', $content, $matches);
                $urls = $matches[0];
                foreach ($urls as $url) {
                    // create curl resource
                    $ch = curl_init();
                    // set url
                    curl_setopt($ch, CURLOPT_URL, $urlShortenerLink . $url);
                    //return the transfer as a string
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    // $output contains the output string
                    $output = curl_exec($ch);
                    // close curl resource to free up system resources
                    curl_close($ch);
                    str_replace($url, $output, $content);
                }
            }
            $event->setContent($content);
        }
    }
}