<?php
namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Control\Director;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

class SwiftypeSiteTree extends SiteTreeExtension {

    public function onAfterPublish(&$original)
    {
        parent::onAfterPublish($original);
        $this->forceSwiftypeIndex();
    }

    public function onAfterUnpublish()
    {
        parent::onAfterUnpublish();
        $this->forceSwiftypeIndex();
    }


    /**
     * According to the documentation of the RestfulService only xml output is supported at this stage.
     * As we need json, we opted to just use standard PHP curl processing.
     *
     * For future proofing purposes, this function returns a boolean value.
     *
     * Note: This request to Swiftype is ignored any time a request is sent for an existing URL (unfortunately). For
     * reindexing edited Pages, we must unfortunately rely on Constant Crawl. This is probably a design choice on
     * Swiftype's part.
     *
     * @return bool
     */
    protected function forceSwiftypeIndex()
    {
        // We don't reindex dev environments.
        if (Director::isDev()) {
            return true;
        }

        $logger = $this->getLogger();
        $config = SiteConfig::current_site_config();

        $engineSlug = $config->SwiftypeEngineSlug;
        $domainID = $config->SwiftypeDomainID;
        $apiKey = $config->SwiftypeAPIKey;

        if (!$engineSlug) {
            $logger->warning(
                'Swiftype Engine Slug value has not been set. Settings > Swiftype Search > Swiftype Engine Slug'
            );

            return false;
        }

        if (!$domainID) {
            $logger->warning(
                'Swiftype Domain ID has not been set. Settings > Swiftype Search > Swiftype Domain ID'
            );

            return false;
        }

        if (!$apiKey) {
            $logger->warning(
                'Swiftype API Key has not been set. Settings > Swiftype Search > Swiftype Production API Key'
            );

            return false;
        }

        $updateUrl = $this->getOwner()->getAbsoluteLiveLink();

        // Create curl resource.
        $ch = curl_init();

        // Set url.
        curl_setopt($ch, CURLOPT_URL,
            "https://api.swiftype.com/api/v1/engines/{$engineSlug}/domains/{$domainID}/crawl_url.json");

        // Set request method to "PUT".
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        // Set headers.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        // Return the transfer as a string.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set our PUT values.
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'auth_token' => $apiKey,
            'url' => $updateUrl,
        )));

        // $output contains the output string.
        $output = curl_exec($ch);

        // Close curl resource to free up system resources.
        curl_close($ch);

        if (!$output) {
            $logger->warning(
                'We got no response from Swiftype for reindexing page: ' . $updateUrl
            );

            return false;
        }

        return true;
    }

    /**
     * @return LoggerInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getLogger()
    {
        return Injector::inst()->get(LoggerInterface::class);
    }
}
