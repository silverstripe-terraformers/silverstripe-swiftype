<?php

namespace Ichaber\SSSwiftype\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use Throwable;

/**
 * Credit: [Bernard Hamlin](https://github.com/blueo) and [Mojmir Fendek](https://github.com/mfendeksilverstripe)
 *
 * Class SwiftypeCrawler
 *
 * @package Ichaber\SSSwiftype\Service
 */
class SwiftypeCrawler
{
    use Injectable;

    const SWIFTYPE_API = 'https://api.swiftype.com/api/v1/engines/%s/domains/%s/crawl_url.json';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $messages = [];

    /**
     * Crawler constructor.
     *
     * @param Client|null $client
     */
    public function __construct(?Client $client = null)
    {
        if ($client === null) {
            $client = new Client();
        }

        $this->client = $client;
    }

    /**
     * Crawls a page based on the locale
     *
     * @param string $url
     * @param mixed|null $additionalData If set, we assume that you want to populate your Credentials through extension
     * @return bool
     */
    public function send(string $url, $additionalData = null): bool
    {
        $credentials = SwiftypeCredentials::create($additionalData);
        if (!$credentials->isEnabled()) {
            $this->addMessage($credentials->getMessage());
            $this->getLogger()->alert($credentials->getMessage());

            return false;
        }

        $swiftypeEndpoint = sprintf(
            self::SWIFTYPE_API,
            $credentials->getEngineSlug(),
            $credentials->getDomainID()
        );

        try {
            $response = $this->client->put(
                $swiftypeEndpoint,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode([
                        'auth_token' => $credentials->getAPIKey(),
                        'url' => $url,
                    ]),
                ]
            );

            $contents = $response->getBody()->getContents();
        } catch (Throwable $e) {
            $message = sprintf('Exception %s for url: %s message: %s', get_class($e), $url, $e->getMessage());

            $this->addMessage($message);
            $this->getLogger()->alert($message);

            return false;
        }

        // invalid response code
        if (strpos((string) $response->getStatusCode(), '2') !== 0) {
            $message = sprintf(
                "Swiftype Crawl request failed - invalid response code \n%s\n%s\n%s",
                $response->getStatusCode(),
                json_encode($response->getHeaders()),
                $contents
            );

            $this->addMessage($message);
            $this->getLogger()->alert($message);

            return false;
        }

        // invalid response data
        $data = json_decode($contents, true);
        if ($data && array_key_exists('error', $data)) {
            $message = sprintf(
                "Swiftype Crawl request failed - invalid response data \n%s\n%s\n%s",
                $response->getStatusCode(),
                json_encode($response->getHeaders()),
                $contents
            );

            $this->addMessage($message);
            $this->getLogger()->alert($message);

            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        if (!$this->logger) {
            $this->logger = Injector::inst()->get(LoggerInterface::class);
        }

        return $this->logger;
    }

    /**
     * @param string $message
     */
    protected function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}
