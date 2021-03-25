<?php

namespace Ichaber\SSSwiftype\Traits;

/**
 * Trait SwiftypeIndexCrawlerTrait
 *
 * Shared functionality to be used across SwiftypeFileCrawler and SwiftypeSiteTreeCrawler extensions
 * to build curl requests.
 *
 * @package Ichaber\SSSwiftype\Traits
 */
trait SwiftypeIndexCrawlerTrait
{
    public function buildCurlRequest(string $engineSlug, string $domainID, string $apiKey, string $updateUrl) {
        // Create curl resource.
        $ch = curl_init();

        // Set url.
        curl_setopt(
            $ch,
            CURLOPT_URL,
            sprintf(
                'https://api.swiftype.com/api/v1/engines/%s/domains/%s/crawl_url.json',
                $engineSlug,
                $domainID
            )
        );

        // Set request method to "PUT".
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        // Set headers.
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        // Return the transfer as a string.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set our PUT values.
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'auth_token' => $apiKey,
            'url' => $updateUrl,
        ]));

        // $output contains the output string.
        return $output = curl_exec($ch);
    }

}
