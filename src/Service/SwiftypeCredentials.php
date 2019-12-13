<?php

namespace Ichaber\SSSwiftype\Service;

use Psr\Log\LoggerInterface;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Credit: [Bernard Hamlin](https://github.com/blueo) and [Mojmir Fendek](https://github.com/mfendeksilverstripe)
 *
 * Class SwiftypeCredentials
 *
 * @package Ichaber\SSSwiftype\Service
 */
class SwiftypeCredentials
{
    use Extensible;
    use Injectable;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $enabled = false;

    /**
     * @var string|null
     */
    private $engineSlug;

    /**
     * @var string|null
     */
    private $domainID;

    /**
     * @var string|null
     */
    private $apiKey;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool|null $enabled
     */
    public function setEnabled(?bool $enabled): void
    {
        $this->enabled = (bool) $enabled;
    }

    /**
     * @return string|null
     */
    public function getEngineSlug(): ?string
    {
        return $this->engineSlug;
    }

    /**
     * @param string|null $engineSlug
     */
    public function setEngineSlug(?string $engineSlug): void
    {
        $this->engineSlug = $engineSlug;
    }

    /**
     * @return string|null
     */
    public function getDomainID(): ?string
    {
        return $this->domainID;
    }

    /**
     * @param string|null $domainID
     */
    public function setDomainID(?string $domainID): void
    {
        $this->domainID = $domainID;
    }

    /**
     * @return string|null
     */
    public function getAPIKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @param string|null $apiKey
     */
    public function setAPIKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Gets the Swiftype credentials
     *
     * @param mixed|null $additionalData If set, we assume that you want to populate your Credentials through extension
     * @return void
     */
    public function __construct($additionalData = null)
    {
        if ($additionalData !== null) {
            // You've supplied the class with $additionalData, so, please populate your Credentials data through the
            // populateCredentials extensions point. It will have access to your $additionalData also
            $this->invokeWithExtensions('populateCredentials', $additionalData);
        } else {
            // Default functionality is to grab Credentials from SiteConfig
            /** @var SiteConfig $config */
            $config = SiteConfig::current_site_config();

            // You might want to implement this via Environment variables or something. Just make sure SiteConfig has
            // access to that variable, and return it here
            $this->setEnabled((bool) $config->relField('SwiftypeEnabled'));

            // If you have multiple Engines per site (maybe you use Fluent with a different Engine on each Locale), then
            // this provides some basic ability to have different credentials returned based on the application state
            $this->setEngineSlug($config->relField('SwiftypeEngineSlug'));
            $this->setDomainID($config->relField('SwiftypeDomainID'));
            $this->setAPIKey($config->relField('SwiftypeAPIKey'));
        }

        if (!$this->isEnabled()) {
            $this->disable(
                'Swiftype is disabled. It can be enabled under Settings > Swiftype Search'
            );

            return;
        }

        if (!$this->getEngineSlug()) {
            $this->disable(
                'Swiftype Engine Slug value has not been set. Settings > Swiftype Search > Swiftype Engine Slug'
            );

            return;
        }

        if (!$this->getDomainID()) {
            $this->disable(
                'Swiftype Domain ID has not been set. Settings > Swiftype Search > Swiftype Domain ID'
            );

            return;
        }

        if (!$this->getAPIKey()) {
            $this->disable(
                'Swiftype API Key has not been set. Settings > Swiftype Search > Swiftype Production API Key'
            );

            return;
        }
    }

    /**
     * @param string $message
     */
    protected function disable(string $message): void
    {
        $trace = debug_backtrace();

        // array_shift for adding context (for RaygunHandler) by using the last item on the stack trace.
        $this->getLogger()->warning($message, array_shift($trace));

        $this->setMessage($message);
        $this->setEnabled(false);
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
}
