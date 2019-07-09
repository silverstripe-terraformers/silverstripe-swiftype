<?php
namespace Ichaber\SSSwiftype\Tests\Extensions;

use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagDescription;
use SilverStripe\Dev\SapphireTest;
use Ichaber\SSSwiftype\Tests\Fake\SwiftypeSiteTree;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Tests SwiftypeMetaTagContentExtension implicitly,
 * it's added on {@link SwiftypeSiteTree}.
 */
class SwiftypeMetaTagContentExtensionTest extends SapphireTest
{
    public function testMetaTagsReturnsEmptyByDefault()
    {
        $page = new SwiftypeSiteTree();
        /** @var DBHTMLText $tags */
        $tags = $page->getSwiftypeMetaTags();
        $this->assertEmpty($tags->getValue());
    }

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
