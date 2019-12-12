<?php

namespace Ichaber\SSSwiftype\Tests\Extensions;

use Exception;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagDescription;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagPublishedAt;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagRobots;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagTitle;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagURL;
use Ichaber\SSSwiftype\Tests\Fake\SwiftypeSiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Class SwiftypeMetaTagContentExtensionTest
 *
 * @package Ichaber\SSSwiftype\Tests\Extensions
 */
class SwiftypeMetaTagContentExtensionTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'SwiftypeMetaTagContentExtensionTest.yml';

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
        Config::inst()->update(
            SwiftypeSiteTree::class,
            'swiftype_meta_tag_classes',
            [
                SwiftypeMetaTagDescription::class,
                SwiftypeMetaTagPublishedAt::class,
                SwiftypeMetaTagRobots::class,
                SwiftypeMetaTagTitle::class,
                SwiftypeMetaTagURL::class,
            ]
        );

        /** @var SwiftypeSiteTree $page */
        $page = $this->objFromFixture(SwiftypeSiteTree::class, 'page1');

        // Quickly render an expected mock
        $mock = file_get_contents(__DIR__ . '/../Mock/TagsOutput.html');
        $mock = trim(preg_replace("/\s+/S", '', $mock));

        // Remove formatting from output output
        $output = trim(preg_replace("/\s+/S", '', $page->getSwiftypeMetaTags()->getValue()));

        $this->assertEquals($mock, $output);
    }

    /**
     * @throws Exception
     */
    public function testMetaTagsReturnsEmptyByDefault()
    {
        $page = new SwiftypeSiteTree();
        /** @var DBHTMLText $tags */
        $tags = $page->getSwiftypeMetaTags();
        $this->assertEmpty($tags->getValue());
    }

    /**
     * @throws Exception
     */
    public function testMetaTagsReturnsConfiguredTags()
    {
        $page = new SwiftypeSiteTree();
        $page->MetaDescription = 'My description';
        $page->config()->update('swiftype_meta_tag_classes', [
            SwiftypeMetaTagDescription::class
        ]);

        /** @var DBHTMLText $tags */
        $tags = $page->getSwiftypeMetaTags();
        $this->assertNotEmpty($tags->getValue());
        $this->assertContains($page->MetaDescription, $tags->getValue());
    }
}
