<?php

namespace Ichaber\SSSwiftype\Tests\Fake;

use Ichaber\SSSwiftype\Extensions\SwiftypeMetaTagContentExtension;
use Ichaber\SSSwiftype\Extensions\SwiftypeFileCrawlerExtension;
use SilverStripe\Assets\File;
use SilverStripe\Dev\TestOnly;

/**
 * @mixin SwiftypeMetaTagContentExtension
 * @mixin SwiftypeFileCrawlerExtension
 */
class SwiftypeFile extends File implements TestOnly
{
    private static array $extensions = [
        SwiftypeMetaTagContentExtension::class,
        SwiftypeFileCrawlerExtension::class,
    ];

    /**
     * config setting to allow which files can be indexed.
     * Defaults to 'pdf' for our unit tests.
     */
    private static array $reindex_allowed_extensions = [
        'pdf',
    ];

    /**
     * This needs to be set in your test.
     *
     * @see SwiftypeMetaTagContentExtensionTest::testMetaTagOutput() for an example
     */
    private static array $swiftype_meta_tag_classes = [];
}
