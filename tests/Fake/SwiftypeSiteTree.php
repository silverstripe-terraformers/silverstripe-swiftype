<?php

namespace Ichaber\SSSwiftype\Tests\Fake;

use Ichaber\SSSwiftype\Extensions\SwiftypeMetaTagContentExtension;
use Ichaber\SSSwiftype\Extensions\SwiftypeSiteTreeCrawlerExtension;
use Ichaber\SSSwiftype\Tests\Extensions\SwiftypeMetaTagContentExtensionTest;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\TestOnly;

/**
 * @mixin SwiftypeMetaTagContentExtension
 * @mixin SwiftypeSiteTreeCrawlerExtension
 */
class SwiftypeSiteTree extends SiteTree implements TestOnly
{
    private static array $extensions = [
        SwiftypeMetaTagContentExtension::class,
        SwiftypeSiteTreeCrawlerExtension::class,
    ];

    /**
     * This needs to be set in your test.
     *
     * @see SwiftypeMetaTagContentExtensionTest::testMetaTagOutput() for an example
     */
    private static array $swiftype_meta_tag_classes = [];
}
