<?php

namespace Ichaber\SSSwiftype\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use SilverStripe\Core\Injector\Injectable;
use Throwable;

/**
 * Credit: [Bernard Hamlin](https://github.com/blueo) and [Mojmir Fendek](https://github.com/mfendeksilverstripe)
 */
class SwiftypeCrawler
{
    use Injectable;

    protected const string SWIFTYPE_API = 'https://api.swiftype.com/api/v1/engines/%s/domains/%s/crawl_url.json';

    private ?Client $client;

    private ?LoggerInterface $logger = null;

    private array $messages = [];

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
     * @param mixed|null $additionalData If set, we assume that you want to populate your Credentials through extension
     */
    public function send(string $url, mixed $additionalData = null): bool
    {
        $credentials = SwiftypeCredentials::create($additionalData);

        if (!$credentials->isEnabled()) {
            $this->addMessage($credentials->getMessage());
            $this->getLogger()->alert($credentials->getMessage());

            return false;
        }

        $swiftypeEndpoint = sprintf(
            SwiftypeCrawler::SWIFTYPE_API,
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
        if (!str_starts_with((string) $response->getStatusCode(), '2')) {
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

    public function getMessages(): array
    {
        return $this->messages;
    }

    protected function getLogger(): LoggerInterface
    {
        if (!$this->logger) {
            $this->logger = singleton(LoggerInterface::class);
        }

        return $this->logger;
    }

    protected function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}
