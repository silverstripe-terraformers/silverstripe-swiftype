<?php

namespace Ichaber\SSSwiftype\Tests\Extensions;

use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagRobots;
use Ichaber\SSSwiftype\Tests\Fake\SwiftypeSiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;

class SwiftypeMetaTagRobotsTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'SwiftypeMetaTagTest.yml';

    public function testRobotsTagNoIndex(): void
    {
        Config::modify()->set(
            SwiftypeSiteTree::class,
            'swiftype_meta_tag_classes',
            [
                SwiftypeMetaTagRobots::class,
            ]
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_index',
            true
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_follow',
            false
        );

        /** @var SwiftypeSiteTree $page */
        $page = $this->objFromFixture(SwiftypeSiteTree::class, 'page2');

        // Quickly render an expected mock
        $mock = '<meta name="robots" content="noindex">';
        $mock = trim(preg_replace("/\s+/S", '', $mock));

        // Remove formatting from output
        $output = trim(preg_replace("/\s+/S", '', $page->getSwiftypeMetaTags()->getValue()));

        $this->assertEquals($mock, $output);
    }

    public function testRobotsTagNoFollow(): void
    {
        Config::modify()->set(
            SwiftypeSiteTree::class,
            'swiftype_meta_tag_classes',
            [
                SwiftypeMetaTagRobots::class,
            ]
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_index',
            false
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_follow',
            true
        );

        /** @var SwiftypeSiteTree $page */
        $page = $this->objFromFixture(SwiftypeSiteTree::class, 'page2');

        // Quickly render an expected mock
        $mock = '<meta name="robots" content="nofollow">';
        $mock = trim(preg_replace("/\s+/S", '', $mock));

        // Remove formatting from output
        $output = trim(preg_replace("/\s+/S", '', $page->getSwiftypeMetaTags()->getValue()));

        $this->assertEquals($mock, $output);
    }

    public function testRobotsTagBoth(): void
    {
        Config::modify()->set(
            SwiftypeSiteTree::class,
            'swiftype_meta_tag_classes',
            [
                SwiftypeMetaTagRobots::class,
            ]
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_index',
            true
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_follow',
            true
        );

        /** @var SwiftypeSiteTree $page */
        $page = $this->objFromFixture(SwiftypeSiteTree::class, 'page2');

        // Quickly render an expected mock
        $mock = '<meta name="robots" content="noindex, nofollow">';
        $mock = trim(preg_replace("/\s+/S", '', $mock));

        // Remove formatting from output
        $output = trim(preg_replace("/\s+/S", '', $page->getSwiftypeMetaTags()->getValue()));

        $this->assertEquals($mock, $output);
    }

    public function testRobotsTagNone(): void
    {
        Config::modify()->set(
            SwiftypeSiteTree::class,
            'swiftype_meta_tag_classes',
            [
                SwiftypeMetaTagRobots::class,
            ]
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_index',
            false
        );

        Config::modify()->set(
            SwiftypeMetaTagRobots::class,
            'no_follow',
            false
        );

        /** @var SwiftypeSiteTree $page */
        $page = $this->objFromFixture(SwiftypeSiteTree::class, 'page2');

        // Remove formatting from output
        $output = trim(preg_replace("/\s+/S", '', $page->getSwiftypeMetaTags()->getValue()));

        $this->assertEquals('', $output);
    }
}
