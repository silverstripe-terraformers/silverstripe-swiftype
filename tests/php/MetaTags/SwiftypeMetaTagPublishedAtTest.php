<?php

namespace Ichaber\SSSwiftype\Tests\MetaTags;

use Exception;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTag;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagPublishedAt;
use Ichaber\SSSwiftype\Tests\Fake\SwiftypeSiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\FieldType\DBDatetime;

/**
 * Class SwiftypeMetaTagPublishedAtTest
 *
 * @package Ichaber\SSSwiftype\Tests\Extensions
 */
class SwiftypeMetaTagPublishedAtTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'SwiftypeMetaTagTest.yml';

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        DBDatetime::set_mock_now('2018-03-01 14:00:00');

        parent::setUp();
    }

    /**
     * @throws Exception
     */
    public function testMetaTagOutput(): void
    {
        Config::inst()->update(SwiftypeSiteTree::class, 'swiftype_meta_tag_classes', [SwiftypeMetaTagPublishedAt::class]);
        Config::inst()->update(SwiftypeMetaTag::class, 'date_format', 'YYYY-MM-dd');

        /** @var SwiftypeSiteTree $page */
        $page = $this->objFromFixture(SwiftypeSiteTree::class, 'page1');

        // Quickly render an expected mock
        $mock = file_get_contents(__DIR__ . '/../Mock/PublishedAtTagOutput.html');
        $mock = trim(preg_replace("/\s+/S", '', $mock));

        // Remove formatting from output output
        $output = trim(preg_replace("/\s+/S", '', $page->getSwiftypeMetaTags()->getValue()));

        $this->assertEquals($mock, $output);
    }
}
