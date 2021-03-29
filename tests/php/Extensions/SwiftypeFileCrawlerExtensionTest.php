<?php

namespace Ichaber\SSSwiftype\Tests\Extensions;

use Exception;
use Ichaber\SSSwiftype\Extensions\SwiftypeFileCrawlerExtension;
use Ichaber\SSSwiftype\Tests\Fake\SwiftypeFile;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;

/**
 * Class SwiftypeMetaTagContentExtensionTest
 *
 * @package Ichaber\SSSwiftype\Tests\Extensions
 */
class SwiftypeFileCrawlerExtensionTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'SwiftypeFileCrawlerExtensionTest.yml';

    public function setUp(): void
    {
        parent::setUp();

        // Make sure that our cache is cleared between tests
        /** @var SwiftypeFileCrawlerExtension $crawlerExtension */
        $crawlerExtension = Injector::inst()->get(SwiftypeFileCrawlerExtension::class);
        $crawlerExtension->clearCacheAll();
    }

    /**
     * @throws Exception
     */
    public function testUrlsToCrawlWrite(): void
    {
        // Set our config to not clear caches after un/publish, so that we can easily fetch the Urls for our test
        Config::inst()->update(
            SwiftypeFileCrawlerExtension::class,
            'clear_cache_disabled',
            true
        );

        /** @var SwiftypeFile $file */
        $file = $this->objFromFixture(SwiftypeFile::class, 'file1');

        // Publish single so that Urls to crawl is populated
        $file->write();
        $key = str_replace('\\', '', $file->ClassName . $file->ID);

        $expectedUrls = [
            'localhost/assets/file1.pdf',
        ];
        $urls = [];

        // Grab the Urls that we expect to have been collated
        $urlsToCrawl = $file->getUrlsToCrawl();

        // Check that the key exists for our page
        $this->assertArrayHasKey($key, $urlsToCrawl);

        // Grab the Urls that are for our page
        $urlsToCrawl = $urlsToCrawl[$key];

        // Strip out any http/https stuff
        foreach ($urlsToCrawl as $urlToCrawl) {
            $url = str_replace('http://', '', $urlToCrawl);
            $url = str_replace('https://', '', $url);

            $urls[] = $url;
        }

        $this->assertEquals($expectedUrls, $urls, '', 0.0, 10, true);
    }

    /**
     * @throws Exception
     */
    public function testUrlsToCrawlDelete(): void
    {
        // Set our config to not clear caches after un/publish, so that we can easily fetch the Urls for our test
        Config::inst()->update(
            SwiftypeFileCrawlerExtension::class,
            'clear_cache_disabled',
            true
        );

        /** @var SwiftypeFile $file */
        $file = $this->objFromFixture(SwiftypeFile::class, 'page2');

        // Make sure our page is published before we begin
        $file->publishSingle();
        $key = str_replace('\\', '', $file->ClassName . $file->ID);

        // Make sure we don't have any Cache set from the above publishing
        $file->flushCache();

        $file->doUnpublish();

        $expectedUrls = [
            'localhost/file2/',
        ];
        $urls = [];

        // Grab the Urls that we expect to have been collated
        $urlsToCrawl = $file->getUrlsToCrawl();

        // Check that the key exists for our page
        $this->assertArrayHasKey($key, $urlsToCrawl);

        // Grab the Urls that are for our page
        $urlsToCrawl = $urlsToCrawl[$key];

        // Strip out any http/https stuff
        foreach ($urlsToCrawl as $urlToCrawl) {
            $url = str_replace('http://', '', $urlToCrawl);
            $url = str_replace('https://', '', $url);

            $urls[] = $url;
        }

        $this->assertEquals($expectedUrls, $urls, '', 0.0, 10, true);
    }

    /**
     * @throws Exception
     */
    public function testUrlsToCrawlSegmentChanged(): void
    {
        // Set our config to not clear caches after un/publish, so that we can easily fetch the Urls for our test
        Config::inst()->update(
            SwiftypeFileCrawlerExtension::class,
            'clear_cache_disabled',
            true
        );

        /** @var SwiftypeFile $file */
        $file = $this->objFromFixture(SwiftypeFile::class, 'file3');

        // Make sure our file is published before we begin
        $file->write();
        $key = str_replace('\\', '', $file->ClassName . $file->ID);

        // Make sure our cache is flushed from the above publishing
        $file->flushCache();

        // Update our URL Segment
        $file->Filename = 'assets/file3Changed.pdf';
        // Write again so that Urls to crawl is populated
        $file->write();

        // We expect two Urls now. One from before the segment change, and one from after it
        $expectedUrls = [
            'localhost/assets/file3.pdf',
            'localhost/assets/file3changed.pdf',
        ];
        $urls = [];

        // Grab the Urls that we expect to have been collated
        $urlsToCrawl = $file->getUrlsToCrawl();

        // Check that the key exists for our page
        $this->assertArrayHasKey($key, $urlsToCrawl);

        // Grab the Urls that are for our page
        $urlsToCrawl = $urlsToCrawl[$key];

        // Strip out any http/https stuff
        foreach ($urlsToCrawl as $urlToCrawl) {
            $url = str_replace('http://', '', $urlToCrawl);
            $url = str_replace('https://', '', $url);

            $urls[] = $url;
        }

        $this->assertEquals($expectedUrls, $urls, '', 0.0, 10, true);
    }

    public function testUrlsToCrawlCacheCleared(): void
    {
        /** @var SwiftypeFile $file */
        $file = $this->objFromFixture(SwiftypeFile::class, 'file1');

        // Publish single so that Urls to crawl is populated
        $file->write();
        $key = str_replace('\\', '', $file->ClassName . $file->ID);

        // Grab the Urls that we expect to have been collated
        $urlsToCrawl = $file->getUrlsToCrawl();

        // Check that the key exists for our page
        $this->assertArrayNotHasKey($key, $urlsToCrawl);
    }
}
