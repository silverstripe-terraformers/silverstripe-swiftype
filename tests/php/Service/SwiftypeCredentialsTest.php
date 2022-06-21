<?php

namespace Ichaber\SSSwiftype\Tests\Service;

use Ichaber\SSSwiftype\Service\SwiftypeCredentials;
use Ichaber\SSSwiftype\Extensions\SwiftypeSiteConfigFieldsExtension;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\ValidationException;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Class SwiftypeCredentialsTest
 *
 * @package Ichaber\SSSwiftype\Tests\Service
 */
class SwiftypeCredentialsTest extends SapphireTest
{
    /**
     * @var bool
     */
    protected $usesDatabase = true;

    /**
     * @var array
     */
    protected static $required_extensions = [
        SiteConfig::class => [
            SwiftypeSiteConfigFieldsExtension::class,
        ],
    ];

    /**
     * @throws ValidationException
     */
    public function testFullCredentials(): void
    {
        /** @var SiteConfig|SwiftypeSiteConfigFieldsExtension $config */
        $config = SiteConfig::current_site_config();

        $config->SwiftypeEnabled = 1;
        $config->SwiftypeEngineSlug = 'test';
        $config->SwiftypeDomainID = 'test';
        $config->SwiftypeAPIKey = 'test';

        $config->write();

        $credentials = SwiftypeCredentials::create();

        $this->assertTrue($credentials->isEnabled());
    }

    /**
     * @throws ValidationException
     */
    public function testNoEngineSlug(): void
    {
        /** @var SiteConfig|SwiftypeSiteConfigFieldsExtension $config */
        $config = SiteConfig::current_site_config();

        $config->SwiftypeEnabled = 1;
        $config->SwiftypeEngineSlug = null;
        $config->SwiftypeDomainID = 'test';
        $config->SwiftypeAPIKey = 'test';

        $config->write();

        $credentials = SwiftypeCredentials::create();

        $this->assertFalse($credentials->isEnabled());
        $this->assertStringContainsString('Swiftype Engine Slug value has not been set', $credentials->getMessage());
    }

    /**
     * @throws ValidationException
     */
    public function testNoDomainID(): void
    {
        /** @var SiteConfig|SwiftypeSiteConfigFieldsExtension $config */
        $config = SiteConfig::current_site_config();

        $config->SwiftypeEnabled = 1;
        $config->SwiftypeEngineSlug = 'test';
        $config->SwiftypeDomainID = null;
        $config->SwiftypeAPIKey = 'test';

        $config->write();

        $credentials = SwiftypeCredentials::create();

        $this->assertFalse($credentials->isEnabled());
        $this->assertStringContainsString('Swiftype Domain ID has not been set', $credentials->getMessage());
    }

    /**
     * @throws ValidationException
     */
    public function testNoApiKey(): void
    {
        /** @var SiteConfig|SwiftypeSiteConfigFieldsExtension $config */
        $config = SiteConfig::current_site_config();

        $config->SwiftypeEnabled = 1;
        $config->SwiftypeEngineSlug = 'test';
        $config->SwiftypeDomainID = 'test';
        $config->SwiftypeAPIKey = null;

        $config->write();

        $credentials = SwiftypeCredentials::create();

        $this->assertFalse($credentials->isEnabled());
        $this->assertStringContainsString('Swiftype API Key has not been set', $credentials->getMessage());
    }
}
