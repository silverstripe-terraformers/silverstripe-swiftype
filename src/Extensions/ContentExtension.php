<?php
namespace Ichaber\SSSwiftype\Extensions;


use SilverStripe\Core\Extension;

class ContentExtension extends Extension
{
//    protected $swiftypeMetaTags = array(
//        'Body',
//        'PublishedAt',
//        'Title',
//        'UpdatedAt',
//        'URL',
//    );
    protected $swiftypeMetaTags = [];

    public function __construct()
    {
        parent::__construct();
        // Get the default meta tags from the config
        $this->swiftypeMetaTags = Config::inst()->get(ContentExtension::class, 'swiftypeMetaTags');
    }

    public function getSwiftypeMetaTags()
    {
        $swiftypeMetaTags = array();

        foreach ($this->swiftypeMetaTags as $tagName) {
            $className = 'SwiftypeMetaTag_' . $tagName;

            if (!class_exists($className)) {
                continue;
            }

            /** @var SwiftypeMetaTag $r */
            $r = new $className();
            $tagsString = $r->getMetaTagsString($this->data());

            if ($tagsString === null) {
                continue;
            }

            $swiftypeMetaTags[] = $tagsString;
        }

        return implode("\r\n", $swiftypeMetaTags);
    }
}